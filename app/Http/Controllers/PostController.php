<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

/**
 * A complete CRUD example: list, create, show, edit and delete posts.
 * Routes come from Route::resource('posts', PostController::class).
 */
class PostController extends Controller
{
    /**
     * GET /posts — list the signed-in user's posts, with search and a status filter.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->value();

        $posts = $request->user()->posts()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('body', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('posts.index', [
            'posts' => $posts,
            'statuses' => Post::STATUSES,
        ]);
    }

    /**
     * GET /posts/create — show the empty form.
     */
    public function create(): View
    {
        return view('posts.create', ['post' => new Post(['status' => 'draft'])]);
    }

    /**
     * POST /posts — save a new post.
     */
    public function store(PostRequest $request): RedirectResponse
    {
        $post = $request->user()->posts()->create($request->postData());

        return redirect()->route('posts.show', $post)->with('success', 'Post created.');
    }

    /**
     * GET /posts/{post} — show one post.
     */
    public function show(Post $post): View
    {
        Gate::authorize('view', $post);

        return view('posts.show', ['post' => $post]);
    }

    /**
     * GET /posts/{post}/edit — show the form filled with the post's data.
     */
    public function edit(Post $post): View
    {
        Gate::authorize('update', $post);

        return view('posts.edit', ['post' => $post]);
    }

    /**
     * PUT /posts/{post} — save changes.
     */
    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        Gate::authorize('update', $post);

        $post->update($request->postData());

        return redirect()->route('posts.show', $post)->with('success', 'Post updated.');
    }

    /**
     * DELETE /posts/{post} — delete the post.
     */
    public function destroy(Post $post): RedirectResponse
    {
        Gate::authorize('delete', $post);

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted.');
    }
}
