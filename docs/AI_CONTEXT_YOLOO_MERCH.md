# AI_CONTEXT — Website YOLOO Merch (Laravel)

> **Untuk AI yang membaca file ini.** Kamu membantu satu anggota Kelompok 4 mengerjakan bagiannya di proyek Website YOLOO Merch. File ini adalah konteks proyek yang **wajib diikuti** bersama file Master PRD (`PRD_WEBSITE_YOLOO_MERCH_-_FINAL.md`). Jika file ini dan Master PRD berbeda soal **kebutuhan produk/aturan bisnis**, ikuti Master PRD. Jika berbeda soal **struktur kode, nama file, nilai enum, atau pembagian tugas**, ikuti file ini.
>
> Langkah pertama: tanyakan atau kenali **siapa penggunanya** (Bertrand, Haidir, Raisyah, Taufik, atau Zhaqtiawan), lalu baca bagian 9 untuk orang itu. Jangan mengerjakan bagian anggota lain.

---

## 0. Cara anggota memakai file ini

1. Unggah ke AI: `PRD_WEBSITE_YOLOO_MERCH_-_FINAL.md` + file ini (`AI_CONTEXT_YOLOO_MERCH.md`). Opsional: `RANCANGAN_STRUKTUR_YOLOO_MERCH.md`.
2. Pesan pertama, contoh: *"Saya Haidir. Bantu saya mengerjakan CAT-FR-001 dan CAT-FR-002."*
3. Template pesan per anggota ada di bagian 12.

---

## 1. Ringkasan proyek

| Item | Nilai |
|---|---|
| Produk | Website YOLOO Merch: company profile, katalog + kalkulator estimasi, pemesanan tanpa login, dashboard admin (CMS, order, pembayaran QRIS manual, invoice PDF, laporan) |
| Konteks | Tugas kelompok mata kuliah Pemrograman Lanjut, Kelompok 4, Tekom A 2025, Universitas Negeri Makassar |
| Arsitektur | Satu aplikasi Laravel monolitik, satu database MySQL, dua area: **Public Website** dan **Admin Dashboard** |
| Stack | Laravel 11+ (struktur `bootstrap/app.php` baru, tanpa `Kernel.php`), PHP, MySQL + Eloquent, Blade, Tailwind CSS via Vite, JavaScript vanilla (tanpa Alpine/React/Vue), barryvdh/laravel-dompdf |
| Lingkungan anggota | Windows + Laragon (Cmder), VS Code, HeidiSQL. URL lokal `http://yoloo-merch.test` atau `http://127.0.0.1:8000` |
| Repository | `https://github.com/zsaayyew/yoloo-merch`; branch `main` (stabil), `develop` (gabungan), `feature/<domain>-<fitur>` (kerja) |
| Pengguna | Visitor & Customer Guest (tanpa login); Owner & Admin (login, RBAC dua role saja) |

Prinsip produk yang memengaruhi kode (dari Master PRD):
- Estimasi harga **bukan** harga final; harga selalu dihitung ulang di server.
- Item pesanan dan invoice menyimpan **snapshot**; perubahan katalog tidak mengubah transaksi lama.
- Tiga status **terpisah**: status custom request, status pengerjaan order, status pembayaran (dihitung, bukan diinput).
- **Tidak ada** halaman/endpoint cek status pesanan untuk pelanggan. **Tidak ada** pengiriman/ongkir (pickup saja). **Tidak ada** payment gateway; QRIS dikirim admin manual via WhatsApp.
- QRIS/instruksi pembayaran **tidak pernah** tampil di keranjang, checkout, ringkasan, halaman konfirmasi, atau email pelanggan.
- Tindakan khusus Owner (kelola akun Admin, ubah QRIS, void, refund, batal transaksi final, batal/revisi invoice) wajib dicek izin dan dicatat di audit log.
- Produk/kategori/data keuangan tidak dihapus permanen (arsip/soft delete/void).

---

## 2. Status proyek saat ini

Fondasi sudah dibuat oleh script `setup-structure.ps1` dan sudah di-push ke GitHub:

- **Semua file di bawah sudah ada sebagai placeholder.** Blade berisi komentar header (ID PRD, domain, catatan) dan kerangka `@extends`/`@include`. Kelas PHP berisi stub kosong dari Artisan. Migration berisi `id()` + `timestamps()` + TODO.
- `routes/web.php` hanya berisi `require __DIR__.'/public.php';` dan `require __DIR__.'/admin.php';`. Kedua file route masih kosong.
- `vite.config.js` input: `resources/css/public.css`, `resources/js/public.js`, `resources/css/admin.css`, `resources/js/admin.js`.
- `bootstrap/app.php` sudah mendaftarkan alias middleware `role` (`EnsureUserHasRole`) dan `active` (`EnsureUserIsActive`), serta `redirectGuestsTo('/admin/login')`.
- Belum ada fitur yang diimplementasikan. Saat ini membuka `/` menampilkan 404 (normal).

**Aturan untuk AI:** saat mengerjakan file yang sudah ada, berikan **isi lengkap file pengganti** beserta path-nya, dan pertahankan komentar header `{{-- ... --}}` di file Blade (perbarui baris Status menjadi "diimplementasikan"). Jangan membuat file baru jika file placeholder untuk fungsi itu sudah ada.

---

## 3. Struktur proyek (yang sudah ada)

