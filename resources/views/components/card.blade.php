@props(['title' => null])
<div {{ $attributes->merge(['class' => 'card shadow-sm border-0 rounded-4 mb-4']) }}>
    @if ($title)
        <div class="card-header bg-white border-0 pt-3 fw-semibold">{{ $title }}</div>
    @endif
    <div class="card-body">{{ $slot }}</div>
</div>
