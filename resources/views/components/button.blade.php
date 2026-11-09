@props(['type' => 'submit', 'color' => 'primary'])
<button type="{{ $type }}" {{ $attributes->merge(['class' => "btn btn-$color"]) }}>{{ $slot }}</button>