```
app/
  Console/Commands/PurgeExpiredOrderingData.php
  Enums/        RoleName, PublicationStatus, ProductStatus, PriceModel, OrderType, OrderChannel,
                RequestStatus, OrderStatus, PaymentStatus, PaymentRecordStatus, RefundStatus,
                ExpenseType, AttachmentStatus
  Http/Controllers/
    PublicSite/CompanyProfile/  HomeController, AboutController, ReviewController, GalleryController, ContactController
    PublicSite/Catalog/         CatalogController, ProductController, PriceEstimateController (invokable)
    PublicSite/Ordering/        CartController, CheckoutController, CustomOrderController, AttachmentController
    Admin/DashboardController.php (invokable)
    Admin/Auth/                 LoginController
    Admin/Content/              HomepageContentController, AboutContentController, SiteSettingController, MediaController,
                                TestimonialController, CollaborationController, GalleryItemController, GalleryCategoryController (resource)
    Admin/Catalog/              CategoryController, ProductController (resource), ProductOptionController, PricingConfigurationController
    Admin/Account/              UserController (resource)
    Admin/Sales/                OrderController, OrderStatusController, RequestStatusController, QuotationController,
                                RequestConversionController, OrderCancellationController, AttachmentDownloadController (invokable),
                                CustomerController (resource)
    Admin/Finance/              PaymentController, PaymentVoidController, RefundController, InvoiceController, ReportController,
                                PaymentSettingController, ExpenseController, ExpenseCategoryController (resource)
  Http/Middleware/  EnsureUserHasRole, EnsureUserIsActive
  Http/Requests/PublicSite/     PriceEstimateRequest, AddCartItemRequest, UpdateCartItemRequest, CheckoutCustomerRequest,
                                SubmitOrderRequest, CustomOrderRequest, UploadAttachmentRequest
  Http/Requests/Admin/Auth/     LoginRequest
  Http/Requests/Admin/Content/  HomepageContentRequest, AboutContentRequest, SiteSettingRequest, TestimonialRequest,
                                CollaborationRequest, GalleryItemRequest, GalleryCategoryRequest, MediaUploadRequest
  Http/Requests/Admin/Catalog/  CategoryRequest, ProductRequest, ProductOptionRequest, PricingConfigurationRequest
  Http/Requests/Admin/Account/  UserRequest
  Http/Requests/Admin/Sales/    ManualOrderRequest, UpdateOrderStatusRequest, UpdateRequestStatusRequest, QuotationRequest,
                                ConvertToOrderRequest, CancelOrderRequest, CustomerRequest
  Http/Requests/Admin/Finance/  StorePaymentRequest, VoidPaymentRequest, StoreRefundRequest, CancelInvoiceRequest,
                                ExpenseRequest, ExpenseCategoryRequest, ReportFilterRequest, PaymentSettingRequest
  Mail/OrderSubmissionReceived.php
  Models/   User (bawaan), Role, Media, SiteContent, SiteSetting, Testimonial, Collaboration, GalleryCategory, GalleryItem,
            Category, Product, ProductImage, OptionGroup, OptionValue, PricingConfiguration, Customer, Cart, CartItem,
            OrderRequest, Order, OrderItem, OrderAttachment, SubmissionToken, OrderStatusHistory, Payment, Refund,
            PaymentMethodConfiguration, Invoice, InvoiceSnapshot, ExpenseCategory, Expense, AuditLog
  Policies/ UserPolicy, OrderPolicy, PaymentPolicy, RefundPolicy, InvoicePolicy, PaymentMethodConfigurationPolicy
  Services/Catalog/   PriceCalculator, ProductConfigurationValidator, ConfigurationPayloadBuilder
  Services/Ordering/  CartService, OrderSubmissionService, CustomRequestSubmissionService, AttachmentService, OrderCodeGenerator
  Services/Sales/     TransactionCalculator, StatusTransitionService, RequestConversionService
  Services/Finance/   PaymentLedgerService, InvoiceService, ReportService
  Services/Audit/     AuditLogger
  Support/            PhoneNumber, Money

database/migrations/2026_11_20_0000NN_*.php   (32 file, urutan tetap; lihat bagian 6)
database/seeders/   RoleSeeder, OwnerUserSeeder, SiteSettingSeeder, GalleryCategorySeeder, ExpenseCategorySeeder

resources/css/      app.css (bawaan, Tailwind), public.css, admin.css
resources/js/       app.js (bawaan), public.js, admin.js,
                    shared/{lightbox,modal,confirm-dialog,submit-guard}.js
                    public/{mobile-nav,gallery-filter,product-configurator,price-calculator,cart,file-upload}.js
                    admin/{sidebar,product-options,pricing-matrix,order-items,expense-items,report-filter}.js

resources/views/
  layouts/          public, admin, admin-auth
  partials/public/  seo-meta, navbar, footer
  partials/admin/   sidebar, topbar, flash-messages
  components/ui/    button, alert, modal, confirm-dialog, badge, status-badge, empty-state, error-state, breadcrumb, card, lightbox
  components/form/  input, textarea, select, checkbox, money-input, file-upload
  components/table/ data-table, filter-bar
  components/catalog/  product-card, price-display, estimate-note
  components/ordering/ cart-badge, pickup-info, order-summary
  components/admin/    page-header, stat-card
  public/home/index + sections/{hero,intro,featured-products,services,advantages,collaboration-preview,gallery-preview,testimonial-preview,cta}
  public/about/index + sections/{profile,vision-mission,values-advantages,ordering-guide}
  public/reviews/index + sections/{testimonials,collaborations}
  public/gallery/index + sections/{filter,grid}
  public/contact/index + sections/{channels,location-hours}
  public/catalog/{index,category,show,not-found} + sections/{search-filter,category-list,product-grid,category-header,
                 product-gallery,product-info,price-info,configurator,calculator-panel,order-actions,related-products}
  public/cart/index + sections/{item-list,totals}
  public/checkout/{customer,summary} + sections/{customer-form,item-attachments,cart-recap,review-items,review-contact,agreement}
  public/orders/success + sections/{confirmation-details,next-steps}
  public/custom-orders/{create,success} + sections/{product-reference,requirements,reference-files,contact-form,agreement,confirmation-details}
  admin/auth/login
  admin/dashboard/index + sections/{content-summary,sales-summary}
  admin/content/{homepage,about,settings}/edit, admin/content/{testimonials,collaborations,gallery}/{index,form}, admin/content/gallery-categories/index
  admin/media/index
  admin/categories/{index,form}
  admin/products/{index,form} + sections/{basic-info,images,options,purchase-settings}
  admin/pricing/{index,edit} + sections/{price-model,parameter-groups,tariff-matrix,quantity-tiers,extra-fees}
  admin/users/{index,form}
  admin/orders/{index,show,form} + sections/{filters,customer-info,items,attachments,request-status,quotation,order-status,
               status-history,payments,refunds,invoices,internal-notes,customer-picker,items-editor,totals}
               + modals/{payment-form,void-payment,refund-form,cancel-order,convert-request}
  admin/customers/{index,show,form}
  admin/expenses/{index,form}, admin/expense-categories/index
  admin/invoices/{index,show,pdf}
  admin/reports/index + sections/{filters,summary,sales,payments,refunds,material-purchases,operating-expenses}
  admin/payment-settings/index
  emails/ordering/submission-received

routes/   web.php (memuat public.php dan admin.php), public.php, admin.php, console.php (bawaan)
storage/app/private/{tmp-uploads,order-attachments,expense-receipts,payment-settings,invoices}   (disk "local", tidak publik)
storage/app/public/media   (disk "public", via storage:link)
tests/Feature/{CompanyProfile,Catalog,Ordering,Admin,Finance}/, tests/Unit/Services/
docs/
```

