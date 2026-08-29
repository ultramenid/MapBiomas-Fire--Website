@props([
    'title',
    'description' => null,
])

<div class="mb-6 flex flex-wrap items-start justify-between gap-3 border-b border-line pb-5">
    <div>
        <h1 class="text-xl font-semibold tracking-tight text-ink">{{ $title }}</h1>
        @if ($description)
            <p class="mt-1 text-sm text-ink-muted">{{ $description }}</p>
        @endif
    </div>
    @isset($actions)
        <div class="flex items-center gap-2">{{ $actions }}</div>
    @endisset
</div>
