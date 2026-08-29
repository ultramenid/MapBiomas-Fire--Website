@props([
    'name',
    'label' => null,
    'rows' => 3,
    'hint' => null,
])

@php
$classes = 'w-full rounded-md border border-line-strong bg-surface px-2.5 py-2 text-sm text-ink placeholder:text-ink-subtle cms-focus';
@endphp

<div>
    @if ($label)
        <label for="{{ $name }}" class="mb-1.5 block text-xs font-medium text-ink">{{ $label }}</label>
    @endif
    <textarea rows="{{ $rows }}" id="{{ $name }}" name="{{ $name }}"
              {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</textarea>
    @error($name)
        <p class="mt-1.5 text-xs text-danger">{{ $message }}</p>
    @enderror
    @if ($hint && ! $errors->has($name))
        <p class="mt-1.5 text-xs text-ink-muted">{{ $hint }}</p>
    @endif
</div>