Pola Blade:
- Halaman: `@extends('layouts.public')` atau `@extends('layouts.admin')`, isi `@section('title')`, `@section('content')` yang hanya berisi `@include` section.
- Section khusus satu halaman ada di `<folder>/sections/` (partial). Potongan yang dipakai di 2+ tempat dibuat sebagai Blade Component `<x-folder.nama>`.
- Form tambah dan ubah memakai **satu file `form.blade.php`**.
- Layout publik memuat `@vite(['resources/css/public.css','resources/js/public.js'])`; layout admin memuat versi admin. Script tambahan per halaman lewat `@push('scripts')`.
- Styling memakai class Tailwind di Blade. Jangan membuat entry Vite baru per halaman.

---

## 4. Aturan wajib untuk AI (semua anggota)

1. **Kerjakan hanya bagian pengguna.** Ubah hanya file yang tercantum sebagai milik pengguna di bagian 9. Jika butuh perubahan di file anggota lain atau file bersama, **jangan ubah**; tuliskan usulan perubahan dan sarankan pengguna mengoordinasikan di grup.
2. **Jangan menambah fitur** yang tidak ada di Master PRD, dan jangan mengubah aturan bisnis (status, rumus, hak akses, alur).
3. **Jangan membuat file duplikat.** Cek dulu daftar file di bagian 3. Komponen/service yang sudah ada harus dipakai.
4. **Ikuti alur lapisan:** route → middleware → Form Request (validasi server) → controller tipis → service (logika bisnis) → model → view. Perhitungan uang dan perubahan status **tidak boleh** ditulis langsung di controller atau Blade.
5. **Nilai enum dan kontrak data** di bagian 5–7 wajib dipakai persis, agar kode antaranggota yang dibuat oleh AI berbeda tetap cocok.
6. **Keamanan:** CSRF di semua form, validasi di server, `$fillable` eksplisit di model, escape output Blade (`{{ }}`), file pelanggan di disk `local` (privat), jangan pernah mempercayai harga dari browser.
7. **Gaya jawaban:** Bahasa Indonesia, untuk mahasiswa yang baru belajar Laravel. Untuk setiap tugas, berikan: (a) daftar file yang diubah/dibuat, (b) isi lengkap setiap file, (c) perintah terminal (Windows/Laragon), (d) cara mengetes di browser, (e) pesan commit dengan format `tipe(domain): pesan`.
8. **Versi Laravel:** jika jawaban bergantung pada versi, minta pengguna menjalankan `php artisan --version` dulu.
9. **Git:** selalu sarankan bekerja di branch `feature/<domain>-<fitur>` dari `develop`, bukan di `main`/`develop`.
10. **Jika data bisnis belum ada** (tarif, alamat, visi/misi, format invoice), jangan mengarang sebagai fakta. Pakai placeholder yang jelas (misalnya `[ALAMAT_BELUM_DIKONFIRMASI]`) atau data dari seeder yang sudah disepakati.

---

## 5. Nilai enum (wajib seragam)

Semua enum adalah *string-backed*. Nama case PascalCase, nilai snake_case, dan sediakan method `label(): string` yang mengembalikan teks Indonesia sesuai PRD.

| Enum | Case => nilai (label) |
|---|---|
| `RoleName` | Owner => `owner` (Owner), Admin => `admin` (Admin) |
| `PublicationStatus` | Draft => `draft` (Draft), Published => `published` (Dipublikasikan) |
| `ProductStatus` | Draft => `draft`, Active => `active` (Aktif), Inactive => `inactive` (Nonaktif), Archived => `archived` (Diarsipkan) |
| `PriceModel` | Fixed => `fixed` (Harga tetap), StartingFrom => `starting_from` (Harga mulai dari), Calculator => `calculator` (Kalkulator estimasi), Quotation => `quotation` (Request quotation) |
| `OrderType` | Direct => `direct` (Direct purchase website), Custom => `custom` (Hasil konversi custom request), Manual => `manual` (Transaksi manual) |
| `OrderChannel` | Website => `website`, Whatsapp => `whatsapp`, Instagram => `instagram`, WalkIn => `walk_in` (Langsung) |
| `RequestStatus` | New => `new` (Baru Diterima), UnderReview => `under_review` (Ditinjau Admin), AwaitingInfo => `awaiting_info` (Menunggu Informasi), QuotationSent => `quotation_sent` (Penawaran Dikirim), Approved => `approved` (Disetujui Pelanggan), Rejected => `rejected` (Ditolak), Expired => `expired` (Kedaluwarsa), Converted => `converted` (Dikonversi Menjadi Order) |
| `OrderStatus` | AwaitingConfirmation => `awaiting_confirmation` (Menunggu Konfirmasi), AwaitingPayment => `awaiting_payment` (Menunggu Pembayaran), Processing => `processing` (Diproses), ReadyForPickup => `ready_for_pickup` (Siap Diambil), Completed => `completed` (Selesai), Cancelled => `cancelled` (Dibatalkan) |
| `PaymentStatus` | Unpaid => `unpaid` (Belum Dibayar), PartiallyPaid => `partially_paid` (Dibayar Sebagian), Paid => `paid` (Lunas) |
| `PaymentRecordStatus` | Valid => `valid` (Valid), Void => `void` (Void) |
| `RefundStatus` | Completed => `completed` (Selesai), Cancelled => `cancelled` (Dibatalkan) |
| `ExpenseType` | Material => `material` (Pembelian bahan), Operational => `operational` (Biaya operasional) |
| `AttachmentStatus` | Temporary => `temporary` (Sementara), Submitted => `submitted` (Terkirim) |

Ringkasan status refund per order (Tidak Ada Refund / Refund Sebagian / Refund Penuh) **dihitung** oleh `PaymentLedgerService`, tidak disimpan sebagai enum.

Transisi status order yang valid (dijaga `StatusTransitionService`):
`awaiting_confirmation → awaiting_payment → processing → ready_for_pickup → completed`; `cancelled` dari status mana pun sebelum `completed`, **Owner saja**. Order hasil konversi custom dimulai dari `awaiting_payment`. Order direct dimulai dari `awaiting_confirmation`. Status pembayaran tidak memengaruhi status pengerjaan secara otomatis.

---

## 6. Database dan migration

Migration kerangka (urutan tetap, nama file `2026_11_20_0000NN_<nama>.php`):

