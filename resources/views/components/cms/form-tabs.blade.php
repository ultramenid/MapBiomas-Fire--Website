@props([])

<div x-data="{ tab: 'en' }" {{ $attributes }}>
    <div class="mb-5 flex justify-end gap-1 border-b border-line">
        <button type="button" @click="tab = 'en'"
                class="-mb-px border-b-2 px-3 py-2 text-sm transition-colors"
                :class="tab === 'en' ? 'border-accent font-semibold text-ink' : 'border-transparent text-ink-muted hover:text-ink'">
            English
        </button>
        <button type="button" @click="tab = 'id'"
                class="-mb-px border-b-2 px-3 py-2 text-sm transition-colors"
                :class="tab === 'id' ? 'border-accent font-semibold text-ink' : 'border-transparent text-ink-muted hover:text-ink'">
            Bahasa Indonesia
        </button>
    </div>
    <div x-show="tab === 'en'" x-cloak>{{ $en }}</div>
    <div x-show="tab === 'id'" x-cloak>{{ $idn }}</div>
</div>
