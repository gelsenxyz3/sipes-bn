@props(['name', 'label', 'type' => 'text', 'value' => null])
<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}"
        {{ $attributes->merge(['class' => 'form-control'.($errors->has($name) ? ' is-invalid' : '')]) }}>
    @error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
