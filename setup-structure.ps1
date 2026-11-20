<#
.SYNOPSIS
    Menyiapkan struktur folder dan file awal proyek YOLOO Merch (Laravel).

.DESCRIPTION
    Script ini HANYA membuat folder, file placeholder, dan kerangka kelas kosong.
    Script TIDAK menulis logika bisnis, query, desain, atau definisi tabel final.

    Jaminan keamanan:
      - Tidak pernah menimpa file yang sudah ada (file yang sudah ada selalu dilewati).
      - Tidak menghapus apa pun dan tidak mengubah file konfigurasi Laravel
        (vite.config.js, routes/web.php, bootstrap/app.php, .env, config/*).
      - Aman dijalankan berulang kali (idempotent).
      - Berhenti jika tidak dijalankan dari root proyek Laravel.

    Tahap:
      1. Pemeriksaan root proyek
      2. Folder
      3. View Blade (layout, partial, komponen, halaman, section)
      4. Aset CSS dan JavaScript
      5. File route tambahan
      6. Migration kerangka (urutan tetap)
      7. Kelas PHP lewat Artisan (controller, model, enum, service, request,
         policy, middleware, seeder, command, mail)

.PARAMETER SkipArtisan
    Lewati tahap 7 (tidak menjalankan perintah php artisan make:*).

.EXAMPLE
    powershell -ExecutionPolicy Bypass -File .\setup-structure.ps1

.EXAMPLE
    powershell -ExecutionPolicy Bypass -File .\setup-structure.ps1 -SkipArtisan
#>

[CmdletBinding()]
param(
    [switch]$SkipArtisan
)

$Root = (Get-Location).Path
$Utf8NoBom = New-Object System.Text.UTF8Encoding($false)

$Stats = [ordered]@{
    'Folder dibuat'           = 0
    'Folder sudah ada'        = 0
    'File dibuat'             = 0
    'File dilewati (ada)'     = 0
    'Artisan berhasil'        = 0
    'Artisan dilewati (ada)'  = 0
    'Artisan gagal'           = 0
}

# Label domain (sesuai Overview PRD)
$CP  = 'PRD-01 CP  - Company Profile (Bertrand)'
$CAT = 'PRD-02 CAT - Catalog & Customization (Haidir)'
$ORD = 'PRD-03 ORD - Customer Ordering (Raisyah)'
$ADM = 'PRD-04 ADM - Admin Dashboard & CMS (Taufik)'
$FIN = 'PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)'
$SH  = 'SHARED - dipakai lintas domain (ubah hanya setelah koordinasi)'

# ---------------------------------------------------------------------------
# Fungsi bantu
# ---------------------------------------------------------------------------

function Write-Step([string]$Text) {
    Write-Host ''
    Write-Host "==> $Text" -ForegroundColor Cyan
}

function Get-FullPath([string]$Rel) {
    return (Join-Path $Root ($Rel -replace '/', [System.IO.Path]::DirectorySeparatorChar))
}

function Ensure-Dir([string]$Rel) {
    $full = Get-FullPath $Rel
    if (Test-Path -LiteralPath $full -PathType Container) {
        $Stats['Folder sudah ada']++
        return
    }
    New-Item -ItemType Directory -Path $full -Force -ErrorAction Stop | Out-Null
    $Stats['Folder dibuat']++
    Write-Host "  [+] folder  $Rel" -ForegroundColor Green
}

function Ensure-File([string]$Rel, [string]$Content) {
    $full = Get-FullPath $Rel
    if (Test-Path -LiteralPath $full) {
        $Stats['File dilewati (ada)']++
        Write-Host "  [=] lewati  $Rel (sudah ada)" -ForegroundColor DarkGray
        return
    }
    $parent = Split-Path -Parent $full
    if (-not (Test-Path -LiteralPath $parent)) {
        New-Item -ItemType Directory -Path $parent -Force -ErrorAction Stop | Out-Null
    }
    [System.IO.File]::WriteAllText($full, $Content, $Utf8NoBom)
    $Stats['File dibuat']++
    Write-Host "  [+] file    $Rel" -ForegroundColor Green
}

function Get-BladeHeader([string]$Id, [string]$Name, [string]$Domain, [string]$Note) {
    $lines = @('{{--', "    $Id - $Name", "    Domain  : $Domain")
    if ($Note) { $lines += "    Catatan : $Note" }
    $lines += '    Status  : placeholder, belum diimplementasikan.'
    $lines += '--}}'
    return (($lines -join "`n") + "`n")
}

# Partial atau komponen Blade. -Component menambahkan @props([]).
function New-Blade {
    param(
        [string]$Path, [string]$Id, [string]$Name, [string]$Domain,
        [string]$Note = '', [switch]$Component
    )
    $content = Get-BladeHeader $Id $Name $Domain $Note
    if ($Component) { $content += "@props([])`n" }
    Ensure-File "resources/views/$Path.blade.php" $content
}

# Halaman Blade utama beserta file section-nya.
function New-Page {
    param(
        [string]$Path, [string]$Layout, [string]$Id, [string]$Name, [string]$Domain,
        [string[]]$Sections = @(), [string]$SectionDir = '', [string]$Note = ''
    )
    $dir = $Path.Substring(0, $Path.LastIndexOf('/'))
    if (-not $SectionDir) { $SectionDir = "$dir/sections" }
    $dotDir = $SectionDir -replace '/', '.'

    $includes = @()
    foreach ($s in $Sections) {
        New-Blade -Path "$SectionDir/$s" -Id $Id -Name "Section '$s' ($Name)" -Domain $Domain `
            -Note 'Partial khusus halaman ini; di-include dari file halaman utama.'
        $includes += "    @include('$dotDir.$s')"
    }
    if ($includes.Count -eq 0) { $includes = @('    {{-- Isi halaman ditulis di sini saat fitur dikerjakan. --}}') }

    $content  = Get-BladeHeader $Id $Name $Domain $Note
    $content += "@extends('$Layout')`n`n"
    $content += "@section('title', '$Name')`n`n"
    $content += "@section('content')`n"
    $content += ($includes -join "`n") + "`n"
    $content += "@endsection`n"
    Ensure-File "resources/views/$Path.blade.php" $content
}

function New-Asset([string]$Path, [string]$Id, [string]$Name, [string]$Domain, [string]$Extra = '') {
    if ($Path.EndsWith('.css')) {
        $content = "/*`n * $Id - $Name`n * Domain : $Domain`n * Status : placeholder, belum diimplementasikan.`n */`n"
    } else {
        $content = "// $Id - $Name`n// Domain : $Domain`n// Status : placeholder, belum diimplementasikan.`n"
    }
    if ($Extra) { $content += "`n$Extra`n" }
    Ensure-File $Path $content
}

# ---------------------------------------------------------------------------
# 1. Pemeriksaan root proyek Laravel
# ---------------------------------------------------------------------------

Write-Step 'Memeriksa root proyek Laravel'

$required = @('artisan', 'composer.json', 'app', 'bootstrap/app.php', 'resources/views', 'routes/web.php', 'database/migrations')
$missing = @($required | Where-Object { -not (Test-Path -LiteralPath (Get-FullPath $_)) })
if ($missing.Count -gt 0) {
    Write-Host "  [x] Ini bukan root proyek Laravel. Tidak ditemukan: $($missing -join ', ')" -ForegroundColor Red
    Write-Host '      Masuk dulu ke folder proyek (cd yoloo-merch), lalu jalankan ulang script.' -ForegroundColor Red
    exit 1
}

