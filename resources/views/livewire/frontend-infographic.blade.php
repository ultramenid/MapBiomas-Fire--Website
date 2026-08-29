<div>
    {{-- Tab kategori memakai partial yang sama dengan ATBD dan factsheet,
         dalam mode Livewire supaya tidak memuat ulang halaman di sebelah
         saringan bulan yang juga reaktif. --}}
    @include('partials.categoryTabs', [
        'wire' => 'category',
        'active' => $category,
        'label' => __('Infographic category'),
        'tabs' => [
            '' => __('All'),
            'annual' => __('Annual'),
            'monthly' => __('Monthly'),
        ],
    ])

    @forelse ($data as $item)
        <div class="mt-4 flex flex-col gap-2">
            <a href="{{ asset('storage/files/photos/'.$item->img) }}"><img src="{{ asset('storage/files/photos/'.$item->img) }}" alt="{{$item->title}}" class="w-full h-full mt-4"></a>
            <p class="text-sm font-medium uppercase tracking-wide text-landy/60">{{ \App\Livewire\FrontendInfographic::monthLabel($item->period, $item->publishdate) }}</p>
            <a href="{{ asset('storage/files/photos/'.$item->img) }}" class="text-landy text-xl font-semibold ">{{$item->title}}</a>
        </div>
    @empty
        <p class="mt-6 text-landy/60">{{ __('Belum ada infografis terbit.') }}</p>
    @endforelse

    {{ $data->links('livewire.pagination') }}
</div>
