@props([
    'title' => 'Nothing here yet',
    'description' => null,
])

<div class="flex flex-col items-center justify-center rounded-md border border-dashed border-line-strong bg-surface px-6 py-16 text-center">
    <svg class="h-8 w-8 text-ink-subtle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
         stroke="currentColor" stroke-width="1.5">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
        <path d="M14 2v6h6" />
    </svg>
    <p class="mt-3 text-sm font-medium text-ink">{{ $title }}</p>
    @if ($description)
        <p class="mt-1 max-w-sm text-sm text-ink-muted">{{ $description }}</p>
    @endif
    @isset($action)
        <div class="mt-5">{{ $action }}</div>
    @endisset
</div>
