@props([
    'name',
    'label' => null,
    'type' => 'text',
    'hint' => null,
])

@php
$classes = 'h-8 w-full rounded-md border border-line-strong bg-surface px-2.5 text-sm text-ink placeholder:text-ink-subtle cms-focus';
@endphp

<div>
    @if ($label)
        <label for="{{ $name }}" class="mb-1.5 block text-xs font-medium text-ink">{{ $label }}</label>
    @endif
    <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}"
           {{ $attributes->merge(['class' => $classes]) }} />
    @error($name)
        <p class="mt-1.5 text-xs text-danger">{{ $message }}</p>
    @enderror
    @if ($hint && ! $errors->has($name))
        <p class="mt-1.5 text-xs text-ink-muted">{{ $hint }}</p>
    @endif
</div>
