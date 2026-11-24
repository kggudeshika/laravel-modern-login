@extends('layouts.admin')

@section('title', 'Users')

@section('content')
    <div class="page-header">
        <div>
            <h1>Users</h1>
            <p class="muted">Create, view, edit and delete user accounts.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn-primary"><x-icon name="plus" /> <span>New user</span></a>
    </div>

    <form method="GET" action="{{ route('admin.users.index') }}" class="toolbar">
        <div class="input-wrap toolbar-search">
            <x-icon name="search" class="input-icon" />
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search name or email…" aria-label="Search users">
        </div>
        <div class="input-wrap no-icon">
            <select name="role" aria-label="Filter by role">
                <option value="">All roles</option>
                <option value="admin" @selected(request('role') === 'admin')>Admins</option>
                <option value="user" @selected(request('role') === 'user')>Users</option>
            </select>
        </div>
        <div class="input-wrap no-icon">
            <select name="verified" aria-label="Filter by email status">
                <option value="">Any email status</option>
                <option value="yes" @selected(request('verified') === 'yes')>Verified</option>
                <option value="no" @selected(request('verified') === 'no')>Not verified</option>
            </select>
        </div>
        <button type="submit" class="btn-secondary">Filter</button>
        @if (request()->hasAny(['search', 'role', 'verified']))
            <a href="{{ route('admin.users.index') }}" class="link">Clear</a>
        @endif
    </form>

    <section class="card card-flush">
        @if ($users->isEmpty())
            <div class="empty"><p>No users found.</p></div>
        @else
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Posts</th>
                            <th>Joined</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.users.show', $user) }}" class="person">
                                        <x-avatar :user="$user" size="sm" />
                                        <span>{{ $user->name }}<small>{{ $user->email }}</small></span>
                                    </a>
                                </td>
                                <td>
                                    @if ($user->isAdmin())
                                        <span class="badge badge-accent"><x-icon name="shield" /> Admin</span>
                                    @else
                                        <span class="badge">User</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($user->hasVerifiedEmail())
                                        <span class="badge badge-success">Verified</span>
                                    @else
                                        <span class="badge badge-warning">Unverified</span>
                                    @endif
                                </td>
                                <td>{{ $user->posts_count }}</td>
                                <td class="muted">{{ $user->created_at->format('M j, Y') }}</td>
                                <td>
                                    <div class="row-actions">
                                        <a href="{{ route('admin.users.show', $user) }}" class="btn-icon" title="View" aria-label="View {{ $user->name }}"><x-icon name="eye-view" /></a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn-icon" title="Edit" aria-label="Edit {{ $user->name }}"><x-icon name="edit" /></a>
                                        @unless (auth()->user()->is($user))
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" data-confirm="Delete {{ $user->name }} and all their posts?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-icon btn-icon-danger" title="Delete" aria-label="Delete {{ $user->name }}"><x-icon name="trash" /></button>
                                            </form>
                                        @endunless
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <div class="pager">{{ $users->links('partials.pagination') }}</div>
@endsection
