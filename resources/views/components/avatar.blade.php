@props(['user', 'size' => 'md'])

{{-- Profile picture, or initials when the user has none. Usage: <x-avatar :user="$user" size="lg" /> --}}
@if ($user->avatarUrl())
    <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" {{ $attributes->merge(['class' => "avatar avatar-{$size} avatar-img"]) }}>
@else
    <span {{ $attributes->merge(['class' => "avatar avatar-{$size}"]) }} aria-hidden="true">{{ $user->initials() }}</span>
@endif
