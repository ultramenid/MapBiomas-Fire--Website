@props([
    'tone' => 'neutral', // neutral | green | red | amber
])

@php
$tones = [
    'neutral' => 'bg-hover text-ink-muted',
    'green' => 'bg-accent-soft text-accent dark:text-accent',
    'red' => 'bg-danger/10 text-danger',
    'amber' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400',
];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-medium ' . $tones[$tone]]) }}>
    {{ $slot }}
</span>