```
01 create_roles_table                      ADM      17 create_carts_table                    ORD
02 add_role_and_status_to_users_table      ADM      18 create_cart_items_table               ORD
03 create_media_table                      ADM      19 create_order_requests_table           ORD+FIN
04 create_site_contents_table              ADM      20 create_orders_table                   ORD+FIN
05 create_site_settings_table              ADM      21 create_order_items_table              ORD+FIN
06 create_testimonials_table               ADM      22 create_order_attachments_table        ORD
07 create_collaborations_table             ADM      23 create_submission_tokens_table        ORD
08 create_gallery_categories_table         ADM      24 create_order_status_histories_table   FIN
09 create_gallery_items_table              ADM      25 create_payments_table                 FIN
10 create_categories_table                 ADM      26 create_refunds_table                  FIN
11 create_products_table                   ADM      27 create_payment_method_configurations  FIN
12 create_product_images_table             ADM      28 create_invoices_table                 FIN
13 create_option_groups_table              ADM      29 create_invoice_snapshots_table        FIN
14 create_option_values_table              ADM      30 create_expense_categories_table       FIN
15 create_pricing_configurations_table     ADM      31 create_expenses_table                 FIN
16 create_customers_table                  ORD+FIN  32 create_audit_logs_table               Bersama
```

Aturan:
- **Fase fondasi (sampai tim menyatakan selesai):** pemilik migration mengisi kolom **langsung di file kerangka**. Setelah menarik perubahan migration dari teman, setiap anggota menjalankan `php artisan migrate:fresh --seed` di laptopnya (data lokal hilang, itu wajar).
- Setelah fase fondasi: perubahan skema lewat **migration baru**, bukan mengedit yang lama.
- Jangan mengubah migration bawaan Laravel (`0001_01_01_*`).
- Uang: `unsignedBigInteger` (Rupiah tanpa desimal). Status: `string` dengan nilai enum bagian 5. Nomor WhatsApp disimpan ternormalisasi `62xxxxxxxxxx`.
- Tabel `order_items` dan `invoice_snapshots`: data snapshot tidak di-update setelah dibuat.

### 6.1 Kontrak kolom minimum tabel bersama (diturunkan dari Master PRD bagian J)

Kolom di bawah adalah **kesepakatan dasar** agar ORD dan FIN cocok. Kolom tambahan boleh ditambah pemilik, tetapi kolom ini jangan diganti nama tanpa koordinasi.

- `customers`: `name`, `whatsapp_number` (unik, `62…`), `email` nullable, `notes` nullable, `is_active` bool.
- `order_requests`: `code` unik (`REQ-YYMMDD-XXXX`), `customer_id`, `channel`, `status` (RequestStatus), `product_id` nullable, `product_name_snapshot` nullable, `options_snapshot` json nullable, `estimate_snapshot` json nullable (hanya informasi), `description` text, `quantity`, `specification` nullable, `desired_deadline` date nullable, `budget` nullable, `notes` nullable, `quotation_amount` nullable, `quotation_terms` nullable, `submitted_at`.
- `orders`: `code` unik (`WEB-YYMMDD-XXXX` untuk website; nomor transaksi manual dibuat FIN), `customer_id`, `type` (OrderType), `channel` (OrderChannel), `source_request_id` nullable unik → `order_requests.id`, `status` (OrderStatus), `payment_status` (PaymentStatus), `subtotal`, `discount`, `additional_fee`, `total`, `estimated_total` nullable, `agreed_dp_amount` nullable, `payment_terms_note` nullable, `fulfillment` (selalu `pickup`), `notes` nullable, `cancellation_reason` nullable, `cancelled_at` nullable, `ordered_at`.
- `order_items`: `order_id`, `product_id` nullable, `product_name_snapshot`, `options_snapshot` json, `quantity`, `price_model`, `unit_price`, `subtotal`, `price_status` (`estimate`/`final`), `description` nullable (custom item).
- `order_attachments`: `order_id` nullable, `order_item_id` nullable, `order_request_id` nullable, `original_name`, `stored_path`, `mime_type`, `size_bytes`, `caption` nullable, `status` (AttachmentStatus), `session_token` nullable.

---

## 7. Kontrak lintas domain

1. **ADM → publik:** model konten/produk menyediakan query scope `scopePublished()` (konten) dan `scopeActive()` (produk/kategori aktif, bukan arsip). Halaman publik selalu memakai scope ini.
2. **CAT → ORD (ConfigurationPayload):** array dengan kunci tetap:
   ```php
   [
       'product_id' => int,
       'product_name_snapshot' => string,
       'price_model' => 'fixed'|'starting_from'|'calculator'|'quotation',
       'options' => [ ['group' => 'Ukuran', 'value' => '5 x 5 cm', 'option_value_id' => 12], ... ],
       'quantity' => int,
       'estimated_unit_price' => int|null,
       'estimated_total' => int|null,
       'pricing_version' => int|null,
   ]
   ```
   Dibuat oleh `Services\Catalog\ConfigurationPayloadBuilder`.
3. **Harga:** hanya `Services\Catalog\PriceCalculator` yang menghitung estimasi. Signature yang disepakati: `estimate(Product $product, array $optionValueIds, int $quantity): array` dan mengembalikan `['unit_price' => int, 'total' => int, 'breakdown' => array, 'is_estimate' => true]`, atau melempar exception validasi jika konfigurasi tidak valid atau tarif tidak ada. ORD **memanggil** service ini, tidak menyalin rumusnya.
4. **Validasi konfigurasi:** `Services\Catalog\ProductConfigurationValidator::validate(Product $product, array $optionValueIds, int $quantity): void` (opsi wajib, kombinasi terlarang, minimum order). Dipakai CAT dan ORD.
5. **ORD → FIN:** ORD menyimpan `customers`, `orders`/`order_requests`, `order_items`, `order_attachments` dalam satu `DB::transaction()` dengan status awal: direct → `status=awaiting_confirmation`, `payment_status=unpaid`; custom request → `order_requests.status=new`. Setelah itu semua perubahan status hanya lewat FIN.
6. **FIN:** total transaksi hanya lewat `TransactionCalculator`; pembayaran bersih, sisa tagihan, status pembayaran, dan ringkasan refund hanya lewat `PaymentLedgerService`; perubahan status hanya lewat `StatusTransitionService` (sekaligus menulis `order_status_histories`).
7. **QRIS:** hanya FIN yang membaca `PaymentMethodConfiguration`. ORD dan CP tidak boleh membacanya.
8. **Audit:** tindakan khusus Owner dan perubahan penting memanggil `Services\Audit\AuditLogger::log(string $action, Model $subject, ?string $reason = null, array $changes = [])`.
9. **Helper:** `Support\PhoneNumber::normalize(string): string` (08xx/+628xx/628xx → 628xx); `Support\Money::format(int): string` (contoh `Rp125.000`).
10. **Data usaha:** kontak, WhatsApp, alamat/jam pickup, identitas usaha dibaca dari `SiteSetting` (dikelola ADM). Kunci setting yang disepakati: `whatsapp_number`, `instagram_handle`, `instagram_url`, `email`, `address`, `operating_hours`, `pickup_location`, `pickup_hours`, `business_name`, `business_logo`.