$composerJson = Get-Content -LiteralPath (Get-FullPath 'composer.json') -Raw
if ($composerJson -notmatch '"laravel/framework"') {
    Write-Host '  [x] composer.json tidak memuat laravel/framework. Script dihentikan.' -ForegroundColor Red
    exit 1
}

$bootstrapApp = Get-Content -LiteralPath (Get-FullPath 'bootstrap/app.php') -Raw
if ($bootstrapApp -notmatch 'Application::configure') {
    Write-Host '  [!] Struktur terlihat seperti Laravel 10 ke bawah. Rancangan ini mengasumsikan Laravel 11+.' -ForegroundColor Yellow
}

Write-Host "  [ok] Root proyek: $Root" -ForegroundColor Green

# ---------------------------------------------------------------------------
# 2. Folder
# ---------------------------------------------------------------------------

Write-Step 'Membuat folder'

$folders = @(
    # Backend
    'app/Enums',
    'app/Services/Catalog', 'app/Services/Ordering', 'app/Services/Sales',
    'app/Services/Finance', 'app/Services/Audit',
    'app/Support',
    # Penyimpanan file (Laravel 11+: disk "local" = storage/app/private)
    'storage/app/private/tmp-uploads',
    'storage/app/private/order-attachments',
    'storage/app/private/expense-receipts',
    'storage/app/private/payment-settings',
    'storage/app/private/invoices',
    'storage/app/public/media',
    # Aset statis publik
    'public/images/brand', 'public/images/placeholders', 'public/images/icons',
    # Pengujian per domain (mengacu acceptance criteria PRD)
    'tests/Feature/CompanyProfile', 'tests/Feature/Catalog', 'tests/Feature/Ordering',
    'tests/Feature/Admin', 'tests/Feature/Finance', 'tests/Unit/Services',
    # Dokumentasi tim
    'docs'
)
foreach ($f in $folders) { Ensure-Dir $f }

# .gitkeep hanya untuk folder yang memang perlu ikut ke Git
foreach ($k in @('public/images/brand', 'public/images/placeholders', 'public/images/icons',
                 'tests/Feature/CompanyProfile', 'tests/Feature/Catalog', 'tests/Feature/Ordering',
                 'tests/Feature/Admin', 'tests/Feature/Finance', 'tests/Unit/Services')) {
    Ensure-File "$k/.gitkeep" ''
}

Ensure-File 'docs/README.md' @'
# Dokumentasi Proyek YOLOO Merch

Simpan di folder ini:

- `RANCANGAN_STRUKTUR_YOLOO_MERCH.md` (rancangan struktur proyek dan aturan kerja)
- Master PRD dan Overview PRD versi terbaru
- ERD final yang disepakati tim

Aturan kerja ringkas ada di bagian 9 dokumen rancangan.
'@

# ---------------------------------------------------------------------------
# 3. View Blade
# ---------------------------------------------------------------------------

Write-Step 'Membuat layout'

Ensure-File 'resources/views/layouts/public.blade.php' @'
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
'@

Ensure-File 'resources/views/layouts/admin.blade.php' @'
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
'@

Ensure-File 'resources/views/layouts/admin-auth.blade.php' @'
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
'@

Write-Step 'Membuat partial global'

Ensure-File 'resources/views/partials/public/seo-meta.blade.php' @'
{{--
    CP-FR-016 - SEO dasar: title dan meta description per halaman
    Domain  : PRD-01 CP  - Company Profile (Bertrand)
    Catatan : halaman mengisi @section('title') dan @section('meta_description').
    Status  : kerangka awal.
--}}
<title>@yield('title', 'YOLOO Merch') - YOLOO Merch</title>
<meta name="description" content="@yield('meta_description', '')">
'@

New-Blade 'partials/public/navbar' 'GLOBAL-CP-001' 'Header dan navigasi global (+ menu mobile)' $CP `
    'Urutan menu: Beranda, Katalog, Galeri, Tentang Kami, Ulasan & Kolaborasi, Kontak. Ikon keranjang memakai <x-ordering.cart-badge /> milik ORD. Tidak ada menu Cek Pesanan.'
New-Blade 'partials/public/footer' 'GLOBAL-CP-002' 'Footer global' $CP `
    'Kontak harus konsisten dengan halaman Kontak (data dari site settings ADM).'
New-Blade 'partials/admin/sidebar' 'ADM' 'Sidebar navigasi dashboard (menu sesuai role)' $ADM `
    'Menu FIN (Order, Pelanggan, Pengeluaran, Invoice, Laporan, Pengaturan QRIS) ditambahkan bersama PRD-05. User Management hanya tampil untuk Owner.'
New-Blade 'partials/admin/topbar' 'ADM' 'Header dashboard (nama pengguna, role, logout)' $ADM
New-Blade 'partials/admin/flash-messages' 'ADM' 'Pesan sukses/gagal setelah aksi' $SH `
    'Memakai komponen <x-ui.alert />.'

Write-Step 'Membuat komponen Blade (resources/views/components)'

# Komponen UI umum
New-Blade 'components/ui/button'         'UI' 'Tombol (varian primary/secondary/danger)'     $SH -Component
New-Blade 'components/ui/alert'          'UI' 'Alert / notifikasi sukses, gagal, info'       $SH -Component
New-Blade 'components/ui/modal'          'UI' 'Modal dialog'                                 $SH -Component
New-Blade 'components/ui/confirm-dialog' 'UI' 'Konfirmasi tindakan destruktif'               $SH -Component `
    -Note 'Wajib untuk hapus/arsip/void/refund/batal (Master PRD bab 11).'
New-Blade 'components/ui/badge'          'UI' 'Label kecil (kategori, tag)'                  $SH -Component
New-Blade 'components/ui/status-badge'   'UI' 'Label status (publikasi, order, request, pembayaran)' $SH -Component `
    -Note 'Satu komponen, warna ditentukan dari enum status. Dipakai ADM dan FIN.'
New-Blade 'components/ui/empty-state'    'UI' 'Kondisi kosong'                               $SH -Component
New-Blade 'components/ui/error-state'    'UI' 'Kondisi error + tombol coba lagi'             $SH -Component
New-Blade 'components/ui/breadcrumb'     'UI' 'Breadcrumb'                                   $SH -Component
New-Blade 'components/ui/card'           'UI' 'Kartu pembungkus konten'                      $SH -Component
New-Blade 'components/ui/lightbox'       'UI' 'Lightbox pembesaran gambar'                   $SH -Component `
    -Note 'Dipakai Galeri (CP-FR-012), Ulasan & Kolaborasi (CP), dan galeri foto produk (CAT-FR-011).'

# Komponen form
New-Blade 'components/form/input'        'FORM' 'Input teks + label + pesan error'           $SH -Component
New-Blade 'components/form/textarea'     'FORM' 'Textarea + label + pesan error'             $SH -Component
New-Blade 'components/form/select'       'FORM' 'Select + label + pesan error'               $SH -Component
New-Blade 'components/form/checkbox'     'FORM' 'Checkbox (termasuk kotak persetujuan)'      $SH -Component
New-Blade 'components/form/money-input'  'FORM' 'Input nominal Rupiah tanpa desimal'         $SH -Component
New-Blade 'components/form/file-upload'  'FORM' 'Unggah file + daftar file + progres'        $SH -Component `
    -Note 'Dipakai ORD (lampiran desain, ORD-FR-011) dan ADM/FIN (media, nota, QRIS).'

# Komponen tabel dashboard
New-Blade 'components/table/data-table'  'TABLE' 'Tabel data dashboard (kosong/loading/error)' $SH -Component
New-Blade 'components/table/filter-bar'  'TABLE' 'Bar pencarian dan filter daftar'           $SH -Component

