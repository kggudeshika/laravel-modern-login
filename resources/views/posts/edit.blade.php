@extends('layouts.app')

@section('title', 'Edit post')

@section('content')
    <div class="page-header">
        <div>
            <a href="{{ route('posts.show', $post) }}" class="link link-inline"><x-icon name="arrow-left" /> Back to post</a>
            <h1>Edit post</h1>
        </div>
    </div>

    <section class="card card-form">
        {{-- HTML forms only send GET/POST, so @method('PUT') tells Laravel this is an update. --}}
        <form method="POST" action="{{ route('posts.update', $post) }}" novalidate>
            @csrf
            @method('PUT')

            @include('posts._form')

            <div class="form-actions">
                <a href="{{ route('posts.show', $post) }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary"><span>Save changes</span></button>
            </div>
        </form>
    </section>
@endsection
