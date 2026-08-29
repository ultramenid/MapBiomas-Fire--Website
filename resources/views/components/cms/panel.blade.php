@props([
    'title' => null,
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'rounded-md border border-line bg-surface']) }}>
    @if ($title)
        <div class="border-b border-line px-4 py-3">
            <h2 class="text-sm font-semibold text-ink">{{ $title }}</h2>
            @if ($description)
                <p class="mt-0.5 text-xs text-ink-muted">{{ $description }}</p>
            @endif
        </div>
    @endif
    <div class="p-4">
        {{ $slot }}
    </div>
</div>
