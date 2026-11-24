@extends('layouts.app')

@section('title', 'Posts')

@section('content')
    <div class="page-header">
        <div>
            <h1>Posts</h1>
            <p class="muted">Create, read, update and delete your posts.</p>
        </div>
        <a href="{{ route('posts.create') }}" class="btn-primary">
            <x-icon name="plus" /> <span>New post</span>
        </a>
    </div>

    {{-- Search + filter: a GET form, so the values end up in the URL (?search=...&status=...) --}}
    <form method="GET" action="{{ route('posts.index') }}" class="toolbar">
        <div class="input-wrap toolbar-search">
            <x-icon name="search" class="input-icon" />
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search title or text…" aria-label="Search posts">
        </div>
        <div class="input-wrap no-icon">
            <select name="status" aria-label="Filter by status">
                <option value="">All statuses</option>
                @foreach ($statuses as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn-secondary">Filter</button>
        @if (request()->hasAny(['search', 'status']))
            <a href="{{ route('posts.index') }}" class="link">Clear</a>
        @endif
    </form>

    <section class="card card-flush">
        @if ($posts->isEmpty())
            <div class="empty">
                <p>No posts found.</p>
                <a href="{{ route('posts.create') }}" class="link">Create a post</a>
            </div>
        @else
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Published</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($posts as $post)
                            <tr>
                                <td>
                                    <a href="{{ route('posts.show', $post) }}" class="table-title">
                                        @if ($post->is_featured)<x-icon name="star" class="icon-star" />@endif
                                        {{ $post->title }}
                                    </a>
                                </td>
                                <td><span class="badge">{{ \App\Models\Post::CATEGORIES[$post->category] ?? $post->category }}</span></td>
                                <td>
                                    <span @class(['badge', 'badge-success' => $post->status === 'published', 'badge-muted' => $post->status === 'draft'])>
                                        {{ $statuses[$post->status] ?? $post->status }}
                                    </span>
                                </td>
                                <td class="muted">{{ $post->published_on?->format('M j, Y') ?? '—' }}</td>
                                <td>
                                    <div class="row-actions">
                                        <a href="{{ route('posts.edit', $post) }}" class="btn-icon" title="Edit" aria-label="Edit {{ $post->title }}">
                                            <x-icon name="edit" />
                                        </a>
                                        <form method="POST" action="{{ route('posts.destroy', $post) }}" data-confirm="Delete “{{ $post->title }}”? This cannot be undone.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-icon btn-icon-danger" title="Delete" aria-label="Delete {{ $post->title }}">
                                                <x-icon name="trash" />
                                            </button>
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

    <div class="pager">
        {{ $posts->links('partials.pagination') }}
    </div>
@endsection