---

## 8. Route (nama wajib dipakai di `route()`)

**Public** (`routes/public.php`, tanpa prefix nama):

| Nama | Method + URL | Controller | Pemilik |
|---|---|---|---|
| `home` | GET `/` | CompanyProfile\HomeController@index | CP |
| `about` | GET `/tentang-kami` | CompanyProfile\AboutController@index | CP |
| `reviews` | GET `/ulasan-kolaborasi` | CompanyProfile\ReviewController@index | CP |
| `gallery` | GET `/galeri` | CompanyProfile\GalleryController@index | CP |
| `contact` | GET `/kontak` | CompanyProfile\ContactController@index | CP |
| `catalog.index` | GET `/produk` | Catalog\CatalogController@index | CAT |
| `catalog.category` | GET `/produk/kategori/{category:slug}` | Catalog\CatalogController@category | CAT |
| `products.show` | GET `/produk/{product:slug}` | Catalog\ProductController@show | CAT |
| `products.estimate` | POST `/produk/{product:slug}/estimasi` | Catalog\PriceEstimateController | CAT |
| `cart.index` / `cart.store` | GET / POST `/keranjang` | Ordering\CartController | ORD |
| `cart.update` / `cart.destroy` | PATCH / DELETE `/keranjang/{cartItem}` | Ordering\CartController | ORD |
| `checkout.customer` / `checkout.customer.store` | GET / POST `/checkout` | Ordering\CheckoutController | ORD |
| `checkout.summary` | GET `/checkout/ringkasan` | Ordering\CheckoutController@summary | ORD |
| `checkout.submit` | POST `/checkout/kirim` (+throttle) | Ordering\CheckoutController@submit | ORD |
| `orders.success` | GET `/pesanan/berhasil/{code}` | Ordering\CheckoutController@success | ORD |
| `custom-orders.create` / `custom-orders.store` | GET / POST `/custom-order` (+throttle pada POST) | Ordering\CustomOrderController | ORD |
| `custom-orders.success` | GET `/custom-order/berhasil/{code}` | Ordering\CustomOrderController@success | ORD |
| `attachments.store` / `attachments.destroy` | POST `/lampiran`, DELETE `/lampiran/{attachment}` | Ordering\AttachmentController | ORD |

**Admin** (`routes/admin.php`, prefix URL `/admin`, prefix nama `admin.`, middleware `auth` + `active`; aksi Owner tambah `role:owner`):

`admin.login`, `admin.login.store`, `admin.logout`, `admin.dashboard`, `admin.content.homepage.edit|update`, `admin.content.about.edit|update`, `admin.content.settings.edit|update`, `admin.content.testimonials.*`, `admin.content.collaborations.*`, `admin.content.gallery.*`, `admin.content.gallery-categories.*`, `admin.media.index|store|destroy`, `admin.categories.*` (+ `archive`), `admin.products.*` (+ `archive`), `admin.products.options.store|update|destroy`, `admin.pricing.index|edit|update`, `admin.users.*` (Owner), `admin.orders.index|create|store|show|edit|update`, `admin.orders.status`, `admin.orders.cancel` (Owner), `admin.requests.show|status|quotation|convert`, `admin.attachments.download`, `admin.payments.store`, `admin.payments.void` (Owner), `admin.refunds.store` (Owner), `admin.invoices.index|show|pdf|store`, `admin.invoices.cancel|revise` (Owner), `admin.customers.*`, `admin.expenses.*`, `admin.expense-categories.*`, `admin.reports.index`, `admin.payment-settings.index|update` (update: Owner).

Setiap pemilik domain menulis route domainnya **di bawah komentar domainnya** di file route (`// PRD-01 CP`, `// PRD-02 CAT`, dst.) supaya tidak bentrok saat merge.

---

## 9. Pembagian per anggota

### 9.1 Bertrand Graciano Kurniawan — PRD-01 CP (Company Profile & Promotional Website)

- **Acuan PRD:** Master PRD bagian 15 (CP), PAGE-CP-001 s.d. 005, GLOBAL-CP-001/002, CP-FR-001 s.d. 017, CP-BR, CP-EC, CP-AT.
- **Branch:** `feature/cp-<fitur>`, commit `feat(cp): ...`.
- **Boleh mengubah:**
  - `app/Http/Controllers/PublicSite/CompanyProfile/*`
  - `resources/views/public/{home,about,reviews,gallery,contact}/**`
  - `resources/views/partials/public/{seo-meta,navbar,footer}.blade.php`
  - `resources/js/public/{mobile-nav,gallery-filter}.js`
  - Bagian `// PRD-01 CP` di `routes/public.php`
  - `tests/Feature/CompanyProfile/*`
- **Hanya membaca (milik orang lain):** model `SiteContent`, `SiteSetting`, `Testimonial`, `Collaboration`, `GalleryCategory`, `GalleryItem`, `Product` (milik ADM); komponen `<x-catalog.product-card>` (CAT), `<x-ordering.cart-badge>` (ORD), `<x-ui.*>`.
- **Koordinasi dulu:** `layouts/public.blade.php`, `components/ui/*` (termasuk `lightbox`), `resources/css/app.css` (token warna brand).
- **Urutan tugas:**
  1. Navbar + menu mobile (CP-FR-002, 003), footer (CP-FR-015), seo-meta (CP-FR-016). Tautan Katalog ke `route('catalog.index')`; jika route CAT belum ada, gunakan `Route::has()` sebagai fallback.
  2. Route CP + controller yang mengembalikan view.
  3. Beranda (CP-FR-001, 004, 005): 9 section berurutan. Section yang datanya kosong disembunyikan atau memakai `<x-ui.empty-state>` (CP-FR-017, CP-BR-011). Produk unggulan memakai `Product::active()` + `<x-catalog.product-card>`.
  4. Tentang Kami (CP-FR-006, 007), Kontak (CP-FR-013, 014; tautan `https://wa.me/<nomor>`), Ulasan & Kolaborasi (CP-FR-008, 009), Galeri + filter + lightbox (CP-FR-010 s.d. 012).
