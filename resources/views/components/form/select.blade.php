@props([
    'label' => '',
    'name' => '',
    'required' => false,
    'options' => [],
    'selected' => '',
    'placeholder' => 'Select an option'
])

<div class="form-group">
    @if(isset($label_content))
        {{ $label_content }}
    @else
        <label for="{{ $name }}">{{ $label }}</label>
    @endif

    <select 
        name="{{ $name }}" 
        id="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'form-control ' . ($errors->has($name) ? 'is-invalid' : '')]) }}>
        
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $value => $label)
            <option value="{{ $value }}" {{ old($name, $selected) == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>

    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>