# Komponen khusus domain
New-Blade 'components/catalog/product-card'   'CAT-FR-002' 'Kartu produk (gambar, nama, label model harga)' $CAT -Component `
    -Note 'Dipakai katalog CAT dan section produk unggulan di Beranda (CP-FR-005).'
New-Blade 'components/catalog/price-display'  'CAT-FR-007' 'Tampilan harga sesuai model harga'  $CAT -Component
New-Blade 'components/catalog/estimate-note'  'CAT-BR-003' 'Label Estimasi + catatan konfirmasi admin' $CAT -Component `
    -Note 'Dipakai juga oleh ORD di keranjang, ringkasan, dan konfirmasi (ORD-BR-004).'
New-Blade 'components/ordering/cart-badge'    'PAGE-ORD-007' 'Ikon keranjang + jumlah item'     $ORD -Component `
    -Note 'Ditempel di navbar milik CP.'
New-Blade 'components/ordering/pickup-info'   'ORD-FR-006' 'Informasi pengambilan langsung (lokasi/jam)' $ORD -Component `
    -Note 'Data dari site settings ADM. Tidak ada opsi kirim/ongkir.'
New-Blade 'components/ordering/order-summary' 'ORD-FR-007' 'Ringkasan item pesanan (snapshot)'   $ORD -Component `
    -Note 'Tanpa QRIS/instruksi pembayaran.'
New-Blade 'components/admin/page-header'      'ADM' 'Judul halaman dashboard + tombol aksi'    $ADM -Component
New-Blade 'components/admin/stat-card'        'ADM' 'Kartu angka ringkasan dashboard'          $ADM -Component

Write-Step 'Membuat halaman Public Website'

$PL = 'layouts.public'

# PRD-01 CP
New-Page -Path 'public/home/index' -Layout $PL -Id 'PAGE-CP-001' -Name 'Beranda' -Domain $CP `
    -Sections @('hero', 'intro', 'featured-products', 'services', 'advantages',
                'collaboration-preview', 'gallery-preview', 'testimonial-preview', 'cta') `
    -Note 'Route /. Urutan section sesuai PAGE-CP-001; navbar dan footer dari layout.'
New-Page -Path 'public/about/index' -Layout $PL -Id 'PAGE-CP-002' -Name 'Tentang Kami' -Domain $CP `
    -Sections @('profile', 'vision-mission', 'values-advantages', 'ordering-guide') -Note 'Route /tentang-kami.'
New-Page -Path 'public/reviews/index' -Layout $PL -Id 'PAGE-CP-003' -Name 'Ulasan & Kolaborasi' -Domain $CP `
    -Sections @('testimonials', 'collaborations') -Note 'Route /ulasan-kolaborasi.'
New-Page -Path 'public/gallery/index' -Layout $PL -Id 'PAGE-CP-004' -Name 'Galeri' -Domain $CP `
    -Sections @('filter', 'grid') -Note 'Route /galeri. Lightbox memakai <x-ui.lightbox />.'
New-Page -Path 'public/contact/index' -Layout $PL -Id 'PAGE-CP-005' -Name 'Kontak' -Domain $CP `
    -Sections @('channels', 'location-hours') -Note 'Route /kontak.'

# PRD-02 CAT
$catSec = 'public/catalog/sections'
New-Page -Path 'public/catalog/index' -Layout $PL -Id 'PAGE-CAT-001' -Name 'Katalog Produk' -Domain $CAT `
    -SectionDir $catSec -Sections @('search-filter', 'category-list', 'product-grid') -Note 'Route /produk.'
New-Page -Path 'public/catalog/category' -Layout $PL -Id 'PAGE-CAT-002' -Name 'Produk per Kategori' -Domain $CAT `
    -SectionDir $catSec -Sections @('category-header', 'search-filter', 'product-grid') -Note 'Route /produk/kategori/{slug}.'
New-Page -Path 'public/catalog/show' -Layout $PL -Id 'PAGE-CAT-003' -Name 'Detail Produk' -Domain $CAT `
    -SectionDir $catSec `
    -Sections @('product-gallery', 'product-info', 'price-info', 'configurator', 'calculator-panel', 'order-actions', 'related-products') `
    -Note 'Route /produk/{slug}. calculator-panel = PAGE-CAT-004 (hanya untuk model kalkulator).'
New-Page -Path 'public/catalog/not-found' -Layout $PL -Id 'PAGE-CAT-005' -Name 'Produk Tidak Ditemukan' -Domain $CAT `
    -Note 'Ditampilkan untuk slug tidak valid/draft/nonaktif, dengan status HTTP 404.'

# PRD-03 ORD
New-Page -Path 'public/cart/index' -Layout $PL -Id 'PAGE-ORD-001' -Name 'Keranjang' -Domain $ORD `
    -Sections @('item-list', 'totals') -Note 'Route /keranjang. Tanpa ongkir.'
$coSec = 'public/checkout/sections'
New-Page -Path 'public/checkout/customer' -Layout $PL -Id 'PAGE-ORD-002' -Name 'Checkout - Data Pelanggan' -Domain $ORD `
    -SectionDir $coSec -Sections @('customer-form', 'item-attachments', 'cart-recap') -Note 'Route /checkout. Pickup saja.'
New-Page -Path 'public/checkout/summary' -Layout $PL -Id 'PAGE-ORD-003' -Name 'Ringkasan dan Persetujuan Pesanan' -Domain $ORD `
    -SectionDir $coSec -Sections @('review-items', 'review-contact', 'agreement') `
    -Note 'Route /checkout/ringkasan. Tanpa QRIS/rekening/instruksi pembayaran.'
New-Page -Path 'public/orders/success' -Layout $PL -Id 'PAGE-ORD-004' -Name 'Pesanan Berhasil Dikirim' -Domain $ORD `
    -Sections @('confirmation-details', 'next-steps') `
    -Note 'Route /pesanan/berhasil/{kode}. Hanya untuk sesi pengirim. Kode = bukti pengajuan, bukan cek status.'
$cuSec = 'public/custom-orders/sections'
New-Page -Path 'public/custom-orders/create' -Layout $PL -Id 'PAGE-ORD-005' -Name 'Formulir Custom Order' -Domain $ORD `
    -SectionDir $cuSec -Sections @('product-reference', 'requirements', 'reference-files', 'contact-form', 'agreement') `
    -Note 'Route /custom-order (opsional ?produk={slug}).'
New-Page -Path 'public/custom-orders/success' -Layout $PL -Id 'PAGE-ORD-006' -Name 'Request Berhasil Dikirim' -Domain $ORD `
    -SectionDir $cuSec -Sections @('confirmation-details') `
    -Note 'Route /custom-order/berhasil/{kode}. Hanya untuk sesi pengirim. Tanpa instruksi pembayaran.'

Write-Step 'Membuat halaman Admin Dashboard'

$AL = 'layouts.admin'

# PRD-04 ADM
New-Page -Path 'admin/auth/login' -Layout 'layouts.admin-auth' -Id 'PAGE-ADM-001' -Name 'Login Admin' -Domain $ADM `
    -Note 'Route /admin/login.'
New-Page -Path 'admin/dashboard/index' -Layout $AL -Id 'PAGE-ADM-002' -Name 'Dashboard' -Domain $ADM `
    -Sections @('content-summary', 'sales-summary') `
    -Note 'Route /admin/dashboard. sales-summary diisi PRD-05 (subfitur Could).'
