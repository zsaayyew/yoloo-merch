{{--
    LAYOUT - Public Website (Visitor dan Customer Guest)
    Domain  : SHARED - kerangka milik koordinator; navbar/footer milik PRD-01 CP
    Catatan : semua halaman publik memakai @extends('layouts.public').
    Status  : kerangka awal, belum ada desain.
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.public.seo-meta')
    @vite(['resources/css/public.css', 'resources/js/public.js'])
    @stack('head')
</head>
<body>
    @include('partials.public.navbar')

    <main>
        @yield('content')
    </main>

    @include('partials.public.footer')
    @stack('scripts')
</body>
</html>