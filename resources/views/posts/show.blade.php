@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <div class="page-header">
        <div>
            <a href="{{ route('posts.index') }}" class="link link-inline"><x-icon name="arrow-left" /> Back to posts</a>
            <h1>{{ $post->title }}</h1>
            <div class="meta">
                <span class="badge">{{ \App\Models\Post::CATEGORIES[$post->category] ?? $post->category }}</span>
                <span @class(['badge', 'badge-success' => $post->status === 'published', 'badge-muted' => $post->status === 'draft'])>{{ ucfirst($post->status) }}</span>
                @if ($post->is_featured)
                    <span class="badge badge-accent"><x-icon name="star" /> Featured</span>
                @endif
                <span class="muted">
                    {{ $post->published_on ? 'Published '.$post->published_on->format('M j, Y') : 'Not published' }}
                    · Updated {{ $post->updated_at->diffForHumans() }}
                </span>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('posts.edit', $post) }}" class="btn-secondary"><x-icon name="edit" /> Edit</a>
            <form method="POST" action="{{ route('posts.destroy', $post) }}" data-confirm="Delete this post? This cannot be undone.">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger"><x-icon name="trash" /> Delete</button>
            </form>
        </div>
    </div>

    <article class="card prose">
        {{-- e() escapes HTML, nl2br() keeps the line breaks the author typed. --}}
        {!! nl2br(e($post->body)) !!}
    </article>
@endsection
