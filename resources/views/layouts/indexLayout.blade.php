<!DOCTYPE html>
{{-- [color-scheme:light] + bg-white: situs publik berdesain terang. Tanpa
     deklarasi ini, peramban ponsel dalam mode gelap melukis kanvas hitam di
     sela bagian berlatar putih dan menggelapkan kontrol formulir. --}}
<html class="[color-scheme:light]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'Page Title' }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.png') }}">

    @yield('meta')

    {{-- Poppins hanya dimuat di <head> landing; halaman dalam memakai layout
         ini, jadi tanpa baris berikut kelas font-display jatuh ke huruf sistem. --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @livewireScripts
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
<body class="font-display bg-white">
    @yield('content')

    @stack('scripts')
</body>
</html>
