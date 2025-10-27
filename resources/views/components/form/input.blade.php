@props([
    'name',
    'label',
    'type' => 'text',
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'class' => '',
    'id' => null,
    'value' => null
])

@php
    $id = $id ?? $name;
    $value = $value ?? old($name);
    $placeholder = $placeholder ?? $label;
@endphp

<div class="mb-3">
    <label for="{{ $id }}" class="form-label fw-semibold">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    
    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        id="{{ $id }}" 
        class="form-control @error($name) is-invalid @enderror {{ $class }}"
        placeholder="{{ $placeholder }}"
        value="{{ $type !== 'password' ? $value : '' }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes }}
    />
    
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
