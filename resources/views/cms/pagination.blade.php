@php
/** Minimal Vercel-style paginator: Previous / "Page X of Y" / Next. Livewire context. */
@endphp

@if ($paginator->hasPages())
    <nav class="mt-4 flex items-center justify-between gap-3" aria-label="Pagination">
        <p class="text-xs text-ink-muted">
            Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
        </p>
        <div class="flex items-center gap-2">
            @if ($paginator->onFirstPage())
                <span class="inline-flex h-8 cursor-not-allowed items-center rounded-md border border-line px-3 text-xs text-ink-subtle">
                    Previous
                </span>
            @else
                <button type="button" wire:click="previousPage" wire:loading.attr="disabled"
                        class="inline-flex h-8 items-center rounded-md border border-line-strong bg-surface px-3 text-xs text-ink transition-colors hover:bg-hover">
                    Previous
                </button>
            @endif

            @if ($paginator->hasMorePages())
                <button type="button" wire:click="nextPage" wire:loading.attr="disabled"
                        class="inline-flex h-8 items-center rounded-md border border-line-strong bg-surface px-3 text-xs text-ink transition-colors hover:bg-hover">
                    Next
                </button>
            @else
                <span class="inline-flex h-8 cursor-not-allowed items-center rounded-md border border-line px-3 text-xs text-ink-subtle">
                    Next
                </span>
            @endif
        </div>
    </nav>
@endif
