{{-- One-time messages set with ->with('success', '...') or ->with('error', '...') --}}
@if (session('success'))
    <div class="alert alert-success flash" role="status">
        <x-icon name="check" /> {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-error" role="alert">
        <x-icon name="alert" /> {{ session('error') }}
    </div>
@endif
