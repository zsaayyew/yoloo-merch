{{--
    LAYOUT - Halaman login dashboard (tanpa sidebar)
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Status  : kerangka awal, belum ada desain.
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Login') - YOLOO Merch Admin</title>
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>
<body>
    <main>
        @yield('content')
    </main>
</body>
</html>