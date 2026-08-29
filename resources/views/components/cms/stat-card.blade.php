@props([
    'label',
    'value',
    'href' => null,
])

@php
$classes = 'block rounded-md border border-line bg-surface p-4 transition-colors';
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes . ' hover:border-line-strong']) }}>
@else
    <div {{ $attributes->merge(['class' => $classes]) }}>
@endif

        <p class="text-xs font-medium text-ink-muted">{{ $label }}</p>
        <p class="mt-2 text-2xl font-semibold tracking-tight text-ink">{{ $value }}</p>
        @isset($slot)
            <div class="mt-1">{{ $slot }}</div>
        @endisset

@if ($href)
    </a>
@else
    </div>
@endif
