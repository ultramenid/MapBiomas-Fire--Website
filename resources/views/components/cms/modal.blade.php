@props([
    'open', // Alpine expression controlling visibility, e.g. "$wire.deleter"
    'close', // Alpine expression executed when backdrop / Cancel is clicked
    'title' => null,
    'maxWidth' => 'max-w-md',
])

<div x-data x-show="{{ $open }}" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
     role="dialog" aria-modal="true" @keydown.escape.window="{{ $close }}">
    <div x-show="{{ $open }}" x-transition.opacity.duration.150ms class="absolute inset-0 bg-black/50"
         @click="{{ $close }}"></div>
    <div x-show="{{ $open }}"
         x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="relative w-full {{ $maxWidth }} rounded-lg border border-line bg-surface p-5 shadow-xl">
        @if ($title)
            <h3 class="text-sm font-semibold text-ink">{{ $title }}</h3>
        @endif
        <div class="{{ $title ? 'mt-3' : '' }}">
            {{ $slot }}
        </div>
    </div>
</div>
