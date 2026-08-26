{{-- Pemilih edisi dokumen (mis. ATBD monthly/annual).

     Sengaja tab, bukan tombol: keduanya adalah dua edisi dari satu dokumen yang
     sama, bukan dua tujuan berbeda. Garis bawah aktif duduk di atas garis wadah
     lewat -mb-px, jadi keduanya menyatu jadi satu garis.

     $route  nama rute tujuan
     $active kunci kategori yang sedang tampil
     $tabs   opsional, default annual/monthly
     $label  opsional, aria-label untuk navigasinya --}}
@php
    $tabs = $tabs ?? ['annual' => __('Annual'), 'monthly' => __('Monthly')];

    /* $wire: nama properti Livewire. Bila diisi, tab menyetel properti itu
       tanpa memuat ulang halaman — dipakai di halaman yang saringan lainnya
       juga reaktif. Bila kosong, tab berpindah lewat URL seperti biasa. */
    $wire = $wire ?? null;
@endphp
<nav class="mb-6 mt-4 flex gap-8 border-b border-gray-200 text-sm"
     aria-label="{{ $label ?? __('Category') }}">
    @foreach ($tabs as $key => $text)
        <{{ $wire ? 'button' : 'a' }}
           @if ($wire) type="button" wire:click="$set('{{ $wire }}', '{{ $key }}')"
           @else href="{{ route($route, ['lang' => app()->getLocale(), 'cat' => $key]) }}" @endif
           @if ($active === $key) aria-current="page" @endif
           class="-mb-px border-b-2 pb-3 transition-colors @if ($active === $key) border-red-600 font-semibold text-red-600 @else border-transparent text-gray-500 hover:border-gray-300 hover:text-black @endif">
            {{ $text }}
        </{{ $wire ? 'button' : 'a' }}>
    @endforeach
</nav>