New-Page -Path 'admin/content/homepage/edit' -Layout $AL -Id 'PAGE-ADM-003' -Name 'Konten Beranda' -Domain $ADM
New-Page -Path 'admin/content/about/edit' -Layout $AL -Id 'PAGE-ADM-003' -Name 'Konten Tentang Kami' -Domain $ADM
New-Page -Path 'admin/content/settings/edit' -Layout $AL -Id 'PAGE-ADM-003' -Name 'Profil Usaha, Kontak, dan Pickup' -Domain $ADM `
    -Note 'WhatsApp, Instagram, email, alamat/jam, lokasi pickup. Tidak menyimpan QRIS/rekening.'
New-Page -Path 'admin/content/testimonials/index' -Layout $AL -Id 'PAGE-ADM-003' -Name 'Daftar Testimoni' -Domain $ADM
New-Page -Path 'admin/content/testimonials/form' -Layout $AL -Id 'PAGE-ADM-003' -Name 'Form Testimoni' -Domain $ADM `
    -Note 'Satu form untuk tambah dan ubah.'
New-Page -Path 'admin/content/collaborations/index' -Layout $AL -Id 'PAGE-ADM-003' -Name 'Daftar Kolaborasi' -Domain $ADM
New-Page -Path 'admin/content/collaborations/form' -Layout $AL -Id 'PAGE-ADM-003' -Name 'Form Kolaborasi' -Domain $ADM
New-Page -Path 'admin/content/gallery/index' -Layout $AL -Id 'PAGE-ADM-003' -Name 'Daftar Foto Galeri' -Domain $ADM
New-Page -Path 'admin/content/gallery/form' -Layout $AL -Id 'PAGE-ADM-003' -Name 'Form Foto Galeri' -Domain $ADM
New-Page -Path 'admin/content/gallery-categories/index' -Layout $AL -Id 'PAGE-ADM-003' -Name 'Kategori Galeri' -Domain $ADM
New-Page -Path 'admin/media/index' -Layout $AL -Id 'PAGE-ADM-005' -Name 'Media' -Domain $ADM -Note 'Route /admin/media.'
New-Page -Path 'admin/categories/index' -Layout $AL -Id 'PAGE-ADM-004' -Name 'Kategori Produk' -Domain $ADM
New-Page -Path 'admin/categories/form' -Layout $AL -Id 'PAGE-ADM-004' -Name 'Form Kategori Produk' -Domain $ADM
New-Page -Path 'admin/products/index' -Layout $AL -Id 'PAGE-ADM-004' -Name 'Daftar Produk' -Domain $ADM -Note 'Route /admin/products.'
New-Page -Path 'admin/products/form' -Layout $AL -Id 'PAGE-ADM-004' -Name 'Form Produk' -Domain $ADM `
    -Sections @('basic-info', 'images', 'options', 'purchase-settings') `
    -Note 'purchase-settings = penanda boleh direct purchase dan wajib file desain (dibaca ORD).'
New-Page -Path 'admin/pricing/index' -Layout $AL -Id 'PAGE-ADM-004' -Name 'Konfigurasi Harga' -Domain $ADM -Note 'Route /admin/pricing.'
New-Page -Path 'admin/pricing/edit' -Layout $AL -Id 'PAGE-ADM-004' -Name 'Ubah Konfigurasi Harga Produk' -Domain $ADM `
    -Sections @('price-model', 'parameter-groups', 'tariff-matrix', 'quantity-tiers', 'extra-fees')
New-Page -Path 'admin/users/index' -Layout $AL -Id 'PAGE-ADM-006' -Name 'Manajemen Akun Admin' -Domain $ADM -Note 'Route /admin/users. Owner saja (Admin -> 403).'
New-Page -Path 'admin/users/form' -Layout $AL -Id 'PAGE-ADM-006' -Name 'Form Akun Admin' -Domain $ADM

# PRD-05 FIN
New-Page -Path 'admin/orders/index' -Layout $AL -Id 'PAGE-FIN-001' -Name 'Daftar Order & Request' -Domain $FIN `
    -Sections @('filters') -Note 'Route /admin/orders.'
New-Page -Path 'admin/orders/show' -Layout $AL -Id 'PAGE-FIN-002' -Name 'Detail Order / Request' -Domain $FIN `
    -Sections @('customer-info', 'items', 'attachments', 'request-status', 'quotation', 'order-status',
                'status-history', 'payments', 'refunds', 'invoices', 'internal-notes') `
    -Note 'payments + refunds = PAGE-FIN-005. Modal aksi ada di admin/orders/modals.'
New-Page -Path 'admin/orders/form' -Layout $AL -Id 'PAGE-FIN-003' -Name 'Form Transaksi Manual' -Domain $FIN `
    -Sections @('customer-picker', 'items-editor', 'totals') -Note 'Route /admin/orders/create dan /admin/orders/{id}/edit. Pickup saja.'
New-Blade 'admin/orders/modals/payment-form'    'PAGE-FIN-005' 'Modal tambah pembayaran QRIS' $FIN
New-Blade 'admin/orders/modals/void-payment'    'FIN-FR-009' 'Modal void pembayaran (Owner)'     $FIN
New-Blade 'admin/orders/modals/refund-form'     'FIN-FR-019' 'Modal refund (Owner)'              $FIN
New-Blade 'admin/orders/modals/cancel-order'    'FIN-FR-016' 'Modal batalkan transaksi final (Owner)' $FIN
New-Blade 'admin/orders/modals/convert-request' 'FIN-FR-004' 'Modal konversi request menjadi order' $FIN
New-Page -Path 'admin/customers/index' -Layout $AL -Id 'PAGE-FIN-004' -Name 'Daftar Pelanggan' -Domain $FIN
New-Page -Path 'admin/customers/show'  -Layout $AL -Id 'PAGE-FIN-004' -Name 'Detail Pelanggan' -Domain $FIN
New-Page -Path 'admin/customers/form'  -Layout $AL -Id 'PAGE-FIN-004' -Name 'Form Pelanggan' -Domain $FIN
New-Page -Path 'admin/expenses/index'  -Layout $AL -Id 'PAGE-FIN-006' -Name 'Pembelian & Pengeluaran' -Domain $FIN
New-Page -Path 'admin/expenses/form'   -Layout $AL -Id 'PAGE-FIN-007' -Name 'Form Pembelian / Pengeluaran' -Domain $FIN
New-Page -Path 'admin/expense-categories/index' -Layout $AL -Id 'PAGE-FIN-006' -Name 'Kategori Pengeluaran' -Domain $FIN
New-Page -Path 'admin/invoices/index'  -Layout $AL -Id 'PAGE-FIN-008' -Name 'Daftar Invoice' -Domain $FIN
New-Page -Path 'admin/invoices/show'   -Layout $AL -Id 'PAGE-FIN-008' -Name 'Pratinjau Invoice' -Domain $FIN
New-Blade 'admin/invoices/pdf' 'FIN-FR-013' 'Template PDF invoice (dompdf)' $FIN `
    'Dokumen mandiri tanpa layout/Vite/Tailwind; CSS inline. Data hanya dari Invoice Snapshot.'
New-Page -Path 'admin/reports/index'   -Layout $AL -Id 'PAGE-FIN-009' -Name 'Laporan' -Domain $FIN `
    -Sections @('filters', 'summary', 'sales', 'payments', 'refunds', 'material-purchases', 'operating-expenses')
New-Page -Path 'admin/payment-settings/index' -Layout $AL -Id 'PAGE-FIN-010' -Name 'Pengaturan QRIS & Instruksi Pembayaran' -Domain $FIN `
    -Note 'Owner ubah, Admin read-only.'

