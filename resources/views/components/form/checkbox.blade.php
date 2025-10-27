@props([
    'name',
    'label',
    'required' => false,
    'disabled' => false,
    'class' => '',
    'id' => null,
    'checked' => false
])

@php
    $id = $id ?? $name;
    $checked = $checked || old($name);
@endphp

<div class="form-check mb-3">
    <input 
        class="form-check-input @error($name) is-invalid @enderror {{ $class }}" 
        type="checkbox" 
        id="{{ $id }}" 
        name="{{ $name }}"
        value="1"
        {{ $checked ? 'checked' : '' }}
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes }}
    />
    
    <label class="form-check-label" for="{{ $id }}">
        {!! $label !!}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    
    @error($name)
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
