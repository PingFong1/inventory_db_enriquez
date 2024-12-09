@props([
    'type' => 'text',
    'name',
    'label',
    'value' => '',
    'required' => false,
    'placeholder' => ''
])

<div class="form-group">
    <label for="{{ $name }}">{{ $label }}</label>
    <input 
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'form-control ' . ($errors->has($name) ? 'is-invalid' : '')]) }}>
        
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>