Write-Step 'Membuat template email'
New-Blade 'emails/ordering/submission-received' 'ORD-FR-015' 'Email penerimaan pesanan/request' $ORD `
    'Opsional (Should). Berisi kode dan ringkasan, tanpa instruksi QRIS.'

# ---------------------------------------------------------------------------
# 4. Aset CSS dan JavaScript
# ---------------------------------------------------------------------------

Write-Step 'Membuat aset CSS dan JavaScript'

New-Asset 'resources/css/public.css' 'ENTRY CSS' 'Styling Public Website' $SH `
    "@import './app.css';`n`n/* Tambahkan style khusus area publik di bawah ini bila Tailwind utility tidak cukup. */"
New-Asset 'resources/css/admin.css'  'ENTRY CSS' 'Styling Admin Dashboard' $SH `
    "@import './app.css';`n`n/* Tambahkan style khusus dashboard di bawah ini bila Tailwind utility tidak cukup. */"

New-Asset 'resources/js/public.js' 'ENTRY JS' 'JavaScript Public Website' $SH `
    "import './app';`n`n// Import modul fitur saat fitur dikerjakan, contoh:`n// import './public/mobile-nav';`n// import './shared/lightbox';"
New-Asset 'resources/js/admin.js'  'ENTRY JS' 'JavaScript Admin Dashboard' $SH `
    "import './app';`n`n// Import modul fitur saat fitur dikerjakan, contoh:`n// import './admin/sidebar';`n// import './shared/confirm-dialog';"

New-Asset 'resources/js/shared/lightbox.js'       'CP-FR-012 / CAT-FR-011' 'Lightbox gambar (tutup dengan tombol dan Escape)' $SH
New-Asset 'resources/js/shared/modal.js'          'UI' 'Buka/tutup modal' $SH
New-Asset 'resources/js/shared/confirm-dialog.js' 'UI' 'Konfirmasi tindakan destruktif' $SH
New-Asset 'resources/js/shared/submit-guard.js'   'ORD-FR-013' 'Nonaktifkan tombol kirim saat proses (anti kirim ganda di sisi klien)' $SH

New-Asset 'resources/js/public/mobile-nav.js'           'CP-FR-003'  'Menu navigasi mobile' $CP
New-Asset 'resources/js/public/gallery-filter.js'       'CP-FR-011'  'Filter kategori galeri' $CP
New-Asset 'resources/js/public/product-configurator.js' 'CAT-FR-005/006' 'Pilihan opsi dan validasi tampilan' $CAT
New-Asset 'resources/js/public/price-calculator.js'     'CAT-FR-008' 'Panel kalkulator; angka final tetap dari server' $CAT
New-Asset 'resources/js/public/cart.js'                 'ORD-FR-002' 'Ubah jumlah dan hapus item keranjang' $ORD
New-Asset 'resources/js/public/file-upload.js'          'ORD-FR-011' 'Unggah file dengan progres dan coba ulang' $ORD

New-Asset 'resources/js/admin/sidebar.js'         'ADM'        'Sidebar dashboard (buka/tutup di mobile)' $ADM
New-Asset 'resources/js/admin/product-options.js' 'ADM-FR-005' 'Tambah/hapus grup dan nilai opsi produk' $ADM
New-Asset 'resources/js/admin/pricing-matrix.js'  'ADM-FR-006' 'Editor matriks tarif dan tingkat kuantitas' $ADM
New-Asset 'resources/js/admin/order-items.js'     'FIN-FR-006/007' 'Baris item transaksi manual; total final dihitung server' $FIN
New-Asset 'resources/js/admin/expense-items.js'   'FIN-FR-012' 'Baris barang pada form pengeluaran' $FIN
New-Asset 'resources/js/admin/report-filter.js'   'FIN-FR-015' 'Filter periode laporan' $FIN

# ---------------------------------------------------------------------------
# 5. File route tambahan (routes/web.php TIDAK diubah)
# ---------------------------------------------------------------------------

Write-Step 'Membuat file route tambahan'

Ensure-File 'routes/public.php' @'
<?php

/*
|--------------------------------------------------------------------------
| Route Public Website
|--------------------------------------------------------------------------
| Di-load dari routes/web.php dengan: require __DIR__.'/public.php';
| Kelompokkan per domain dengan komentar di bawah. Nama route TANPA prefix.
| Daftar route lengkap ada di dokumen rancangan bagian 4.5.
|
| PRD-01 CP  : /, /tentang-kami, /ulasan-kolaborasi, /galeri, /kontak
| PRD-02 CAT : /produk, /produk/kategori/{slug}, /produk/{slug}
| PRD-03 ORD : /keranjang, /checkout, /checkout/ringkasan, /pesanan/berhasil/{kode},
|              /custom-order, /custom-order/berhasil/{kode}
| Larangan   : TIDAK ADA route cek status pesanan (ORD-BR-013).
*/

use Illuminate\Support\Facades\Route;

// PRD-01 CP

// PRD-02 CAT

// PRD-03 ORD
'@

Ensure-File 'routes/admin.php' @'
<?php

/*
|--------------------------------------------------------------------------
| Route Admin Dashboard
|--------------------------------------------------------------------------
| Di-load dari routes/web.php dengan: require __DIR__.'/admin.php';
| Semua route di sini memakai prefix "admin", nama "admin.*",
| middleware auth + active, dan role:owner untuk aksi khusus Owner.
| Daftar route lengkap ada di dokumen rancangan bagian 4.5.
*/

use Illuminate\Support\Facades\Route;

// PRD-04 ADM - login (guest)

// PRD-04 ADM - dashboard, konten, katalog, harga, media, user (Owner)

// PRD-05 FIN - order, pelanggan, pembayaran, refund, invoice, pengeluaran, laporan, QRIS
'@

# ---------------------------------------------------------------------------
# 6. Migration kerangka (urutan tetap agar foreign key aman)
# ---------------------------------------------------------------------------
# Dibuat oleh script, bukan "php artisan make:migration", karena artisan
# memberi timestamp detik saat itu sehingga banyak file dalam detik yang sama
# bisa berurutan salah. Isi file mengikuti stub resmi Laravel.

Write-Step 'Membuat migration kerangka'

$migrationCreate = @'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Tabel   : {table}
 * Domain  : {domain}
 * Catatan : kerangka. Kolom final ditulis pemilik domain saat fitur dikerjakan,
 *           mengacu ke Master PRD (bagian J Data requirements) dan ERD bersama.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('{table}', function (Blueprint $table) {
            $table->id();
            // TODO: kolom sesuai Master PRD dan ERD final.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('{table}');
    }
};
'@

$migrationAlterUsers = @'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Tabel   : users (tabel bawaan Laravel, TIDAK diubah file aslinya)
 * Domain  : {domain}
 * Catatan : kerangka. Nanti menambah role_id (FK ke roles) dan status akun
 *           aktif/nonaktif sesuai ADM bagian J.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // TODO: role_id dan status akun.
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // TODO: kebalikan dari up().
        });
    }
};
'@

