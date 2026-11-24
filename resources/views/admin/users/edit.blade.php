@extends('layouts.admin')

@section('title', 'Edit user')

@section('content')
    <div class="page-header">
        <div>
            <a href="{{ route('admin.users.show', $user) }}" class="link link-inline"><x-icon name="arrow-left" /> Back to {{ $user->name }}</a>
            <h1>Edit user</h1>
        </div>
    </div>

    <section class="card card-form">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')

            @include('admin.users._form')

            <div class="form-actions">
                <a href="{{ route('admin.users.show', $user) }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary"><span>Save changes</span></button>
            </div>
        </form>
    </section>
@endsection
