<x-cms-layout title="Preview card">
    <x-cms.page-header title="Preview card — {{ $data->titleID }}" description="Side-by-side Indonesian / English news card, exactly as it appears on the landing page.">
        <x-slot:actions>
            <x-cms.badge :tone="$data->status == 1 ? 'green' : 'amber'">
                {{ $data->status == 1 ? 'Published' : 'Draft' }}
            </x-cms.badge>
        </x-slot:actions>
    </x-cms.page-header>

    {{-- Kartu direplikasi dari section kabar di landing (latar #fdf0ee)
         supaya thumbnail, judul, dan deskripsi terlihat persis seperti
         nantinya — termasuk ruang minimum judul/deskripsi. --}}
    <div class="rounded-md border border-line px-[4%] py-[5%] bg-[#fdf0ee]">
        <div class="grid gap-[4.5%] gap-y-8 sm:grid-cols-2">
            @php $cardImg = 'storage/files/photos/' . $data->img; @endphp
            @foreach ($cards as $card)
                <div class="block">
                    <div class="overflow-hidden">
                        @if ($data->img && file_exists(public_path($cardImg)))
                            <img src="{{ asset($cardImg) }}" alt="{{ $card['title'] }}"
                                 class="aspect-[476/268] w-full object-cover">
                        @else
                            <div class="flex aspect-[476/268] w-full items-center justify-center bg-neutral-100 text-neutral-300">
                                <svg class="h-12 w-12" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="1.5">
                                    <rect x="3" y="3" width="18" height="18" rx="2" />
                                    <circle cx="9" cy="9" r="2" />
                                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <p class="mt-4 font-display text-[14px] font-medium text-ember">
                        {{ $card['date'] }}
                    </p>

                    <h3 class="mt-1 min-h-[26px] font-display text-[18px] font-semibold leading-[26px] text-neutral-900">
                        {{ $card['title'] }}
                    </h3>

                    <div class="mt-2 min-h-[88px] max-w-[60ch] font-display text-[14px] font-normal leading-[22px] text-neutral-500">
                        {{ $card['description'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <p class="mt-3 text-xs text-ink-muted">
        Left: Indonesia · Right: English — same size &amp; colors as the cards on the landing page.
    </p>

    <div class="mt-6 flex items-center gap-2 border-t border-line pt-5">
        <x-cms.button variant="secondary" href="{{ url('/cms/previewnews/' . $data->id) }}" target="_blank">Preview detail page →</x-cms.button>
        <x-cms.button href="{{ url('/cms/listnews') }}">Back to news</x-cms.button>
    </div>
</x-cms-layout>
