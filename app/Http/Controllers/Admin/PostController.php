<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminPostRequest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Admin CRUD for posts: admins can manage everyone's posts
 * and choose the author.
 */
class PostController extends Controller
{
    /**
     * GET /admin/posts
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->value();

        $posts = Post::query()
            ->with('user')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(fn ($q) => $q->where('title', 'like', "%{$search}%")->orWhere('body', 'like', "%{$search}%"));
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->input('category')))
            ->when($request->filled('author'), fn ($q) => $q->where('user_id', $request->integer('author')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.posts.index', [
            'posts' => $posts,
            'authors' => $this->authorOptions(),
        ]);
    }

    /**
     * GET /admin/posts/create
     */
    public function create(Request $request): View
    {
        return view('admin.posts.create', [
            'post' => new Post(['status' => 'draft', 'user_id' => $request->user()->id]),
            'authors' => $this->authorOptions(),
        ]);
    }

    /**
     * POST /admin/posts
     */
    public function store(AdminPostRequest $request): RedirectResponse
    {
        $post = new Post($request->postData());
        $post->user_id = $request->postData()['user_id'];
        $post->save();

        return redirect()->route('admin.posts.show', $post)->with('success', 'Post created.');
    }

    /**
     * GET /admin/posts/{post}
     */
    public function show(Post $post): View
    {
        return view('admin.posts.show', ['post' => $post->load('user')]);
    }

    /**
     * GET /admin/posts/{post}/edit
     */
    public function edit(Post $post): View
    {
        return view('admin.posts.edit', [
            'post' => $post,
            'authors' => $this->authorOptions(),
        ]);
    }

    /**
     * PUT /admin/posts/{post}
     */
    public function update(AdminPostRequest $request, Post $post): RedirectResponse
    {
        $post->fill($request->postData());
        $post->user_id = $request->postData()['user_id'];
        $post->save();

        return redirect()->route('admin.posts.show', $post)->with('success', 'Post updated.');
    }

    /**
     * DELETE /admin/posts/{post}
     */
    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Post deleted.');
    }

    /**
     * ['id' => 'Name (email)'] for the author select box.
     *
     * @return array<int, string>
     */
    private function authorOptions(): array
    {
        return User::orderBy('name')->get()
            ->mapWithKeys(fn (User $user) => [$user->id => "{$user->name} ({$user->email})"])
            ->all();
    }
}
