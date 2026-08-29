{{-- Row-action menu. Positioned with `fixed` against the viewport so no
    scrolling table wrapper (overflow-x-auto) can clip it; flips upward when
    there is no room below the button. --}}
<div x-data="{
        open: false,
        pos: {},
        toggle() {
            if (this.open) { this.open = false; return; }
            const r = this.$refs.button.getBoundingClientRect();
            this.pos = { left: (r.right - 160) + 'px', top: (r.bottom + 4) + 'px' };
            this.open = true;
            this.$nextTick(() => {
                const menu = this.$refs.menu;
                const h = menu.offsetHeight || 74;
                if (r.bottom + 4 + h > window.innerHeight - 8) {
                    this.pos = { left: (r.right - 160) + 'px', top: Math.max(8, r.top - h - 4) + 'px' };
                }
            });
        },
    }"
     @click.outside="open = false"
     @keydown.escape.window="open = false"
     @resize.window="open = false"
     @scroll.window="open = false"
     class="relative inline-block">
    <button type="button" x-ref="button" @click="toggle()" aria-label="Row actions"
            class="inline-flex h-7 w-7 items-center justify-center rounded-md text-ink-muted transition-colors hover:bg-hover hover:text-ink">
        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <circle cx="12" cy="5" r="1.6" />
            <circle cx="12" cy="12" r="1.6" />
            <circle cx="12" cy="19" r="1.6" />
        </svg>
    </button>
    <div x-show="open" x-cloak x-ref="menu" x-bind:style="pos"
         x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed z-40 w-40 rounded-md border border-line bg-surface py-1 shadow-lg">
        {{ $slot }}
    </div>
</div>