$migrations = @(
    @{ Name = 'create_roles_table';                      Table = 'roles';                       Domain = $ADM },
    @{ Name = 'add_role_and_status_to_users_table';      Table = 'users';                       Domain = $ADM; Alter = $true },
    @{ Name = 'create_media_table';                      Table = 'media';                       Domain = $ADM },
    @{ Name = 'create_site_contents_table';              Table = 'site_contents';               Domain = $ADM },
    @{ Name = 'create_site_settings_table';              Table = 'site_settings';               Domain = $ADM },
    @{ Name = 'create_testimonials_table';               Table = 'testimonials';                Domain = $ADM },
    @{ Name = 'create_collaborations_table';             Table = 'collaborations';              Domain = $ADM },
    @{ Name = 'create_gallery_categories_table';         Table = 'gallery_categories';          Domain = $ADM },
    @{ Name = 'create_gallery_items_table';              Table = 'gallery_items';               Domain = $ADM },
    @{ Name = 'create_categories_table';                 Table = 'categories';                  Domain = $ADM },
    @{ Name = 'create_products_table';                   Table = 'products';                    Domain = $ADM },
    @{ Name = 'create_product_images_table';             Table = 'product_images';              Domain = $ADM },
    @{ Name = 'create_option_groups_table';              Table = 'option_groups';               Domain = $ADM },
    @{ Name = 'create_option_values_table';              Table = 'option_values';               Domain = $ADM },
    @{ Name = 'create_pricing_configurations_table';     Table = 'pricing_configurations';      Domain = $ADM },
    @{ Name = 'create_customers_table';                  Table = 'customers';                   Domain = "$ORD + $FIN" },
    @{ Name = 'create_carts_table';                      Table = 'carts';                       Domain = $ORD },
    @{ Name = 'create_cart_items_table';                 Table = 'cart_items';                  Domain = $ORD },
    @{ Name = 'create_order_requests_table';             Table = 'order_requests';              Domain = "$ORD + $FIN" },
    @{ Name = 'create_orders_table';                     Table = 'orders';                      Domain = "$ORD + $FIN" },
    @{ Name = 'create_order_items_table';                Table = 'order_items';                 Domain = "$ORD + $FIN" },
    @{ Name = 'create_order_attachments_table';          Table = 'order_attachments';           Domain = $ORD },
    @{ Name = 'create_submission_tokens_table';          Table = 'submission_tokens';           Domain = $ORD },
    @{ Name = 'create_order_status_histories_table';     Table = 'order_status_histories';      Domain = $FIN },
    @{ Name = 'create_payments_table';                   Table = 'payments';                    Domain = $FIN },
    @{ Name = 'create_refunds_table';                    Table = 'refunds';                     Domain = $FIN },
    @{ Name = 'create_payment_method_configurations_table'; Table = 'payment_method_configurations'; Domain = $FIN },
    @{ Name = 'create_invoices_table';                   Table = 'invoices';                    Domain = $FIN },
    @{ Name = 'create_invoice_snapshots_table';          Table = 'invoice_snapshots';           Domain = $FIN },
    @{ Name = 'create_expense_categories_table';         Table = 'expense_categories';          Domain = $FIN },
    @{ Name = 'create_expenses_table';                   Table = 'expenses';                    Domain = $FIN },
    @{ Name = 'create_audit_logs_table';                 Table = 'audit_logs';                  Domain = $SH }
)

$migDir = Get-FullPath 'database/migrations'
$seq = 0
foreach ($m in $migrations) {
    $seq++
    $existing = @(Get-ChildItem -LiteralPath $migDir -Filter "*_$($m.Name).php" -ErrorAction SilentlyContinue)
    if ($existing.Count -gt 0) {
        $Stats['File dilewati (ada)']++
        Write-Host "  [=] lewati  migration $($m.Name) (sudah ada: $($existing[0].Name))" -ForegroundColor DarkGray
        continue
    }
    $file = '2026_11_20_{0:D6}_{1}.php' -f $seq, $m.Name
    $template = if ($m.Alter) { $migrationAlterUsers } else { $migrationCreate }
    $content = $template.Replace('{table}', $m.Table).Replace('{domain}', $m.Domain)
    Ensure-File "database/migrations/$file" $content
}

# ---------------------------------------------------------------------------
# 7. Kelas PHP lewat Artisan (stub resmi Laravel, tanpa logika)
# ---------------------------------------------------------------------------

