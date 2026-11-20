{{--
    LAYOUT - Admin Dashboard (Owner dan Admin, wajib login)
    Domain  : SHARED - kerangka milik koordinator; sidebar/topbar milik PRD-04 ADM
    Catatan : semua halaman dashboard memakai @extends('layouts.admin').
    Status  : kerangka awal, belum ada desain.
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Dashboard') - YOLOO Merch Admin</title>
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
    @stack('head')
</head>
<body>
    @include('partials.admin.sidebar')

    <div>
        @include('partials.admin.topbar')
        @include('partials.admin.flash-messages')

        <main>
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>