@props([
    'name',
    'label' => null,
    'hint' => null,
])

<div class="flex items-start justify-between gap-4">
    <div>
        @if ($label)
            <span class="block text-xs font-medium text-ink">{{ $label }}</span>
        @endif
        @if ($hint)
            <span class="mt-0.5 block text-xs text-ink-muted">{{ $hint }}</span>
        @endif
    </div>
    <label class="relative inline-flex h-5 w-8 shrink-0 cursor-pointer">
        <input type="checkbox" name="{{ $name }}" class="peer sr-only"
               {{ $attributes->except('class') }} />
        <span class="w-8 h-5 rounded-full bg-line-strong transition-colors peer-checked:bg-accent
                     peer-focus-visible:ring-2 peer-focus-visible:ring-accent/40"></span>
        <span class="absolute top-0.5 left-0.5 h-4 w-4 rounded-full bg-white shadow transition-transform
                     peer-checked:translate-x-3"></span>
    </label>
</div>
