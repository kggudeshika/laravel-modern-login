@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="page-header">
        <div>
            <h1>Admin dashboard</h1>
            <p class="muted">Everything happening across the site.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.users.create') }}" class="btn-secondary"><x-icon name="users" /> New user</a>
            <a href="{{ route('admin.posts.create') }}" class="btn-primary"><x-icon name="plus" /> <span>New post</span></a>
        </div>
    </div>

    <div class="stats stats-6">
        <a href="{{ route('admin.users.index') }}" class="stat"><span class="stat-label">Users</span><strong>{{ $stats['users'] }}</strong></a>
        <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="stat"><span class="stat-label">Admins</span><strong>{{ $stats['admins'] }}</strong></a>
        <a href="{{ route('admin.users.index', ['verified' => 'no']) }}" class="stat"><span class="stat-label">Unverified</span><strong>{{ $stats['unverified'] }}</strong></a>
        <a href="{{ route('admin.posts.index') }}" class="stat"><span class="stat-label">Posts</span><strong>{{ $stats['posts'] }}</strong></a>
        <a href="{{ route('admin.posts.index', ['status' => 'published']) }}" class="stat"><span class="stat-label">Published</span><strong>{{ $stats['published'] }}</strong></a>
        <a href="{{ route('admin.posts.index', ['status' => 'draft']) }}" class="stat"><span class="stat-label">Drafts</span><strong>{{ $stats['drafts'] }}</strong></a>
    </div>

    <div class="grid-2">
        <section class="card">
            <div class="card-header">
                <h2>Newest users</h2>
                <a href="{{ route('admin.users.index') }}" class="link">View all</a>
            </div>
            @forelse ($latestUsers as $user)
                <a href="{{ route('admin.users.show', $user) }}" class="list-row">
                    <span class="person">
                        <x-avatar :user="$user" size="sm" />
                        <span>{{ $user->name }}<small>{{ $user->email }}</small></span>
                    </span>
                    <span class="list-meta">
                        @if ($user->isAdmin())<span class="badge badge-accent">Admin</span>@endif
                        <span class="muted">{{ $user->created_at->diffForHumans() }}</span>
                    </span>
                </a>
            @empty
                <div class="empty"><p>No users yet.</p></div>
            @endforelse
        </section>

        <section class="card">
            <div class="card-header">
                <h2>Latest posts</h2>
                <a href="{{ route('admin.posts.index') }}" class="link">View all</a>
            </div>
            @forelse ($latestPosts as $post)
                <a href="{{ route('admin.posts.show', $post) }}" class="list-row">
                    <span class="list-title">{{ $post->title }}<small class="muted">by {{ $post->user->name }}</small></span>
                    <span @class(['badge', 'badge-success' => $post->status === 'published', 'badge-muted' => $post->status === 'draft'])>{{ ucfirst($post->status) }}</span>
                </a>
            @empty
                <div class="empty"><p>No posts yet.</p></div>
            @endforelse
        </section>
    </div>
@endsection
