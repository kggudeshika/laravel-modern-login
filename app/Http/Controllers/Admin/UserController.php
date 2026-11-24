<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * Admin CRUD for users: list, create, view, edit (incl. password,
 * role, verification and profile picture) and delete.
 */
class UserController extends Controller
{
    /**
     * GET /admin/users
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->value();

        $users = User::query()
            ->withCount('posts')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            })
            ->when($request->input('role') === 'admin', fn ($q) => $q->where('is_admin', true))
            ->when($request->input('role') === 'user', fn ($q) => $q->where('is_admin', false))
            ->when($request->input('verified') === 'yes', fn ($q) => $q->whereNotNull('email_verified_at'))
            ->when($request->input('verified') === 'no', fn ($q) => $q->whereNull('email_verified_at'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', ['users' => $users]);
    }

    /**
     * GET /admin/users/create
     */
    public function create(): View
    {
        return view('admin.users.create', ['user' => new User]);
    }

    /**
     * POST /admin/users
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        $user = new User;
        $this->fillUser($user, $request, $validated);

        return redirect()->route('admin.users.show', $user)->with('success', 'User created.');
    }

    /**
     * GET /admin/users/{user}
     */
    public function show(User $user): View
    {
        return view('admin.users.show', [
            'user' => $user->loadCount('posts'),
            'posts' => $user->posts()->latest()->take(10)->get(),
        ]);
    }

    /**
     * GET /admin/users/{user}/edit
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', ['user' => $user]);
    }

    /**
     * PUT /admin/users/{user}
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate($this->rules($user));

        // Don't let an admin lock themselves out of the admin panel.
        if ($request->user()->is($user) && ! $request->boolean('is_admin')) {
            return back()->withInput()->withErrors(['is_admin' => 'You cannot remove your own admin role.']);
        }

        $this->fillUser($user, $request, $validated);

        return redirect()->route('admin.users.show', $user)->with('success', 'User updated.');
    }

    /**
     * DELETE /admin/users/{user}
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return back()->with('error', 'You cannot delete your own account from the admin panel.');
        }

        $user->deleteAvatar();
        $user->delete(); // posts are removed too (cascadeOnDelete in the migration)

        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }

    /**
     * Validation rules shared by store() and update().
     * Password is required when creating, optional when editing.
     */
    private function rules(?User $user = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user?->id)],
            'password' => [$user ? 'nullable' : 'required', 'confirmed', Password::defaults()],
            'is_admin' => ['nullable', 'boolean'],
            'verified' => ['nullable', 'boolean'],
            'avatar' => ['nullable', File::image()->types(['jpg', 'jpeg', 'png', 'webp'])->max(2 * 1024)],
            'remove_avatar' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Copy the form values onto the user and save.
     * is_admin / email_verified_at / avatar_path are not fillable, so we use forceFill().
     */
    private function fillUser(User $user, Request $request, array $validated): void
    {
        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (! empty($validated['password'])) {
            $user->password = $validated['password']; // hashed by the model cast
        }

        $user->forceFill([
            'is_admin' => $request->boolean('is_admin'),
            'email_verified_at' => $request->boolean('verified') ? ($user->email_verified_at ?? now()) : null,
        ]);

        $oldAvatar = $user->avatar_path;

        if ($request->hasFile('avatar')) {
            $user->forceFill(['avatar_path' => $request->file('avatar')->store('avatars', 'public')]);
        } elseif ($request->boolean('remove_avatar')) {
            $user->forceFill(['avatar_path' => null]);
        }

        $user->save();

        if ($oldAvatar && $oldAvatar !== $user->avatar_path) {
            Storage::disk('public')->delete($oldAvatar);
        }
    }
}