if ($SkipArtisan) {
    Write-Step 'Tahap Artisan dilewati (-SkipArtisan)'
} elseif (-not (Get-Command php -ErrorAction SilentlyContinue)) {
    Write-Step 'Tahap Artisan dilewati: perintah php tidak ditemukan di PATH'
    Write-Host '  [!] Buka terminal Laragon/XAMPP yang punya PHP, lalu jalankan ulang script.' -ForegroundColor Yellow
} else {
    Write-Step 'Membuat kelas PHP lewat php artisan make:* (bisa 1-3 menit)'

    function Invoke-Make([string]$Rel, [string]$Command) {
        $full = Get-FullPath $Rel
        if (Test-Path -LiteralPath $full) {
            $Stats['Artisan dilewati (ada)']++
            Write-Host "  [=] lewati  $Rel (sudah ada)" -ForegroundColor DarkGray
            return
        }
        $argList = @('artisan') + ($Command -split ' ')
        $output = & php @argList 2>&1
        if ((Test-Path -LiteralPath $full)) {
            $Stats['Artisan berhasil']++
            Write-Host "  [+] artisan $Rel" -ForegroundColor Green
        } else {
            $Stats['Artisan gagal']++
            Write-Host "  [x] gagal   php artisan $Command" -ForegroundColor Red
            if ($output) { Write-Host "      $($output | Out-String)" -ForegroundColor DarkRed }
        }
    }

    $C = 'app/Http/Controllers'
    $make = @(
        # --- Controllers: Public Website ("PublicSite" karena "public" kata kunci PHP) ---
        @("$C/PublicSite/CompanyProfile/HomeController.php",    'make:controller PublicSite/CompanyProfile/HomeController'),
        @("$C/PublicSite/CompanyProfile/AboutController.php",   'make:controller PublicSite/CompanyProfile/AboutController'),
        @("$C/PublicSite/CompanyProfile/ReviewController.php",  'make:controller PublicSite/CompanyProfile/ReviewController'),
        @("$C/PublicSite/CompanyProfile/GalleryController.php", 'make:controller PublicSite/CompanyProfile/GalleryController'),
        @("$C/PublicSite/CompanyProfile/ContactController.php", 'make:controller PublicSite/CompanyProfile/ContactController'),
        @("$C/PublicSite/Catalog/CatalogController.php",        'make:controller PublicSite/Catalog/CatalogController'),
        @("$C/PublicSite/Catalog/ProductController.php",        'make:controller PublicSite/Catalog/ProductController'),
        @("$C/PublicSite/Catalog/PriceEstimateController.php",  'make:controller PublicSite/Catalog/PriceEstimateController --invokable'),
        @("$C/PublicSite/Ordering/CartController.php",          'make:controller PublicSite/Ordering/CartController'),
        @("$C/PublicSite/Ordering/CheckoutController.php",      'make:controller PublicSite/Ordering/CheckoutController'),
        @("$C/PublicSite/Ordering/CustomOrderController.php",   'make:controller PublicSite/Ordering/CustomOrderController'),
        @("$C/PublicSite/Ordering/AttachmentController.php",    'make:controller PublicSite/Ordering/AttachmentController'),

        # --- Controllers: Admin - PRD-04 ADM ---
        @("$C/Admin/Auth/LoginController.php",                  'make:controller Admin/Auth/LoginController'),
        @("$C/Admin/DashboardController.php",                   'make:controller Admin/DashboardController --invokable'),
        @("$C/Admin/Content/HomepageContentController.php",     'make:controller Admin/Content/HomepageContentController'),
        @("$C/Admin/Content/AboutContentController.php",        'make:controller Admin/Content/AboutContentController'),
        @("$C/Admin/Content/SiteSettingController.php",         'make:controller Admin/Content/SiteSettingController'),
        @("$C/Admin/Content/TestimonialController.php",         'make:controller Admin/Content/TestimonialController --resource'),
        @("$C/Admin/Content/CollaborationController.php",       'make:controller Admin/Content/CollaborationController --resource'),
        @("$C/Admin/Content/GalleryItemController.php",         'make:controller Admin/Content/GalleryItemController --resource'),
        @("$C/Admin/Content/GalleryCategoryController.php",     'make:controller Admin/Content/GalleryCategoryController --resource'),
        @("$C/Admin/Content/MediaController.php",               'make:controller Admin/Content/MediaController'),
        @("$C/Admin/Catalog/CategoryController.php",            'make:controller Admin/Catalog/CategoryController --resource'),
        @("$C/Admin/Catalog/ProductController.php",             'make:controller Admin/Catalog/ProductController --resource'),
        @("$C/Admin/Catalog/ProductOptionController.php",       'make:controller Admin/Catalog/ProductOptionController'),
        @("$C/Admin/Catalog/PricingConfigurationController.php",'make:controller Admin/Catalog/PricingConfigurationController'),
        @("$C/Admin/Account/UserController.php",                'make:controller Admin/Account/UserController --resource'),

        # --- Controllers: Admin - PRD-05 FIN ---
        @("$C/Admin/Sales/OrderController.php",                 'make:controller Admin/Sales/OrderController'),
        @("$C/Admin/Sales/OrderStatusController.php",           'make:controller Admin/Sales/OrderStatusController'),
        @("$C/Admin/Sales/RequestStatusController.php",         'make:controller Admin/Sales/RequestStatusController'),
        @("$C/Admin/Sales/QuotationController.php",             'make:controller Admin/Sales/QuotationController'),
        @("$C/Admin/Sales/RequestConversionController.php",     'make:controller Admin/Sales/RequestConversionController'),
        @("$C/Admin/Sales/OrderCancellationController.php",     'make:controller Admin/Sales/OrderCancellationController'),
        @("$C/Admin/Sales/AttachmentDownloadController.php",    'make:controller Admin/Sales/AttachmentDownloadController --invokable'),
        @("$C/Admin/Sales/CustomerController.php",              'make:controller Admin/Sales/CustomerController --resource'),
        @("$C/Admin/Finance/PaymentController.php",             'make:controller Admin/Finance/PaymentController'),
        @("$C/Admin/Finance/PaymentVoidController.php",         'make:controller Admin/Finance/PaymentVoidController'),
        @("$C/Admin/Finance/RefundController.php",              'make:controller Admin/Finance/RefundController'),
        @("$C/Admin/Finance/InvoiceController.php",             'make:controller Admin/Finance/InvoiceController'),
        @("$C/Admin/Finance/ExpenseController.php",             'make:controller Admin/Finance/ExpenseController --resource'),
        @("$C/Admin/Finance/ExpenseCategoryController.php",     'make:controller Admin/Finance/ExpenseCategoryController --resource'),
        @("$C/Admin/Finance/ReportController.php",              'make:controller Admin/Finance/ReportController'),
        @("$C/Admin/Finance/PaymentSettingController.php",      'make:controller Admin/Finance/PaymentSettingController'),

        # --- Middleware dan Policy ---
        @('app/Http/Middleware/EnsureUserHasRole.php',  'make:middleware EnsureUserHasRole'),
        @('app/Http/Middleware/EnsureUserIsActive.php', 'make:middleware EnsureUserIsActive'),
        @('app/Policies/UserPolicy.php',                       'make:policy UserPolicy --model=User'),
        @('app/Policies/OrderPolicy.php',                      'make:policy OrderPolicy --model=Order'),
        @('app/Policies/PaymentPolicy.php',                    'make:policy PaymentPolicy --model=Payment'),
        @('app/Policies/RefundPolicy.php',                     'make:policy RefundPolicy --model=Refund'),
        @('app/Policies/InvoicePolicy.php',                    'make:policy InvoicePolicy --model=Invoice'),
        @('app/Policies/PaymentMethodConfigurationPolicy.php', 'make:policy PaymentMethodConfigurationPolicy --model=PaymentMethodConfiguration'),

        # --- Enum status dan pilihan tetap (app/Enums sudah dibuat di tahap 2) ---
        @('app/Enums/RoleName.php',            'make:enum RoleName --string'),
        @('app/Enums/PublicationStatus.php',   'make:enum PublicationStatus --string'),
        @('app/Enums/ProductStatus.php',       'make:enum ProductStatus --string'),
        @('app/Enums/PriceModel.php',          'make:enum PriceModel --string'),
        @('app/Enums/OrderType.php',           'make:enum OrderType --string'),
        @('app/Enums/OrderChannel.php',        'make:enum OrderChannel --string'),
        @('app/Enums/RequestStatus.php',       'make:enum RequestStatus --string'),
        @('app/Enums/OrderStatus.php',         'make:enum OrderStatus --string'),
        @('app/Enums/PaymentStatus.php',       'make:enum PaymentStatus --string'),
        @('app/Enums/PaymentRecordStatus.php', 'make:enum PaymentRecordStatus --string'),
        @('app/Enums/RefundStatus.php',        'make:enum RefundStatus --string'),
        @('app/Enums/ExpenseType.php',         'make:enum ExpenseType --string'),
        @('app/Enums/AttachmentStatus.php',    'make:enum AttachmentStatus --string'),

        # --- Service (logika bisnis bersama/kompleks) dan Support ---
        @('app/Services/Catalog/PriceCalculator.php',               'make:class Services/Catalog/PriceCalculator'),
        @('app/Services/Catalog/ProductConfigurationValidator.php', 'make:class Services/Catalog/ProductConfigurationValidator'),
        @('app/Services/Catalog/ConfigurationPayloadBuilder.php',   'make:class Services/Catalog/ConfigurationPayloadBuilder'),
        @('app/Services/Ordering/CartService.php',                  'make:class Services/Ordering/CartService'),
        @('app/Services/Ordering/OrderSubmissionService.php',       'make:class Services/Ordering/OrderSubmissionService'),
        @('app/Services/Ordering/CustomRequestSubmissionService.php','make:class Services/Ordering/CustomRequestSubmissionService'),
        @('app/Services/Ordering/AttachmentService.php',            'make:class Services/Ordering/AttachmentService'),
        @('app/Services/Ordering/OrderCodeGenerator.php',           'make:class Services/Ordering/OrderCodeGenerator'),
        @('app/Services/Sales/TransactionCalculator.php',           'make:class Services/Sales/TransactionCalculator'),
        @('app/Services/Sales/StatusTransitionService.php',         'make:class Services/Sales/StatusTransitionService'),
        @('app/Services/Sales/RequestConversionService.php',        'make:class Services/Sales/RequestConversionService'),
        @('app/Services/Finance/PaymentLedgerService.php',          'make:class Services/Finance/PaymentLedgerService'),
        @('app/Services/Finance/InvoiceService.php',                'make:class Services/Finance/InvoiceService'),
        @('app/Services/Finance/ReportService.php',                 'make:class Services/Finance/ReportService'),
        @('app/Services/Audit/AuditLogger.php',                     'make:class Services/Audit/AuditLogger'),
        @('app/Support/PhoneNumber.php',                            'make:class Support/PhoneNumber'),
        @('app/Support/Money.php',                                  'make:class Support/Money'),

        # --- Form Request: Public ---
        @('app/Http/Requests/PublicSite/PriceEstimateRequest.php',    'make:request PublicSite/PriceEstimateRequest'),
        @('app/Http/Requests/PublicSite/AddCartItemRequest.php',      'make:request PublicSite/AddCartItemRequest'),
        @('app/Http/Requests/PublicSite/UpdateCartItemRequest.php',   'make:request PublicSite/UpdateCartItemRequest'),
        @('app/Http/Requests/PublicSite/CheckoutCustomerRequest.php', 'make:request PublicSite/CheckoutCustomerRequest'),
        @('app/Http/Requests/PublicSite/SubmitOrderRequest.php',      'make:request PublicSite/SubmitOrderRequest'),
        @('app/Http/Requests/PublicSite/CustomOrderRequest.php',      'make:request PublicSite/CustomOrderRequest'),
        @('app/Http/Requests/PublicSite/UploadAttachmentRequest.php', 'make:request PublicSite/UploadAttachmentRequest'),

        # --- Form Request: Admin ADM ---
        @('app/Http/Requests/Admin/Auth/LoginRequest.php',                 'make:request Admin/Auth/LoginRequest'),
        @('app/Http/Requests/Admin/Content/HomepageContentRequest.php',    'make:request Admin/Content/HomepageContentRequest'),
        @('app/Http/Requests/Admin/Content/AboutContentRequest.php',       'make:request Admin/Content/AboutContentRequest'),
        @('app/Http/Requests/Admin/Content/SiteSettingRequest.php',        'make:request Admin/Content/SiteSettingRequest'),
        @('app/Http/Requests/Admin/Content/TestimonialRequest.php',        'make:request Admin/Content/TestimonialRequest'),
        @('app/Http/Requests/Admin/Content/CollaborationRequest.php',      'make:request Admin/Content/CollaborationRequest'),
        @('app/Http/Requests/Admin/Content/GalleryItemRequest.php',        'make:request Admin/Content/GalleryItemRequest'),
        @('app/Http/Requests/Admin/Content/GalleryCategoryRequest.php',    'make:request Admin/Content/GalleryCategoryRequest'),
        @('app/Http/Requests/Admin/Content/MediaUploadRequest.php',        'make:request Admin/Content/MediaUploadRequest'),
        @('app/Http/Requests/Admin/Catalog/CategoryRequest.php',           'make:request Admin/Catalog/CategoryRequest'),
        @('app/Http/Requests/Admin/Catalog/ProductRequest.php',            'make:request Admin/Catalog/ProductRequest'),
        @('app/Http/Requests/Admin/Catalog/ProductOptionRequest.php',      'make:request Admin/Catalog/ProductOptionRequest'),
        @('app/Http/Requests/Admin/Catalog/PricingConfigurationRequest.php','make:request Admin/Catalog/PricingConfigurationRequest'),
        @('app/Http/Requests/Admin/Account/UserRequest.php',               'make:request Admin/Account/UserRequest'),

        # --- Form Request: Admin FIN ---
        @('app/Http/Requests/Admin/Sales/ManualOrderRequest.php',          'make:request Admin/Sales/ManualOrderRequest'),
        @('app/Http/Requests/Admin/Sales/UpdateOrderStatusRequest.php',    'make:request Admin/Sales/UpdateOrderStatusRequest'),
        @('app/Http/Requests/Admin/Sales/UpdateRequestStatusRequest.php',  'make:request Admin/Sales/UpdateRequestStatusRequest'),
        @('app/Http/Requests/Admin/Sales/QuotationRequest.php',            'make:request Admin/Sales/QuotationRequest'),
        @('app/Http/Requests/Admin/Sales/ConvertToOrderRequest.php',       'make:request Admin/Sales/ConvertToOrderRequest'),
        @('app/Http/Requests/Admin/Sales/CancelOrderRequest.php',          'make:request Admin/Sales/CancelOrderRequest'),
        @('app/Http/Requests/Admin/Sales/CustomerRequest.php',             'make:request Admin/Sales/CustomerRequest'),
        @('app/Http/Requests/Admin/Finance/StorePaymentRequest.php',       'make:request Admin/Finance/StorePaymentRequest'),
        @('app/Http/Requests/Admin/Finance/VoidPaymentRequest.php',        'make:request Admin/Finance/VoidPaymentRequest'),
        @('app/Http/Requests/Admin/Finance/StoreRefundRequest.php',        'make:request Admin/Finance/StoreRefundRequest'),
        @('app/Http/Requests/Admin/Finance/CancelInvoiceRequest.php',      'make:request Admin/Finance/CancelInvoiceRequest'),
        @('app/Http/Requests/Admin/Finance/ExpenseRequest.php',            'make:request Admin/Finance/ExpenseRequest'),
        @('app/Http/Requests/Admin/Finance/ExpenseCategoryRequest.php',    'make:request Admin/Finance/ExpenseCategoryRequest'),
        @('app/Http/Requests/Admin/Finance/ReportFilterRequest.php',       'make:request Admin/Finance/ReportFilterRequest'),
        @('app/Http/Requests/Admin/Finance/PaymentSettingRequest.php',     'make:request Admin/Finance/PaymentSettingRequest'),

        # --- Seeder, Command, Mail ---
        @('database/seeders/RoleSeeder.php',            'make:seeder RoleSeeder'),
        @('database/seeders/OwnerUserSeeder.php',       'make:seeder OwnerUserSeeder'),
        @('database/seeders/SiteSettingSeeder.php',     'make:seeder SiteSettingSeeder'),
        @('database/seeders/GalleryCategorySeeder.php', 'make:seeder GalleryCategorySeeder'),
        @('database/seeders/ExpenseCategorySeeder.php', 'make:seeder ExpenseCategorySeeder'),
        @('app/Console/Commands/PurgeExpiredOrderingData.php', 'make:command PurgeExpiredOrderingData'),
        @('app/Mail/OrderSubmissionReceived.php',       'make:mail OrderSubmissionReceived')
    )

    # Model dibuat TANPA -m karena migration sudah dibuat di tahap 6.
    $models = @('Role', 'Media', 'SiteContent', 'SiteSetting', 'Testimonial', 'Collaboration',
                'GalleryCategory', 'GalleryItem', 'Category', 'Product', 'ProductImage',
                'OptionGroup', 'OptionValue', 'PricingConfiguration', 'Customer', 'Cart', 'CartItem',
                'OrderRequest', 'Order', 'OrderItem', 'OrderAttachment', 'SubmissionToken',
                'OrderStatusHistory', 'Payment', 'Refund', 'PaymentMethodConfiguration',
                'Invoice', 'InvoiceSnapshot', 'ExpenseCategory', 'Expense', 'AuditLog')

    foreach ($model in $models) { Invoke-Make "app/Models/$model.php" "make:model $model" }
    foreach ($item in $make)    { Invoke-Make $item[0] $item[1] }
}

# ---------------------------------------------------------------------------
# Ringkasan
# ---------------------------------------------------------------------------

Write-Step 'Selesai'
foreach ($k in $Stats.Keys) { Write-Host ("  {0,-24} {1}" -f $k, $Stats[$k]) }

Write-Host ''
Write-Host 'Langkah manual berikutnya (lihat dokumen rancangan bagian 8):' -ForegroundColor Cyan
Write-Host '  1. routes/web.php     : tambahkan require __DIR__.''/public.php''; dan require __DIR__.''/admin.php'';'
Write-Host '  2. vite.config.js     : ganti input menjadi public.css, public.js, admin.css, admin.js'
Write-Host '  3. bootstrap/app.php  : daftarkan alias middleware role dan active'
Write-Host '  4. composer require barryvdh/laravel-dompdf'
Write-Host '  5. php artisan storage:link'
Write-Host '  6. php artisan migrate'
