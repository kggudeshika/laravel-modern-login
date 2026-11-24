@extends('layouts.admin')

@section('title', 'Edit post')

@section('content')
    <div class="page-header">
        <div>
            <a href="{{ route('admin.posts.show', $post) }}" class="link link-inline"><x-icon name="arrow-left" /> Back to post</a>
            <h1>Edit post</h1>
        </div>
    </div>

    <section class="card card-form">
        <form method="POST" action="{{ route('admin.posts.update', $post) }}" novalidate>
            @csrf
            @method('PUT')

            <x-field name="user_id" label="Author" type="select" :options="$authors" :value="$post->user_id" required />

            @include('posts._form')

            <div class="form-actions">
                <a href="{{ route('admin.posts.show', $post) }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary"><span>Save changes</span></button>
            </div>
        </form>
    </section>
@endsection
