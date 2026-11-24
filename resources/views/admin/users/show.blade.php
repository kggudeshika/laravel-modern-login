@extends('layouts.admin')

@section('title', $user->name)

@section('content')
    <a href="{{ route('admin.users.index') }}" class="link link-inline"><x-icon name="arrow-left" /> Back to users</a>

    <div class="profile-hero card">
        <x-avatar :user="$user" size="xl" />
        <div class="profile-hero-body">
            <h1>{{ $user->name }}</h1>
            <p class="muted">{{ $user->email }}</p>
            <div class="meta">
                @if ($user->isAdmin())
                    <span class="badge badge-accent"><x-icon name="shield" /> Admin</span>
                @else
                    <span class="badge">User</span>
                @endif
                @if ($user->hasVerifiedEmail())
                    <span class="badge badge-success">Verified {{ $user->email_verified_at->format('M j, Y') }}</span>
                @else
                    <span class="badge badge-warning">Email not verified</span>
                @endif
                <span class="muted">Joined {{ $user->created_at->format('M j, Y') }} · {{ $user->posts_count }} {{ Str::plural('post', $user->posts_count) }}</span>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn-secondary"><x-icon name="edit" /> Edit</a>
            @unless (auth()->user()->is($user))
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" data-confirm="Delete {{ $user->name }} and all their posts? This cannot be undone.">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger"><x-icon name="trash" /> Delete</button>
                </form>
            @endunless
        </div>
    </div>

    <section class="card">
        <div class="card-header">
            <h2>Recent posts</h2>
            <a href="{{ route('admin.posts.index', ['author' => $user->id]) }}" class="link">All posts by {{ $user->name }}</a>
        </div>
        @forelse ($posts as $post)
            <a href="{{ route('admin.posts.show', $post) }}" class="list-row">
                <span class="list-title">{{ $post->title }}</span>
                <span @class(['badge', 'badge-success' => $post->status === 'published', 'badge-muted' => $post->status === 'draft'])>{{ ucfirst($post->status) }}</span>
            </a>
        @empty
            <div class="empty"><p>This user has no posts yet.</p></div>
        @endforelse
    </section>
@endsection