- **Larangan:** CP tidak menghitung harga, tidak membuat katalog/checkout duplikat, tidak menampilkan konten berstatus draft, tidak membuat konten fiktif sebagai fakta, tidak ada menu "Cek Pesanan".
- **Ketergantungan:** data konten dari ADM (Taufik). Selama tabel belum terisi, pakai seeder lokal atau tampilan empty state.

### 9.2 Muhammad Haidir — PRD-02 CAT (Product Catalog & Customization)

- **Acuan PRD:** Master PRD bagian 16 (CAT), PAGE-CAT-001 s.d. 005, CAT-FR-001 s.d. 012, CAT-BR, CAT-EC, CAT-AT.
- **Branch:** `feature/cat-<fitur>`, commit `feat(cat): ...`.
- **Boleh mengubah:**
  - `app/Http/Controllers/PublicSite/Catalog/*`
  - `app/Http/Requests/PublicSite/PriceEstimateRequest.php`
  - `app/Services/Catalog/*` (PriceCalculator, ProductConfigurationValidator, ConfigurationPayloadBuilder)
  - `resources/views/public/catalog/**`
  - `resources/views/components/catalog/*` (product-card, price-display, estimate-note)
  - `resources/js/public/{product-configurator,price-calculator}.js`
  - Bagian `// PRD-02 CAT` di `routes/public.php`
  - `tests/Feature/Catalog/*`, `tests/Unit/Services/` untuk service Catalog
- **Hanya membaca:** model `Category`, `Product`, `ProductImage`, `OptionGroup`, `OptionValue`, `PricingConfiguration` (milik ADM).
- **Koordinasi dulu:** struktur tabel `option_*` dan `pricing_configurations` (dengan Taufik, keputusan K-2 dan K-3); bentuk ConfigurationPayload dan signature `PriceCalculator` (dengan Raisyah, bagian 7).
- **Urutan tugas:**
  1. Sepakati dengan Taufik bentuk JSON konfigurasi harga, lalu tulis `PriceCalculator` + unit test (CAT-FR-008, 009; CAT-BR-004). Rumus terbaca dari konfigurasi, bukan hardcode.
  2. Katalog, kategori, pencarian/filter (CAT-FR-001 s.d. 003), komponen product-card dan price-display.
  3. Detail produk + galeri foto (CAT-FR-004, 011), tampilan 4 model harga (CAT-FR-007).
  4. Configurator + validator (CAT-FR-005, 006), endpoint `products.estimate` + `price-calculator.js` (hasil berlabel Estimasi).
  5. `ConfigurationPayloadBuilder` + section `order-actions` yang mengirim ke `cart.store` atau mengarahkan ke `custom-orders.create?produk={slug}` (CAT-FR-010).
  6. `not-found` + kondisi kosong/error (CAT-FR-012).
- **Larangan:** tidak mengelola master data (itu ADM), tidak membuat cart/checkout, tidak menampilkan stok, tidak menampilkan angka tanpa label Estimasi, tidak menaruh rumus di JavaScript.
- **Ketergantungan:** kolom tabel master dari Taufik. `PriceCalculator` dipakai ORD, jadi signature bagian 7 jangan diubah tanpa memberi tahu Raisyah.

### 9.3 Raisyah Ramadhani Majid — PRD-03 ORD (Customer Ordering & Online Purchase) + koordinator repo

- **Acuan PRD:** Master PRD bagian 17 (ORD), PAGE-ORD-001 s.d. 007, ORD-FR-001 s.d. 017, ORD-BR, ORD-EC, ORD-AT.
- **Branch:** `feature/ord-<fitur>`, commit `feat(ord): ...`.
- **Boleh mengubah:**
  - `app/Http/Controllers/PublicSite/Ordering/*`
  - `app/Http/Requests/PublicSite/{AddCartItemRequest,UpdateCartItemRequest,CheckoutCustomerRequest,SubmitOrderRequest,CustomOrderRequest,UploadAttachmentRequest}.php`
  - `app/Services/Ordering/*`
  - Model `Cart`, `CartItem`, `OrderAttachment`, `SubmissionToken` dan migration 17, 18, 22, 23
  - `resources/views/public/{cart,checkout,orders,custom-orders}/**`
  - `resources/views/components/ordering/*` (cart-badge, pickup-info, order-summary)
  - `resources/views/emails/ordering/submission-received.blade.php`, `app/Mail/OrderSubmissionReceived.php`
  - `app/Console/Commands/PurgeExpiredOrderingData.php`
  - `resources/js/public/{cart,file-upload}.js`
  - Bagian `// PRD-03 ORD` di `routes/public.php`
  - `tests/Feature/Ordering/*`
- **Bersama dengan Zhaqtiawan (FIN):** model + migration `Customer` (16), `OrderRequest` (19), `Order` (20), `OrderItem` (21). Kolom minimum di bagian 6.1. Raisyah boleh mengisi kolom sesuai 6.1; tambahan kolom dikoordinasikan.
- **Hanya membaca:** `Product`, `OptionValue`, `PricingConfiguration`, `SiteSetting` (ADM); memanggil `PriceCalculator`, `ProductConfigurationValidator` (CAT).
- **Peran koordinator** (boleh mengubah, setelah memberi tahu grup): `layouts/*`, `components/{ui,form,table}/*`, `resources/css/app.css`, `vite.config.js`, `routes/web.php`, `bootstrap/app.php`, `app/Services/Audit/AuditLogger.php`, `app/Support/*`, migration 32 `audit_logs`, `resources/js/shared/*`. Juga me-review dan merge Pull Request ke `develop`.
- **Urutan tugas:**
  1. Sepakati kolom tabel bersama (6.1) dengan Zhaqtiawan; isi migration 16, 19–23; buat `Support\PhoneNumber`.
  2. Keranjang (ORD-FR-001 s.d. 004): tabel `carts` + cookie token 7 hari, `CartService` (gabung item identik, hitung ulang via `PriceCalculator`, tandai item tidak tersedia), halaman `cart/index`, `cart-badge`. Produk `quotation` ditolak dan diarahkan ke custom request.
  3. Checkout data pelanggan + info pickup (ORD-FR-005, 006): validasi nama 2–100 karakter, WA 10–15 digit lalu dinormalisasi, email opsional, catatan ≤ 500; data disimpan di session; tidak ada field alamat/ongkir.
  4. Ringkasan + persetujuan (ORD-FR-007), lalu submit atomik `OrderSubmissionService` (ORD-FR-008, 013, 014): token sekali pakai, hitung ulang harga (jika berbeda, kembali ke ringkasan untuk persetujuan ulang), kode `WEB-YYMMDD-XXXX` lewat `OrderCodeGenerator`, kosongkan keranjang, halaman `orders.success` (ORD-FR-009) yang hanya bisa dibuka sesi pengirim.
  5. Upload lampiran (ORD-FR-011): `AttachmentService`, JPG/PNG/PDF, ≤ 10 MB, ≤ 5 file, cek MIME dari isi file, nama acak, disk `local` folder `tmp-uploads` lalu dipindah ke `order-attachments` saat submit.
  6. Custom request (ORD-FR-010, 012): deskripsi 20–2.000 karakter, jumlah positif, tenggat tidak di masa lalu, kode `REQ-YYMMDD-XXXX`, status `new`, halaman `custom-orders.success`.
  7. Should: email (ORD-FR-015), tautan WhatsApp berisi kode (ORD-FR-016), throttle 5 kiriman/IP/10 menit + honeypot (ORD-FR-017), command pembersihan dijadwalkan di `routes/console.php`.
