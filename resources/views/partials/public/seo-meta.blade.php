{{-- CP-FR-016: metadata dasar untuk semua halaman publik. --}}
@php
    $siteTitle = 'YOLOO Merch';
    $pageTitle = html_entity_decode(trim($__env->yieldContent('title')), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $metaDescription = trim($__env->yieldContent('meta_description'));
@endphp

<title>{{ $pageTitle !== '' ? $pageTitle.' | '.$siteTitle : $siteTitle }}</title>
<meta name="description" content="{{ $metaDescription !== '' ? $metaDescription : 'Your Personal Merchandise Solution. Jelajahi layanan merchandise custom dan printing YOLOO Merch.' }}">
<meta name="theme-color" content="#f8f8f8">
