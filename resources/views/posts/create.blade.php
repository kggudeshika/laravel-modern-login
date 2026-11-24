@extends('layouts.app')

@section('title', 'New post')

@section('content')
    <div class="page-header">
        <div>
            <a href="{{ route('posts.index') }}" class="link link-inline"><x-icon name="arrow-left" /> Back to posts</a>
            <h1>New post</h1>
        </div>
    </div>

    <section class="card card-form">
        {{-- POST /posts → PostController@store --}}
        <form method="POST" action="{{ route('posts.store') }}" novalidate>
            @csrf

            @include('posts._form')

            <div class="form-actions">
                <a href="{{ route('posts.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary"><span>Create post</span></button>
            </div>
        </form>
    </section>
@endsection
