@php
    $lang = app()->getLocale();

    /** Angka diambil dari infografis Koleksi 1 pada berkas desain. */
    /**
     * Angka dieja mengikuti lokal: Indonesia memakai koma desimal dan titik
     * ribuan, Inggris sebaliknya. Tanpa ini "232.996" terbaca sebagai 232 koma
     * 996 oleh pembaca berbahasa Inggris — meleset seribu kali lipat.
     */
    $angka = fn (float $n, int $desimal = 0): string => app()->getLocale() === 'id'
        ? number_format($n, $desimal, ',', '.')
        : number_format($n, $desimal, '.', ',');

    // Rentang tahun memakai tanda hubung tak-putus (U+2011) supaya barisnya
    // pindah sebelum rentang, bukan memotongnya jadi "2000-" / "2024".
    $highlights = [
        ['value' => $angka(9.5, 1), 'unit' => __('juta ha'), 'label' => __('2000-2024 burned areas')],
        ['value' => $angka(40), 'unit' => '%', 'label' => __('2000-2024 burned areas are on peat land')],
        ['value' => $angka(178232), 'unit' => __('ha'), 'label' => __('January-June 2026 burned areas')],
        ['value' => $angka(21), 'unit' => '%', 'label' => __('January-June 2026 burned areas are in Kalimantan')],
    ];

    /** Tanggal publikasi kabar mengikuti bahasa aktif (id/en). */
    $tanggal = fn (string $date): string => \Illuminate\Support\Carbon::parse($date)
        ->locale(app()->getLocale())
        ->translatedFormat('j F Y');

    $partners = [
        ['heading' => __('An Initiative of:'), 'image' => 'partner-auriga.png', 'alt' => 'Auriga Nusantara', 'width' => 'w-[129px]'],
        // Sembilan logo dalam satu berkas 868px; di layar sempit digulirkan
        // mendatar agar tidak menyusut jadi 28px dan tak terbaca.
        ['heading' => __('Collaboration:'), 'image' => 'partner-kolaborasi.png', 'alt' => 'Rimba Papua, Save Our Borneo, Borneo, HaKa, Lingkar Kalimantan, HaKI, Genesis, UKOMIU, Sampan Kalimantan', 'width' => 'w-[min(100%,620px)]', 'scroll' => true],
        ['heading' => __('Supported by:'), 'image' => 'partner-dukungan.png', 'alt' => 'MapBiomas dan Woods & Wayside International', 'width' => 'w-[213px]'],
    ];

    /**
     * Atribut Alpine untuk elemen yang muncul saat masuk viewport. State
     * disimpan di Alpine, bukan class CSS, supaya tidak bergantung pada
     * urutan cascade Tailwind.
     */
    $reveal = fn (int $delay = 0): string => 'x-data="{ shown: false }" x-intersect.once="shown = true" '
        .':class="shown ? \'opacity-100 translate-y-0\' : \'opacity-0 translate-y-6\'" '
        .'style="transition-delay: '.$delay.'ms"';

    /**
     * Berkas foto bisa hilang: baris CMS lama, unggahan yang gagal, atau
     * environment yang belum menjalankan storage:link. Gambar rusak lebih
     * buruk daripada tidak ada gambar, jadi tampilkan placeholder.
     */
    $adaFoto = fn (?string $img): bool => $img && file_exists(public_path('storage/files/photos/'.$img));

    /**
     * Kolom EN nullable (titleEN, descriptionEN), kolom ID tidak. Jadi hanya
     * halaman /en yang bisa kebagian teks kosong — tanpa fallback, strip_tags
     * menerima null dan kartunya tampil melompong.
     */
    $teks = fn (?string $isi, string $ganti): string => trim(strip_tags((string) $isi)) ?: $ganti;

    $anim = 'transition duration-700 ease-[cubic-bezier(0.16,0.84,0.28,1)]';
    $shell = 'mx-auto w-[90%] max-w-[1520px]';
    $frame = 'mx-auto w-full max-w-6xl';
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-pt-24 scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    @include('partials.indexMeta')
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.png') }}">
    @yield('meta')

    {{-- Keluarga huruf landing: Poppins (display), Instrument Sans (body),
         IBM Plex Mono (data). Open Sans halaman lama tetap dimuat app.css. --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Instrument+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-9BQRCF1TCG"></script>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-2GJ5GW51ZT"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-2GJ5GW51ZT');
    </script>
</head>
{{-- x-data di <body> memberi seluruh halaman satu cakupan Alpine, sehingga
     x-intersect pada elemen yang jauh di bawah ikut diinisialisasi.
     font-instrument: body landing memakai Instrument Sans tanpa mengubah
     font-sans (Open Sans) halaman lain. --}}
<body class="font-instrument mx-auto max-w-6xl overflow-x-clip antialiased" x-data>

    {{-- Laci seluler situs, dipakai sampai lg karena di bawah itu menu
         6 item tidak muat sebaris. --}}
    @include('partials.navMobile', ['hideFrom' => 'lg'])

    {{-- Navbar sama dengan halaman lain; varian overlay mengambang di atas hero. --}}
    @include('partials.navPC', ['overlay' => true])

    <main>
        {{-- ─────────────────────────  HERO  ─────────────────────────
             Kedua kartu adalah lapisan tembus di atas foto yang sama: kiri
             putih 68,5%, kanan multiply #f87171. --}}
        <section class="relative isolate ml-[calc(50%-50vw)] w-screen pb-8 lg:pb-0">
            <picture>
                <source media="(min-width: 1024px)" srcset="{{ asset('images/hero-fire.jpg') }}">
                <img src="{{ asset('images/hero-fire.jpg') }}"
                     alt="Pandangan udara kepulan asap kebakaran di atas tutupan hutan"
                     class="h-[52vh] min-h-[300px] w-full object-cover lg:h-[max(660px,min(49.8vw,860px))]"
                     fetchpriority="high">
            </picture>

            {{-- Di layar kecil kartu mengalir di bawah foto dan membawa fotonya
                 sendiri; mulai lg kartu menumpuk foto hero seperti desain. --}}
            <div class="relative -mt-14 lg:absolute lg:inset-x-0 lg:bottom-[11.2%] lg:mt-0">
                <div class="{{ $frame }}">
                    <div class="{{ $shell }} grid lg:grid-cols-2">

                    {{-- monthly --}}
                    <article class="[container-type:inline-size] relative overflow-hidden lg:aspect-[760/428] border border-black">
                        <img src="{{ asset('images/hero-fire.jpg') }}" alt="" aria-hidden="true"
                             class="absolute inset-0 h-full w-full object-cover lg:hidden">
                        <div class="absolute inset-0 bg-white/[0.685]"></div>

                        <div class="relative flex h-full flex-col px-[7.6cqw] pb-[8.8cqw] pt-[8.2cqw]">
                            <h2 class="font-display text-[clamp(1.55rem,6.6cqw,3.3rem)] font-semibold lowercase leading-none tracking-[-0.005em] text-ember">
                                {{ __('monthly fire') }}
                            </h2>
                            <p class="mt-[5.1cqw] font-display text-[clamp(1.1rem,3.8cqw,1.9rem)] font-medium leading-[1.3] text-neutral-900">
                                {{ __('2026 maps and data of monthly burned areas in Indonesia') }}
                            </p>

                            {{-- Seluler: dua kolom sama lebar supaya tidak ada yang
                                 melompat ke baris sendiri. lg: kembali sebaris auto. --}}
                            {{-- Grid dua kolom sama lebar di semua ukuran: lebar tombol jadi
                                 tidak bergantung panjang labelnya, sehingga tombol kartu kiri
                                 dan kanan tetap segaris walau labelnya berbeda. --}}
                            <div class="mt-auto grid grid-cols-2 gap-2 pt-8 lg:gap-[2.6cqw] lg:pt-0">
                                <a href="{{ $factsheetLink ?? route('factsheet', $lang) }}"
                                   class="border border-ember px-3 py-2.5 text-center lg:px-[3.5cqw] lg:py-[2.05cqw] font-display text-[clamp(0.85rem,3.16cqw,1.55rem)] font-light leading-[1.2] text-neutral-900 transition-colors hover:bg-ember hover:text-white">
                                       {{ __('Factsheet') }}
                                </a>
                                <a href="https://plataform.firemonitor-id.mapbiomas.org/"
                                   class="border border-ember px-3 py-2.5 text-center lg:px-[3.5cqw] lg:py-[2.05cqw] font-display text-[clamp(0.85rem,3.16cqw,1.55rem)] font-light leading-[1.2] text-neutral-900 transition-colors hover:bg-ember hover:text-white">
                                    {{ __('Access The Platform') }}
                                </a>
                            </div>
                        </div>
                    </article>

                    {{-- annual --}}
                    <article class="[container-type:inline-size] relative overflow-hidden lg:aspect-[760/428] border border-black">
                        <img src="{{ asset('images/hero-fire.jpg') }}" alt="" aria-hidden="true"
                             class="absolute inset-0 h-full w-full object-cover lg:hidden">
                        {{-- Tint solid ber-alpha, mekanisme yang sama persis dengan
                             kartu kiri (putih 68,5%) dan dengan alpha yang sama pula.
                             Sebelumnya di sini dipakai mix-blend-multiply, dan itu
                             menimbulkan dua masalah: tepi bawahnya lenyap di atas hutan
                             gelap karena multiply tidak punya warna dasar sendiri, dan
                             tepi atasnya tergambar ~1px lebih rendah karena lapisan
                             blend dirasterisasi terpisah sementara tepi kartu jatuh di
                             posisi pecahan. --}}
                        <div class="absolute inset-0 bg-[#96241c]/[0.685]"></div>

                        <div class="relative flex h-full flex-col px-[7.5cqw] pb-[8.8cqw] pt-[8.2cqw]">
                            <h2 class="font-display text-[clamp(1.55rem,6.6cqw,3.3rem)] font-semibold lowercase leading-none tracking-[-0.005em] text-white">
                                {{ __('annual fire') }}
                            </h2>
                            <p class="mt-[5.1cqw] font-display text-[clamp(1.1rem,3.8cqw,1.9rem)] font-medium leading-[1.3] text-white">
                                {{ __('Annual burned areas maps and data, 2000-2024') }}
                            </p>

                            {{-- Seluler: dua kolom sama lebar supaya tidak ada yang
                                 melompat ke baris sendiri. lg: kembali sebaris auto. --}}
                            <div class="mt-auto grid grid-cols-2 gap-2 pt-8 lg:gap-[2.6cqw] lg:pt-0">
                                <a href="{{ route('atbd', app()->getLocale()) }}"
                                    class="border border-white px-3 py-2.5 text-center lg:px-[3.5cqw] lg:py-[2.05cqw] font-display text-[clamp(0.85rem,3.16cqw,1.55rem)] font-light leading-[1.2] text-white transition-colors hover:bg-white hover:text-neutral-900">
                                    {{ __('Methodology') }}
                                </a>
                                <a href="https://plataforma.mapbiomas.org/fire/fire_annual?t[regionKey]=indonesia"
                                   class="border border-white px-3 py-2.5 text-center lg:px-[3.5cqw] lg:py-[2.05cqw] font-display text-[clamp(0.85rem,3.16cqw,1.55rem)] font-light leading-[1.2] text-white transition-colors hover:bg-white hover:text-neutral-900">
                                    {{ __('Access The Platform') }}
                                </a>
                            </div>
                        </div>
                    </article>
                    </div>
                </div>
            </div>
        </section>

        {{-- ─────────────────────────  ANGKA KUNCI  ───────────────────────── --}}
        <section class="bg-white py-[6%] sm:py-[3.3%]" aria-label="Angka kunci Koleksi 1">
            {{-- 2x2 di seluler: empat angka terbaca sebagai satu blok, bukan
                 empat balok setinggi layar. --}}
            <div class="{{ $shell }} grid grid-cols-2 gap-2 sm:gap-x-[2.7%] sm:gap-y-6 xl:grid-cols-4 text-center">
                @foreach ($highlights as $i => $tile)
{{-- justify-start, bukan center: dengan center, ubin yang labelnya lebih
                              pendek ikut turun sehingga angka antar ubin tidak sebaris. --}}
                    <div class="{{ $anim }} flex flex-col justify-start bg-ember-soft px-4 pb-5 pt-7 sm:aspect-[349/197] sm:px-[8%] sm:pb-[7%] sm:pt-[11%]"
                         {!! $reveal($i * 90) !!}>
                        {{-- Skala disetel agar nilai terpanjang (232.996) tetap muat; ukurannya
                             dibuat seragam supaya keempat ubin terbaca sebagai satu set.
                             Batas atasnya disetel agar kombinasi terpanjang ("9.5 million ha"
                             pada versi Inggris) tetap muat bersanding, tidak turun baris. --}}
                        <p class="font-display text-[clamp(1.2rem,1.9vw,1.875rem)] font-bold leading-none text-white">
                            {{-- Semua satuan seukuran angkanya. Bedanya hanya jarak:
                                 "%" menempel karena ia simbol, satuan berupa kata
                                 dipisah spasi supaya tidak menyatu jadi satu kata. --}}
                            @if ($tile['unit'] === '%'){{ $tile['value'] }}{{ $tile['unit'] }}@else{{ $tile['value'] }} {{ $tile['unit'] }}@endif
                        </p>
                        <p class="mt-3 font-display text-[clamp(0.75rem,0.85vw,0.95rem)] font-normal leading-snug text-white/95">
                            {{ $tile['label'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- ─────────────────────────  KABAR  ───────────────────────── --}}
        <section id="kabar" class="ml-[calc(50%-50vw)] w-screen bg-[#fdf0ee] py-[9%] sm:py-[6.2%]">
            <div class="{{ $frame }}">
                <div class="{{ $shell }}">
                    <h2 class="sr-only">{{ __('Kabar terbaru') }}</h2>
                    <div class="grid gap-[4.5%] gap-y-8 sm:grid-cols-2">
                        @forelse ($news as $i => $item)
                            {{-- Isi kartu langsung di atas latar, tanpa panel terisi.
                                 Tanggal mendahului judul sebagai penanda waktu. --}}
                            <a href="{{ route('detailnews', [app()->getLocale(), $item->id, $item->slug]) }}"
                               class="{{ $anim }} group block" {!! $reveal($i * 80) !!}>
                                <div class="overflow-hidden">
                                    @if ($adaFoto($item->img))
                                        <img src="{{ asset('storage/files/photos/' . $item->img) }}" alt="{{ $item->title }}"
                                             loading="lazy"
                                             class="aspect-[476/268] w-full object-cover transition-transform duration-700 ease-[cubic-bezier(0.16,0.84,0.28,1)] group-hover:scale-[1.04]">
                                    @else
                                        {{-- Rasio disamakan dengan foto asli supaya tata letak
                                             kartu tidak melompat saat gambarnya tidak ada. --}}
                                        <div class="flex aspect-[476/268] w-full items-center justify-center bg-cloud">
                                            <span class="font-mono text-[10px] uppercase tracking-[0.16em] text-neutral-400">
                                                {{ __('Gambar belum tersedia') }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <p class="mt-4 font-display text-[14px] font-medium text-ember">
                                    {{ $tanggal($item->publishdate) }}
                                </p>

                                <h3 class="mt-1 min-h-[26px] font-display text-[18px] font-bold leading-[26px] text-neutral-900 transition-colors group-hover:text-ember">
                                    {{ $teks($item->title, __('Tanpa judul')) }}
                                </h3>

                                <div class="mt-2 min-h-[88px] max-w-[60ch] font-display text-[15px] font-normal leading-[22px] text-black">
                                    {{ $teks($item->description, __('Belum ada ringkasan.')) }}
                                </div>
                            </a>
                        @empty
                            <p class="font-display text-neutral-500">{{ __('Belum ada kabar terbit.') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        {{-- ─────────────────────────  INFOGRAFIS  ───────────────────────── --}}
        <section id="data" class="bg-white py-[7%] sm:py-[4%]">
            <div class="{{ $shell }} flex flex-col gap-14">
                @forelse ($infographic as $item)
                    @php
                        // Gambar dipakai di dua tempat (kartu dan modal), jadi file_exists
                        // di dalam $adaFoto cukup dijalankan sekali.
                        $gambar = $adaFoto($item->img);
                        $src = $gambar ? asset('storage/files/photos/'.$item->img) : '';
                    @endphp

                    {{-- State zoom per infografis: kalau dipakai bersama, membuka
                         satu panel akan ikut membuka panel yang lain. --}}
                    <div x-data="{ zoom: false }">
                        {{-- Berkasnya bisa hilang: baris CMS lama, unggahan gagal, atau
                             storage:link belum jalan. Judul dan keterangan di bawah tetap
                             tampil, jadi gambarnya cukup dilewati. --}}
                        @if ($gambar)
                            <button type="button" x-on:click="zoom = true"
                                    class="{{ $anim }} block w-full cursor-zoom-in" {!! $reveal() !!}
                                    aria-label="Perbesar infografis {{ $item->title }}">
                                <img src="{{ $src }}" alt="{{ $item->title }}" class="w-full" loading="lazy">
                                {{-- Angka pada infografis terlalu kecil di layar seluler; kursor
                                     zoom tidak terlihat di sana, jadi petunjuknya ditulis. --}}
                                <span class="mt-3 block text-left font-mono text-[10px] uppercase tracking-[0.16em] text-ember lg:hidden">
                                    {{ __('Tap to enlarge') }}
                                </span>
                            </button>

                            <div x-show="zoom" x-cloak x-on:keydown.escape.window="zoom = false"
                                 {{-- .self: hanya klik pada latarnya yang menutup, bukan klik
                                      yang merambat dari elemen lain. --}}
                                 x-on:click.self="zoom = false"
                                 class="fixed inset-0 z-[60] flex overflow-auto bg-black/90 p-4"
                                 role="dialog" aria-modal="true" aria-label="{{ $item->title }}">
                                <button type="button" x-on:click="zoom = false"
                                        class="fixed right-5 top-5 z-10 border border-white/40 bg-black/60 px-4 py-2 font-display text-sm text-white transition-colors hover:bg-white hover:text-black">
                                    {{ __('Tutup') }}
                                </button>
                                {{-- Di seluler sengaja lebih lebar dari layar dan digeser, karena
                                     menyusutkannya agar muat justru membuatnya tetap tak terbaca. --}}
                                <img src="{{ $src }}" alt=""
                                     class="m-auto w-[900px] max-w-none lg:max-h-full lg:w-auto lg:max-w-full lg:object-contain" x-on:click.stop>
                            </div>
                        @endif

                        {{-- <div class="{{ $anim }} mt-6" {!! $reveal(120) !!}>
                            <p class="font-display text-[20px] font-semibold leading-[29px] text-neutral-900">
                                {{ $teks($item->title, __('Tanpa judul')) }}
                            </p>
                            <div class="mt-2 max-w-[70ch] font-display text-[16px] font-normal leading-[24px] text-neutral-500">
                                {{ $teks($item->description, __('Belum ada ringkasan.')) }}
                            </div>
                        </div> --}}
                    </div>
                @empty
                    <p class="{{ $anim }} py-16 text-center font-display text-neutral-400" {!! $reveal() !!}>
                        {{ __('Belum ada infografis terbit.') }}
                    </p>
                @endforelse
            </div>
        </section>

        {{-- ─────────────────────────  MITRA  ───────────────────────── --}}
        <section id="inisiatif" class="ml-[calc(50%-50vw)] w-screen border-y-[3px] border-ember bg-cloud py-[9%] sm:py-[4.2%]">
            <div class="{{ $frame }}">
                {{-- Seluler: tiap kelompok mitra ditumpuk agar logo terbaca; sm ke atas berdampingan. --}}
                <div class="{{ $shell }} flex flex-col items-start gap-y-8 sm:flex-row sm:items-start sm:justify-between sm:gap-x-[4%] sm:gap-y-0">
                    @foreach ($partners as $group)
                        <div class="min-w-0 max-w-full">
                            <p class="whitespace-nowrap font-display text-[clamp(0.75rem,0.85vw,0.95rem)] font-normal text-ember">{{ $group['heading'] }}</p>
                            @if ($group['scroll'] ?? false)
                                <div class="max-w-full overflow-x-auto">
                                    <img src="{{ asset('images/' . $group['image']) }}" alt="{{ $group['alt'] }}" loading="lazy"
                                         class="mt-5 h-auto min-w-[560px] max-w-none sm:min-w-0 sm:max-w-full {{ $group['width'] }}">
                                </div>
                            @else
                                <img src="{{ asset('images/' . $group['image']) }}" alt="{{ $group['alt'] }}" loading="lazy"
                                     class="mt-5 h-auto max-w-full {{ $group['width'] }}">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </main>

    {{-- ─────────────────────────  FOOTER  ───────────────────────── --}}
    <footer class="ml-[calc(50%-50vw)] w-screen bg-ash">
        <div class="{{ $frame }}">
            {{-- Satu kolom per kelompok menu: judul sebaris di atas, anaknya di
                 bawah. Membungkus sendiri di layar sempit, jadi tidak perlu
                 daftar seluler terpisah. --}}
            <nav class="{{ $shell }} grid grid-cols-2 gap-x-6 gap-y-7 py-8 sm:flex sm:flex-wrap sm:gap-x-12" aria-label="Tautan footer">
                @foreach ($nav as $item)
                    <div>
                        @if (isset($item['children']))
                            {{-- Kelompok tidak punya halaman sendiri, jadi judulnya bukan tautan. --}}
                            <p class="font-display text-[15px] font-semibold text-white">{{ $item['label'] }}</p>
                            <ul class="mt-2 space-y-1">
                                @foreach ($item['children'] as $child)
                                    <li>
                                        <a href="{{ $child['href'] }}"
                                           class="font-display text-[15px] font-normal text-white/85 transition-colors hover:text-white">
                                            {{ $child['label'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <a href="{{ $item['href'] }}"
                               class="font-display text-[15px] font-semibold text-white transition-colors hover:text-white/85">
                                {{ $item['label'] }}
                            </a>
                        @endif
                    </div>
                @endforeach
            </nav>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
