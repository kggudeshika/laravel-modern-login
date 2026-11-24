@extends('layouts.admin')

@section('title', 'Posts')

@section('content')
    <div class="page-header">
        <div>
            <h1>All posts</h1>
            <p class="muted">Manage every post on the site.</p>
        </div>
        <a href="{{ route('admin.posts.create') }}" class="btn-primary"><x-icon name="plus" /> <span>New post</span></a>
    </div>

    <form method="GET" action="{{ route('admin.posts.index') }}" class="toolbar">
        <div class="input-wrap toolbar-search">
            <x-icon name="search" class="input-icon" />
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search title or text…" aria-label="Search posts">
        </div>
        <div class="input-wrap no-icon">
            <select name="status" aria-label="Filter by status">
                <option value="">All statuses</option>
                @foreach (\App\Models\Post::STATUSES as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="input-wrap no-icon">
            <select name="category" aria-label="Filter by category">
                <option value="">All categories</option>
                @foreach (\App\Models\Post::CATEGORIES as $value => $label)
                    <option value="{{ $value }}" @selected(request('category') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="input-wrap no-icon">
            <select name="author" aria-label="Filter by author">
                <option value="">All authors</option>
                @foreach ($authors as $id => $label)
                    <option value="{{ $id }}" @selected((string) request('author') === (string) $id)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn-secondary">Filter</button>
        @if (request()->hasAny(['search', 'status', 'category', 'author']))
            <a href="{{ route('admin.posts.index') }}" class="link">Clear</a>
        @endif
    </form>

    <section class="card card-flush">
        @if ($posts->isEmpty())
            <div class="empty"><p>No posts found.</p></div>
        @else
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Updated</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($posts as $post)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.posts.show', $post) }}" class="table-title">
                                        @if ($post->is_featured)<x-icon name="star" class="icon-star" />@endif
                                        {{ $post->title }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('admin.users.show', $post->user) }}" class="person person-sm">
                                        <x-avatar :user="$post->user" size="xs" /> {{ $post->user->name }}
                                    </a>
                                </td>
                                <td><span class="badge">{{ \App\Models\Post::CATEGORIES[$post->category] ?? $post->category }}</span></td>
                                <td><span @class(['badge', 'badge-success' => $post->status === 'published', 'badge-muted' => $post->status === 'draft'])>{{ ucfirst($post->status) }}</span></td>
                                <td class="muted">{{ $post->updated_at->diffForHumans() }}</td>
                                <td>
                                    <div class="row-actions">
                                        <a href="{{ route('admin.posts.show', $post) }}" class="btn-icon" title="View" aria-label="View {{ $post->title }}"><x-icon name="eye-view" /></a>
                                        <a href="{{ route('admin.posts.edit', $post) }}" class="btn-icon" title="Edit" aria-label="Edit {{ $post->title }}"><x-icon name="edit" /></a>
                                        <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" data-confirm="Delete “{{ $post->title }}”?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-icon btn-icon-danger" title="Delete" aria-label="Delete {{ $post->title }}"><x-icon name="trash" /></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <div class="pager">{{ $posts->links('partials.pagination') }}</div>
@endsection
