{{--
    CP-FR-016 - SEO dasar: title dan meta description per halaman
    Domain  : PRD-01 CP  - Company Profile (Bertrand)
    Catatan : halaman mengisi @section('title') dan @section('meta_description').
    Status  : fallback title tersedia; description hanya dirender jika kontennya tersedia.
--}}

@php
    $siteTitle = config('app.name') ?: 'YOLOO Merch';
    $pageTitle = html_entity_decode(
        trim($__env->yieldContent('title')),
        ENT_QUOTES | ENT_HTML5,
        'UTF-8',
    );
    $metaDescription = trim($__env->yieldContent('meta_description'));
@endphp

<title>{{ $pageTitle !== '' ? $pageTitle . ' | ' . $siteTitle : $siteTitle }}</title>

@if ($metaDescription !== '')
    <meta name="description" content="{{ $metaDescription }}">
@endif