- **Larangan:** tidak membaca/menampilkan QRIS atau instruksi pembayaran; tidak membuat halaman/endpoint cek status; tidak mengubah status setelah order tersimpan (itu FIN); tidak mempercayai harga dari form; tidak menyimpan file pelanggan di disk `public`.

### 9.4 Taufik — PRD-04 ADM (Admin Dashboard & Content Management)

- **Acuan PRD:** Master PRD bagian 18 (ADM), PAGE-ADM-001 s.d. 006, ADM-FR-001 s.d. 009, ADM-BR, ADM-EC, ADM-AT.
- **Branch:** `feature/adm-<fitur>`, commit `feat(adm): ...`.
- **Boleh mengubah:**
  - `app/Http/Controllers/Admin/{Auth,Content,Catalog,Account}/*`, `app/Http/Controllers/Admin/DashboardController.php`
  - `app/Http/Requests/Admin/{Auth,Content,Catalog,Account}/*`
  - `app/Http/Middleware/{EnsureUserHasRole,EnsureUserIsActive}.php`, `app/Policies/UserPolicy.php`
  - Model `User` (tambahan role/status), `Role`, `Media`, `SiteContent`, `SiteSetting`, `Testimonial`, `Collaboration`, `GalleryCategory`, `GalleryItem`, `Category`, `Product`, `ProductImage`, `OptionGroup`, `OptionValue`, `PricingConfiguration` dan migration 01–15
  - Seeder `RoleSeeder`, `OwnerUserSeeder`, `SiteSettingSeeder`, `GalleryCategorySeeder`
  - `resources/views/admin/{auth,dashboard,content,media,categories,products,pricing,users}/**`, `resources/views/layouts/admin-auth.blade.php`
  - `resources/views/partials/admin/{sidebar,topbar}.blade.php`, `resources/views/components/admin/*`
  - `resources/js/admin/{sidebar,product-options,pricing-matrix}.js`
  - Bagian `// PRD-04 ADM` di `routes/admin.php`
  - `tests/Feature/Admin/*`
- **Koordinasi dulu:** struktur tabel produk/opsi/harga (dengan Haidir); `layouts/admin.blade.php` dan `partials/admin/flash-messages` (koordinator); menu FIN di sidebar (dengan Zhaqtiawan); section `sales-summary` dashboard milik Zhaqtiawan.
- **Urutan tugas:**
  1. Migration 01–02 + `Role`/`User` + `RoleSeeder` + `OwnerUserSeeder` (password dari `.env`, misalnya `OWNER_EMAIL`, `OWNER_PASSWORD`).
  2. Login/logout manual dengan `Auth::attempt` (tanpa Breeze), cek akun aktif, middleware `role` dan `active`, struktur grup route admin (ADM-FR-001).
  3. Layout admin, sidebar sesuai role, dashboard ringkasan (ADM-FR-002).
  4. Migration + CRUD kategori dan produk dengan arsip, bukan hapus permanen (ADM-FR-004, ADM-BR-006). Sediakan `scopeActive()` di `Product` dan `Category`.
  5. Opsi produk (ADM-FR-005) dan konfigurasi harga (ADM-FR-006), dengan bentuk data yang disepakati bersama Haidir.
  6. Konten beranda/about/settings (ADM-FR-003) dengan kunci `SiteSetting` di bagian 7 poin 10, testimoni/kolaborasi/galeri (ADM-FR-007), media + status publikasi (ADM-FR-008). Sediakan `scopePublished()`.
  7. User Management khusus Owner + `UserPolicy` + audit log (ADM-FR-009).
- **Larangan:** ADM tidak mengelola order, pembayaran, invoice, pengeluaran, laporan; tidak menyimpan QRIS/rekening; tidak ada role selain owner/admin; tidak ada registrasi publik.

### 9.5 Zhaqtiawan Dimas Anugrah — PRD-05 FIN (Sales, Transaction & Financial Management)

- **Acuan PRD:** Master PRD bagian 19 (FIN), PAGE-FIN-001 s.d. 010, FIN-FR-001 s.d. 020, FIN-BR, FIN-EC, FIN-AT.
- **Branch:** `feature/fin-<fitur>`, commit `feat(fin): ...`.
- **Boleh mengubah:**
  - `app/Http/Controllers/Admin/{Sales,Finance}/*`
  - `app/Http/Requests/Admin/{Sales,Finance}/*`
  - `app/Services/{Sales,Finance}/*`
  - `app/Policies/{OrderPolicy,PaymentPolicy,RefundPolicy,InvoicePolicy,PaymentMethodConfigurationPolicy}.php`
  - Model `OrderStatusHistory`, `Payment`, `Refund`, `PaymentMethodConfiguration`, `Invoice`, `InvoiceSnapshot`, `ExpenseCategory`, `Expense` dan migration 24–31; seeder `ExpenseCategorySeeder`
  - `resources/views/admin/{orders,customers,expenses,expense-categories,invoices,reports,payment-settings}/**`
  - `resources/views/admin/dashboard/sections/sales-summary.blade.php`
  - `resources/js/admin/{order-items,expense-items,report-filter}.js`
  - Bagian `// PRD-05 FIN` di `routes/admin.php`
  - `tests/Feature/Finance/*`
