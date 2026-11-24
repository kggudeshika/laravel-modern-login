@extends('layouts.admin')

@section('title', 'New user')

@section('content')
    <div class="page-header">
        <div>
            <a href="{{ route('admin.users.index') }}" class="link link-inline"><x-icon name="arrow-left" /> Back to users</a>
            <h1>New user</h1>
        </div>
    </div>

    <section class="card card-form">
        {{-- enctype is required for file uploads --}}
        <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data" novalidate>
            @csrf

            @include('admin.users._form')

            <div class="form-actions">
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary"><span>Create user</span></button>
            </div>
        </form>
    </section>
@endsection
