@foreach (['success' => 'success', 'error' => 'danger'] as $key => $color)
    @if (session($key))
        <div class="alert alert-{{ $color }} alert-dismissible fade show shadow-sm border-0 rounded-3 no-print" role="alert">
            {{ $key === 'success' ? '✓' : '✕' }} {{ session($key) }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
@endforeach
