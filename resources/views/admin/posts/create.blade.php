@extends('layouts.admin')

@section('title', 'New post')

@section('content')
    <div class="page-header">
        <div>
            <a href="{{ route('admin.posts.index') }}" class="link link-inline"><x-icon name="arrow-left" /> Back to posts</a>
            <h1>New post</h1>
        </div>
    </div>

    <section class="card card-form">
        <form method="POST" action="{{ route('admin.posts.store') }}" novalidate>
            @csrf

            {{-- Admin-only field: who wrote the post --}}
            <x-field name="user_id" label="Author" type="select" :options="$authors" :value="$post->user_id" required />

            @include('posts._form')

            <div class="form-actions">
                <a href="{{ route('admin.posts.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary"><span>Create post</span></button>
            </div>
        </form>
    </section>
@endsection
