@props([
    'name',
    'label',
    'options' => [],
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'class' => '',
    'id' => null
])

@php
    $id = $id ?? $name;
    $placeholder = $placeholder ?? "-- Select {$label} --";
@endphp

<div class="mb-3">
    <label for="{{ $id }}" class="form-label fw-semibold">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    
    <select 
        name="{{ $name }}" 
        id="{{ $id }}" 
        class="form-select @error($name) is-invalid @enderror {{ $class }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes }}
    >
        <option value="" disabled {{ old($name) ? '' : 'selected' }}>{{ $placeholder }}</option>
        
        @foreach($options as $value => $label)
            <option value="{{ $value }}" {{ old($name) === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
