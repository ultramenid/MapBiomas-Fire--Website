{{-- Modal konfirmasi hapus, gaya CMS (Vercel monokrom).
     Kontrak Livewire tidak berubah: induk punya x-data { open: @entangle('deleter') },
     aksi `deleting(id)` menghapus, `closeDelete` menutup. --}}
<div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
     role="dialog" aria-modal="true" @keydown.escape.window="open = false">
    <div x-show="open" x-transition.opacity.duration.150ms class="absolute inset-0 bg-black/50"
         @click="open = false"></div>
    <div x-show="open"
         x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="relative w-full max-w-md rounded-lg border border-line bg-surface p-5 shadow-xl">
        <h3 class="text-sm font-semibold text-ink">Delete “{{ $deleteName }}”?</h3>
        <p class="mt-2 text-sm text-ink-muted">
            This action cannot be undone.
        </p>
        <div class="mt-5 flex justify-end gap-2">
            <x-cms.button variant="secondary" wire:click="closeDelete" loadingTarget="closeDelete">Cancel</x-cms.button>
            <x-cms.button variant="danger" wire:click="deleting({{ $deleteID }})" loadingTarget="deleting({{ $deleteID }})">Delete</x-cms.button>
        </div>
    </div>
</div>
