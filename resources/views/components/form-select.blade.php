@props(['name', 'label', 'options', 'value' => null])
<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <select id="{{ $name }}" name="{{ $name }}"
        {{ $attributes->merge(['class' => 'form-select'.($errors->has($name) ? ' is-invalid' : '')]) }}>
        <option value="">-- Pilih --</option>
        @foreach ($options as $k => $v)
            <option value="{{ $k }}" @selected((string) old($name, $value) === (string) $k)>{{ $v }}</option>
        @endforeach
    </select>
    @error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
