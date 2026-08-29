@props([
    'variant' => 'primary', // primary | secondary | danger | ghost
    'size' => 'sm', // sm | md
    'type' => 'button',
    'href' => null,
    'loadingTarget' => null, // wire:loading target — swaps label for a spinner
])

@php
$base = 'inline-flex items-center justify-center gap-1.5 rounded-md font-medium whitespace-nowrap transition-colors disabled:pointer-events-none disabled:opacity-50 select-none';
$sizes = ['sm' => 'h-8 px-3 text-xs', 'md' => 'h-9 px-4 text-sm'];
$variants = [
    'primary' => 'bg-accent text-accent-fg hover:bg-accent-hover shadow-sm',
    'secondary' => 'bg-surface border border-line-strong text-ink hover:bg-hover',
    'danger' => 'bg-danger text-white hover:bg-danger-hover shadow-sm',
    'ghost' => 'text-ink-muted hover:text-ink hover:bg-hover',
];
$classes = $base . ' ' . $sizes[$size] . ' ' . $variants[$variant];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        <span wire:loading.remove @if($loadingTarget) wire:target="{{ $loadingTarget }}" @endif
              class="inline-flex items-center gap-1.5">{{ $slot }}</span>
        @if ($loadingTarget)
            <span wire:loading wire:target="{{ $loadingTarget }}" class="inline-flex items-center">
                <svg class="animate-spin h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </span>
        @endif
    </button>
@endif
