@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page-header">
        <div>
            <h1>Hello, {{ auth()->user()->name }} 👋</h1>
            <p class="muted">Here’s what’s happening with your posts.</p>
        </div>
        <a href="{{ route('posts.create') }}" class="btn-primary">
            <x-icon name="plus" /> <span>New post</span>
        </a>
    </div>

    <div class="stats">
        <div class="stat"><span class="stat-label">Total posts</span><strong>{{ $stats['total'] }}</strong></div>
        <div class="stat"><span class="stat-label">Published</span><strong>{{ $stats['published'] }}</strong></div>
        <div class="stat"><span class="stat-label">Drafts</span><strong>{{ $stats['drafts'] }}</strong></div>
        <div class="stat"><span class="stat-label">Featured</span><strong>{{ $stats['featured'] }}</strong></div>
    </div>

    <section class="card">
        <div class="card-header">
            <h2>Recent posts</h2>
            <a href="{{ route('posts.index') }}" class="link">View all</a>
        </div>

        @forelse ($recent as $post)
            <a href="{{ route('posts.show', $post) }}" class="list-row">
                <span class="list-title">
                    @if ($post->is_featured)<x-icon name="star" class="icon-star" />@endif
                    {{ $post->title }}
                </span>
                <span class="list-meta">
                    <span class="badge">{{ $categories[$post->category] ?? $post->category }}</span>
                    <span @class(['badge', 'badge-success' => $post->status === 'published', 'badge-muted' => $post->status === 'draft'])>{{ ucfirst($post->status) }}</span>
                </span>
            </a>
        @empty
            <div class="empty">
                <p>No posts yet.</p>
                <a href="{{ route('posts.create') }}" class="link">Write your first post</a>
            </div>
        @endforelse
    </section>
@endsection
