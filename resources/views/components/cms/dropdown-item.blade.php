@props([
    'variant' => 'default', // default | danger
    'href' => null,
])

@php
$classes = $variant === 'danger'
    ? 'flex w-full items-center gap-2 px-3 py-1.5 text-left text-sm text-danger hover:bg-hover'
    : 'flex w-full items-center gap-2 px-3 py-1.5 text-left text-sm text-ink hover:bg-hover';
@endphp

@if (isset($href))
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="button" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