- **Bersama dengan Raisyah (ORD):** model + migration `Customer`, `OrderRequest`, `Order`, `OrderItem` (kolom minimum 6.1).
- **Hanya membaca:** `Product`, `SiteSetting` (identitas usaha untuk snapshot invoice), `User`; memakai middleware/layout dari ADM.
- **Urutan tugas:**
  1. Sepakati tabel bersama dengan Raisyah; isi migration 24–31.
  2. `PaymentLedgerService` + `TransactionCalculator` + unit test lebih dulu (rumus FIN-BR-003, 005, 006, 008, 020; contoh angka di FIN-AT-004 s.d. 006, 017 s.d. 020).
  3. Daftar dan detail order/request (FIN-FR-001, 002), pelanggan (FIN-FR-011).
  4. `StatusTransitionService` + histori (FIN-FR-010), review request + penawaran + konversi sekali saja (FIN-FR-003, 004).
  5. Transaksi manual (FIN-FR-005 s.d. 007), pembayaran (FIN-FR-008), void Owner (FIN-FR-009), refund Owner (FIN-FR-019), batal transaksi Owner (FIN-FR-016).
  6. Pengaturan QRIS: Owner ubah, Admin read-only (FIN-FR-020).
  7. Pengeluaran (FIN-FR-012), invoice PDF dari snapshot immutable + revisi Owner (FIN-FR-013, 014) dengan dompdf (`admin/invoices/pdf` memakai CSS inline), laporan (FIN-FR-015), Could: CSV, bukti pembayaran, kartu ringkasan.
- **Larangan:** status pembayaran tidak boleh diinput manual; jangan menghapus payment/refund/invoice/transaksi (pakai void/batal); invoice lama tidak boleh berubah; request belum dikonversi tidak dihitung sebagai penjualan; tidak ada payment gateway.

---

## 10. Keputusan tim yang belum final

Jika pengguna belum memberi tahu hasil keputusan, AI memakai **nilai default** ini dan menyebutkannya di jawaban.

| Kode | Pertanyaan | Default yang dipakai |
|---|---|---|
| K-1 | Request dan order satu tabel atau dua? | Dua tabel: `order_requests` dan `orders` (`orders.source_request_id`) |
| K-2 | Variasi produk | `option_groups` + `option_values` (bukan tabel Product Variant tunggal) |
| K-3 | Bentuk konfigurasi harga | Satu baris aktif per produk di `pricing_configurations` dengan kolom `price_model`, `base_price` nullable, `config` JSON (parameter, tarif per opsi/matriks, tier kuantitas, biaya tambahan, minimum order), `version`, `effective_date`, `status` |
| K-5 | Penyimpanan keranjang | Tabel `carts` + cookie token acak 7 hari |
| K-6 | Halaman konten admin | Sub-route `/admin/content/{homepage,about,settings,testimonials,collaborations,gallery,gallery-categories}` |
| K-7 | Histori status request | Dicatat di `audit_logs` (belum ada tabel khusus) |
| K-8 | Harga di tabel produk | Harga hanya di `pricing_configurations` |

Data bisnis yang belum ada (tarif nyata, visi/misi, alamat/jam, format invoice, kategori pengeluaran, format file AI/PSD) memakai placeholder, bukan karangan.

---

## 11. Konvensi penamaan

| Jenis | Aturan | Contoh |
|---|---|---|
| Controller | PascalCase tunggal + `Controller` | `CartController` |
| Model / tabel | Model tunggal PascalCase, tabel jamak snake_case | `OrderItem` / `order_items` |
| Kolom | snake_case bahasa Inggris | `estimated_unit_price` |
| View / folder / JS | kebab-case | `custom-orders/success.blade.php` |
| Komponen | `<x-folder.nama>` | `<x-ordering.pickup-info />` |
| URL publik | Bahasa Indonesia sesuai PRD | `/keranjang` |
| URL admin | Bahasa Inggris sesuai PRD | `/admin/orders` |
| Branch | `feature/<domain>-<fitur>`, `fix/<domain>-<masalah>` | `feature/ord-cart` |
| Commit | `<tipe>(<domain>): <pesan>`; tipe: feat, fix, wip, refactor, docs, chore, test | `feat(ord): simpan item keranjang` |
| Teks antarmuka | Bahasa Indonesia | "Tambah ke Keranjang" |

---

## 12. Template pesan pertama untuk AI

Salin, ganti bagian dalam kurung siku, lalu kirim bersama file Master PRD dan file ini.

**Bertrand**
> Saya Bertrand (PRD-01 CP). Baca AI_CONTEXT bagian 9.1. Saya sedang di branch `feature/cp-[fitur]`. Bantu saya mengerjakan [contoh: navbar, menu mobile, dan footer (CP-FR-002, 003, 015)]. Berikan isi lengkap setiap file yang diubah, perintah terminal, dan cara mengetesnya.

**Haidir**
> Saya Haidir (PRD-02 CAT). Baca AI_CONTEXT bagian 9.2. Saya sedang di branch `feature/cat-[fitur]`. Bantu saya mengerjakan [contoh: PriceCalculator dan unit test-nya (CAT-FR-008, 009)]. Struktur pricing_configurations yang disepakati: [tempel jika sudah ada / pakai default K-3].

**Raisyah**
> Saya Raisyah (PRD-03 ORD, koordinator repo). Baca AI_CONTEXT bagian 9.3. Saya sedang di branch `feature/ord-[fitur]`. Bantu saya mengerjakan [contoh: keranjang (ORD-FR-001 s.d. 004)]. Status keputusan tim: [K-1 …, atau pakai default].

**Taufik**
> Saya Taufik (PRD-04 ADM). Baca AI_CONTEXT bagian 9.4. Saya sedang di branch `feature/adm-[fitur]`. Bantu saya mengerjakan [contoh: role, akun Owner, login/logout, dan middleware (ADM-FR-001)].

**Zhaqtiawan**
> Saya Zhaqtiawan (PRD-05 FIN). Baca AI_CONTEXT bagian 9.5. Saya sedang di branch `feature/fin-[fitur]`. Bantu saya mengerjakan [contoh: PaymentLedgerService dan TransactionCalculator beserta unit test (FIN-FR-007, 008)].

---

## 13. Checklist sebelum AI memberi jawaban final

- [ ] Semua file yang diubah termasuk dalam daftar "Boleh mengubah" milik pengguna.
- [ ] Tidak ada fitur di luar PRD, tidak ada aturan bisnis yang berubah.
- [ ] Nilai enum, nama route, nama kolom tabel bersama, dan signature service sesuai bagian 5–8.
- [ ] Validasi ada di Form Request (server), uang/status lewat service.
- [ ] Teks UI berbahasa Indonesia; label "Estimasi" muncul di semua angka estimasi.
- [ ] Ada perintah terminal, cara tes, dan pesan commit.
- [ ] Jika butuh perubahan di file orang lain, sudah ditulis sebagai usulan koordinasi, bukan diubah langsung.
