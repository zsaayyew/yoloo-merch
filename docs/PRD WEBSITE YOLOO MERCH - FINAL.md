PRODUCT REQUIREMENT DOCUMENT

**YOLOO MERCH WEBSITE**



| **Informasi Dokumen** | **Keterangan** |
| :---: | :---: |
| Nama proyek | YOLOO Merch Website |
| Mata kuliah | Pemrograman Lanjut |
| Kelas / kelompok | Kelompok 4/Kelas Tekom a 2025 |
| Dosen pengampu | Mushaf, S.Kom., M.Kom. |
| Anggota kelompok | Bertrand Graciano Kurniawan Muhammad Haidir Raisyah Ramadhani Majid Taufik Zhaqtiawan Dimas Anugrah |
| Versi dokumen | 0.9 |
| Tanggal dibuat | 14 November 2026 |
| Status | Draft completed |
# 
# PENGELOLAAN DOKUMEN
## Penanggung jawab pengisian


| **Domain** | **Penanggung jawab** | **Status pengisian** |
| :---: | :---: | :---: |
| PRD-01 Company Profile & Promotional Website | Bertrand Graciano Kurniawan | Selesai |
| PRD-02 Product Catalog & Customization System | Muhammad Haidir | Selesai |
| PRD-03 Customer Ordering & Online Purchase System | Raisyah Ramadhani Majid | Selesai |
| PRD-04 Admin Dashboard & Content Management System | Taufik | Selesai |
| PRD-05 Sales, Transaction & Financial Management System | Zhaqtiawan Dimas Anugrah | Selesai |
# DAFTAR ISI
**BAGIAN A — MASTER PRODUCT REQUIREMENTS**

1. Product Overview

2. Product Background dan Problem Context

3. Product Vision, Principles, dan Objectives

4. Success Metrics dan Definition of Success

5. Stakeholders, Users, dan User Roles

6. Product Scope dan Batasan

7. Product Structure dan Information Architecture

8. End-to-End Business Workflows

9. Shared Business Rules dan System-Wide Policies

10. Shared Data Concepts dan Cross-Domain Contracts

11. Shared UX/UI Principles

12. Shared Technical dan Non-Functional Requirements

13. Security, Privacy, dan Access Principles

14. Cross-Domain Acceptance dan Quality Checklist

**BAGIAN B — SUB-PRODUCT REQUIREMENTS (DIISI ANGGOTA)**

15. PRD-01 Company Profile & Promotional Website 

16. PRD-02 Product Catalog & Customization System 

17. PRD-03 Customer Ordering & Online Purchase System 

18. PRD-04 Admin Dashboard & Content Management System 

19. PRD-05 Sales, Transaction & Financial Management System

# BAGIAN A — MASTER PRODUCT REQUIREMENTS
# 1. PRODUCT OVERVIEW
## 1.1 Nama dan deskripsi produk
Nama produk: YOLOO Merch Website.

YOLOO Merch Website adalah platform digital untuk mendukung aktivitas informasi, promosi, penayangan katalog, pemesanan produk merchandise custom, serta pengelolaan operasional usaha secara internal. Platform menggabungkan public website yang dapat diakses pelanggan dengan admin dashboard untuk mengelola konten dan aktivitas bisnis.
## 1.2 Konsep produk
Website memiliki dua lingkungan utama yang saling terhubung melalui data yang dikelola sistem:

- Public Website: digunakan pengunjung dan pelanggan untuk mengenal usaha, melihat produk dan variasinya, memeriksa informasi harga atau estimasi, melihat portofolio/ulasan, serta melakukan pembelian langsung atau mengirim permintaan pesanan custom.

- Admin Dashboard: digunakan pihak internal untuk mengelola konten website, katalog dan konfigurasi produk, permintaan/pesanan, transaksi penjualan, pembayaran, pembelian bahan/pengeluaran, invoice, dan laporan sederhana.
## 1.3 Nilai utama produk
- Menyediakan satu sumber informasi resmi mengenai layanan dan produk YOLOO Merch.

- Memudahkan calon pelanggan memahami pilihan produk, variasi, dan cara pemesanan.

- Menyediakan jalur pembelian langsung untuk produk yang spesifikasinya jelas serta jalur permintaan penawaran untuk produk custom.

- Membantu admin mengelola konten dan data usaha tanpa harus mengubah kode website.

- Menyatukan pencatatan transaksi dari website maupun kanal pemesanan lain dalam satu sistem internal.

- Membantu pencatatan pembayaran, pengeluaran, dan pembuatan invoice dari data transaksi.
## 1.4 Sumber konteks produk
Ruang lingkup produk mengacu pada katalog produk YOLOO Merch. Katalog tersebut memperlihatkan kategori sticker (diecut, kisscut, sheet dan laminasi), printing (A3, A4, artprint, poster, photo, pilihan jenis kertas), round pin (bottle caps dan beberapa ukuran), acrylic (keychain, charm, phone charm, chained keychain), serta custom merchandise kit dan jasa desain. Daftar aktual, ketersediaan layanan, dan spesifikasi produksinya tetap perlu dikonfirmasi kepada pemilik usaha.
# 2. PRODUCT BACKGROUND DAN PROBLEM CONTEXT
## 2.1 Latar belakang
YOLOO Merch menyediakan layanan pembuatan merchandise dan produk cetak yang dapat memiliki variasi bahan, ukuran, ketebalan, finishing, desain, jumlah, serta kebutuhan khusus pelanggan. Sebagian pesanan dapat berupa produk dengan opsi standar, sementara pesanan lainnya memerlukan diskusi dan penawaran harga terlebih dahulu.

Website dirancang sebagai media informasi dan promosi sekaligus sarana pemesanan. Di sisi internal, sistem diharapkan membantu pencatatan data pelanggan, pesanan, item transaksi, pembayaran, pembelian bahan/pengeluaran, dan dokumen invoice.
## 2.2 Problem/opportunity statement
Calon pelanggan membutuhkan tempat terpusat untuk melihat produk, variasi, contoh hasil pekerjaan, dan cara memesan. Di sisi usaha, informasi produk dan konten perlu dikelola dengan mudah, sedangkan transaksi yang berasal dari berbagai kanal perlu dapat dicatat secara terstruktur. Untuk produk custom, harga dapat bergantung pada konfigurasi dan kesepakatan sehingga sistem perlu mendukung estimasi maupun input harga final secara fleksibel.

# 3. PRODUCT VISION, PRINCIPLES, DAN OBJECTIVES
## 3.1 Product vision
Menjadi platform digital YOLOO Merch yang memudahkan pelanggan mengenal, memilih, dan memesan merchandise custom sekaligus membantu pengelola usaha mengelola konten, pesanan, transaksi, dan administrasi usaha secara lebih terstruktur.
## 3.2 Prinsip perancangan
- Custom-first, not generic e-commerce: proses harus mengakomodasi pesanan dengan spesifikasi dan harga yang bervariasi.

- Guest-friendly: pelanggan dapat mengirim pesanan tanpa kewajiban membuat akun.

- Admin-manageable: data katalog, harga, konten, dan transaksi dapat dikelola melalui dashboard sesuai hak akses.

- Estimate is not final quote: kalkulator menampilkan estimasi; harga final dapat memerlukan konfirmasi admin.

- One source of transaction data: transaksi website dan transaksi manual dicatat dalam sistem internal yang sama.

- No unnecessary real-time operations: versi awal tidak membangun tracking pesanan real-time atau stok real-time.

- Clear separation: status pengerjaan pesanan dan status pembayaran disimpan sebagai dua status berbeda.

## 3.3 Product objectives


| **ID** | **Tujuan produk** | **Indikator awal / cara evaluasi** |
| :---: | :---: | :---: |
| OBJ-01 | Menyediakan informasi dan promosi YOLOO Merch secara terpusat. | Halaman profil, layanan, katalog, galeri, portofolio, dan kontak tersedia serta dapat dikelola. |
| OBJ-02 | Memudahkan pelanggan memahami opsi produk dan mengirim pesanan. | Pengunjung dapat menemukan detail produk dan menyelesaikan alur direct purchase atau custom request. |
| OBJ-03 | Mendukung estimasi harga untuk produk terpilih. | Produk yang memiliki aturan harga dapat menghitung estimasi harga per unit dan total. |
| OBJ-04 | Menyatukan pencatatan transaksi dari berbagai kanal. | Admin dapat membuat transaksi dari pesanan website maupun input manual. |
| OBJ-05 | Membantu pengelolaan pembayaran dan pengeluaran. | Pembayaran parsial/pelunasan dan pengeluaran dapat dicatat serta direkap. |
| OBJ-06 | Mempermudah penerbitan dokumen transaksi. | Invoice PDF dapat dibuat dari data transaksi yang tersimpan. |
# 4. SUCCESS METRICS DAN DEFINITION OF SUCCESS


| **Area** | **Metrik / indikator** | **Kriteria Keberhasilan** |
| :---: | :---: | :---: |
| Public website | Seluruh halaman inti dapat diakses dan ditampilkan secara responsif. | 100% halaman inti tersedia dan dapat digunakan pada desktop maupun perangkat mobile. |
| Product discovery | Produk dapat ditemukan melalui kategori/pencarian dan halaman detail. | Seluruh produk aktif dapat ditemukan melalui kategori atau pencarian dan memiliki halaman detail. |
| Price calculator | Perhitungan untuk konfigurasi yang didukung menghasilkan harga per unit dan total yang konsisten. | Seluruh konfigurasi produk yang didukung berhasil menghasilkan estimasi harga per unit dan total yang sesuai dengan aturan perhitungan. |
| Ordering | Direct purchase dan custom request dapat dikirim tanpa login pelanggan. | Direct purchase dan custom request dapat dikirim tanpa login pelanggan dan tersimpan di sistem. |
| CMS | Admin dapat melakukan CRUD konten dan produk sesuai hak akses. | Seluruh modul pengelolaan konten dan produk yang ditetapkan mendukung CRUD sesuai hak akses admin. |
| Transaction | Transaksi dapat dicatat dari kanal website dan manual. | Transaksi dari website maupun pencatatan manual dapat disimpan dengan informasi pelanggan, item, jumlah, dan total harga. |
| Payment | Pembayaran parsial dan pelunasan menghasilkan sisa tagihan yang benar. | Seluruh skenario pembayaran, termasuk DP, pembayaran bertahap, dan pelunasan, menghasilkan sisa tagihan yang benar. |
| Invoice | Invoice PDF sesuai dengan data transaksi dan dapat dibuka. | Seluruh invoice yang berhasil dibuat menghasilkan PDF yang dapat dibuka dan informasi di dalamnya sesuai dengan transaksi. |
| Expense/ report | Pengeluaran tersimpan dan laporan dapat difilter berdasarkan periode. | Seluruh pengeluaran yang dicatat tersimpan dan laporan dapat menampilkan data sesuai periode filter. |
 
## 4.1 Definition of success
- Setiap domain utama telah diimplementasikan dan terintegrasi.

- Tidak ada alur kritis yang membutuhkan data palsu atau input manual di luar rancangan tanpa alasan yang terdokumentasi.

- Perhitungan harga, total transaksi, total pembayaran, sisa tagihan, dan rekap pengeluaran telah diuji.

- Hak akses admin dan validasi input berfungsi sesuai spesifikasi.

- Versi akhir dapat didemonstrasikan melalui skenario pengguna yang disepakati.

# 5. STAKEHOLDERS, USERS, DAN USER ROLES
## 5.1 Stakeholders


| **Stakeholder** | **Kepentingan / tanggung jawab** |
| :---: | :---: |
| Pemilik YOLOO Merch | Memberikan validasi proses usaha, data produk, kebijakan harga, pembayaran, dan prioritas kebutuhan. |
| Pelanggan/pengunjung | Mengakses informasi, katalog, galeri, ulasan, dan melakukan pemesanan/permintaan penawaran. |
| Admin usaha | Mengelola konten, produk, pesanan, transaksi, pembayaran, pengeluaran, dan invoice. |
| Tim pengembang (5 anggota) | Menganalisis, mendesain, mengimplementasikan, mengintegrasikan, dan menguji sistem sesuai domain. |
| Dosen/pembimbing | Memberikan masukan terhadap kesesuaian ruang lingkup dan hasil proyek akademik. |
 
## 5.2 User roles dan akses tingkat tinggi


| **Role** | **Akses utama** | **Catatan** |
| :---: | :---: | :---: |
| Visitor | Melihat halaman publik, katalog, detail produk, galeri, portofolio, ulasan, dan kontak. | Tidak memerlukan login. |
| Customer Guest | Mengirim direct purchase atau custom order request melalui formulir publik tanpa membuat akun. | Tidak memiliki akses ke dashboard atau data pelanggan/pesanan lain. Hanya melihat halaman konfirmasi dari pengajuan yang dikirim dalam sesi sendiri. |
| Owner | Mengakses seluruh dashboard dan memiliki hak penuh untuk mengelola website, master data, pesanan, transaksi, pembayaran, pengeluaran, invoice, laporan, pengguna, serta konfigurasi sistem. | Wajib login. Memiliki hak khusus untuk mengelola akun Admin, mengubah konfigurasi QRIS, melakukan void pembayaran, mencatat refund, membatalkan transaksi final, serta membatalkan dan menerbitkan revisi invoice. |
| Admin | Mengakses dashboard untuk mengelola konten, produk, kategori, variasi, konfigurasi harga, pesanan, transaksi manual, pelanggan, pembayaran, pengeluaran, invoice awal, dan laporan. | Wajib login. Tidak dapat mengelola akun atau role pengguna, mengubah konfigurasi QRIS, melakukan void/refund, membatalkan transaksi final, atau membatalkan/merevisi invoice. |
 

Catatan: Sistem hanya memiliki dua role internal, yaitu Owner dan Admin. Visitor dan Customer Guest merupakan aktor publik yang tidak memerlukan autentikasi dashboard. Tidak terdapat role Super Admin, Manager, atau Admin/Editor.
# 6. PRODUCT SCOPE DAN BATASAN
## 6.1 In scope
- Public website berisi homepage, profil usaha, informasi layanan, katalog, detail produk, ulasan, portofolio/kolaborasi, galeri, dan kontak.

- Katalog kategori dan variasi produk, termasuk spesifikasi bahan/ukuran/ketebalan/finishing sesuai data usaha.

- Model harga fleksibel: harga tetap, harga mulai dari, kalkulator estimasi untuk produk terpilih, atau request quotation.

- Kalkulator estimasi produk yang mendukung pilihan konfigurasi, jumlah pesanan, estimasi harga per unit, dan estimasi total.

- Direct purchase dan custom order request melalui guest flow tanpa wajib membuat akun.

- Unggah file desain/referensi jika disepakati untuk jenis pesanan yang memerlukannya.

- Admin dashboard untuk mengelola konten, kategori, produk, variasi, dan konfigurasi harga.

- Pengelolaan pesanan dan transaksi yang berasal dari website maupun input manual (WhatsApp, Instagram, atau kanal langsung).

- Pencatatan pembayaran DP/parsial/pelunasan, pembelian bahan/pengeluaran, pembuatan invoice PDF, dan laporan sederhana.

- Status pengerjaan pesanan dikelola melalui dashboard internal oleh Admin atau Owner. Untuk pesanan yang telah selesai diproduksi, status Siap Diambil digunakan sebagai penanda internal. Admin menginformasikan kesiapan pengambilan kepada pelanggan melalui WhatsApp secara manual.
## 
## 6.2 Out of scope — tidak dibangun pada versi awal
- Tracking proses pesanan real-time untuk pelanggan.

- Halaman publik untuk pencarian status pesanan berdasarkan kode pesanan atau nomor WhatsApp, endpoint lookup status pelanggan, dan notifikasi status otomatis.

- Manajemen stok atau persediaan bahan real-time.

- Sistem akuntansi lengkap, pembukuan pajak, neraca, atau laporan keuangan standar akuntansi.

- Payment gateway dan verifikasi pembayaran otomatis oleh penyedia pembayaran, kecuali diputuskan kemudian.

- Akun/login wajib untuk pelanggan.

- Integrasi otomatis dengan ekspedisi atau pelacakan kurir.

- Otomatisasi produksi, penjadwalan mesin, dan kalkulasi biaya produksi kompleks yang belum memiliki data tarif.

# 7. PRODUCT STRUCTURE DAN INFORMATION ARCHITECTURE
## 7.1 Struktur public website


| **Halaman / area** | **Tujuan** | **Pemilik domain** |
| :---: | :---: | :---: |
| Homepage | Memperkenalkan YOLOO Merch, menonjolkan layanan dan produk, serta mengarahkan pengunjung. | PRD-01 |
| About Us | Menjelaskan profil, sejarah, layanan, dan keunggulan usaha. | PRD-01 |
| Product Catalog | Menampilkan kategori dan daftar produk. | PRD-02 |
| Product Detail | Menampilkan gambar, spesifikasi, variasi, harga, dan pilihan konfigurasi. | PRD-02 |
| Price Calculator | Menghitung estimasi harga untuk produk yang didukung. | PRD-02 |
| Cart / Checkout | Menyelesaikan pembelian langsung tanpa login wajib. | PRD-03 |
| Custom Order Request | Mengirim kebutuhan dan referensi untuk penawaran harga. | PRD-03 |
| Testimonials | Menampilkan ulasan pelanggan yang dikurasi admin. | PRD-01 |
| Portfolio / Collaboration | Menampilkan hasil kerja sama dan proyek. | PRD-01 |
| Gallery | Menampilkan foto hasil produksi. | PRD-01 |
| Contact | Menyediakan kanal komunikasi. | PRD-01 |
 
## 
## 
## 
## 7.2 Struktur admin dashboard


| **Area dashboard** | **Fungsi** | **Pemilik domain** |
| :---: | :---: | :---: |
| Authentication | Login, logout, manajemen session, dan pembatasan akses dashboard menggunakan dua role internal: Owner dan Admin. | PRD-04 |
| User Management | Mengelola akun Admin, termasuk membuat akun, mengubah informasi akun, mengaktifkan atau menonaktifkan akun. Hanya Owner yang memiliki akses. | PRD-04 |
| Dashboard overview | Ringkasan aktivitas dan navigasi administrasi. | PRD-04 |
| Content management | Kelola homepage, profil, ulasan, portofolio, galeri, kontak. | PRD-04 |
| Product management | Kelola kategori, produk, detail, variasi, dan gambar. | PRD-04 |
| Pricing configuration | Kelola model harga, opsi, nilai tarif, dan aturan kalkulator. | PRD-04 |
| Order & sales management | Kelola order dari website dan transaksi manual. | PRD-05 |
| Payment management | Mencatat pembayaran yang telah diterima, menghitung total pembayaran dan sisa tagihan, serta menampilkan riwayat pembayaran. | PRD-05 |
| Payment Method & Payment Instruction Settings | Mengelola informasi QRIS dan teks instruksi pembayaran yang digunakan setelah pesanan dikonfirmasi. Pengubahan konfigurasi hanya dapat dilakukan Owner; Admin hanya dapat melihat konfigurasi aktif untuk keperluan operasional. | PRD-05 |
| Purchase & expense | Catat pembelian bahan dan pengeluaran. | PRD-05 |
| Invoice & reports | Generate invoice dan laporan transaksi/keuangan sederhana. | PRD-05 |
 
# 8. END-TO-END BUSINESS WORKFLOWS
## 8.1 Alur direct purchase
1. Pelanggan membuka public website dan memilih produk yang memiliki opsi pembelian langsung.

2. Pelanggan membaca detail dan memilih konfigurasi yang tersedia.

3. Jika produk mendukung kalkulator, pelanggan memilih parameter dan jumlah untuk melihat estimasi harga per unit serta total.

4. Pelanggan menambahkan produk ke keranjang atau melanjutkan pembelian.

5. Pelanggan mengisi identitas dan kontak tanpa membuat akun.

6. Pelanggan memeriksa ringkasan pesanan yang berisi produk, konfigurasi, jumlah, estimasi harga, informasi pengambilan di tempat, data kontak, dan catatan pesanan. Tidak ada instruksi pembayaran pada tahap checkout.

7. Pelanggan mengirim pesanan. Sistem menyimpan order dengan kode pesanan unik, status pengerjaan Menunggu Konfirmasi, dan status pembayaran Belum Dibayar. Sistem menampilkan halaman konfirmasi penerimaan tanpa QRIS atau instruksi pembayaran.

8. Admin memeriksa spesifikasi, lampiran desain/referensi jika ada, dan rincian pesanan. Admin menetapkan total tagihan final. Jika nilai final berbeda dari estimasi yang dilihat pelanggan, Admin menyampaikan perubahan dan memperoleh persetujuan pelanggan terlebih dahulu.

9. Setelah total final dan nominal DP atau pelunasan disepakati, Admin mengubah status pengerjaan menjadi Menunggu Pembayaran dan mengirimkan total tagihan serta informasi QRIS melalui WhatsApp secara manual. Informasi QRIS diambil dari konfigurasi FIN.

10. Pelanggan melakukan pembayaran QRIS di luar sistem. Admin memverifikasi penerimaan dana secara manual, mencatat pembayaran valid di FIN, memperbarui status pengerjaan sesuai proses produksi, dan menerbitkan invoice PDF dari data transaksi final bila diperlukan.

11. Setelah produksi selesai, Admin mengubah status pengerjaan menjadi Siap Diambil dan menghubungi pelanggan melalui WhatsApp secara manual. Setelah pelanggan mengambil barang, Admin mengubah status menjadi Selesai. Seluruh status dan riwayatnya hanya tersedia pada dashboard internal; tidak ada halaman publik untuk memantau pesanan.
## 8.2 Alur custom order / request quotation
1. Pelanggan memilih kategori/produk atau menghubungi formulir custom request.

2. Pelanggan menjelaskan kebutuhan, jumlah, spesifikasi, tenggat yang diharapkan, dan mengunggah referensi bila diperlukan.

3. Sistem menyimpan permintaan sebagai request yang belum otomatis dianggap transaksi final.

4. Admin memeriksa permintaan dan berkomunikasi dengan pelanggan untuk mengklarifikasi kebutuhan.

5. Admin memasukkan penawaran harga dan persyaratan yang telah disepakati.

6. Jika pelanggan menyetujui, admin mengonversi atau mencatatnya sebagai pesanan/transaksi.

7. Request custom yang baru dikirim tidak menghasilkan instruksi pembayaran. Admin terlebih dahulu meninjau kebutuhan dan lampiran, menyusun penawaran harga, serta menyampaikan penawaran dan ketentuan DP/pelunasan kepada pelanggan melalui WhatsApp. Setelah pelanggan menyetujui penawaran, Admin mengonversi request menjadi order dengan snapshot harga final. Order kemudian berstatus Menunggu Pembayaran dan status pembayaran Belum Dibayar. Admin mengirimkan informasi QRIS melalui WhatsApp secara manual setelah konversi dan kesepakatan pembayaran tersebut.

8. Setelah produksi selesai, Admin mengubah status menjadi Siap Diambil dan memberi tahu pelanggan melalui WhatsApp secara manual. Setelah barang diambil, Admin menandai order Selesai.
## 8.3 Alur transaksi manual dari kanal luar website
1. Admin membuka menu transaksi/penjualan.

2. Admin memilih pelanggan yang sudah ada atau memasukkan data pelanggan baru.

3. Admin menambahkan item dari katalog atau memasukkan custom item secara manual.

4. Admin memasukkan kuantitas, harga satuan, diskon/biaya tambahan jika berlaku, dan catatan.

5. Sistem menghitung total transaksi; admin memeriksa dan menyimpan transaksi.

6. Admin mencatat pembayaran yang sudah diterima dan dapat membuat invoice.
## 8.4 Alur pembelian bahan/pengeluaran
1. Admin membuka modul pembelian/pengeluaran.

2. Admin memasukkan tanggal, kategori, nama barang/bahan, kuantitas, harga, pemasok bila ada, dan keterangan.

3. Sistem menghitung total pengeluaran dan menyimpan catatan.

4. Catatan dapat dilihat dan direkap berdasarkan rentang tanggal atau kategori.

# 9. SHARED BUSINESS RULES DAN SYSTEM-WIDE POLICIES
## 9.1 Identitas dan akses pelanggan
- Pengunjung dapat melihat halaman publik tanpa login.

- Pelanggan tidak wajib membuat akun untuk mengirim direct purchase maupun custom order request.

- Sistem mengumpulkan informasi minimum yang dibutuhkan untuk menghubungi dan memproses pesanan, misalnya nama dan nomor kontak; field tambahan harus disepakati.

- Informasi kontak pelanggan hanya boleh diakses oleh pihak internal yang berwenang.
## 9.2 Model produk dan harga
- Setiap produk dapat menggunakan model harga yang sesuai: harga tetap, harga mulai dari, kalkulator estimasi, atau request quotation.

- Tidak semua produk wajib memiliki kalkulator harga.

- Opsi konfigurasi dapat berbeda antarproduk; contoh: ukuran, ketebalan, bahan, laminasi/finishing, jenis aksesori, dan jumlah.

- Kalkulator menghitung estimasi harga per pcs dan total berdasarkan aturan yang dikonfigurasi admin untuk produk terkait.

- Rumus, tarif, minimum order, diskon kuantitas, dan biaya tambahan harus dapat ditentukan per produk bila diperlukan; nilai aktual menunggu validasi pemilik usaha.

- Hasil kalkulator harus diberi label estimasi dan bukan jaminan harga final apabila masih memerlukan pemeriksaan desain, spesifikasi, atau persetujuan admin.

- Harga final yang disepakati untuk sebuah transaksi harus disimpan sebagai nilai transaksi, tidak berubah otomatis ketika tarif katalog diubah kemudian.

- Produk dan kategori yang telah direferensikan transaksi tidak dihapus permanen melalui dashboard. Penghapusan dilakukan dengan Archive/Soft Delete atau status inactive. Produk arsip tidak tampil pada katalog dan tidak dapat dipilih untuk pesanan baru, tetapi tetap dapat direferensikan pada snapshot dan riwayat transaksi lama.
## 9.3 Jenis pesanan
- Direct purchase digunakan untuk produk yang konfigurasi dan aturan harganya cukup jelas untuk diajukan melalui checkout.

- Custom order request digunakan untuk produk atau kebutuhan yang memerlukan klarifikasi dan penawaran harga.

- Request quotation belum dianggap transaksi final sampai disetujui dan dicatat sebagai pesanan oleh admin.

- Sistem internal harus menerima transaksi yang dimasukkan manual meskipun tidak berasal dari website.

- Item transaksi dapat dipilih dari katalog atau diinput sebagai custom item dengan nama, deskripsi, kuantitas, dan harga.
## 9.4 Status pesanan dan status pembayaran
Status pesanan dan status pembayaran harus disimpan terpisah. Satu status tidak boleh disimpulkan hanya dari status yang lain.

| **Jenis status** | **Nilai awal yang diusulkan** | **Catatan** |
| :---: | :---: | :---: |
| Status Custom Request | Baru Diterima; Ditinjau Admin; Menunggu Informasi (opsional); Penawaran Dikirim; Disetujui Pelanggan; Ditolak; Kedaluwarsa; Dikonversi Menjadi Order. | Digunakan hanya selama proses penawaran custom. Request belum dihitung sebagai penjualan dan belum memiliki status pengerjaan/pembayaran sampai disetujui dan dikonversi. |
| Status Pengerjaan Order | Menunggu Konfirmasi; Menunggu Pembayaran; Diproses; Siap Diambil; Selesai; Dibatalkan. | Direct purchase dimulai dari Menunggu Konfirmasi; setelah harga final dikonfirmasi menjadi Menunggu Pembayaran. Hasil konversi custom dimulai dari Menunggu Pembayaran. Order diproses setelah ketentuan pembayaran yang disepakati terpenuhi dan Admin memperbarui status. Siap Diambil hanya untuk pickup; tidak ada Siap Dikirim. Dibatalkan hanya oleh Owner untuk transaksi final. Status dan histori hanya dikelola internal. |
| Status Pembayaran | Belum Dibayar; Dibayar Sebagian; Lunas. | Dihitung sistem: Pembayaran Bersih = Pembayaran Valid − Refund Selesai; Sisa Tagihan = Total Tagihan − Pembayaran Bersih. Tidak diubah manual. |
 
## 9.5 Pembayaran
- Metode pembayaran yang digunakan pada versi pertama adalah QRIS. Informasi QRIS dan teks instruksi pembayaran dikelola melalui modul FIN.

- Pembayaran dilakukan di luar sistem. Sistem tidak menyediakan payment gateway, integrasi pemrosesan QRIS, atau verifikasi pembayaran otomatis.

- Instruksi pembayaran tidak ditampilkan pada keranjang, checkout, ringkasan sebelum kirim, atau halaman konfirmasi awal. Instruksi baru diberikan setelah Admin memeriksa pesanan, menetapkan total tagihan final, dan menyepakati nominal pembayaran dengan pelanggan.

- Admin mengirim total tagihan dan instruksi QRIS kepada pelanggan melalui WhatsApp secara manual. Admin hanya dapat menggunakan konfigurasi QRIS aktif yang dikelola Owner melalui FIN.

- Penerimaan pembayaran diverifikasi secara manual oleh Admin sebelum dicatat sebagai pembayaran valid. Setiap catatan pembayaran menyimpan tanggal, nominal, metode QRIS, dan referensi/keterangan bila tersedia.

- Satu transaksi dapat memiliki beberapa catatan pembayaran, termasuk DP, pembayaran bertahap, dan pelunasan.

- Tidak ada persentase DP atau nominal minimum DP yang ditetapkan secara global. Nominal DP ditentukan berdasarkan kesepakatan untuk setiap pesanan dan dicatat pada transaksi terkait. Bila tidak ada DP, jumlah pembayaran mengikuti kesepakatan pelunasan dengan pelanggan.
## 9.6 Invoice dan bukti pembayaran
- Invoice dibuat dari data transaksi dan pelanggan yang telah tersimpan. Admin dan Owner dapat menerbitkan invoice awal. Hanya Owner yang dapat membatalkan invoice yang telah diterbitkan dan menerbitkan invoice revisi dengan nomor baru yang merujuk invoice sebelumnya.

- Invoice menampilkan identitas usaha dan pelanggan, nomor dan tanggal invoice, rincian item dan spesifikasi, jumlah, harga satuan, subtotal, diskon/biaya tambahan, total tagihan, Pembayaran Valid, Refund Selesai bila relevan, Pembayaran Bersih, dan Sisa Tagihan pada waktu penerbitan.

- Invoice harus menyimpan snapshot immutable dari identitas usaha, pelanggan, transaksi, item, harga, pembayaran, refund, dan sisa tagihan ketika diterbitkan. Perubahan data master, transaksi, pembayaran, atau refund setelah penerbitan tidak boleh mengubah invoice lama.

- Invoice berbeda dari bukti pembayaran; bukti pembayaran dapat dibuat sebagai dokumen terpisah jika disetujui dalam Sub-PRD-05.

- Format nomor invoice, logo, identitas usaha, masa berlaku, pajak, dan ketentuan pembayaran perlu dikonfirmasi pemilik usaha.
## 9.7 Pembelian bahan dan persediaan
- Modul pembelian/pengeluaran hanya digunakan untuk mencatat pengeluaran usaha, termasuk pembelian bahan dan kebutuhan operasional.

- Catatan pembelian tidak otomatis menambah stok dan tidak mengurangi stok ketika pesanan diproduksi.

- Tidak ada saldo stok real-time, peringatan stok minimum, atau reservasi stok dalam versi awal.
## 9.8 Konten, ulasan, dan portofolio
- Ulasan, galeri, dan dokumentasi kolaborasi diunggah serta dikurasi oleh admin.

- Pelanggan tidak mengirim ulasan secara otomatis dari alur checkout pada versi awal, kecuali diputuskan kemudian.

- Konten yang ditampilkan harus memiliki status publikasi/aktif agar admin dapat menyimpan draft tanpa langsung menayangkannya.

# 10. SHARED DATA CONCEPTS DAN CROSS-DOMAIN CONTRACTS


| **Entitas konseptual** | **Tujuan** | **Domain terkait** |
| :---: | :---: | :---: |
| User | Akun pengguna internal yang menyimpan identitas, kredensial, status akun, dan role. | PRD-04 |
| Role | Mendefinisikan dua role internal, Owner dan Admin, beserta permission masing-masing. Setiap akun internal memiliki satu role. | PRD-04 |
| Customer | Identitas dan kontak pelanggan; dapat dibuat dari guest checkout atau input manual. | PRD-03, PRD-05 |
| Product | Informasi produk, kategori, deskripsi, gambar, dan status publikasi. | PRD-02, PRD-04 |
| Product Variant / Option | Opsi seperti ukuran, ketebalan, bahan, finishing, aksesori. | PRD-02, PRD-04 |
| Pricing Rule | Model harga, tarif, formula, rentang kuantitas, biaya tambahan, dan versi konfigurasi. | PRD-02, PRD-04 |
| Order / Request | Permintaan pelanggan, status request, atau pesanan yang telah dikonfirmasi. | PRD-03, PRD-05 |
| Order Item / Transaction Item | Rincian item katalog atau custom item, kuantitas, harga, dan spesifikasi snapshot. | PRD-03, PRD-05 |
| Payment | Catatan pembayaran yang diterima untuk suatu transaksi. | PRD-05 |
| Payment Method Configuration / Payment Instruction | Menyimpan konfigurasi metode pembayaran aktif, informasi QRIS, teks instruksi pembayaran, status aktif, dan riwayat perubahan konfigurasi. | PRD-05 |
| Refund | Catatan pengembalian dana manual secara parsial atau penuh yang terhubung ke pembayaran sumber yang valid. | PRD-05 |
| Expense / Purchase Record | Catatan pembelian bahan dan biaya operasional. | PRD-05 |
| Invoice | Dokumen tagihan yang diterbitkan dari transaksi. | PRD-05 |
| Invoice Snapshot | Salinan immutable identitas usaha/pelanggan, item, nilai transaksi, pembayaran, refund, dan sisa tagihan pada waktu invoice diterbitkan. | PRD-05 |
| Order Status History | Histori perubahan status pengerjaan order yang menyimpan status sebelumnya/baru, pengguna, waktu, dan catatan. | PRD-05 |
| Testimonial / Review | Ulasan yang dikurasi dan ditampilkan oleh admin. | PRD-01, PRD-04 |
| Portfolio / Collaboration | Dokumentasi hasil proyek atau kerja sama. | PRD-01, PRD-04 |
| Gallery Media | File gambar hasil produksi yang dikelola admin. | PRD-01, PRD-04 |
| Website Content / Settings | Banner, profil, kontak, dan konten halaman publik. | PRD-01, PRD-04 |
 
## 10.1 Kontrak data lintas domain
- PRD-04 mengelola data master produk, kategori, opsi, dan konfigurasi tarif; PRD-02 membaca dan menampilkannya pada katalog/kalkulator.

- PRD-03 mengirim data customer, konfigurasi item, estimasi (bila ada), dan permintaan/order ke sistem internal; PRD-05 mengelola tindak lanjut transaksi.

- PRD-05 dapat membuat customer atau transaksi manual tanpa melalui PRD-03.

- Setiap order item perlu menyimpan snapshot nama produk, spesifikasi, kuantitas, dan harga yang disepakati agar transaksi lama tetap konsisten.

- PRD-01 menampilkan konten publik; PRD-04 menyediakan mekanisme pengelolaan konten tersebut.

- PRD-04 mengelola akun internal dan role Owner/Admin. Owner memiliki seluruh hak Admin serta hak khusus atas akun, konfigurasi QRIS, void, refund, pembatalan transaksi final, dan revisi invoice.

- PRD-05 mengelola status request, status pengerjaan order, status pembayaran, Payment, Refund, Order Status History, Invoice, dan Invoice Snapshot. Refund merupakan entitas terpisah dari Payment dan hanya memengaruhi Pembayaran Bersih setelah berstatus Selesai.

- Invoice Snapshot menyimpan identitas usaha dan pelanggan serta nilai transaksi pada saat penerbitan. Identitas usaha diambil dari profil PRD-04 dan disalin ke snapshot; perubahan profil atau transaksi setelah penerbitan tidak mengubah invoice lama.

- PRD-05 memiliki dan mengelola konfigurasi QRIS serta teks instruksi pembayaran. PRD-04 tidak menyimpan rekening atau instruksi pembayaran; PRD-04 tetap mengelola profil dan kontak usaha, termasuk informasi lokasi pengambilan.

- PRD-03 tidak membaca atau menampilkan konfigurasi QRIS saat checkout. Setelah order diterima, PRD-05 meninjau pesanan, menetapkan harga final, dan mengelola proses pemberian instruksi pembayaran kepada pelanggan melalui Admin secara manual.

- Admin dapat melihat konfigurasi QRIS aktif untuk mengirimkan informasi pembayaran melalui WhatsApp, sedangkan perubahan konfigurasi hanya dapat dilakukan Owner.
## 10.2 Prinsip relasi data awal
Satu pelanggan dapat memiliki banyak order/transaksi. Satu custom request dapat dikonversi menjadi paling banyak satu order. Satu order memiliki banyak order item, payment, refund, dan order status history. Setiap refund terhubung ke satu payment sumber. Satu order dapat memiliki invoice awal dan invoice revisi yang saling merujuk; setiap invoice memiliki satu Invoice Snapshot yang immutable. Produk yang diarsipkan tetap dapat direferensikan dari snapshot item historis. Catatan pengeluaran berdiri sendiri dan tidak otomatis menjadi data persediaan.
## 10.3 ERD Global Sistem


| **Entitas / kelompok** | **Relasi utama** | **Keterangan** |
| :---: | :---: | :---: |
| User / Role | User.role_id → Role.id | Dua role internal: Owner dan Admin. |
| Category / Product | Product.category_id → Category.id | Produk arsip tetap direferensikan pada histori transaksi. |
| Product Options | Option Group.product_id → Product.id; Option Value.group_id → Option Group.id | Menyimpan pilihan parameter/variasi produk. |
| Pricing Configuration | Pricing Configuration.product_id → Product.id | Menyimpan versi aturan harga kalkulator per produk. |
| Customer | Customer 1 → N Orders dan Requests | Dibuat dari guest checkout atau transaksi manual. |
| Cart / Cart Item | Cart 1 → N Cart Items | Keranjang guest berbasis sesi; bukan transaksi final. |
| Order Request | Customer 1 → N Requests | Request custom belum menjadi penjualan sampai disetujui dan dikonversi. |
| Order | Customer 1 → N Orders; Order.source_request_id → Request.id (nullable, unique) | Order direct, hasil konversi custom, atau transaksi manual. |
| Order Item | Order 1 → N Order Items; Order Item.product_id → Product.id (nullable) | Menyimpan snapshot nama, spesifikasi, kuantitas, dan harga. |
| Order Attachment | Order / Order Item 1 → N Attachments | File desain/referensi pada lokasi non-publik. |
| Payment | Order 1 → N Payments | Metode versi pertama QRIS; Valid setelah verifikasi manual. |
| Refund | Payment 1 → N Refunds; Refund.order_id → Order.id | Refund manual parsial/penuh; Owner-only. |
| Payment Method Configuration | Dikelola FIN; updated_by_user_id → User.id | Konfigurasi QRIS aktif; Owner mengubah, Admin hanya membaca. |
| Order Status History | Order 1 → N Histories; changed_by_user_id → User.id | Append-only: status lama/baru, pengguna, waktu, dan catatan. |
| Invoice | Order 1 → N Invoices; revises_invoice_id → Invoice.id (nullable) | Invoice revisi bernomor baru dan merujuk invoice sebelumnya. |
| Invoice Snapshot | Invoice 1 → 1 Invoice Snapshot | Snapshot immutable transaksi saat invoice diterbitkan. |
| Expense / Expense Category | Expense.category_id → Expense Category.id | Pembelian bahan dan biaya operasional; tidak mengubah stok. |
| CMS / Media | Media digunakan Content, Product, Gallery, Testimonial, dan Portfolio | Konten publik dikelola melalui ADM. |
| Audit Log | Audit Log.actor_user_id → User.id | Mencatat tindakan penting, termasuk tindakan khusus Owner. |
# 11. SHARED UX/UI PRINCIPLES
- Public website harus menampilkan identitas YOLOO Merch secara konsisten dan memudahkan pengunjung menemukan katalog serta jalur pemesanan.

- Halaman detail produk harus menjelaskan spesifikasi dan batasan konfigurasi secara mudah dipahami.

- Kalkulator harga harus memperlihatkan parameter yang dipilih, harga per unit, total estimasi, dan catatan bahwa hasil dapat dikonfirmasi admin.

- Guest checkout harus meminta data secukupnya dan menyajikan ringkasan sebelum pesanan dikirim.

- Dashboard admin harus memprioritaskan keterbacaan data, form yang jelas, pencarian/filter pada daftar panjang, serta feedback sukses/gagal.

- Setiap form harus memiliki label, petunjuk format, validasi, dan pesan kesalahan yang dapat dimengerti.

- Setiap daftar data perlu memiliki kondisi loading, empty state, error state, dan konfirmasi untuk tindakan destruktif.

- Website harus dirancang responsif untuk desktop dan perangkat mobile.

# 12. SHARED TECHNICAL DAN NON-FUNCTIONAL REQUIREMENTS
## 12.1 Technical decisions


| **Keputusan teknis** | **Pilihan/keputusan tim** | **Penanggung jawab** |
| :---: | :---: | :---: |
| Backend framework | Laravel (PHP) sebagai aplikasi monolitik untuk public website dan admin dashboard. | Koordinator + seluruh tim |
| Frontend approach | Laravel Blade untuk server-rendered views dan Tailwind CSS untuk styling. Tidak menggunakan frontend framework terpisah pada versi awal. | Koordinator + anggota 1–4 |
| Database | MySQL sebagai database utama; akses data melalui Laravel Eloquent ORM. | Koordinator + anggota 4–5 |
| File storage | Laravel Filesystem. File upload disimpan pada storage aplikasi; file pelanggan dan dokumen internal tidak ditempatkan pada folder publik. | Anggota 3–4 |
| PDF generation | Paket barryvdh/laravel-dompdf untuk menghasilkan invoice PDF dari snapshot. | Anggota 5 |
| Authentication | Autentikasi session Laravel untuk pengguna internal dengan role-based access control (RBAC) Owner dan Admin. | Anggota 4 |
| Deployment | Hosting yang mendukung PHP/Laravel dan MySQL. Konfigurasi domain dan server ditetapkan saat deployment. | Koordinator |
| Version control | Git dengan repository bersama di GitHub dan penggunaan branch untuk pekerjaan anggota. | Koordinator |
 
## 12.2 Non-functional requirements lintas sistem


| **ID** | **Kategori** | **Kebutuhan awal** | **Target/ukuran yang disepakati** |
| --- | --- | --- | --- |
| NFR-001 | Usability | Navigasi dan label antarmuka mudah dipahami pengguna sasaran. | [TBD / hasil uji pengguna] |
| NFR-002 | Responsiveness | Halaman publik dan dashboard beradaptasi dengan desktop dan mobile. | [TBD ukuran viewport uji] |
| NFR-003 | Performance | Halaman dan operasi data utama merespons dalam waktu yang wajar. | [TBD target detik dan kondisi uji] |
| NFR-004 | Security | Password tidak disimpan sebagai teks biasa; halaman internal dilindungi autentikasi dan otorisasi. | [TBD standar implementasi] |
| NFR-005 | Data integrity | Perhitungan dan transaksi tersimpan konsisten; operasi penting tidak menghasilkan data parsial. | [TBD skenario uji] |
| NFR-006 | Maintainability | Struktur kode, validasi, dan penamaan konsisten serta terdokumentasi. | [TBD konvensi tim] |
| NFR-007 | Compatibility | Website berjalan pada browser modern yang disepakati untuk pengujian. | [TBD daftar browser] |
| NFR-008 | File handling | Upload file dibatasi berdasarkan jenis, ukuran, dan validasi keamanan. | [TBD format dan batas ukuran] |
| NFR-009 | Backup | Tersedia prosedur backup data untuk lingkungan demonstrasi/operasional. | [TBD frekuensi dan metode] |
| NFR-010 | Document and Report Integrity | Invoice PDF dihasilkan dari snapshot dan tetap konsisten dengan data pada saat penerbitan. Laporan penjualan, Pembayaran Valid, Refund Selesai, Pembayaran Bersih, dan pengeluaran harus dapat ditelusuri ke data sumber serta sesuai hasil perhitungan manual. | Uji invoice awal/revisi, pembayaran/refund, transaksi dibatalkan, dan laporan per periode; hasil harus cocok dengan data sumber. |
 
# 13. SECURITY, PRIVACY, DAN ACCESS PRINCIPLES
- Dashboard, data transaksi, data pembayaran, dan pengeluaran hanya dapat diakses oleh pengguna internal yang telah terautentikasi dan memiliki izin.

- Pelanggan guest hanya mengirim data melalui form publik; tidak mendapatkan akses untuk melihat data pelanggan atau transaksi lain.

- Password harus diproses menggunakan mekanisme hashing yang aman dari framework yang dipilih.

- Input pengguna harus divalidasi di sisi server; jangan mengandalkan validasi tampilan saja.

- File upload harus dibatasi tipe dan ukurannya serta tidak boleh dieksekusi sebagai kode.

- Data kontak pelanggan dan bukti pembayaran tidak boleh ditampilkan pada halaman publik.

- Tindakan penting seperti penghapusan data transaksi atau perubahan status perlu dikendalikan; pertimbangkan soft delete atau arsip agar riwayat tidak hilang.

- Kebijakan retensi data, backup, dan akses multi-admin perlu dikonfirmasi sebelum implementasi final.

- Sistem menerapkan role-based access control (RBAC) dengan dua role internal, yaitu Owner dan Admin. Setiap halaman dan tindakan pada dashboard harus memeriksa autentikasi serta permission sebelum memberikan akses. Owner memiliki akses penuh; Admin menjalankan fungsi operasional sesuai batas permission.

- Tindakan khusus berupa pengelolaan akun Admin, perubahan konfigurasi QRIS, void pembayaran, pencatatan refund, pembatalan transaksi final, serta pembatalan/penerbitan revisi invoice hanya dapat dilakukan oleh Owner. Tindakan khusus harus tercatat dalam audit log dengan pengguna, waktu, objek, dan alasan bila relevan.

- Produk dan kategori yang telah direferensikan transaksi tidak dihapus permanen melalui dashboard; gunakan Archive/Soft Delete agar histori dan snapshot tetap utuh. Pelanggan guest tidak memiliki halaman publik atau endpoint untuk mencari status pesanan.

- Konfigurasi QRIS dan teks instruksi pembayaran dikelola dalam modul FIN. Hanya Owner yang dapat membuat atau mengubah konfigurasi tersebut; Admin hanya memiliki akses baca untuk kebutuhan operasional. Informasi QRIS tidak ditampilkan pada halaman publik, checkout, atau halaman konfirmasi awal. Setelah total final dan nominal pembayaran disepakati, Admin mengirimkan informasi QRIS secara manual melalui WhatsApp.

# 14. CROSS-DOMAIN ACCEPTANCE DAN QUALITY CHECKLIST


| **ID** | **Kriteria integrasi/hasil akhir** | **Status** |
| :---: | :---: | :---: |
| INT-01 | Konten yang dikelola admin tampil benar pada public website. | [Belum diuji] |
| INT-02 | Produk dan variasi dari katalog dapat digunakan di halaman detail dan kalkulator sesuai konfigurasi. | [Belum diuji] |
| INT-03 | Hasil konfigurasi/estimasi dapat diteruskan ke cart atau custom request dengan detail yang tidak hilang. | [Belum diuji] |
| INT-04 | Order dari website masuk ke sistem internal dengan identitas dan item yang benar. | [Belum diuji] |
| INT-05 | Admin dapat membuat transaksi manual dari kanal selain website. | [Belum diuji] |
| INT-06 | Harga item dan spesifikasi yang sudah disepakati tersimpan sebagai snapshot transaksi. | [Belum diuji] |
| INT-07 | Pembayaran Valid, Refund Selesai, Pembayaran Bersih, status pembayaran, dan Sisa Tagihan dihitung secara konsisten. | [Belum diuji] |
| INT-08 | Invoice menampilkan snapshot transaksi, pelanggan, item, Pembayaran Valid, Refund Selesai, Pembayaran Bersih, dan Sisa Tagihan pada waktu penerbitan; invoice lama tidak berubah setelah revisi. | [Belum diuji] |
| INT-09 | Pengeluaran tercatat tanpa memengaruhi stok real-time. | [Belum diuji] |
| INT-10 | Status pengerjaan dan pembayaran dapat berbeda dan dikelola terpisah. | [Belum diuji] |
| INT-11 | Tidak ada fitur tracking pesanan real-time atau pengelolaan stok real-time pada versi awal. | [Sesuai scope] |
| INT-12 | Produk/kategori yang diarsipkan tidak tersedia untuk pesanan baru, tetapi snapshot dan riwayat transaksi lama tetap utuh. | [Belum diuji] |
| INT-13 | Void dan Refund merupakan tindakan berbeda: Void membatalkan catatan pembayaran yang salah; Refund mencatat dana yang benar-benar dikembalikan. | [Belum diuji] |
| INT-14 | Status Siap Diambil dan histori perubahan hanya tersedia pada dashboard internal; tidak ada halaman/endpoint cek status pelanggan dan Admin memberi tahu melalui WhatsApp manual. | [Belum diuji] |
 

# BAGIAN B — SUB-PRODUCT REQUIREMENTS
# 15. CP — COMPANY PROFILE & PROMOTIONAL WEBSITE
Penanggung jawab: Anggota 1. Nama anggota: Bertrand Graciano Kurniawan. Status: Selesai.
## A. Tujuan domain dan ringkasan solusi
Domain CP menyediakan pengalaman publik yang memperkenalkan YOLOO Merch sebagai usaha jasa pembuatan merchandise custom dan layanan cetak berdasarkan produk yang tersedia dalam katalog. Fokus komunikasi website adalah jasa produksi merchandise, bukan jasa desain grafis sebagai layanan utama.

Website ditujukan untuk masyarakat umum yang membutuhkan merchandise atau layanan cetak, termasuk individu, mahasiswa, organisasi, komunitas, dan pihak lain. Pemesanan reseller atau jumlah besar tetap dapat dilayani, tetapi bukan target komunikasi utama.

Tujuan utamanya adalah membantu pengunjung memahami jenis layanan dan produk yang tersedia, mengenal YOLOO Merch, melihat contoh hasil produksi, membangun kepercayaan melalui ulasan dan kolaborasi, lalu mengarahkan pengunjung ke katalog, formulir pemesanan, atau WhatsApp untuk konsultasi.

Domain ini mencakup struktur dan tampilan halaman publik: Beranda, Galeri, Tentang Kami, Ulasan & Kolaborasi, Kontak, serta komponen navigasi global. Domain ini tidak mengelola CRUD konten, master produk, proses checkout, atau transaksi. Pengelolaan konten dilakukan oleh ADM; katalog oleh CAT; pemesanan oleh ORD; dan pencatatan transaksi/keuangan oleh FIN.
## B. Tujuan, scope, dan batasan domain


| **Aspek** | **Ketentuan domain CP** |
| :---: | :---: |
| Tujuan domain | Menyajikan informasi usaha, promosi, bukti hasil pekerjaan, dan jalur kontak/pemesanan secara jelas, konsisten, dan responsif. |
| In Scope | Homepage; About Us; Ulasan & Kolaborasi; Galeri dengan kategori/filter dan lightbox; Contact Us; navigasi, header, footer, CTA; tautan menuju CAT dan ORD; tautan WhatsApp, Instagram, dan email; SEO dasar untuk halaman publik. |
| Out of Scope | CRUD konten; pengelolaan produk/detail/variasi/harga; kalkulator harga; keranjang dan checkout; form order custom; status pesanan; pembayaran; invoice; pembelian bahan; pengeluaran; laporan keuangan; review submission langsung oleh pelanggan; tracking real-time; live stock. |
| Asumsi | Konten dan materi visual disediakan atau disetujui pemilik YOLOO Merch. Admin mengelola konten melalui ADM. Website tersedia untuk publik tanpa login. Kategori galeri dikelola secara internal dan dapat diselaraskan dengan kategori produk jika disepakati. |
| Stakeholder / aktor | Visitor/pengunjung; calon pelanggan; pelanggan yang sedang mencari informasi; Admin dan Owner sebagai penyedia dan kurator konten; anggota domain CAT, ORD, dan ADM untuk integrasi. |
 **Batasan khusus**

- Ulasan, dokumentasi kolaborasi, dan foto galeri merupakan konten yang dikurasi admin. Tidak tersedia unggahan bebas dari pelanggan pada versi ini.

- Halaman Ulasan & Kolaborasi menggabungkan dua jenis konten dalam satu halaman, tetapi keduanya tetap memiliki bagian/section yang berbeda.

- Halaman Galeri memiliki kategori/filter produk. Ketika gambar dipilih, gambar diperbesar melalui lightbox; ini bukan halaman detail proyek.

- Homepage hanya menampilkan ringkasan produk unggulan. Pengelolaan kartu produk, spesifikasi dan harga dilakukan di CAT.

- CP tidak menghitung harga, memproses checkout, menyimpan pembayaran, atau mengelola status pesanan. CP hanya menyediakan CTA/link ke domain yang menangani proses tersebut.

## C. Daftar subfitur yang wajib dianalisis


| **Subfitur** | **Spesifikasi / perilaku yang ditetapkan** | **Prioritas** |
| --- | --- | --- |
| Homepage / landing page | Menampilkan hero, pengenalan singkat, produk/kategori unggulan, layanan, keunggulan, cuplikan kolaborasi, galeri, ulasan, CTA pemesanan, dan footer. Urutan section mengikuti kebutuhan komunikasi yang telah disepakati. | Must |
| About Us / profil usaha | Menampilkan profil, visi, misi, nilai usaha, keunggulan, serta panduan pemesanan melalui website dan WhatsApp. Informasi faktual yang belum tersedia menunggu bahan dari pemilik usaha. | Must |
| Testimonials / ulasan pelanggan | Menampilkan ulasan yang dikurasi admin secara sederhana: nama pelanggan/organisasi atau nama tampilan, produk/pesanan, dan isi ulasan. Rating dan foto bersifat opsional apabila tersedia. | Should |
| Portfolio & collaboration | Digabungkan dengan halaman ulasan. Menampilkan identitas/logo pihak yang berkolaborasi, nama organisasi/event, jenis merchandise, dan gambar contoh hasil. Tidak membutuhkan studi kasus panjang atau dokumentasi event yang detail. | Should |
| Gallery hasil produk | Menampilkan foto hasil produksi dalam grid. Pengunjung dapat menyaring berdasarkan kategori produk dan memperbesar foto melalui lightbox. Kategori awal yang diusulkan: Acrylic/Keychain, Sticker, Pin, Printing, dan Merchandise Lainnya. | Must |
| Contact Us dan kanal komunikasi | Menampilkan WhatsApp, Instagram, email, serta lokasi/jam operasional jika informasi tersebut diberikan dan disetujui. Tautan harus dapat membuka kanal terkait. | Must |
| Navigasi, header, footer, CTA | Navigasi utama berurutan: Beranda, Katalog, Galeri, Tentang Kami, Ulasan & Kolaborasi, Kontak. Header/footer konsisten di semua halaman. CTA menuju katalog, pemesanan, atau WhatsApp. | Must |
| SEO dasar dan responsivitas | Menyediakan judul halaman, meta description dasar, struktur heading, alt text gambar, layout responsif desktop dan smartphone, serta optimasi ukuran gambar. | Should |
 
## D. User roles dan permission dalam domain


| **Aktor / role** | **Melihat** | **Membuat** | **Mengubah** | **Menghapus / tindakan khusus** |
| --- | --- | --- | --- | --- |
| Visitor / pengunjung | Ya | Tidak | Tidak | Tidak memiliki akses pengelolaan. Dapat membuka tautan, menggunakan filter galeri, dan memperbesar gambar. |
| Calon pelanggan / Customer Guest | Ya | Tidak di CP | Tidak | Dapat melanjutkan ke katalog, pemesanan, atau kanal kontak. Pengiriman order berada di ORD. Tidak memiliki akses ke dashboard. |
| Admin | Ya sebagai halaman publik | Bukan melalui CP | Bukan melalui CP | Mengelola konten publik melalui dashboard ADM sesuai permission Admin. |
| Owner | Ya sebagai halaman publik | Bukan melalui CP | Bukan melalui CP | Memiliki hak penuh atas pengelolaan konten melalui dashboard ADM. |
## E. Page / screen specifications


| **Page ID / nama** | **Route / akses** | **Tujuan dan isi utama** | **Aksi / interaksi utama** |
| --- | --- | --- | --- |
| PAGE-CP-001  Beranda | /  Public | Halaman utama untuk mengenalkan YOLOO Merch dan mengarahkan pengunjung ke katalog atau pemesanan. Section berurutan: (1) Navbar; (2) Hero dengan headline, deskripsi singkat, visual/mascot, CTA; (3) pengenalan singkat; (4) kategori/produk unggulan; (5) layanan merchandise custom; (6) keunggulan; (7) cuplikan kolaborasi; (8) galeri pilihan; (9) ulasan pilihan; (10) CTA; (11) footer. | Lihat Katalog → CAT; Pesan Sekarang → ORD; Konsultasi Custom → WhatsApp; Tentang Kami → PAGE-CP-002; Lihat Galeri → PAGE-CP-004. |
| PAGE-CP-002  Tentang Kami | /tentang-kami  Public | Memuat pengenalan dan profil YOLOO Merch, visi, misi, nilai, keunggulan, serta panduan pemesanan melalui website dan WhatsApp. Jenis layanan rinci dan variasi produk tetap diarahkan ke CAT. | Navigasi ke Katalog; tombol Pesan melalui Website → ORD; tombol Konsultasi via WhatsApp → kanal WhatsApp; navigasi ke Kontak. |
| PAGE-CP-003  Ulasan & Kolaborasi | /ulasan-kolaborasi  Public | Satu halaman yang memiliki dua section: Ulasan Pelanggan dan Collaboration. Ulasan berisi nama tampilan, produk/pesanan, teks ulasan, serta rating/foto opsional. Collaboration berisi logo pihak, nama organisasi/event, jenis merchandise, dan satu/beberapa foto hasil. Detail panjang tidak diwajibkan. | Navigasi antarbagian melalui anchor/tab bila diperlukan; gambar kolaborasi dapat diperbesar; CTA menuju katalog atau kontak. |
| PAGE-CP-004  Galeri | /galeri  Public | Menampilkan kumpulan foto hasil produksi dalam grid. Memiliki filter kategori produk. Kategori awal usulan: Acrylic/Keychain, Sticker, Pin, Printing, dan Merchandise Lainnya; final mengikuti data/kategori yang disepakati dengan ADM. | Pilih kategori/filter; reset filter; klik gambar untuk membuka lightbox; tutup lightbox dengan tombol close atau Escape; navigasi ke katalog/pemesanan bila relevan. |
| PAGE-CP-005  Kontak | /kontak  Public | Menampilkan informasi kontak secara jelas: WhatsApp, Instagram, email, serta alamat dan jam operasional apabila disediakan. Tampilkan tombol/tautan aktif dan instruksi singkat untuk konsultasi atau pemesanan. | Klik WhatsApp membuka percakapan/tautan WhatsApp; klik Instagram membuka profil; klik email membuka aplikasi email; alamat dapat membuka peta jika lokasi resmi disediakan. |
| GLOBAL-CP-001  Header dan navigasi global | Komponen global  Public | Logo YOLOO Merch dan menu: Beranda, Katalog, Galeri, Tentang Kami, Ulasan & Kolaborasi, Kontak. Pada layar sempit, navigasi berubah menjadi menu mobile yang tetap mudah digunakan. | Klik logo/beranda; navigasi ke halaman; menu mobile dibuka/ditutup; link Katalog diarahkan ke CAT. |
| GLOBAL-CP-002  Footer global | Komponen global  Public | Menampilkan identitas singkat, navigasi cepat, kanal kontak/media sosial, dan informasi hak cipta. Informasi kontak harus konsisten dengan halaman Kontak. | Klik navigasi cepat; klik kanal eksternal; seluruh tautan harus menuju tujuan yang benar. |
 
## F. User flow dan workflow


| **Flow ID / nama** | **Trigger & precondition** | **Main flow** | **Alternative / exception** | **Output** |
| --- | --- | --- | --- | --- |
| FLOW-CP-001 — Mengenal YOLOO Merch | Pengunjung membuka URL website. | 1. Sistem menampilkan Beranda. 2. Pengunjung membaca hero dan pengenalan singkat. 3. Pengunjung melihat layanan dan keunggulan. 4. Pengunjung memilih menu Tentang Kami, Katalog, atau Kontak. | Jika konten pilihan tidak tersedia, navigasi ke halaman terkait tetap menampilkan pesan/kondisi kosong yang informatif atau section disembunyikan sesuai aturan publikasi. | Pengunjung memahami gambaran usaha dan menemukan tujuan selanjutnya. |
| FLOW-CP-002 — Beranda ke katalog | Pengunjung berada di Beranda dan melihat kategori/produk unggulan. | 1. Pengunjung menekan Lihat Katalog atau kartu produk. 2. Sistem mengarahkan ke halaman katalog/detail yang dimiliki CAT. 3. Pengunjung melanjutkan pencarian produk di CAT. | Jika route CAT tidak tersedia saat integrasi, tampilkan fallback tautan katalog/halaman informasi; jangan membuat halaman katalog duplikat di CP. | Pengunjung masuk ke Product Catalog (CAT). |
| FLOW-CP-003 — Menjelajahi galeri | Pengunjung membuka halaman Galeri. | 1. Sistem menampilkan grid foto yang sudah dipublikasikan. 2. Pengunjung memilih kategori.3. Sistem menampilkan foto yang sesuai.4. Pengunjung memilih foto.5. Sistem menampilkan lightbox.6. Pengunjung menutup lightbox. | Jika kategori tidak memiliki foto, tampilkan empty state. Jika gambar gagal dimuat, tampilkan placeholder dan tetap sediakan cara menutup lightbox. | Pengunjung dapat menemukan dan melihat contoh hasil produksi. |
| FLOW-CP-004 — Membaca ulasan dan kolaborasi | Pengunjung membuka Ulasan & Kolaborasi. | 1. Sistem menampilkan section Ulasan.2. Pengunjung membaca pengalaman pelanggan. 3. Pengunjung berpindah ke section Collaboration. 4. Pengunjung melihat logo dan contoh produk kolaborasi.5. Pengunjung dapat memperbesar gambar. | Jika salah satu jenis konten belum tersedia, section lainnya tetap dapat ditampilkan; section kosong dapat menampilkan pesan singkat atau disembunyikan sesuai keputusan konten. | Pengunjung memperoleh bukti sosial dan contoh kolaborasi. |
| FLOW-CP-005 — Membaca About Us dan panduan pemesanan | Pengunjung membuka Tentang Kami. | 1. Sistem menampilkan profil, visi, misi, nilai, dan keunggulan. 2. Pengunjung membaca panduan pemesanan. 3. Pengunjung memilih jalur pemesanan website atau WhatsApp. | Jika informasi visi/misi belum disetujui, konten tidak ditampilkan sebagai fakta final. Tombol pemesanan tetap dapat diarahkan ke ORD atau WhatsApp. | Pengunjung memahami usaha dan mengetahui cara memesan. |
| FLOW-CP-006 — Menghubungi YOLOO Merch | Pengunjung membuka Kontak atau memilih CTA konsultasi. | 1. Sistem menampilkan kanal kontak.2. Pengunjung memilih WhatsApp, Instagram, atau email. 3. Browser/perangkat membuka kanal terkait. | Jika aplikasi eksternal tidak tersedia, browser tetap membuka versi web atau menampilkan alamat/handle agar dapat disalin. | Pengunjung berhasil diarahkan ke kanal komunikasi yang dipilih. |
## G. Functional requirements register


| **Requirement ID** | **Nama fungsi** | **Deskripsi ringkas** | **Aktor** | **Prioritas** | **Status** |
| :---: | :---: | :---: | :---: | :---: | :---: |
| CP-FR-001 | Menampilkan Beranda | Sistem menampilkan seluruh section utama Beranda sesuai urutan konten yang disetujui. | Visitor | Must | Draft |
| CP-FR-002 | Navigasi global | Sistem menyediakan navigasi konsisten ke Beranda, Katalog, Galeri, Tentang Kami, Ulasan & Kolaborasi, dan Kontak. | Visitor | Must | Draft |
| CP-FR-003 | Navigasi responsif | Pada layar kecil, sistem menyediakan menu mobile yang dapat dibuka dan ditutup. | Visitor | Must | Draft |
| CP-FR-004 | CTA dan tautan tujuan | Sistem mengarahkan CTA ke CAT, ORD, WhatsApp, halaman internal, atau kontak sesuai tujuan. | Visitor | Must | Draft |
| CP-FR-005 | Ringkasan produk unggulan | Beranda menampilkan kartu/ringkasan produk yang bersumber dari CAT dan tautan detail/katalog. | Visitor | Should | Draft |
| CP-FR-006 | Menampilkan Tentang Kami | Sistem menampilkan profil, visi, misi, nilai, keunggulan, dan panduan pemesanan yang telah dipublikasikan. | Visitor | Must | Draft |
| CP-FR-007 | Panduan jalur pemesanan | Sistem menampilkan pilihan cara memesan melalui website dan WhatsApp dengan tautan yang benar. | Visitor | Must | Draft |
| CP-FR-008 | Menampilkan ulasan pelanggan | Sistem menampilkan ulasan yang telah dipublikasikan beserta informasi produk/pesanan dan isi ulasan. | Visitor | Should | Draft |
| CP-FR-009 | Menampilkan kolaborasi | Sistem menampilkan logo/nama pihak, jenis merchandise, dan foto contoh hasil kolaborasi. | Visitor | Should | Draft |
| CP-FR-010 | Menampilkan galeri | Sistem menampilkan foto hasil produksi yang telah dipublikasikan dalam grid. | Visitor | Must | Draft |
| CP-FR-011 | Filter galeri | Pengunjung dapat menyaring foto berdasarkan kategori galeri yang tersedia. | Visitor | Must | Draft |
| CP-FR-012 | Lightbox gambar | Pengunjung dapat memperbesar foto dan menutup tampilan lightbox. | Visitor | Must | Draft |
| CP-FR-013 | Menampilkan kontak | Sistem menampilkan WhatsApp, Instagram, email, serta lokasi/jam operasional jika tersedia. | Visitor | Must | Draft |
| CP-FR-014 | Tautan kanal eksternal | Sistem membuka kanal WhatsApp, Instagram, email, dan peta (bila tersedia) pada tujuan yang benar. | Visitor | Must | Draft |
| CP-FR-015 | Footer global | Sistem menampilkan identitas singkat, tautan cepat, kanal sosial/kontak, dan hak cipta. | Visitor | Must | Draft |
| CP-FR-016 | SEO dasar dan metadata | Halaman publik menyediakan title, description, heading terstruktur, dan alt text relevan untuk gambar. | Visitor / Search engine | Should | Draft |
| CP-FR-017 | Kondisi konten kosong | Sistem menangani section galeri, ulasan, atau kolaborasi yang belum memiliki konten publik. | Visitor | Must | Draft |
 
## H. Kartu spesifikasi rinci functional requirement
### CP-FR-001 — Menampilkan Beranda


| **Elemen** | **Spesifikasi** |
| :---: | :---: |
| Tujuan dan deskripsi | Memberikan gambaran awal tentang YOLOO Merch dan menyediakan jalur yang jelas bagi pengunjung untuk melihat katalog, mengenal usaha, melihat contoh hasil, atau menghubungi admin. |
| Aktor dan hak akses | Visitor; tidak membutuhkan login. |
| Precondition dan trigger | Pengunjung membuka URL utama website atau memilih menu Beranda. |
| Input dan field | Tidak ada input wajib dari pengunjung. Konten diambil dari data publik yang telah dipublikasikan oleh ADM dan ringkasan produk dari CAT. |
| Alur utama | Sistem memuat navbar dan hero; menampilkan pengenalan singkat; produk/kategori unggulan; layanan dan keunggulan; cuplikan kolaborasi, galeri, dan ulasan; CTA; footer. |
| Alur alternatif | Jika salah satu sumber konten belum memiliki data publik, sistem menyembunyikan section tersebut atau menampilkan empty state yang telah disetujui. Section lain tetap berjalan. |
| Validasi dan business rules | Hanya konten berstatus published/active yang ditampilkan. Kartu produk mengambil data dari CAT; CP tidak membuat salinan master produk. |
| Output dan feedback | Halaman Beranda tampil dengan tautan navigasi dan CTA yang dapat digunakan. |
| Error dan edge cases | Gambar hero atau kartu gagal dimuat; data produk tidak tersedia; ukuran layar sempit; koneksi lambat. |
| Dependencies | ADM untuk konten; CAT untuk produk unggulan; ORD untuk jalur pemesanan; aset brand dari pemilik usaha. |
| Priority | Must. |
| Acceptance criteria | Given pengunjung membuka halaman utama, When halaman selesai dimuat, Then section yang tersedia ditampilkan dalam urutan yang ditetapkan, responsif, dan CTA menuju tujuan yang benar. |
### CP-FR-002 / CP-FR-003 — Navigasi global dan navigasi mobile


| **Elemen** | **Spesifikasi** |
| :---: | :---: |
| Tujuan dan deskripsi | Menyediakan akses konsisten ke seluruh tujuan utama website melalui header global. |
| Aktor dan hak akses | Visitor; tanpa login. |
| Precondition dan trigger | Pengunjung membuka halaman publik mana pun. |
| Input dan field | Pilihan menu: Beranda, Katalog, Galeri, Tentang Kami, Ulasan & Kolaborasi, Kontak. Katalog mengarah ke route yang ditentukan CAT. |
| Alur utama | Pada desktop, menu tampil di header. Pada smartphone, menu disajikan dalam tombol menu. Pengunjung memilih menu dan sistem mengarahkan ke route yang sesuai. |
| Alur alternatif | Jika menu mobile terbuka lalu pengunjung memilih item, menu ditutup setelah navigasi. Jika route lintas domain belum terpasang, tampilkan fallback atau tandai belum tersedia pada lingkungan pengembangan. |
| Validasi dan business rules | Urutan menu mengikuti kesepakatan. Katalog bukan halaman CP dan harus menggunakan route CAT. Link aktif harus konsisten pada header/footer. |
| Output dan feedback | Pengunjung dapat berpindah halaman dengan jelas; menu mobile memiliki status terbuka/tertutup. |
| Error dan edge cases | Menu menutupi konten, link salah, keyboard/focus tidak dapat mencapai menu, atau ukuran layar sangat sempit. |
| Dependencies | CAT untuk route katalog; route CP untuk halaman publik; standar desain responsif. |
| Priority | Must. |
| Acceptance criteria | Given pengunjung berada di halaman publik desktop atau mobile, When memilih item navigasi, Then halaman/tujuan yang sesuai terbuka dan menu dapat digunakan tanpa overflow horizontal. |
### CP-FR-006 / CP-FR-007 — Tentang Kami dan panduan pemesanan


| **Elemen** | **Spesifikasi** |
| :---: | :---: |
| Tujuan dan deskripsi | Memberikan informasi identitas YOLOO Merch, nilai usaha, keunggulan, dan cara melakukan pemesanan. |
| Aktor dan hak akses | Visitor. |
| Precondition dan trigger | Pengunjung membuka menu Tentang Kami atau CTA terkait. |
| Input dan field | Konten profil, visi, misi, nilai, keunggulan, panduan direct purchase melalui web, dan panduan konsultasi/pemesanan melalui WhatsApp. Isi final bersumber dari pemilik usaha. |
| Alur utama | Sistem menampilkan section profil dan identitas; menampilkan visi, misi, nilai dan keunggulan; menampilkan panduan pemesanan; menyediakan tombol untuk menuju ORD atau WhatsApp. |
| Alur alternatif | Jika detail resmi belum disetujui, konten tersebut tidak ditampilkan sebagai fakta. Panduan pemesanan tetap menjelaskan jalur umum yang telah disetujui. |
| Validasi dan business rules | Jangan menambahkan klaim seperti tahun berdiri, jumlah pelanggan, jangkauan layanan, atau keunggulan terukur tanpa konfirmasi. Detail layanan/variasi produk diarahkan ke CAT. |
| Output dan feedback | Pengunjung memahami profil usaha dan mengetahui langkah selanjutnya untuk memesan. |
| Error dan edge cases | Konten visi/misi kosong, CTA route belum tersedia, atau tautan WhatsApp salah. |
| Dependencies | ADM untuk konten publik; ORD untuk alur pemesanan website; nomor WhatsApp resmi dari pemilik usaha. |
| Priority | Must. |
| Acceptance criteria | Given konten About Us sudah dipublikasikan, When pengunjung membuka halaman dan memilih cara pemesanan, Then informasi ditampilkan dan tombol mengarah ke ORD atau WhatsApp sesuai label. |
### CP-FR-008 / CP-FR-009 — Ulasan pelanggan dan collaboration


| **Elemen** | **Spesifikasi** |
| :---: | :---: |
| Tujuan dan deskripsi | Membangun kepercayaan dengan menampilkan pengalaman pelanggan dan contoh hasil kolaborasi secara ringkas dalam satu halaman. |
| Aktor dan hak akses | Visitor melihat konten. Admin dan Owner mengelola konten melalui ADM; tidak ada input ulasan publik. |
| Precondition dan trigger | Pengunjung membuka halaman Ulasan & Kolaborasi. |
| Input dan field | Ulasan: nama tampilan, produk/pesanan, isi ulasan; rating dan foto opsional. Kolaborasi: nama/logo organisasi atau event, jenis merchandise, gambar hasil, deskripsi sangat singkat opsional. |
| Alur utama | Sistem menampilkan section ulasan; pengunjung membaca kartu ulasan. Pengunjung menuju section collaboration; sistem menampilkan logo/nama pihak dan contoh merchandise. Gambar dapat diperbesar jika disediakan. |
| Alur alternatif | Jika ulasan kosong, section ulasan dapat menampilkan pesan yang disetujui atau disembunyikan. Jika kolaborasi masih sedikit, tampilkan kartu yang tersedia tanpa memaksa jumlah minimum. |
| Validasi dan business rules | Hanya konten yang dikurasi dan dipublikasikan admin yang ditampilkan. Jangan menampilkan data pribadi yang tidak disetujui pelanggan. Tidak perlu studi kasus panjang atau form submit review publik. |
| Output dan feedback | Pengunjung melihat bukti sosial berupa komentar dan contoh kolaborasi. |
| Error dan edge cases | Logo atau gambar rusak; teks ulasan kosong; nama pihak tidak disetujui untuk publikasi; tidak ada item tersedia. |
| Dependencies | ADM untuk mengelola dan mempublikasikan testimonial serta collaboration data. |
| Priority | Should. |
| Acceptance criteria | Given terdapat ulasan dan/atau kolaborasi yang berstatus published, When pengunjung membuka halaman, Then konten relevan ditampilkan dengan ringkasan yang dapat dibaca dan gambar dapat dilihat dengan jelas. |
### CP-FR-010 / CP-FR-011 / CP-FR-012 — Galeri kategori dan lightbox


| **Elemen** | **Spesifikasi** |
| :---: | :---: |
| Tujuan dan deskripsi | Membantu pengunjung menemukan contoh hasil produksi berdasarkan jenis merchandise dan melihat gambar dengan ukuran lebih besar. |
| Aktor dan hak akses | Visitor; tanpa login. |
| Precondition dan trigger | Pengunjung membuka halaman Galeri. |
| Input dan field | Filter kategori: Acrylic/Keychain, Sticker, Pin, Printing, Merchandise Lainnya atau kategori aktif yang disediakan ADM. Input pencarian tidak diwajibkan. |
| Alur utama | Sistem memuat foto published dalam grid. Pengunjung memilih kategori. Sistem menampilkan foto kategori terkait. Pengunjung mengklik foto dan lightbox muncul dengan gambar lebih besar, caption jika ada, dan kontrol tutup. |
| Alur alternatif | Filter Semua menampilkan seluruh foto published. Kategori kosong menampilkan pesan “Belum ada foto pada kategori ini”. Lightbox ditutup melalui tombol close, Escape, atau klik overlay bila disepakati. |
| Validasi dan business rules | Hanya foto published yang ditampilkan. Filter tidak mengubah data. Kategori harus cocok dengan klasifikasi galeri yang dikelola ADM. Lightbox tidak menjadi halaman portfolio/detail proyek. |
| Output dan feedback | Grid terfilter dan tampilan gambar besar. Pengunjung dapat kembali ke grid tanpa kehilangan konteks filter. |
| Error dan edge cases | Gambar gagal dimuat, kategori tidak memiliki item, gambar memiliki orientasi berbeda, layar kecil, atau keyboard tidak dapat menutup lightbox. |
| Dependencies | ADM untuk foto, caption, kategori, urutan, status publikasi; aset visual dari pemilik usaha. |
| Priority | Must. |
| Acceptance criteria | Given galeri memiliki foto dengan beberapa kategori, When pengunjung memilih kategori lalu mengklik foto, Then grid menampilkan foto yang sesuai dan lightbox menampilkan gambar besar yang dapat ditutup. |
### CP-FR-013 / CP-FR-014 — Kontak dan tautan kanal komunikasi


| **Elemen** | **Spesifikasi** |
| :---: | :---: |
| Tujuan dan deskripsi | Memudahkan pengunjung menghubungi YOLOO Merch melalui kanal resmi. |
| Aktor dan hak akses | Visitor. |
| Precondition dan trigger | Pengunjung membuka Kontak, footer, atau menekan CTA konsultasi. |
| Input dan field | WhatsApp: nomor/tautan resmi; Instagram: handle/URL; email; alamat dan jam operasional bila disediakan. Informasi katalog awal: 0851-1131-0591, @yoloo.merch, yoloomerch@gmail.com; detail harus diverifikasi sebelum rilis. |
| Alur utama | Sistem menampilkan kanal. Klik WhatsApp membuka tujuan WhatsApp; klik Instagram membuka profil; klik email membuka aplikasi email. Alamat dapat membuka peta jika telah dikonfirmasi. |
| Alur alternatif | Jika kanal eksternal tidak tersedia, tampilkan handle/alamat teks yang dapat disalin. Alamat dan jam operasional dapat disembunyikan bila belum dikonfirmasi. |
| Validasi dan business rules | Tautan harus menggunakan alamat tujuan yang benar. Jangan membuat alamat lokasi atau jam operasional sendiri. Informasi kontak di footer harus konsisten dengan halaman Kontak. |
| Output dan feedback | Pengunjung memperoleh informasi kontak dan dapat berpindah ke kanal yang dipilih. |
| Error dan edge cases | Nomor WhatsApp berubah, link sosial tidak valid, email salah, atau aplikasi eksternal tidak terpasang. |
| Dependencies | ADM/settings konten atau konfigurasi kontak; konfirmasi nomor, URL sosial, email, alamat, dan jam dari pemilik usaha. |
| Priority | Must. |
| Acceptance criteria | Given kontak resmi sudah dikonfirmasi, When pengunjung memilih kanal komunikasi, Then browser membuka kanal yang benar dan teks kontak tetap tersedia sebagai alternatif. |
### CP-FR-015 / CP-FR-016 — Footer, SEO dasar, dan metadata


| **Elemen** | **Spesifikasi** |
| :---: | :---: |
| Tujuan dan deskripsi | Menyediakan navigasi tambahan dan mendukung keterbacaan serta penemuan halaman publik melalui mesin pencari. |
| Aktor dan hak akses | Visitor dan search engine crawler. |
| Precondition dan trigger | Setiap halaman publik dimuat. |
| Input dan field | Nama situs, title halaman, deskripsi singkat, heading, alt text gambar, tautan cepat, dan kanal kontak. |
| Alur utama | Sistem menampilkan footer konsisten; setiap halaman menyajikan title dan deskripsi yang relevan; gambar informatif memiliki alt text. |
| Alur alternatif | Jika metadata spesifik belum tersedia, gunakan fallback title situs dan deskripsi umum yang telah disetujui. |
| Validasi dan business rules | Metadata tidak boleh berisi klaim yang belum diverifikasi. Alt text menjelaskan gambar secara ringkas; gambar dekoratif dapat menggunakan alt kosong. |
| Output dan feedback | Footer dapat digunakan dan metadata dasar tersedia pada halaman publik. |
| Error dan edge cases | Link footer salah, title duplikat, gambar tanpa alt text, atau deskripsi terpotong tidak wajar. |
| Dependencies | ADM untuk konten/metadata jika dikelola melalui CMS; koordinator untuk identitas dan tagline final. |
| Priority | Should. |
| Acceptance criteria | Given halaman publik dibuka, When halaman dirender dan diperiksa, Then footer konsisten, tautan bekerja, title/description relevan, dan gambar informatif memiliki alt text. |
## I. Business rules domain


| **Rule ID** | **Aturan bisnis** | **Kondisi / batasan** | **Dampak jika tidak terpenuhi** |
| :---: | :---: | :---: | :---: |
| CP-BR-001 | Hanya konten publik yang ditampilkan. | Konten harus berstatus published/active dan tidak berada dalam status draft/archived. | Konten yang belum disetujui dapat terlihat oleh publik. |
| CP-BR-002 | Konten dikelola melalui ADM. | CP hanya menampilkan data; tambah/edit/hapus/publikasi dilakukan di PRD-04. | Terjadi duplikasi fungsi dan konflik kepemilikan domain. |
| CP-BR-003 | Ulasan pelanggan dikurasi admin. | Tidak ada form atau unggahan ulasan bebas dari publik pada versi ini. | Konten tidak terverifikasi atau data pribadi dapat terpublikasi. |
| CP-BR-004 | Ulasan tampil ringkas dan relevan. | Informasi minimum: nama tampilan, produk/pesanan, isi ulasan. Rating/foto opsional. | Kartu ulasan terlalu padat atau tidak memberi konteks. |
| CP-BR-005 | Collaboration tidak membutuhkan studi kasus panjang. | Cukup nama/logo pihak, jenis merchandise, dan foto contoh hasil; deskripsi singkat opsional. | Beban kurasi meningkat dan tidak sesuai jumlah portofolio saat ini. |
| CP-BR-006 | Galeri dapat difilter berdasarkan kategori. | Hanya foto published pada kategori aktif yang ditampilkan. Pilihan “Semua” menampilkan semua foto published. | Pengunjung mendapatkan foto yang tidak relevan atau tidak aktif. |
| CP-BR-007 | Foto galeri dapat diperbesar. | Lightbox menampilkan foto, menyediakan close, dan tetap usable pada smartphone. | Pengunjung sulit melihat detail produk. |
| CP-BR-008 | Tautan CTA memiliki tujuan sesuai label. | Lihat Katalog → CAT; Pesan melalui Website → ORD; Konsultasi/WhatsApp → kanal WA; About/Galeri/Kontak → halaman CP. | Pengunjung salah diarahkan atau alur pemesanan terputus. |
| CP-BR-009 | CP tidak menetapkan harga produk. | Harga, spesifikasi, opsi custom, dan kalkulator berada di CAT/ADM. | Informasi harga tidak konsisten antara halaman. |
| CP-BR-010 | Informasi kontak harus terverifikasi. | Nomor, email, handle, lokasi dan jam operasional harus disetujui pemilik usaha sebelum publikasi. | Pengunjung menghubungi kanal yang salah atau memperoleh informasi keliru. |
| CP-BR-011 | Jika konten belum tersedia, halaman tetap terkendali. | Section dapat disembunyikan atau memakai empty state yang disetujui; jangan membuat konten fiktif. | Halaman tampak rusak atau menampilkan informasi palsu. |
| CP-BR-012 | Identitas visual harus konsisten. | Gunakan aset logo/maskot resmi dan palet visual brand yang telah disetujui. | Pengalaman brand tidak konsisten. |
 
## J. Data requirements


| **Entitas / data** | **Field / atribut utama** | **Wajib?** | **Validasi / format** | **Sumber / relasi** |
| :---: | :---: | :---: | :---: | :---: |
| Site Content / Homepage | section_key, heading, body_text, image_asset, CTA_label, CTA_target, display_order, publication_status | Bergantung section | Teks tidak kosong untuk section yang ditampilkan; CTA target valid; status published. | ADM mengelola; digunakan CP. |
| About Content | profile_text, vision, mission, values, strengths, ordering_guide_web, ordering_guide_whatsapp | Konten profil wajib; detail resmi dapat bertahap | Tidak boleh memuat klaim faktual yang belum disetujui; teks terstruktur. | ADM; disediakan pemilik usaha. |
| Testimonial | testimonial_id, display_name, ordered_product, review_text, rating_optional, image_optional, publication_status, display_order | Nama tampilan, produk/pesanan, ulasan | Panjang teks dibatasi secara wajar; rating jika ada pada rentang yang disepakati; izin publikasi. | ADM; ditampilkan CP. |
| Collaboration / Portfolio | collaboration_id, organization_name, logo_asset, merchandise_type, result_images, short_description_optional, publication_status, display_order | Nama pihak dan visual hasil minimal satu | Logo/foto harus disetujui untuk publikasi; deskripsi opsional. | ADM; ditampilkan CP. |
| Gallery Item | gallery_item_id, image_asset, category_id, caption_optional, alt_text, publication_status, display_order | Gambar dan kategori | Format file gambar yang didukung; alt text untuk gambar informatif; hanya published. | ADM; kategori galeri dikelola ADM. |
| Gallery Category | category_id, category_name, slug, display_order, active_status | Nama dan slug | Slug unik; status aktif. Kategori awal dapat berupa Acrylic/Keychain, Sticker, Pin, Printing, Merchandise Lainnya. | ADM; bisa diselaraskan dengan kategori CAT setelah kesepakatan. |
| Contact Settings | whatsapp_number, whatsapp_url, instagram_handle, instagram_url, email, address_optional, operating_hours_optional | Kanal utama yang aktif | Format nomor/email/URL valid; alamat dan jam bersifat opsional sampai dikonfirmasi. | ADM/settings; data usaha diverifikasi pemilik. |
| Product Reference (read-only) | product_id, product_name, thumbnail, category, public_detail_url, publication_status | Untuk produk teaser yang ditampilkan | Hanya produk aktif/published; tidak menduplikasi deskripsi/variasi/harga master. | CAT sebagai sumber utama. |
 
## K. Validation, error handling, dan edge cases


| **Case ID** | **Kondisi / skenario** | **Validasi atau respons sistem yang diharapkan** | **Severity** |
| :---: | :---: | :---: | :---: |
| CP-EC-001 | Homepage tidak memiliki satu atau beberapa konten pilihan. | Sembunyikan section yang tidak relevan atau tampilkan empty state yang disetujui. Layout tidak meninggalkan ruang kosong besar. | Medium |
| CP-EC-002 | Produk unggulan dari CAT tidak tersedia atau tidak aktif. | Jangan menampilkan kartu produk yang tidak published; tampilkan kategori/CTA katalog sebagai alternatif jika tersedia. | Medium |
| CP-EC-003 | Tidak ada testimonial atau kolaborasi yang dipublikasikan. | Section dapat disembunyikan atau menampilkan pesan singkat; jangan menampilkan contoh fiktif seolah nyata. | Low |
| CP-EC-004 | Kategori galeri yang dipilih tidak memiliki gambar. | Tampilkan empty state yang jelas dan sediakan opsi kembali ke Semua kategori. | Low |
| CP-EC-005 | Gambar galeri gagal dimuat atau formatnya bermasalah. | Tampilkan placeholder; halaman tetap dapat digunakan; lightbox dapat ditutup. | Medium |
| CP-EC-006 | Pengunjung menutup lightbox atau menekan Escape. | Lightbox tertutup dan pengunjung kembali ke grid dengan filter sebelumnya tetap aktif. | Medium |
| CP-EC-007 | Tautan WhatsApp/Instagram/email tidak valid. | Tautan diperiksa sebelum rilis; jika tujuan eksternal gagal, teks kanal tetap dapat disalin/dibaca. | High |
| CP-EC-008 | Informasi alamat/jam operasional belum tersedia. | Jangan menampilkan field kosong atau membuat informasi sendiri. Tampilkan hanya data yang telah disetujui. | Medium |
| CP-EC-009 | Pengunjung membuka website dari smartphone dengan layar sempit. | Navigasi menjadi menu mobile; gambar dan grid menyesuaikan; tidak ada horizontal overflow. | High |
| CP-EC-010 | Gambar logo/mascot tidak tersedia. | Gunakan fallback brand yang disetujui (misalnya logo teks) dan jangan memakai aset tidak resmi. | Medium |
| CP-EC-011 | Link CTA ke CAT atau ORD belum tersedia saat integrasi. | Tandai sebagai dependency; sebelum release link wajib diuji. Jangan membuat checkout atau katalog duplikat di CP. | High |
| CP-EC-012 | Nama/identitas pelanggan atau organisasi belum mendapat izin publikasi. | Konten tidak dipublikasikan sampai mendapat persetujuan atau menggunakan nama tampilan yang disetujui. | High |
 
## L. Acceptance criteria dan test scenarios


| **Test ID** | **Requirement terkait** | **Given (kondisi awal)** | **When (aksi)** | **Then (hasil yang diharapkan)** | **Status** |
| :---: | :---: | :---: | :---: | :---: | :---: |
| CP-AT-001 | CP-FR-001 | Beranda tersedia dan memiliki konten published. | Visitor membuka `/`. | Section utama tampil sesuai susunan, konten terbaca, dan tidak ada layout rusak. | Belum diuji |
| CP-AT-002 | CP-FR-002 | Visitor berada pada halaman publik. | Visitor memilih setiap item navigasi. | Visitor diarahkan ke halaman/tujuan yang sesuai termasuk katalog CAT. | Belum diuji |
| CP-AT-003 | CP-FR-003 | Website dibuka pada viewport smartphone. | Visitor membuka dan menggunakan menu mobile. | Menu dapat dibuka/ditutup dan item navigasi dapat dipilih tanpa overflow. | Belum diuji |
| CP-AT-004 | CP-FR-004 | CTA telah memiliki target route/URL. | Visitor menekan CTA yang tersedia. | Setiap CTA membuka tujuan sesuai label (CAT, ORD, WhatsApp, atau halaman CP). | Belum diuji |
| CP-AT-005 | CP-FR-005 | CAT menyediakan produk aktif untuk teaser. | Visitor melihat section produk unggulan. | Kartu menampilkan data produk yang aktif dan tautan menuju detail/katalog CAT. | Belum diuji |
| CP-AT-006 | CP-FR-006 | Konten Tentang Kami telah dipublikasikan. | Visitor membuka `/tentang-kami`. | Profil, visi/misi/nilai/keunggulan yang tersedia, dan panduan pemesanan tampil benar. | Belum diuji |
| CP-AT-007 | CP-FR-007 | Link ORD dan WhatsApp telah disepakati. | Visitor memilih salah satu cara pemesanan. | Visitor diarahkan ke ORD atau WhatsApp sesuai pilihan. | Belum diuji |
| CP-AT-008 | CP-FR-008 | Terdapat testimonial berstatus published. | Visitor membuka halaman Ulasan & Kolaborasi. | Kartu menampilkan nama tampilan, produk/pesanan, dan teks ulasan; rating/foto hanya jika tersedia. | Belum diuji |
| CP-AT-009 | CP-FR-009 | Terdapat data kolaborasi published. | Visitor membuka section Collaboration. | Logo/nama pihak dan contoh hasil merchandise ditampilkan tanpa membutuhkan studi kasus panjang. | Belum diuji |
| CP-AT-010 | CP-FR-010 | Terdapat foto galeri published. | Visitor membuka `/galeri`. | Foto ditampilkan dalam grid dengan layout responsif. | Belum diuji |
| CP-AT-011 | CP-FR-011 | Galeri memiliki beberapa kategori. | Visitor memilih kategori tertentu. | Hanya foto yang sesuai kategori dan published yang ditampilkan. | Belum diuji |
| CP-AT-012 | CP-FR-012 | Visitor melihat grid galeri. | Visitor mengklik foto lalu menutup lightbox. | Foto tampil lebih besar dan lightbox dapat ditutup dengan kontrol yang tersedia. | Belum diuji |
| CP-AT-013 | CP-FR-013 | Informasi kontak sudah dikonfirmasi. | Visitor membuka `/kontak`. | Kanal WhatsApp, Instagram, email serta alamat/jam jika tersedia ditampilkan dengan jelas. | Belum diuji |
| CP-AT-014 | CP-FR-014 | Tautan kanal eksternal telah diverifikasi. | Visitor mengklik tautan WhatsApp/Instagram/email. | Tujuan eksternal yang benar dibuka atau teks kanal tetap dapat disalin. | Belum diuji |
| CP-AT-015 | CP-FR-015 | Visitor membuka halaman publik. | Visitor mencapai footer. | Footer konsisten, tautan cepat dan kontak bekerja. | Belum diuji |
| CP-AT-016 | CP-FR-016 | Halaman publik memiliki metadata dan gambar. | Halaman diperiksa. | Title/description relevan dan gambar informatif memiliki alt text. | Belum diuji |
| CP-AT-017 | CP-FR-017 | Salah satu section tidak memiliki konten published. | Visitor membuka halaman terkait. | Halaman tidak menampilkan konten fiktif atau layout kosong yang rusak. | Belum diuji |
 
## M. Non-functional requirements domain


| **Kategori** | **Kebutuhan khusus domain** | **Ukuran / target uji** |
| :---: | :---: | :---: |
| Usability | Navigasi dan CTA mudah dipahami. Pengunjung dapat menemukan katalog, cara pemesanan, dan kontak tanpa harus memahami istilah teknis. | Uji tugas sederhana: pengguna dapat menemukan katalog, galeri, kontak, dan cara pemesanan tanpa bantuan moderator pada skenario uji. |
| Responsive design | Halaman publik harus usable pada desktop dan smartphone. Layout, menu, grid, gambar, dan lightbox menyesuaikan ukuran layar. | Uji minimal pada desktop dan viewport smartphone 360 px; tidak ada horizontal scrolling yang tidak disengaja. |
| Performance | Gambar hero, galeri, dan konten visual harus dioptimasi agar tidak membebani halaman. | Gunakan gambar terkompresi/responsive dan lazy loading untuk gambar di luar viewport; target awal halaman utama dapat digunakan dalam waktu sekitar 3 detik pada koneksi uji yang disepakati (target perlu divalidasi). |
| Accessibility | Teks memiliki kontras memadai; tombol/tautan dapat dikenali; gambar informatif memiliki alt text; lightbox dapat ditutup melalui keyboard. | Pemeriksaan manual navigasi keyboard, fokus terlihat, alt text, dan label tombol. |
| Compatibility | Mendukung browser modern yang umum digunakan pada desktop dan smartphone. | Uji sekurang-kurangnya pada Chrome desktop dan Chrome Android; browser tambahan menyesuaikan perangkat tim. |
| SEO dasar | Setiap halaman utama memiliki title, meta description, struktur heading dan URL yang dapat dibaca. | Pemeriksaan metadata per halaman; tidak ada title halaman inti yang kosong; heading tidak digunakan hanya untuk dekorasi. |
| Privacy | Informasi pelanggan dan pihak kolaborasi hanya ditampilkan jika telah disetujui untuk publikasi. | Setiap konten testimonial/kolaborasi memiliki status publikasi dan sumber persetujuan yang dapat dikonfirmasi. |
| Maintainability / consistency | Komponen header, footer, CTA, dan card visual digunakan konsisten di halaman publik. | Perubahan pada komponen global tercermin di seluruh halaman CP. |
 
## N. Dependencies dan integrasi lintas domain


| **Domain terkait** | **Data / fungsi yang dibutuhkan CP** | **Data / fungsi yang diberikan CP** | **Kesepakatan interface** |
| :---: | :---: | :---: | :---: |
| PRD-04 / ADM | Konten profil, visi/misi/nilai, hero, gambar, testimonial, collaboration, galeri, kategori galeri, kontak, status publikasi, urutan tampil. | Kebutuhan field konten, penempatan section, spesifikasi gambar, kebutuhan status publikasi, alt text/caption, CTA target. | ADM menyediakan konten yang sudah dipublikasikan; CP hanya membaca/menampilkan. Perubahan konten tidak dilakukan dari CP. |
| PRD-02 / CAT | Nama/thumbnail produk aktif, kategori/produk unggulan, URL detail/katalog, informasi yang diizinkan untuk ringkasan. | Permintaan data ringkas produk untuk homepage dan tautan menuju katalog/detail. | CAT menjadi sumber data master produk dan harga. CP tidak menduplikasi variasi, spesifikasi lengkap, atau harga. |
| PRD-03 / ORD | Route/URL pemesanan langsung dan custom request; label/jenis jalur yang tersedia. | Pengunjung yang memilih CTA pesan atau panduan pemesanan dari About Us. | CTA CP mengarahkan ke ORD. Form, validasi, upload, dan penyimpanan order adalah milik ORD. |
| PRD-05 / FIN | Tidak membutuhkan akses langsung ke data keuangan/transaksi. | Tidak ada data transaksi yang diberikan oleh CP. | Tidak ada integrasi langsung pada domain CP. Jika di masa depan diperlukan data agregat, harus menjadi perubahan scope yang disepakati. |
Kesepakatan interface awal

- CP → CAT: tautan katalog dari menu dan homepage; kartu produk unggulan mengambil data publik yang disediakan CAT.

- CP → ORD: tautan CTA Pesan Sekarang/Pesan melalui Website; CP tidak mengirim form order secara langsung.

- CP → WhatsApp: tautan konsultasi menggunakan nomor resmi yang dikonfirmasi.

- ADM → CP: data konten dan media hanya ditampilkan jika status publikasinya aktif/published.

- ADM ↔ CAT: kategori galeri dan kategori produk dapat dibuat konsisten, tetapi pemetaan final ditetapkan bersama agar kategori galeri tidak bergantung secara keliru pada kategori produk. 

# 16. CAT — PRODUCT CATALOG & CUSTOMIZATION SYSTEM
Penanggung jawab: Anggota 2. Nama anggota: Muhammad Haidir. Status: Selesai.
## A. Tujuan domain dan ringkasan solusi
Menyediakan katalog produk yang dapat dijelajahi pelanggan, halaman detail, pilihan kustomisasi, dan kalkulator estimasi harga untuk produk yang didukung. Domain ini bertanggung jawab atas pengalaman pelanggan saat memahami produk dan mengonfigurasi pilihannya. Pengelolaan master data dan tarif di dashboard dimiliki PRD-04.

Catatan khusus domain: Kalkulator harus mendukung hasil estimasi harga per pcs dan total, dengan rumus berbeda sesuai produk bila dibutuhkan. Parameter contoh acrylic dapat mencakup tipe produk, ketebalan, ukuran, finishing/aksesori, dan kuantitas; nilai tarif dan formula nyata harus divalidasi dengan pemilik usaha dan dikelola melalui PRD-04. Estimasi bukan harga final yang mengikat.

Masalah pengguna. Produk YOLOO Merch memiliki banyak variasi, mulai dari bahan, ukuran, ketebalan, finishing, aksesori, sampai jumlah pesanan. Calon pelanggan sulit membandingkan pilihan dan tidak tahu perkiraan biayanya sebelum menghubungi admin. Akibatnya admin harus menjawab pertanyaan yang sama berulang kali, dan pelanggan bisa berhenti sebelum memesan.

Solusi domain. Domain CAT menyediakan katalog yang dapat ditelusuri lewat kategori, pencarian, dan filter. Setiap produk memiliki halaman detail berisi foto, spesifikasi, dan pilihan konfigurasi. Untuk produk yang memiliki aturan harga, pelanggan dapat melihat estimasi harga per pcs dan total yang diperbarui saat pilihan atau jumlah diubah. Untuk produk yang harganya belum bisa dipastikan, pelanggan diarahkan meminta penawaran. Seluruh data dibaca dari master data yang dikelola admin melalui PRD-04, sehingga perubahan di dashboard langsung tampil di halaman publik.

Nilai bagi produk. Pelanggan memperoleh informasi yang cukup untuk memutuskan tanpa harus bertanya lebih dulu. Admin menerima pesanan dengan spesifikasi yang sudah jelas, sehingga proses konfirmasi lebih singkat. Sistem juga memisahkan estimasi dari harga final, sehingga pelanggan tidak mengira angka kalkulator sudah mengikat.

Hasil akhir yang diharapkan. Setelah domain ini selesai:

1. Pengunjung dapat menemukan semua produk aktif melalui kategori atau pencarian dan membuka halaman detailnya tanpa login.

2. Halaman detail menampilkan spesifikasi, foto, variasi, dan informasi harga sesuai model harga produk, dengan data yang sama seperti yang diatur admin di PRD-04.

3. Pelanggan dapat memilih konfigurasi dan jumlah. Kombinasi opsi yang tidak didukung dan jumlah di bawah minimum order ditolak dengan pesan yang jelas.

4. Untuk produk berkalkulator, sistem menampilkan estimasi harga per pcs dan total yang konsisten dengan aturan harga, selalu berlabel estimasi.

5. Konfigurasi dan estimasi diteruskan ke PRD-03 (cart atau custom request) tanpa ada detail yang hilang, termasuk snapshot nama produk dan spesifikasi.

6. Seluruh halaman dapat dipakai dengan baik di desktop.

## B. Tujuan, scope, dan batasan domain


| **Aspek** | **Isi** |
| :---: | :---: |
| Tujuan domain | Membuat pengunjung mampu menemukan produk, memahami spesifikasi dan variasinya, memilih konfigurasi yang valid, serta melihat estimasi harga per pcs dan total, lalu meneruskan hasilnya ke proses pemesanan. Mendukung OBJ-02 dan OBJ-03 pada Master PRD. |
| In scope | Daftar kategori dan produk; pencarian dan filter; halaman detail dengan foto, deskripsi, dan spesifikasi; pilihan variasi dan konfigurasi; validasi kombinasi opsi dan jumlah minimum; tampilan informasi harga untuk empat model harga (harga tetap, mulai dari, estimasi, request quotation); kalkulator estimasi dengan minimum order, tingkat kuantitas, dan biaya tambahan; penerusan konfigurasi dan estimasi ke cart atau custom request; kondisi halaman (loading, kosong, error); tampilan responsif. |
| Out of scope | Pengelolaan master data produk, opsi, dan tarif (PRD-04); cart, checkout, dan formulir custom request (PRD-03); pembuatan transaksi, pembayaran, dan invoice (PRD-05); stok atau ketersediaan bahan real-time; harga final yang mengikat; akun pelanggan; payment gateway; pratinjau desain visual atau editor desain online. |
| Asumsi | (1) Data produk, opsi, dan tarif tersedia dari PRD-04 dan dapat dibaca PRD-02. (2) Hanya sebagian produk yang memiliki kalkulator; sisanya memakai harga tetap, harga mulai dari, atau request quotation. (3) Formula tarif bisa berbeda per produk. (4) Pelanggan tidak perlu login untuk melihat katalog dan memakai kalkulator. (5) Daftar produk dan kategori aktual mengikuti katalog YOLOO Merch dan dikonfirmasi pemilik usaha. Seluruh asumsi ini perlu divalidasi. |
| Stakeholder / aktor | Visitor dan customer guest sebagai pengguna langsung; admin sebagai pengelola data lewat PRD-04; pemilik usaha sebagai penentu daftar produk, harga, dan aturan estimasi; tim PRD-03 dan PRD-04 sebagai pihak yang bertukar data dengan domain ini. |
## C. Daftar subfitur yang wajib dianalisis


| **Subfitur awal** | **Spesifikasi** |
| :---: | :---: |
| Katalog produk, kategori, pencarian, dan filter | Menampilkan kategori dan produk aktif dalam bentuk kartu (gambar utama, nama, label model harga). Pengunjung dapat mencari berdasarkan kata kunci nama produk dan menyaring berdasarkan kategori dan model harga. Produk draft atau nonaktif tidak tampil. Keluaran: daftar produk yang sudah tersaring dan mengarah ke halaman detail. |
| Halaman detail produk dan foto | Menampilkan nama, kategori, deskripsi, satu atau lebih foto, spesifikasi, informasi harga, dan tombol aksi. Foto tambahan ditampilkan sebagai galeri kecil. Keluaran: halaman informatif yang menjadi titik awal konfigurasi dan pemesanan. |
| Spesifikasi dan pilihan variasi produk | Menampilkan opsi yang dikonfigurasi untuk produk tersebut (ukuran, bahan, ketebalan, finishing, aksesori). Hanya opsi milik produk yang muncul. Keluaran: daftar opsi yang dapat dipilih beserta keterangan singkat. |
| Product customization / configurator | Pelanggan memilih satu nilai pada setiap grup opsi dan mengisi jumlah. Sistem memvalidasi kombinasi dan jumlah minimum, lalu menampilkan ringkasan konfigurasi. Keluaran: konfigurasi valid yang siap dihitung atau diteruskan. |
| Price calculator untuk produk terpilih | Menghitung estimasi harga per pcs dan total dari konfigurasi dan jumlah, hanya untuk produk yang punya aturan harga. Hasil selalu berlabel estimasi. Keluaran: harga per pcs, total estimasi, dan catatan estimasi. |
| Tampilan harga tetap / mulai dari / estimasi / request quotation | Setiap produk memiliki satu model harga. Tampilan menyesuaikan: harga pasti, harga awal dengan keterangan, kalkulator, atau ajakan meminta penawaran tanpa angka. Keluaran: informasi harga yang sesuai model. |
| Minimum order, quantity tier, biaya tambahan, dan catatan estimasi | Aturan per produk: jumlah minimum, rentang kuantitas dengan tarif berbeda, biaya tambahan tetap, dan catatan bahwa hasil dapat dikonfirmasi admin. Nilai dibaca dari PRD-04. Keluaran: perhitungan yang memuat aturan tersebut dan penjelasan yang terlihat pelanggan. |
| Penerusan konfigurasi ke pemesanan (subfitur tambahan) | Membawa produk, opsi, jumlah, dan estimasi ke cart atau custom request milik PRD-03 tanpa ada detail yang hilang. Alasan penambahan: dibutuhkan untuk memenuhi INT-03 pada Master PRD. |
| Galeri foto produk dan contoh hasil (subfitur tambahan) | Beberapa foto per produk dan pembesaran gambar. Alasan penambahan: membantu pelanggan menilai hasil. |
| Produk terkait dan urutan tampilan (subfitur tambahan) | Rekomendasi produk sekategori dan pengurutan daftar. Alasan penambahan: mempermudah penjelajahan. |
Daftar di atas adalah titik awal, bukan batas jumlah fitur. Tambahkan baris jika diperlukan dan tandai subfitur yang dipindahkan/dihilangkan beserta alasannya.
## D. User roles dan permission dalam domain


| **Aktor / role** | **Melihat** | **Membuat** | **Mengubah** | **Menghapus / tindakan khusus** |
| --- | --- | --- | --- | --- |
| Visitor | Ya: katalog, detail produk, harga, spesifikasi | Tidak | Tidak | Tidak. Dapat memakai kalkulator tanpa login. |
| Customer Guest | Ya: sama dengan Visitor | Konfigurasi sementara dan hasil estimasi (tidak disimpan permanen) | Ya, mengubah pilihan opsi dan jumlah pada konfigurasinya sendiri | Dapat meneruskan konfigurasi ke cart atau custom request (PRD-03). Tidak dapat melihat data pelanggan lain. |
| Admin | Ya: tampilan publik sebagai pratinjau | Tidak di domain ini | Tidak di domain ini | Pengelolaan produk, opsi, dan tarif dilakukan melalui ADM sesuai hak akses Admin. |
| Owner | Ya: tampilan publik sebagai pratinjau | Tidak di domain ini | Tidak di domain ini | Memiliki akses penuh pengelolaan master produk, opsi, dan tarif melalui ADM. |
## E. Page / screen specifications
Spesifikasi untuk setiap halaman atau layar yang menjadi tanggung jawab domain. Cantumkan route jika sudah diketahui, role yang dapat mengakses, tujuan, konten, komponen, aksi utama, dan kondisi halaman.

| **Page ID / nama** | **Route / akses** | **Tujuan dan isi utama** | **Aksi / interaksi utama** |
| :---: | :---: | :---: | :---: |
| PAGE-CAT-001 Katalog Produk | /produk; visitor, customer guest | Pintu masuk katalog. Isi: daftar kategori, kolom pencarian, filter model harga, kartu produk (gambar, nama, label model harga), pembagian halaman. Kondisi: loading, kosong, error. | Klik kategori, ketik pencarian, pilih filter, klik kartu produk, pindah halaman. |
| PAGE-CAT-002 Daftar Produk per Kategori | /produk/kategori/{slug}; visitor, customer guest | Menampilkan produk dalam satu kategori dengan kolom pencarian dan filter yang sama. Isi: judul dan deskripsi singkat kategori, kartu produk. | Pencarian, filter, klik kartu produk, kembali ke katalog. |
| PAGE-CAT-003 Detail Produk | /produk/{slug}; visitor, customer guest | Informasi lengkap produk. Isi: nama, kategori, galeri foto, deskripsi, tabel spesifikasi, informasi harga sesuai model, grup opsi konfigurasi, ringkasan pilihan, tombol aksi. | Pilih opsi, isi jumlah, ganti foto, klik tombol tambah ke cart atau minta penawaran. |
| PAGE-CAT-004 Panel Kalkulator Estimasi | Bagian pada PAGE-CAT-003 (usulan: /produk/{slug}#kalkulator); visitor, customer guest | Menampilkan hasil hitung. Isi: parameter terpilih, harga per pcs, total estimasi, rincian tingkat kuantitas atau biaya tambahan, catatan estimasi, pesan bila konfigurasi tidak didukung. Hanya muncul untuk produk bermodel kalkulator. | Ubah opsi atau jumlah dan hasil ikut berubah; klik lanjut ke pemesanan. |
| PAGE-CAT-005 Produk Tidak Ditemukan | Ditampilkan pada route produk yang tidak valid atau nonaktif; visitor, customer guest | Pesan yang jelas bahwa produk tidak tersedia, tautan kembali ke katalog, dan saran kategori. | Klik kembali ke katalog. |
## F. User flow dan workflow
Untuk setiap alur, jelaskan aktor, trigger, precondition, langkah normal, hasil akhir, serta cabang alternatif/error. Sisipkan flowchart atau diagram jika membantu.

| **Flow ID / nama** | **Trigger & precondition** | **Main flow** | **Alternative / exception** | **Output** |
| :---: | :---: | :---: | :---: | :---: |
| FLOW-CAT-001 Menemukan produk | Pengunjung membuka website; ada minimal satu produk aktif. | (1) Pengunjung membuka katalog. (2) Sistem menampilkan kategori dan produk aktif. (3) Pengunjung memilih kategori atau mengetik kata kunci. (4) Sistem menampilkan daftar yang sesuai. (5) Pengunjung membuka kartu produk. (6) Sistem menampilkan halaman detail. | Tidak ada hasil: tampil kondisi kosong dengan saran mengubah kata kunci atau melihat semua kategori. Gagal memuat data: tampil pesan error dan tombol coba lagi. Slug tidak valid: tampil PAGE-CAT-005. | Pengunjung berada di halaman detail produk yang dituju. |
| FLOW-CAT-002 Konfigurasi dan estimasi harga | Pengunjung berada di halaman detail produk; produk memiliki grup opsi dan model harga kalkulator. | (1) Pengunjung memilih nilai pada setiap grup opsi. (2) Pengunjung mengisi jumlah. (3) Sistem memvalidasi kombinasi dan jumlah. (4) Sistem menghitung harga per pcs dan total. (5) Sistem menampilkan hasil berlabel estimasi beserta catatan. (6) Pengunjung mengubah pilihan dan sistem menghitung ulang. | Kombinasi tidak didukung: tampil pesan dan tombol lanjut dinonaktifkan. Jumlah di bawah minimum: tampil pesan batas minimum. Konfigurasi tanpa aturan harga: tidak ada angka, sistem menyarankan minta penawaran. Data tarif gagal dimuat: pesan error, kalkulator tidak menampilkan angka. | Konfigurasi valid dengan estimasi harga per pcs dan total. |
| FLOW-CAT-003 Meneruskan ke pemesanan | Konfigurasi valid sudah dipilih; model harga mengizinkan aksi pembelian langsung atau permintaan penawaran. | (1) Pengunjung menekan tombol aksi. (2) Sistem mengemas data: produk, opsi, jumlah, estimasi bila ada. (3) Sistem mengirim paket data ke PRD-03. (4) Pengunjung melanjutkan ke cart atau formulir custom request dengan detail terisi. | Produk bermodel request quotation: pengunjung diarahkan ke custom request tanpa angka. Produk dinonaktifkan saat proses berjalan: tampil pesan bahwa produk tidak lagi tersedia dan data tidak dikirim. | Detail konfigurasi diterima PRD-03 tanpa ada yang hilang. |
 
## G. Functional requirements register


| **Requirement ID** | **Nama fungsi** | **Deskripsi ringkas** | **Aktor** | **Priority** | **Status** |
| :---: | :---: | :---: | :---: | :---: | :---: |
| CAT-FR-001 | Tampil kategori produk | Menampilkan daftar kategori yang berstatus aktif. | Visitor, customer guest | M | Draft |
| CAT-FR-002 | Tampil daftar produk | Menampilkan produk aktif per kategori dalam bentuk kartu dengan gambar, nama, dan label model harga. | Visitor, customer guest | M | Draft |
| CAT-FR-003 | Pencarian dan filter produk | Mencari berdasarkan kata kunci dan menyaring berdasarkan kategori dan model harga. | Visitor, customer guest | M | Draft |
| CAT-FR-004 | Halaman detail produk | Menampilkan gambar, deskripsi, spesifikasi, dan informasi harga satu produk. | Visitor, customer guest | M | Draft |
| CAT-FR-005 | Pilihan variasi dan opsi | Menampilkan grup opsi milik produk dan menerima pilihan pelanggan. | Visitor, customer guest | M | Draft |
| CAT-FR-006 | Validasi konfigurasi | Menolak kombinasi opsi yang tidak didukung dan jumlah di bawah minimum. | Sistem | M | Draft |
| CAT-FR-007 | Tampil informasi harga sesuai model | Menampilkan harga tetap, harga mulai dari, kalkulator, atau ajakan minta penawaran. | Visitor, customer guest | M | Draft |
| CAT-FR-008 | Kalkulator estimasi harga | Menghitung estimasi harga per pcs dan total dari konfigurasi dan jumlah. | Visitor, customer guest | M | Draft |
| CAT-FR-009 | Aturan tingkat kuantitas dan biaya tambahan | Menerapkan minimum order, diskon kuantitas, dan biaya tambahan pada perhitungan serta menjelaskannya. | Sistem | M | Draft |
| CAT-FR-010 | Penerusan konfigurasi ke pemesanan | Mengirim produk, opsi, jumlah, dan estimasi ke PRD-03. | Customer guest | M | Draft |
| CAT-FR-011 | Galeri foto produk | Menampilkan beberapa foto dan pembesaran gambar. | Visitor, customer guest | S | Draft |
| CAT-FR-012 | Kondisi halaman | Menampilkan kondisi loading, kosong, error, dan produk tidak ditemukan. | Sistem | M | Draft |
Kartu spesifikasi rinci disusun untuk tiga requirement yang paling kompleks : CAT-FR-003, CAT-FR-008, DAN CAT-FR-010. 

## H. Kartu spesifikasi rinci functional requirement
CAT-FR — Kartu Spesifikasi Functional Requirement 1

| **Elemen** | **Isi / spesifikasi** |
| :---: | :---: |
| Requirement ID | CAT-FR-003 |
| Nama requirement | Pencarian dan filter produk |
| Tujuan dan deskripsi | Membantu pengunjung menemukan produk tanpa menelusuri seluruh daftar. Sistem mencocokkan kata kunci dengan nama produk dan menyaring daftar berdasarkan kategori dan model harga. |
| Aktor dan hak akses | Visitor dan customer guest. Tanpa login. |
| Precondition dan trigger | Halaman katalog atau daftar kategori terbuka. Pemicu: pengunjung mengetik kata kunci atau memilih filter. |
| Input dan field | Kata kunci (teks, opsional, batas panjang ditentukan tim); kategori (pilihan dari daftar kategori aktif, opsional); model harga (pilihan: harga tetap, mulai dari, estimasi, request quotation; opsional); nomor halaman (angka, otomatis). Sumber data: tabel produk dan kategori dari PRD-04. |
| Alur utama | (1) Pengunjung mengetik kata kunci atau memilih filter. (2) Sistem membersihkan input. (3) Sistem mengambil produk aktif yang cocok. (4) Sistem menampilkan hasil beserta jumlah hasil. (5) Pengunjung dapat menggabungkan beberapa filter. |
| Alur alternatif | Pengunjung menghapus filter dan daftar kembali ke kondisi awal. Pengunjung mengganti halaman hasil. |
| Validasi dan business rules | Hanya produk berstatus aktif yang dicari. Pencarian tidak membedakan huruf besar dan kecil. Kata kunci kosong berarti tidak ada pembatasan. Input dibersihkan di sisi server agar aman dari penyisipan kode. |
| Output dan feedback | Daftar kartu produk yang sudah tersaring, jumlah hasil, dan indikator loading selama proses. |
| Error dan edge cases | Tidak ada hasil: tampil kondisi kosong dengan saran. Kata kunci hanya berisi spasi: diperlakukan sebagai kosong. Gagal memuat: pesan error dan tombol coba lagi. Kategori tidak lagi aktif: filter diabaikan dan pengunjung diberi tahu. |
| Dependencies | Data produk dan kategori dari PRD-04. |
| Priority | Must |
| Acceptance criteria | Given ada produk aktif bernama sesuai kata kunci, When pengunjung mencari kata kunci tersebut, Then produk tampil di hasil. Given produk berstatus nonaktif, When pengunjung mencari namanya, Then produk tidak tampil. |
CAT-FR — Kartu Spesifikasi Functional Requirement 2

| **Elemen** | **Isi / spesifikasi** |
| :---: | :---: |
| Requirement ID | CAT-FR-008 |
| Nama requirement | Kalkulator estimasi harga |
| Tujuan dan deskripsi | Memberi pelanggan gambaran biaya per pcs dan total sebelum memesan, berdasarkan konfigurasi dan jumlah, menggunakan aturan harga yang diatur admin untuk produk tersebut. |
| Aktor dan hak akses | Visitor dan customer guest. Tanpa login. |
| Precondition dan trigger | Produk bermodel kalkulator dengan aturan harga aktif, dan semua grup opsi wajib sudah dipilih. Pemicu: pelanggan mengubah opsi atau jumlah. |
| Input dan field | Produk (rujukan, wajib); nilai tiap grup opsi (pilihan, wajib sesuai grup); jumlah (bilangan bulat positif, wajib); aturan harga (tarif dasar, tarif tambahan per opsi, rentang kuantitas, minimum order, biaya tambahan; sumber PRD-04). |
| Alur utama | (1) Sistem memeriksa konfigurasi lengkap dan valid. (2) Sistem mengambil aturan harga produk. (3) Sistem menghitung harga per pcs dari tarif dasar ditambah tarif tambahan setiap opsi terpilih, lalu menerapkan diskon sesuai rentang kuantitas. (4) Sistem menghitung total dari harga per pcs dikali jumlah, ditambah biaya tambahan tetap. (5) Bila total di bawah minimum order, sistem memakai nilai minimum order dan menjelaskannya. (6) Sistem menampilkan hasil dengan label estimasi dan catatan konfirmasi admin. |
| Alur alternatif | Pelanggan mengubah opsi atau jumlah dan sistem menghitung ulang otomatis. Konfigurasi yang tidak punya aturan harga tidak menghasilkan angka; sistem menyarankan minta penawaran. |
| Validasi dan business rules | Rumus dan tarif berbeda per produk dan dibaca dari konfigurasi, tidak ditulis tetap dalam kode. Contoh bentuk rumus di atas bersifat ilustrasi; rumus nyata menunggu validasi pemilik usaha. Perhitungan dilakukan ulang di sisi server saat konfigurasi diteruskan ke PRD-03 sehingga angka tidak bergantung pada tampilan. Pembulatan mengikuti kebijakan yang disepakati. Konfigurasi yang sama selalu memberi hasil yang sama. |
| Output dan feedback | Harga per pcs, total estimasi, rincian tingkat kuantitas atau biaya tambahan, label Estimasi, dan catatan bahwa harga final dikonfirmasi admin. |
| Error dan edge cases | Jumlah nol, negatif, atau bukan angka: pesan validasi. Jumlah di bawah minimum order: pesan batas minimum. Opsi belum lengkap: angka belum ditampilkan dan sistem menunjukkan opsi yang belum dipilih. Tarif tidak dapat dimuat: pesan error tanpa angka. Jumlah sangat besar di luar rentang tarif: sistem menyarankan minta penawaran. |
| Dependencies | Aturan harga dan opsi dari PRD-04; hasil diteruskan ke PRD-03. |
| Priority | Must |
| Acceptance criteria | Given produk berkalkulator dengan tarif yang sudah diisi, When pelanggan memilih konfigurasi valid dan jumlah, Then sistem menampilkan harga per pcs dan total yang sesuai aturan perhitungan. Given jumlah di bawah minimum, When pelanggan menghitung, Then sistem menampilkan pesan minimum dan tidak menampilkan total yang menyesatkan. Given konfigurasi yang sama dihitung dua kali, Then hasilnya identik. |
CAT-FR — Kartu Spesifikasi Functional Requirement 3

| **Elemen** | **Isi / spesifikasi** |
| :---: | :---: |
| Requirement ID | CAT-FR-010 |
| Nama requirement | Penerusan konfigurasi ke pemesanan |
| Tujuan dan deskripsi | Memastikan pilihan yang sudah dibuat pelanggan sampai ke cart atau custom request tanpa kehilangan detail, termasuk snapshot nama produk dan spesifikasi. |
| Aktor dan hak akses | Customer guest. Tanpa login. |
| Precondition dan trigger | Konfigurasi valid sudah dipilih dan produk berstatus aktif. Pemicu: pelanggan menekan tombol tambah ke cart atau minta penawaran. |
| Input dan field | Produk (rujukan, wajib); nama produk saat itu (teks, snapshot); nilai tiap opsi (pasangan nama opsi dan nilai, snapshot); jumlah (bilangan bulat positif); estimasi harga per pcs dan total (angka, opsional, berstatus estimasi); model harga (pilihan, wajib). |
| Alur utama | (1) Sistem memeriksa ulang produk masih aktif dan konfigurasi masih valid. (2) Sistem menghitung ulang estimasi di sisi server bila produk berkalkulator. (3) Sistem menyusun paket data konfigurasi. (4) Sistem menyerahkan paket ke PRD-03. (5) Pelanggan dibawa ke cart atau formulir custom request dengan detail terisi. |
| Alur alternatif | Produk bermodel request quotation: paket dikirim tanpa angka estimasi dan pelanggan diarahkan ke custom request. |
| Validasi dan business rules | Estimasi yang diteruskan tetap berstatus estimasi sampai dikonfirmasi admin. Setiap item membawa snapshot nama produk dan spesifikasi agar perubahan katalog tidak mengubah pesanan yang sudah dibuat. Harga katalog yang berubah kemudian tidak mengubah paket yang sudah dikirim. |
| Output dan feedback | Pelanggan melihat ringkasan konfigurasi di cart atau formulir. Pesan konfirmasi singkat bahwa item ditambahkan. |
| Error dan edge cases | Produk dinonaktifkan sebelum aksi: pesan produk tidak lagi tersedia dan tidak ada data yang dikirim. Konfigurasi tidak valid saat diperiksa ulang: pesan dan pelanggan kembali ke halaman detail. Gagal menyerahkan data ke PRD-03: pesan error dan konfigurasi tidak hilang dari halaman. |
| Dependencies | PRD-03 (menerima paket data); PRD-04 (data produk dan opsi). |
| Priority | Must |
| Acceptance criteria | Given konfigurasi valid dengan estimasi, When pelanggan menekan tambah ke cart, Then cart memuat produk, opsi, jumlah, dan estimasi yang sama persis dengan yang dipilih. Given produk bermodel request quotation, When pelanggan menekan minta penawaran, Then formulir custom request terisi produk dan opsi tanpa angka harga. |
## I. Business rules domain


| **Rule ID** | **Aturan bisnis** | **Kondisi / rumus / batasan** | **Dampak jika tidak terpenuhi** |
| :---: | :---: | :---: | :---: |
| CAT-BR-001 | Hanya produk aktif yang tampil di halaman publik. | Produk berstatus draft atau nonaktif disembunyikan dari katalog, pencarian, dan route detail. | Produk yang belum siap bisa terlihat pelanggan. Sistem menampilkan PAGE-CAT-005 untuk route produk nonaktif. |
| CAT-BR-002 | Setiap produk memiliki tepat satu model harga: harga tetap, harga mulai dari, kalkulator estimasi, atau request quotation. | Model harga ditetapkan admin di PRD-04. Tidak semua produk wajib berkalkulator. | Tampilan harga tidak konsisten. Bila model tidak terisi, sistem memperlakukan produk sebagai request quotation dan tidak menampilkan angka. |
| CAT-BR-003 | Hasil kalkulator adalah estimasi, bukan harga final yang mengikat. | Setiap angka dari kalkulator diberi label Estimasi dan catatan bahwa admin dapat mengonfirmasi harga final. | Pelanggan bisa mengira harga sudah pasti. Sistem tidak boleh menampilkan angka tanpa label. |
| CAT-BR-004 | Rumus, tarif, minimum order, diskon kuantitas, dan biaya tambahan dapat berbeda per produk dan dibaca dari konfigurasi PRD-04. | Bentuk perhitungan yang diusulkan: harga per pcs sama dengan tarif dasar ditambah tarif tambahan tiap opsi terpilih, dikurangi diskon sesuai rentang kuantitas. Total sama dengan harga per pcs dikali jumlah, ditambah biaya tambahan tetap. Bila total di bawah minimum order, dipakai nilai minimum order. Nilai nyata menunggu validasi pemilik usaha. | Angka salah atau tidak konsisten. Bila aturan harga tidak ada, sistem tidak menampilkan angka. |
| CAT-BR-005 | Kombinasi opsi harus valid. | Kombinasi yang tidak didukung ditandai di konfigurasi produk (misalnya ukuran tertentu tidak tersedia pada bahan tertentu). Jumlah harus bilangan bulat positif dan memenuhi minimum order. | Pesanan dengan spesifikasi yang tidak bisa diproduksi. Sistem menolak melanjutkan dan menampilkan pesan. |
| CAT-BR-006 | Konfigurasi yang diteruskan membawa snapshot nama produk dan spesifikasi. | Snapshot dibuat saat konfigurasi diteruskan ke PRD-03. Perubahan katalog sesudahnya tidak mengubah item yang sudah dikirim. | Pesanan lama berubah mengikuti katalog baru. Snapshot wajib ada sebelum data diserahkan. |
| CAT-BR-007 | Katalog tidak menampilkan stok dan tidak mengelola persediaan. | Tidak ada indikator stok, peringatan stok minimum, atau reservasi stok pada versi awal. | Pelanggan mengira ada jaminan ketersediaan. Informasi ketersediaan dikonfirmasi admin saat memproses pesanan. |
| CAT-BR-008 | Katalog dan kalkulator dapat diakses tanpa login. | Tidak ada data pelanggan yang diminta untuk melihat produk atau menghitung estimasi. | Hambatan akses yang tidak perlu. |
 
## J. Data requirements


| **Entitas / data** | **Field / atribut** | **Wajib?** | **Validasi / format** | **Sumber / relasi** |
| :---: | :---: | :---: | :---: | :---: |
| Category | id, nama, slug, deskripsi singkat, status aktif, urutan tampil | Ya (id, nama, slug, status) | Nama teks tidak kosong; slug unik, huruf kecil dan tanda hubung; status aktif atau nonaktif | Milik PRD-04; satu kategori memiliki banyak produk |
| Product | id, id kategori, nama, slug, deskripsi, spesifikasi, model harga, status publikasi | Ya (id, kategori, nama, slug, model harga, status) | Nama teks tidak kosong; slug unik; model harga salah satu dari empat nilai; status draft, aktif, atau nonaktif | Milik PRD-04; milik satu kategori; memiliki banyak gambar dan grup opsi |
| Product Image | id, id produk, lokasi file, teks alternatif, urutan, penanda gambar utama | Ya (id produk, lokasi file) | Tipe dan ukuran file dibatasi; satu gambar utama per produk | Milik PRD-04; milik satu produk |
| Option Group | id, id produk, nama grup (ukuran, bahan, ketebalan, finishing, aksesori), wajib dipilih, urutan | Ya (id produk, nama) | Nama unik dalam satu produk | Milik PRD-04; milik satu produk; memiliki banyak nilai opsi |
| Option Value | id, id grup, label, status aktif, daftar kombinasi yang dilarang | Ya (id grup, label) | Label tidak kosong; unik dalam satu grup | Milik PRD-04; milik satu grup opsi |
| Pricing Rule | id, id produk, tarif dasar, tarif tambahan per nilai opsi, jumlah minimum, rentang kuantitas dengan tarif atau diskon, biaya tambahan, versi konfigurasi | Ya untuk produk berkalkulator | Angka tidak negatif; rentang kuantitas tidak tumpang tindih | Milik PRD-04; satu aturan aktif per produk berkalkulator |
| Configuration Payload | id produk, snapshot nama produk, pasangan nama opsi dan nilai, jumlah, estimasi per pcs, estimasi total, model harga | Ya (produk, opsi, jumlah) | Jumlah bilangan bulat positif; estimasi opsional dan berstatus estimasi | Tidak disimpan permanen oleh CAT; diserahkan ke PRD-03 |
## K. Validation, error handling, dan edge cases


| **Case ID** | **Kondisi / skenario** | **Validasi atau respons sistem yang diharapkan** | **Severity** |
| :---: | :---: | :---: | :---: |
| CAT-EC-001 | Jumlah kosong, nol, negatif, atau bukan angka | Pesan validasi di dekat kolom jumlah; total tidak ditampilkan; tombol lanjut nonaktif. | High |
| CAT-EC-002 | Jumlah di bawah minimum order | Pesan yang menyebut batas minimum; perhitungan tidak dilanjutkan. | High |
| CAT-EC-003 | Kombinasi opsi tidak didukung | Pesan yang menjelaskan kombinasi tidak tersedia; pilihan yang bertentangan ditandai; tombol lanjut nonaktif. | High |
| CAT-EC-004 | Opsi wajib belum dipilih | Angka belum ditampilkan; sistem menunjukkan grup opsi yang belum diisi. | Med |
| CAT-EC-005 | Pencarian atau filter tidak menghasilkan produk | Kondisi kosong dengan saran mengubah kata kunci atau menampilkan semua kategori. | Low |
| CAT-EC-006 | Produk tidak ditemukan, draft, atau nonaktif | Tampil PAGE-CAT-005 dengan tautan kembali ke katalog. Status HTTP yang sesuai dikembalikan. | Med |
| CAT-EC-007 | Gagal memuat data (jaringan atau server) atau gambar tidak termuat | Pesan error yang dapat dimengerti dengan tombol coba lagi. Gambar yang gagal diganti gambar pengganti. Kalkulator tidak menampilkan angka bila tarif gagal dimuat. | High |
| CAT-EC-008 | Aturan harga belum diatur atau produk berubah saat pelanggan berada di halaman | Sistem tidak menampilkan angka dan menyarankan minta penawaran. Pada saat penerusan, data diperiksa ulang; produk yang sudah nonaktif tidak diteruskan dan pelanggan diberi tahu. | High |
| CAT-EC-009 | Input pencarian mengandung karakter berbahaya atau URL dimanipulasi | Input dibersihkan di sisi server; parameter tidak valid diabaikan tanpa membocorkan detail teknis. | High |
##  L. Acceptance criteria dan test scenarios


| **Test ID** | **Requirement terkait** | **Given (kondisi awal)** | **When (aksi)** | **Then (hasil yang diharapkan)** | **Status** |
| :---: | :---: | :---: | :---: | :---: | :---: |
| CAT-AT-001 | CAT-FR-001, CAT-FR-002 | Ada kategori dan produk aktif | Pengunjung membuka katalog | Kategori dan produk aktif tampil, produk draft dan nonaktif tidak tampil | Belum diuji |
| CAT-AT-002 | CAT-FR-003 | Ada produk aktif dengan nama tertentu | Pengunjung mencari nama tersebut dan memilih filter kategori | Produk yang sesuai tampil dan hasil tersaring benar | Belum diuji |
| CAT-AT-003 | CAT-FR-004, CAT-FR-011 | Produk aktif memiliki beberapa foto dan spesifikasi | Pengunjung membuka halaman detail | Nama, deskripsi, spesifikasi, foto, dan informasi harga tampil sesuai data admin | Belum diuji |
| CAT-AT-004 | CAT-FR-005, CAT-FR-006 | Produk memiliki grup opsi dan satu kombinasi yang dilarang | Pengunjung memilih kombinasi yang dilarang | Sistem menampilkan pesan dan tidak mengizinkan lanjut | Belum diuji |
| CAT-AT-005 | CAT-FR-007 | Empat produk dengan model harga berbeda | Pengunjung membuka detail masing-masing | Tampilan harga sesuai model: harga pasti, harga awal, kalkulator, atau ajakan minta penawaran | Belum diuji |
| CAT-AT-006 | CAT-FR-008 | Produk berkalkulator dengan tarif terisi | Pelanggan memilih konfigurasi valid dan mengisi jumlah | Harga per pcs dan total sesuai perhitungan manual yang disepakati, berlabel Estimasi | Belum diuji |
| CAT-AT-007 | CAT-FR-009 | Produk dengan minimum order, dua rentang kuantitas, dan biaya tambahan | Pelanggan menghitung pada jumlah tepat di batas minimum dan batas rentang | Aturan minimum, diskon, dan biaya tambahan diterapkan benar pada nilai batas | Belum diuji |
| CAT-AT-008 | CAT-FR-008 | Produk berkalkulator tanpa aturan harga untuk satu konfigurasi | Pelanggan memilih konfigurasi tersebut | Tidak ada angka; sistem menyarankan minta penawaran | Belum diuji |
| CAT-AT-009 | CAT-FR-010 | Konfigurasi valid dengan estimasi | Pelanggan menekan tambah ke cart | Cart di PRD-03 memuat produk, opsi, jumlah, dan estimasi yang sama | Belum diuji |
| CAT-AT-010 | CAT-FR-010 | Produk bermodel request quotation | Pelanggan menekan minta penawaran | Formulir custom request terisi produk dan opsi tanpa harga | Belum diuji |
| CAT-AT-011 | CAT-FR-012 | Data gagal dimuat atau produk tidak ada | Pengunjung membuka halaman terkait | Tampil kondisi error atau PAGE-CAT-005 sesuai kasus | Belum diuji |
| CAT-AT-012 | CAT-FR-001 sampai CAT-FR-012 | Halaman katalog dan detail tersedia | Dibuka di desktop dan mobile pada browser uji | Tata letak terbaca dan semua fungsi dapat dipakai | Belum diuji |
## M. Non-functional requirements domain


| **Kategori** | **Kebutuhan khusus domain** | **Ukuran / target uji** |
| :---: | :---: | :---: |
| Usability | Pelanggan dapat memilih konfigurasi dan memahami hasil estimasi tanpa instruksi tambahan. Label opsi jelas, pesan error menyebut penyebab dan cara memperbaiki, label Estimasi selalu terlihat. | Uji dengan beberapa calon pengguna; target persentase keberhasilan ditentukan tim (TBD) |
| Performance | Halaman katalog, halaman detail, dan hasil kalkulator tampil dalam waktu yang wajar. Perhitungan ulang saat opsi diubah terasa langsung. | Target detik dan kondisi uji ditentukan tim (TBD) |
| Security / privacy | Input pencarian dan jumlah divalidasi di sisi server. Perhitungan yang diteruskan ke PRD-03 dihitung ulang di server. Tidak ada data pelanggan yang diproses di domain ini. Data tarif tidak boleh ditulis dari sisi publik. | Uji input berbahaya dan manipulasi parameter; hasil aman |
| Compatibility / accessibility | Berjalan baik di desktop dan mobile pada browser modern yang disepakati. Gambar memiliki teks alternatif, kontras warna cukup, dan opsi dapat dipakai dengan keyboard. | Daftar browser dan ukuran layar uji ditentukan tim (TBD) |
## N. Dependencies dan integrasi lintas domain


| **Domain terkait** | **Data / fungsi yang dibutuhkan** | **Data / fungsi yang diberikan** | **Kesepakatan interface** |
| :---: | :---: | :---: | :---: |
| PRD-04 | Data master produk, kategori, gambar, grup dan nilai opsi, model harga, dan aturan tarif; status publikasi | Kebutuhan tampilan dan struktur data yang diperlukan katalog dan kalkulator | Data dibaca oleh CAT dari sumber yang sama dengan yang diubah admin, sehingga perubahan di dashboard langsung tampil (INT-01, INT-02). Format field disepakati bersama. |
| PRD-03 | Menerima konfigurasi untuk cart, checkout, dan custom request | Paket data konfigurasi: produk, snapshot nama dan spesifikasi, opsi terpilih, jumlah, estimasi bila ada, model harga | Paket diserahkan saat pelanggan menekan tombol aksi. Format disepakati dengan anggota 3 (INT-03). |
| PRD-01 | Gaya visual, navigasi, dan komponen umum website publik | Tautan dari homepage ke katalog dan kategori | Navigasi dan identitas visual konsisten pada seluruh halaman publik. |
| PRD-05 | Tidak ada pertukaran langsung | Tidak ada. Data transaksi sampai ke PRD-05 melalui PRD-03. | Tidak ada. |
# 17. ORD — CUSTOMER ORDERING & ONLINE PURCHASE SYSTEM
Penanggung jawab: Anggota 3. Nama anggota: Raisyah Ramadhani Majid. Status: Selesai.
## A. Tujuan domain dan ringkasan solusi
Memungkinkan pengunjung mengirim pembelian langsung untuk produk yang siap dipesan serta mengirim custom order request untuk kebutuhan yang memerlukan penawaran. Pengalaman dibuat tanpa kewajiban login pelanggan. Domain ini mengelola pengumpulan data pesanan dan pengiriman data ke sistem internal; penanganan administrasi transaksi setelah diterima dimiliki PRD-05.

*Catatan khusus domain: Pelanggan tidak wajib login. Tidak tersedia halaman atau endpoint publik untuk mencari status pesanan, termasuk melalui kode pesanan atau nomor WhatsApp. Status Siap Diambil dikelola Admin atau Owner pada dashboard FIN; Admin menghubungi pelanggan melalui WhatsApp secara manual. Pemenuhan pesanan hanya melalui pickup di lokasi usaha.*

**Masalah pengguna.** Saat ini pelanggan memesan lewat chat WhatsApp atau Instagram. Spesifikasi, jumlah, dan file desain dikirim terpisah dan mudah tercecer, sehingga admin harus menanyakan ulang detail yang sama. Pelanggan juga tidak memiliki ringkasan resmi atas apa yang sudah dipesan, berapa perkiraan biayanya, dan langkah pembayaran berikutnya. Untuk kebutuhan custom, pelanggan tidak tahu informasi apa yang harus disiapkan agar admin bisa memberi penawaran.

**Solusi domain. Domain ORD menyediakan dua jalur pemesanan tanpa akun. Jalur pertama adalah direct purchase: produk yang telah dikonfigurasi di PRD-02 masuk ke keranjang, pelanggan mengisi data kontak dan memeriksa ringkasan yang hanya memuat estimasi serta informasi pickup, lalu mengirim pesanan. Jalur kedua adalah custom order request: pelanggan mengisi kebutuhan, jumlah, tenggat, dan mengunggah file referensi melalui formulir terstruktur. Setiap kiriman diberi kode unik dan menyimpan snapshot item. Pelanggan menerima halaman konfirmasi berupa kode pesanan dan ringkasan pengajuan; kode tersebut berfungsi sebagai bukti pengajuan/pengambilan, bukan sebagai alat untuk memantau status. Instruksi QRIS baru dikirim Admin melalui WhatsApp setelah harga final dan nominal pembayaran disepakati melalui FIN.**

**Nilai bagi produk.** Mendukung OBJ-02 dan sebagian OBJ-04 pada Master PRD. Admin menerima pesanan dengan data yang lengkap dan seragam, sehingga konfirmasi lebih cepat. Pelanggan mendapat bukti pesanan yang jelas tanpa harus membuat akun. Request yang belum disetujui tidak tercampur dengan transaksi final, sehingga laporan PRD-05 tetap akurat.

**Hasil akhir yang diharapkan.** Setelah domain ini selesai:

1. Pelanggan dapat menambahkan produk hasil konfigurasi PRD-02 ke keranjang, mengubah jumlah, dan menghapus item tanpa login, dengan detail konfigurasi yang tidak hilang (INT-03).

2. Pelanggan dapat menyelesaikan guest checkout dengan data minimum (nama dan nomor WhatsApp), melihat informasi pengambilan langsung, dan menyetujui ringkasan sebelum mengirim.

3. Pelanggan dapat mengirim custom order request beserta file referensi yang tervalidasi jenis dan ukurannya.

4. Setiap kiriman tersimpan dengan kode unik, snapshot item, dan status awal yang benar, lalu tampil di dashboard PRD-05 dengan identitas dan item yang sama (INT-04).

5. Harga dihitung ulang di sisi server saat pesanan dikirim dan selalu diberi label estimasi bila masih perlu konfirmasi admin.

6. Pelanggan menerima bukti penerimaan berupa kode pesanan dan ringkasan. Status pengerjaan disampaikan Admin secara manual melalui WhatsApp bila perlu; tidak ada halaman cek status pelanggan.

7. Seluruh halaman dapat dipakai dengan baik di desktop dan mobile.
## B. Tujuan, scope, dan batasan domain


| **Aspek** | **Isi** |
| --- | --- |
| Tujuan domain | Membuat pelanggan mampu mengirim direct purchase atau custom order request tanpa akun, dengan data pesanan lengkap, tervalidasi, tersimpan sebagai snapshot, dan diteruskan ke sistem internal FIN. |
| In Scope | Keranjang tanpa login; guest checkout; informasi pickup langsung; ringkasan pesanan tanpa instruksi pembayaran; validasi dan penghitungan ulang estimasi harga di server; formulir custom request; unggah file desain/referensi; kode pesanan unik; penyimpanan order/request dan snapshot; halaman konfirmasi penerimaan; penyerahan data ke FIN; tautan WhatsApp untuk tindak lanjut; notifikasi email bila digunakan; pencegahan kiriman ganda dan spam. |
| Out of Scope | Master produk/kalkulator (PRD-02); pengelolaan produk, tarif, profil usaha dan konten (PRD-04); peninjauan request, penetapan harga final, konversi request, instruksi QRIS, verifikasi/pencatatan pembayaran, invoice, dan laporan (PRD-05); halaman publik/endpoint pencarian status pesanan; tracking dan notifikasi status otomatis; pengiriman, alamat pengiriman, ongkir, kurir, dan integrasi ekspedisi; payment gateway; akun pelanggan; stok real-time. |
| Asumsi | (1) PRD-04 menyediakan data produk, penanda direct purchase, informasi profil usaha dan lokasi/jam pickup, serta nomor WhatsApp usaha. (2) FIN menyimpan order/request, meninjau pesanan, menetapkan total final, mengelola konfigurasi QRIS, pembayaran, dan status internal. (3) Pembayaran versi pertama hanya QRIS, dilakukan di luar sistem dan diverifikasi manual. (4) Instruksi QRIS dikirim setelah harga final dan nominal DP/pelunasan disepakati. (5) Tidak ada persentase atau nominal minimum DP global; nilai DP disepakati per pesanan. (6) Pemenuhan hanya melalui pengambilan langsung. (7) Mata uang Rupiah tanpa desimal; batas file mengikuti ketentuan yang disepakati. |
| Stakeholder / aktor | Visitor dan Customer Guest sebagai pengguna langsung; Admin sebagai penerima serta penindak lanjut pesanan melalui FIN; Owner sebagai pemilik role dan pengelola konfigurasi pembayaran/akses khusus; PRD-04 sebagai penyedia master produk dan informasi profil/pickup; FIN sebagai pemilik order, pembayaran, refund, status internal, dan invoice. |
 
## C. Daftar subfitur yang wajib dianalisis


| **Subfitur awal** | **Spesifikasi** | **Prioritas** |
| --- | --- | --- |
| Shopping cart (bila digunakan) | Keranjang dipakai karena pelanggan biasanya memesan beberapa produk sekaligus (misalnya sticker dan acrylic keychain). Menerima paket konfigurasi dari PRD-02, menampilkan setiap item dengan nama, ringkasan opsi, jumlah, harga per pcs, dan subtotal. Pelanggan dapat mengubah jumlah, menghapus item, atau kembali ke detail produk untuk mengganti opsi. Keranjang disimpan per sesi peramban tanpa login, berlaku 7 hari (usulan). Keluaran: daftar item valid dengan total estimasi. | Must |
| Guest checkout dan data pelanggan | Formulir data minimum: nama lengkap dan nomor WhatsApp (wajib), email (opsional, untuk notifikasi), catatan pesanan (opsional). Nomor dinormalkan ke format 62xxxxxxxxxx. Tidak ada pembuatan akun dan tidak ada password. Keluaran: data pelanggan tervalidasi yang dikirim bersama order. | Must |
| Ringkasan order dan konfirmasi | Sebelum mengirim, pelanggan melihat seluruh item/snapshot opsi, jumlah, harga atau total estimasi, data kontak, informasi pickup, serta persetujuan bahwa harga final akan diperiksa Admin. Ringkasan dan konfirmasi awal tidak menampilkan QRIS, rekening, atau instruksi pembayaran. Setelah kirim, tampil halaman konfirmasi dengan kode pesanan sebagai bukti pengajuan. | Must |
| Direct purchase untuk produk yang memenuhi syarat | Hanya produk aktif yang ditandai boleh dibeli langsung oleh Admin di PRD-04 dan bermodel harga tetap, harga mulai dari, atau kalkulator estimasi yang dapat masuk keranjang. Produk request quotation diarahkan ke custom request. Order direct yang dikirim dimulai dengan status pengerjaan Menunggu Konfirmasi dan status pembayaran Belum Dibayar. | Must |
| Custom order/request quotation form | Formulir berisi produk/kategori (opsional, terisi otomatis bila datang dari PRD-02), deskripsi kebutuhan, perkiraan jumlah, spesifikasi yang diinginkan, tenggat yang diharapkan, anggaran (opsional), dan data kontak. Disimpan sebagai request berstatus Baru diterima, bukan transaksi final. Keluaran: request dengan kode unik di PRD-05. | Must |
| Upload desain/referensi dan validasi file | Pelanggan dapat mengunggah file desain/referensi pada custom request, dan opsional per item pada checkout bila produk memerlukan file desain. Batas usulan: JPG, PNG, PDF; maksimal 10 MB per file; maksimal 5 file. Validasi di sisi klien dan server (ekstensi dan tipe isi file), nama file diganti acak, disimpan di luar folder publik, tidak dapat dieksekusi. Keluaran: lampiran tertaut ke request/item. | Must |
| Informasi pengambilan dan tindak lanjut pembayaran | Pemenuhan pesanan hanya melalui pickup di lokasi usaha. Sistem menampilkan lokasi/jam pengambilan dari profil PRD-04; tidak ada pilihan kirim, alamat pengiriman, ongkir, atau ekspedisi. Instruksi QRIS tidak ditampilkan saat checkout atau konfirmasi awal. Setelah FIN menetapkan total final dan menyepakati nominal pembayaran, Admin mengirim QRIS manual melalui WhatsApp. | Must |
| Order submission dan notifikasi penerimaan | Sistem memvalidasi ulang data dan harga, membuat kode unik, menyimpan order/request serta snapshot secara atomik, lalu menampilkan halaman konfirmasi. Konfirmasi hanya menampilkan kode, ringkasan, status awal, informasi pickup, dan pemberitahuan bahwa Admin akan menghubungi pelanggan. Tidak ada instruksi QRIS pada tahap ini. | Must (simpan dan halaman konfirmasi); Should (email dan tombol WhatsApp) |
| Pencegahan kiriman ganda dan spam (subfitur tambahan) | Token sekali pakai pada formulir, tombol kirim dinonaktifkan saat proses, batas jumlah kiriman per alamat IP, dan field jebakan (honeypot) atau captcha. Alasan penambahan: form publik tanpa login rawan spam dan duplikasi order (NFR-005 data integrity). | Must (anti-ganda); Should (anti-spam) |
| Penyerahan data ke FIN (subfitur tambahan) | Kontrak data order/request memuat pelanggan, jenis dan kanal pesanan, kode pesanan, item snapshot, estimasi, lampiran, catatan, serta informasi pickup. FIN mengelola harga final, status internal, pembayaran, refund, dan invoice. ORD tidak membaca konfigurasi QRIS untuk checkout. | Must |
 
## D. User roles dan permission dalam domain


| **Aktor / role** | **Melihat** | **Membuat** | **Mengubah** | **Menghapus / tindakan khusus** |
| --- | --- | --- | --- | --- |
| Visitor | Ya: halaman keranjang kosong, formulir custom request, dan halaman publik. | Tidak (menjadi Customer Guest ketika mulai mengisi pemesanan). | Tidak | Tidak memiliki akses ke dashboard, data pesanan, atau status pelanggan lain. |
| Customer Guest | Ya: keranjang sendiri, ringkasan pesanan, formulir custom request, dan halaman konfirmasi dari pengajuan pada sesi sendiri. | Ya: item keranjang, direct purchase, custom request, dan lampiran. | Ya: jumlah/item keranjang dan data formulir sebelum dikirim. Setelah dikirim, perubahan melalui Admin. | Dapat menghapus item/file yang belum dikirim. Kode pesanan berfungsi sebagai bukti pengajuan/pengambilan, bukan untuk mencari status. |
| Admin | Ya: order/request dan status internal melalui dashboard FIN. | Tidak di ORD; transaksi manual dibuat melalui FIN. | Tidak di ORD; perubahan status dan data transaksi dilakukan di FIN. | Menindaklanjuti pelanggan dan memberi tahu status Siap Diambil melalui WhatsApp manual. Tidak mengubah konfigurasi QRIS. |
| Owner | Ya: seluruh order/request serta status internal melalui FIN. | Tidak di ORD; transaksi manual dibuat melalui FIN. | Tidak di ORD; perubahan status dan data transaksi dilakukan di FIN. | Memiliki hak penuh di FIN, termasuk tindakan khusus yang hanya diperbolehkan bagi Owner. |
| Sistem | Membaca produk, tarif, dan informasi pickup dari PRD-04. | Membuat kode pesanan, snapshot item, data pelanggan, dan order/request di FIN. | Menghitung ulang harga dan menormalisasi nomor kontak. | Menghapus keranjang/file sementara kedaluwarsa sesuai aturan; submission token hanya untuk anti-duplikasi. |
 
## E. Page / screen specifications
Seluruh halaman dapat diakses tanpa login, responsif untuk desktop dan mobile, dan memiliki kondisi loading, kosong, dan error.

| **Page ID / nama** | **Route / akses** | **Tujuan dan isi utama** | **Aksi / interaksi utama** |
| --- | --- | --- | --- |
| PAGE-ORD-001 Keranjang | /keranjang; visitor, customer guest | Menampilkan daftar item (gambar, nama snapshot, ringkasan opsi, harga per pcs berlabel Estimasi bila perlu, jumlah, subtotal), total estimasi, informasi pickup, dan tombol checkout. Tidak menampilkan ongkir. Kondisi kosong dan penanda item tidak tersedia tetap ditangani. | Ubah jumlah, hapus item, ubah opsi kembali ke detail produk, lanjut checkout. |
| PAGE-ORD-002 Checkout: data pelanggan dan penerimaan | /checkout; customer guest dengan keranjang tidak kosong | Mengumpulkan nama, nomor WhatsApp, email opsional, dan catatan pesanan. Informasi penerimaan ditampilkan sebagai pengambilan langsung di lokasi usaha; pelanggan tidak memilih pengiriman dan tidak mengisi alamat pengiriman. Alamat/lokasi pickup diambil dari profil usaha PRD-04. | Isi form, unggah file per item bila diperlukan, lanjut ke ringkasan. |
| PAGE-ORD-003 Ringkasan dan persetujuan pesanan | /checkout/ringkasan; customer guest yang telah mengisi PAGE-ORD-002 | Pemeriksaan terakhir sebelum kirim. Isi: item/snapshot opsi, jumlah, harga dan total estimasi, data kontak, informasi pickup, serta kotak persetujuan bahwa Admin akan mengonfirmasi harga final. Tidak menampilkan rekening, QRIS, atau instruksi pembayaran. | Ubah data, centang persetujuan, kirim pesanan. |
| PAGE-ORD-004 Pesanan berhasil dikirim | /pesanan/berhasil/{kode}; hanya sesi yang mengirim pesanan | Bukti penerimaan berisi kode pesanan, tanggal, ringkasan item, total estimasi, status awal Menunggu Konfirmasi, informasi pickup, dan pemberitahuan bahwa Admin akan memeriksa pesanan serta menghubungi pelanggan melalui WhatsApp. Kode menjadi bukti pengajuan/pengambilan, bukan sarana cek status. Tidak ada instruksi QRIS. | Salin kode pesanan, buka WhatsApp untuk tindak lanjut manual, cetak/simpan halaman, kembali ke beranda. |
| PAGE-ORD-005 Formulir custom order request | /custom-order (opsional ?produk={slug} dari PRD-02); visitor, customer guest | Mengumpulkan kebutuhan untuk penawaran. Isi: produk/kategori (terisi otomatis bila datang dari PRD-02, bisa dikosongkan), deskripsi kebutuhan, perkiraan jumlah, spesifikasi (ukuran, bahan, finishing), tenggat yang diharapkan, anggaran (opsional), unggah file referensi, nama, nomor WhatsApp, email, catatan, persetujuan bahwa request belum menjadi pesanan final. | Isi form, unggah/hapus file dengan indikator progres, kirim request. |
| PAGE-ORD-006 Request berhasil dikirim | /custom-order/berhasil/{kode}; hanya sesi yang mengirim request | Bukti penerimaan request. Isi: kode request, ringkasan kebutuhan, daftar file terkirim, tampilkan kode request, ringkasan kebutuhan, daftar file terkirim, status Baru Diterima, dan pesan bahwa Admin akan memeriksa kebutuhan serta mengirim penawaran. Jangan menampilkan QRIS atau instruksi pembayaran. Instruksi pembayaran baru diberikan setelah pelanggan menyetujui penawaran dan request dikonversi menjadi order di FIN. | Salin kode, buka WhatsApp, kembali ke katalog. |
| PAGE-ORD-007 Komponen ikon keranjang dan notifikasi | Komponen di header semua halaman publik (bersama PRD-01) | Menampilkan jumlah item keranjang dan pesan singkat setelah item ditambahkan. | Klik ikon menuju PAGE-ORD-001. |
 
## F. User flow dan workflow


| **Flow ID / nama** | **Trigger & precondition** | **Main flow (langkah)** | **Alternative / exception** | **Output** |
| --- | --- | --- | --- | --- |
| FLOW-ORD-001 Menambah dan mengelola keranjang | Pelanggan menekan "Tambah ke keranjang" di PRD-02; konfigurasi valid; produk aktif dan boleh dibeli langsung. | 1. Sistem menerima paket konfigurasi dari PRD-02. 2. Sistem memeriksa ulang produk, opsi, dan jumlah. 3. Sistem menghitung ulang harga di server. 4. Item disimpan di keranjang sesi; konfigurasi yang sama persis digabung jumlahnya. 5. Ikon keranjang bertambah dan muncul notifikasi. 6. Pelanggan membuka keranjang, mengubah jumlah atau menghapus item; total dihitung ulang. | Produk bermodel request quotation: diarahkan ke FLOW-ORD-003. Produk nonaktif atau konfigurasi tidak valid: pesan dan item tidak ditambahkan. Jumlah di bawah minimum: ditolak dengan pesan batas minimum. Gagal menyimpan: pesan error, konfigurasi tetap di halaman detail. | Keranjang berisi item valid dengan total estimasi. |
| FLOW-ORD-002 Guest checkout direct purchase | Keranjang berisi minimal satu item valid. | 1. Pelanggan menekan Checkout. 2. Sistem memvalidasi item. 3. Pelanggan mengisi nama, nomor WhatsApp, email opsional, dan catatan. 4. Sistem menampilkan informasi pickup saja. 5. Pelanggan meninjau ringkasan item, estimasi, kontak, dan pickup tanpa instruksi pembayaran. 6. Pelanggan menyetujui dan mengirim. 7. Sistem membuat kode unik dan menyimpan pelanggan, order direct berstatus Menunggu Konfirmasi, status pembayaran Belum Dibayar, item snapshot, dan lampiran secara atomik ke FIN. 8. Halaman konfirmasi menampilkan kode, ringkasan, informasi pickup, dan pemberitahuan tindak lanjut Admin. Tidak ada instruksi pembayaran pada checkout. | Harga/produk berubah: sistem memperbarui estimasi dan meminta persetujuan ulang. Data tidak valid atau gagal simpan: pesan kesalahan dan tidak ada data parsial. Tombol dikirim berulang: hanya satu order tersimpan. | Order direct tersimpan di FIN; pelanggan menerima kode pengajuan dan informasi bahwa Admin akan menghubungi. |
| FLOW-ORD-003 Custom order request dengan file referensi | Pelanggan menekan "Minta penawaran" di PRD-02 atau membuka menu Custom Order. | 1. Sistem menampilkan formulir; produk dan opsi terisi bila datang dari PRD-02. 2. Pelanggan mengisi kebutuhan, jumlah, spesifikasi, tenggat, dan data kontak. 3. Pelanggan mengunggah file; sistem memvalidasi tiap file dan menampilkan progres. 4. Pelanggan mencentang persetujuan dan menekan Kirim request. 5. Sistem memvalidasi ulang, membuat kode request, menyimpan pelanggan, request, item, dan lampiran ke PRD-05 dengan status Baru diterima. 6. Sistem menampilkan PAGE-ORD-006 dan mengirim email bila diisi. | File salah format atau terlalu besar: file ditolak dengan pesan, file lain tetap. Upload gagal di tengah: pelanggan dapat mencoba ulang file tersebut. Tenggat di masa lalu: ditolak. Gagal simpan: tidak ada request parsial, file sementara tidak hilang selama sesi. | Request tersimpan di PRD-05 sebagai permintaan yang belum menjadi transaksi final. |
| FLOW-ORD-004 Tindak lanjut setelah diterima (batas dengan FIN) | Order atau request tersimpan di FIN. | 1. Admin membuka order/request di dashboard FIN. 2. Untuk direct purchase, Admin meninjau spesifikasi dan menetapkan harga final; bila berubah dari estimasi, Admin meminta persetujuan pelanggan. 3. Untuk custom request, Admin meninjau kebutuhan, membuat penawaran, dan menunggu persetujuan. 4. Setelah disetujui, custom request dikonversi menjadi order dengan status Menunggu Pembayaran. 5. Setelah total final dan nominal pembayaran disepakati, Admin mengirim instruksi QRIS secara manual melalui WhatsApp. 6. Admin memverifikasi pembayaran secara manual dan mencatatnya di FIN. 7. Setelah produksi selesai, Admin mengubah status menjadi Siap Diambil dan memberi tahu pelanggan melalui WhatsApp. 8. Setelah barang diambil, Admin mengubah status menjadi Selesai. | Pelanggan tidak merespons: request dapat ditolak/kedaluwarsa atau order ditindaklanjuti melalui kanal komunikasi sesuai kebijakan. Tidak ada halaman cek status pelanggan. | ORD menyediakan data awal; seluruh status, pembayaran, dan histori dikelola FIN. |
 
## G. Functional requirements register


| **Requirement ID** | **Nama fungsi** | **Deskripsi ringkas** | **Aktor** | **Priority** | **Status** |
| --- | --- | --- | --- | --- | --- |
| ORD-FR-001 | Tambah item ke keranjang | Menerima paket konfigurasi dari PRD-02, memvalidasi ulang, dan menyimpannya sebagai item keranjang. | Customer guest, Sistem | M | Draft |
| ORD-FR-002 | Kelola keranjang | Menampilkan item, mengubah jumlah, menghapus item, dan menghitung ulang subtotal serta total estimasi. | Customer guest | M | Draft |
| ORD-FR-003 | Penyimpanan keranjang tanpa login | Menyimpan keranjang per sesi peramban dengan masa berlaku terbatas tanpa akun. | Sistem | M | Draft |
| ORD-FR-004 | Validasi kelayakan direct purchase | Hanya mengizinkan produk aktif yang boleh dibeli langsung dan bukan request quotation masuk keranjang dan checkout. | Sistem | M | Draft |
| ORD-FR-005 | Guest checkout data pelanggan | Mengumpulkan dan memvalidasi nama, nomor WhatsApp, email opsional, dan catatan tanpa membuat akun. | Customer guest | M | Draft |
| ORD-FR-006 | Informasi Pengambilan | Menampilkan informasi pengambilan langsung di lokasi usaha dari profil PRD-04. Sistem tidak menyediakan pilihan pengiriman atau field alamat pengiriman pelanggan. | Customer Guest, Sistem | M | Draft |
| ORD-FR-007 | Ringkasan Pesanan dan Persetujuan | Menampilkan produk, spesifikasi, jumlah, harga/total estimasi, data kontak, informasi pickup, dan persetujuan. Tidak menampilkan rekening, QRIS, atau instruksi pembayaran. | Customer Guest | M | Draft |
| ORD-FR-008 | Submit Order Direct Purchase | Memvalidasi ulang data dan harga, membuat kode unik, lalu menyimpan order dengan status Menunggu Konfirmasi dan pembayaran Belum Dibayar beserta snapshot secara atomik. Tidak membuat atau menampilkan instruksi pembayaran. | Customer Guest, Sistem | M | Draft |
| ORD-FR-009 | Halaman Konfirmasi Penerimaan Pesanan | Menampilkan kode pesanan, ringkasan, status awal Menunggu Konfirmasi, informasi pickup, dan pemberitahuan bahwa Admin akan menghubungi pelanggan melalui WhatsApp. Tidak menampilkan instruksi pembayaran. | Customer Guest | M | Draft |
| ORD-FR-010 | Formulir custom order request | Mengumpulkan kebutuhan, jumlah, spesifikasi, tenggat, dan kontak untuk penawaran. | Visitor, Customer guest | M | Draft |
| ORD-FR-011 | Upload file desain/referensi | Menerima, memvalidasi, dan menyimpan file dengan aman, tertaut ke request atau item. | Customer guest, Sistem | M | Draft |
| ORD-FR-012 | Submit custom request | Menyimpan request berstatus Baru diterima dengan kode unik dan lampiran. | Customer guest, Sistem | M | Draft |
| ORD-FR-013 | Pencegahan kiriman ganda | Memastikan satu kiriman formulir hanya menghasilkan satu order/request. | Sistem | M | Draft |
| ORD-FR-014 | Penyerahan Data ke FIN | Mengirim data pelanggan, kode pesanan/request, jenis dan kanal, item snapshot, estimasi, lampiran, catatan, dan informasi pickup. FIN menetapkan harga final, mengelola status/pembayaran/refund/invoice. ORD tidak membaca konfigurasi QRIS untuk checkout. | Sistem | M | Draft |
| ORD-FR-015 | Notifikasi email penerimaan | Mengirim email ringkasan pesanan/request bila pelanggan mengisi email. | Sistem | S | Draft |
| ORD-FR-016 | Tautan WhatsApp otomatis | Membuka WhatsApp usaha dengan pesan berisi kode pesanan dan nama pelanggan. | Customer guest | S | Draft |
| ORD-FR-017 | Perlindungan spam | Membatasi kiriman per IP dan memakai honeypot atau captcha pada formulir publik. | Sistem | S | Draft |
 

Kartu spesifikasi rinci disusun untuk lima requirement yang paling kompleks: ORD-FR-002, ORD-FR-005, ORD-FR-008, ORD-FR-011, dan ORD-FR-012.
## H. Kartu spesifikasi rinci functional requirement
### **ORD-FR — Kartu Spesifikasi Functional Requirement 1**


| **Elemen** | **Isi / spesifikasi** |
| --- | --- |
| Requirement ID | ORD-FR-002 |
| Nama requirement | Kelola keranjang |
| Tujuan dan deskripsi | Memberi pelanggan tempat meninjau dan mengubah beberapa item sebelum checkout, dengan total estimasi yang selalu konsisten dengan aturan harga terkini. |
| Aktor dan hak akses | Customer guest. Tanpa login; hanya keranjang milik sesinya sendiri. |
| Precondition dan trigger | Keranjang sesi berisi minimal satu item. Pemicu: pelanggan membuka /keranjang, mengubah jumlah, atau menghapus item. |
| Input dan field | ID item keranjang (rujukan, wajib); jumlah baru (bilangan bulat positif, wajib saat ubah); aksi hapus (konfirmasi). Data item dari paket PRD-02: produk, snapshot nama dan opsi, model harga, estimasi. Aturan harga dari PRD-04. |
| Alur utama | (1) Sistem memuat item keranjang dan memeriksa status produk. (2) Sistem menghitung ulang harga tiap item di server. (3) Sistem menampilkan item, subtotal, dan total estimasi. (4) Pelanggan mengubah jumlah; sistem memvalidasi dan menghitung ulang. (5) Pelanggan menghapus item setelah konfirmasi; total diperbarui. (6) Pelanggan menekan Checkout. |
| Alur alternatif | Pelanggan memilih "Ubah opsi": dibawa ke detail produk PRD-02 dengan konfigurasi terisi; item lama diganti setelah disimpan. Keranjang kosong setelah hapus: tampil kondisi kosong dengan tautan katalog. |
| Validasi dan business rules | Subtotal item: harga tetap = harga × jumlah; kalkulator = total estimasi hasil hitung ulang PRD-02 (sudah memuat tingkat kuantitas, biaya tambahan, dan minimum order); harga mulai dari = harga awal × jumlah berlabel "mulai dari". Total estimasi = jumlah subtotal seluruh item. Pemenuhan pesanan hanya melalui pengambilan langsung. Jumlah harus memenuhi minimum order produk. Maksimal 20 baris item per keranjang (usulan). Angka yang tampil dihitung di server, bukan di tampilan saja. |
| Output dan feedback | Daftar item, subtotal, total estimasi berlabel Estimasi, catatan konfirmasi admin, pesan singkat setelah perubahan. |
| Error dan edge cases | Jumlah nol, negatif, desimal, atau bukan angka: pesan validasi dan jumlah lama dipertahankan. Di bawah minimum order: pesan batas minimum. Produk dinonaktifkan: item ditandai "tidak tersedia" dan checkout dinonaktifkan sampai item dihapus. Tarif berubah: item ditandai "harga diperbarui" dengan nilai lama dan baru. Sesi kedaluwarsa: keranjang kosong dengan pesan. |
| Dependencies | ORD-FR-001, ORD-FR-003; PRD-02 (perhitungan estimasi, CAT-FR-008); PRD-04 (status produk dan tarif). |
| Priority | Must |
| Acceptance criteria | Given keranjang berisi 2 item, When pelanggan mengubah jumlah item pertama menjadi nilai valid, Then subtotal dan total estimasi diperbarui sesuai aturan harga. Given produk di keranjang dinonaktifkan admin, When pelanggan membuka keranjang, Then item ditandai tidak tersedia dan tombol checkout nonaktif. |
 
### **ORD-FR — Kartu Spesifikasi Functional Requirement 2**


| **Elemen** | **Isi / spesifikasi** |
| --- | --- |
| Requirement ID | ORD-FR-005 |
| Nama requirement | Guest checkout data pelanggan |
| Tujuan dan deskripsi | Mengumpulkan data secukupnya agar admin dapat menghubungi dan memproses pesanan, tanpa memaksa pelanggan membuat akun (Master PRD 9.1). |
| Aktor dan hak akses | Customer guest. Tanpa login. |
| Precondition dan trigger | Keranjang berisi item valid. Pemicu: pelanggan menekan Checkout. |
| Input dan field | Nama lengkap (teks, wajib, 2–100 karakter); nomor WhatsApp (teks angka, wajib, 10–15 digit); email (format email, opsional); catatan (teks, opsional, maksimal 500 karakter). Informasi lokasi dan jam pengambilan langsung ditampilkan dari profil usaha PRD-04. Tidak ada pilihan metode pengiriman maupun field alamat, kota, atau kode pos pelanggan. |
| Alur utama | (1) Sistem menampilkan formulir dan ringkasan keranjang. (2) Pelanggan mengisi data. (3) Sistem memvalidasi per kolom saat diketik dan saat lanjut. (4) Sistem menormalkan nomor WhatsApp ke format 62xxxxxxxxxx dan merapikan spasi. (5) Data disimpan sementara di sesi dan pelanggan dibawa ke ringkasan. |
| Alur alternatif | Pelanggan kembali ke keranjang: data yang sudah diisi tetap tersimpan di sesi. Informasi pengambilan langsung tetap ditampilkan dari profil usaha PRD-04. |
| Validasi dan business rules | Validasi di klien dan wajib diulang di server. Nomor 08xx, +628xx, dan 628xx diperlakukan sama. Tidak ada password dan tidak ada akun yang dibuat. Pencocokan dengan pelanggan lama dilakukan PRD-05 berdasarkan nomor yang sudah dinormalkan; pelanggan tidak melihat data pelanggan lain. Input dibersihkan dari kode berbahaya. |
| Output dan feedback | Formulir tervalidasi, pesan error di bawah kolom yang salah, lanjut ke PAGE-ORD-003. |
| Error dan edge cases | Nama atau nomor WhatsApp kosong: pesan per kolom. Nomor berisi huruf atau terlalu pendek: pesan format. Email salah format: pesan, tetapi boleh dikosongkan. Sesi habis saat mengisi: pesan dan pelanggan diminta mengulang dari keranjang. |
| Dependencies | ORD-FR-006; PRD-04 (alamat pengambilan, pengaturan usaha); PRD-05 (entitas Customer dan aturan FIN-BR-012). |
| Priority | Must |
| Acceptance criteria | Given pelanggan mengisi nama dan nomor 0812-3456-7890, When lanjut ke ringkasan, Then nomor tersimpan sebagai 6281234567890 tanpa akun dibuat. Given checkout dibuka, When informasi pengambilan ditampilkan, Then lokasi dan jam pengambilan berasal dari profil PRD-04 dan pelanggan tidak diminta mengisi alamat pengiriman. |
 
### **ORD-FR — Kartu Spesifikasi Functional Requirement 3**


| **Elemen** | **Isi / spesifikasi** |
| --- | --- |
| Requirement ID | ORD-FR-008 |
| Nama requirement | Submit order direct purchase |
| Tujuan dan deskripsi | Mengubah keranjang yang sudah disetujui pelanggan menjadi order resmi di sistem internal, dengan harga dan spesifikasi tersimpan sebagai snapshot dan tanpa duplikasi. |
| Aktor dan hak akses | Customer guest (pemicu) dan Sistem. |
| Precondition dan trigger | Keranjang valid, data pelanggan lengkap, informasi pickup tersedia dari profil usaha PRD-04, dan kotak persetujuan dicentang. Pemicu: pelanggan menekan Kirim pesanan. |
| Input dan field | Item keranjang (produk, snapshot nama, opsi, jumlah, model harga); data pelanggan; informasi pickup yang dibaca dari profil PRD-04; catatan; lampiran per item (opsional); token formulir sekali pakai (wajib); persetujuan (boolean, wajib true). Tidak ada pilihan metode pengiriman atau alamat pengiriman pelanggan. |
| Alur utama | (1) Sistem memeriksa token agar tidak diproses dua kali. (2) Sistem memvalidasi ulang data pelanggan dan setiap item (produk aktif, boleh dibeli langsung, opsi valid, jumlah ≥ minimum). (3) Sistem menghitung ulang harga di server. (4) Bila harga sama dengan yang dilihat pelanggan, sistem membuat kode pesanan unik. (5) Dalam satu transaksi database, sistem menyimpan atau mencocokkan pelanggan, membuat order jenis direct dengan kanal Website, status pengerjaan Menunggu konfirmasi, status pembayaran Belum dibayar, menyimpan item dengan snapshot dan lampiran. (6) Sistem mengosongkan keranjang dan menampilkan PAGE-ORD-004. (7) Sistem mengirim email bila diisi (di luar transaksi database). |
| Alur alternatif | Harga hasil hitung ulang berbeda dari yang ditampilkan: order tidak disimpan, pelanggan dikembalikan ke ringkasan dengan penanda harga baru dan diminta menyetujui ulang. Ada item tidak lagi tersedia: pelanggan diminta menghapusnya terlebih dahulu. |
| Validasi dan business rules | Total estimasi order = jumlah subtotal item (ORD-BR-004). Semua angka berlabel estimasi sampai dikonfirmasi Admin di PRD-05. Informasi pengambilan langsung mengikuti profil usaha PRD-04. Snapshot tidak berubah walau katalog berubah kemudian. Penyimpanan atomik: semua tersimpan atau tidak sama sekali. Satu token hanya menghasilkan satu order. |
| Output dan feedback | Order tersimpan di FIN; halaman konfirmasi menampilkan kode pesanan sebagai bukti pengajuan/pengambilan, ringkasan pesanan, status Menunggu Konfirmasi, informasi pickup, dan pemberitahuan bahwa Admin akan menghubungi pelanggan melalui WhatsApp. Tidak ada instruksi pembayaran pada tahap ini. Instruksi QRIS diberikan secara manual oleh Admin setelah total final dan nominal pembayaran disepakati melalui FIN. |
| Error dan edge cases | Tombol ditekan dua kali atau halaman dimuat ulang: kiriman kedua ditolak dan pelanggan diarahkan ke halaman konfirmasi yang sama. Gagal database atau jaringan: tidak ada order parsial, pesan "Pesanan belum terkirim, silakan coba lagi", data form dipertahankan. Email gagal: order tetap tersimpan dan kegagalan dicatat di log. Kode pesanan bentrok: dibuat ulang otomatis karena kode unik di tingkat database. |
| Dependencies | ORD-FR-002, ORD-FR-004 s.d. ORD-FR-007, ORD-FR-013, ORD-FR-014; PRD-02 (hitung ulang estimasi); PRD-05 (FIN-FR-002 penerimaan order). |
| Priority | Must |
| Acceptance criteria | Given keranjang berisi 2 item valid dan data lengkap, When pelanggan menekan Kirim pesanan, Then satu order tersimpan dengan 2 item snapshot, status Menunggu konfirmasi dan Belum dibayar, dan tampil di daftar PRD-05. Given tombol ditekan dua kali cepat, Then hanya satu order tersimpan. Given harga katalog diubah setelah order dikirim, When admin membuka order, Then harga item tetap sama. |
 
### **ORD-FR — Kartu Spesifikasi Functional Requirement 4**


| **Elemen** | **Isi / spesifikasi** |
| --- | --- |
| Requirement ID | ORD-FR-011 |
| Nama requirement | Upload file desain/referensi dan validasi file |
| Tujuan dan deskripsi | Memungkinkan pelanggan melampirkan desain atau contoh visual agar admin memahami kebutuhan tanpa meminta file lewat chat, dengan tetap aman dari file berbahaya (Master PRD Bab 13, NFR-008). |
| Aktor dan hak akses | Customer guest (mengunggah) dan Sistem (memvalidasi dan menyimpan). File hanya dapat dibuka admin terautentikasi di PRD-05. |
| Precondition dan trigger | Pelanggan berada di PAGE-ORD-005 atau di PAGE-ORD-002 untuk item yang memerlukan file desain. Pemicu: pelanggan memilih file atau menyeretnya ke area unggah. |
| Input dan field | File (biner, opsional kecuali produk mewajibkan desain); format diizinkan JPG, JPEG, PNG, PDF (usulan; AI/PSD/ZIP menunggu keputusan); ukuran maksimal 10 MB per file; maksimal 5 file per request atau per item; keterangan file (teks, opsional, maksimal 200 karakter). |
| Alur utama | (1) Sistem memeriksa ekstensi dan ukuran di klien. (2) File dikirim ke server dengan indikator progres. (3) Server memeriksa ulang ukuran, ekstensi, dan tipe isi file (MIME dari isi, bukan dari nama). (4) Server mengganti nama file dengan nama acak, menyimpannya di folder non-publik sebagai file sementara. (5) Sistem menampilkan nama asli, ukuran, dan pratinjau (gambar) atau ikon (PDF). (6) Saat pesanan dikirim, file sementara ditautkan ke request/item dan menjadi permanen. |
| Alur alternatif | Pelanggan menghapus file sebelum kirim: file sementara dihapus. Pelanggan tidak jadi mengirim: file sementara dihapus otomatis setelah 24 jam. Unggah gagal: tombol coba lagi per file. |
| Validasi dan business rules | Ekstensi dan isi harus cocok (misalnya file .exe yang diganti nama menjadi .jpg ditolak). File tidak pernah dieksekusi dan tidak dapat diakses lewat URL publik langsung. Nama asli disimpan hanya sebagai metadata. Jumlah dan ukuran dihitung ulang di server. File tidak ditampilkan di halaman publik mana pun. |
| Output dan feedback | Daftar file terunggah dengan nama, ukuran, status berhasil/gagal; data lampiran tersimpan di entitas Order Attachment. |
| Error dan edge cases | Format tidak diizinkan: "Format file harus JPG, PNG, atau PDF". Terlalu besar: "Ukuran maksimal 10 MB". Melebihi 5 file: file tambahan ditolak. Koneksi putus: status gagal dan dapat dicoba ulang. File rusak atau kosong (0 byte): ditolak. Penyimpanan server penuh: pesan error umum dan kejadian dicatat. |
| Dependencies | ORD-FR-010, ORD-FR-012; keputusan file storage (Master PRD 12.1); PRD-05 (tampilan dan unduh lampiran di PAGE-FIN-002). |
| Priority | Must |
| Acceptance criteria | Given file PNG 3 MB, When pelanggan mengunggah, Then file diterima dan tampil di daftar. Given file 12 MB atau file .exe berganti nama .jpg, When diunggah, Then sistem menolak dengan pesan yang sesuai. Given request terkirim dengan 2 file, When admin membuka detail di PRD-05, Then kedua file dapat diunduh dan tidak dapat diakses tanpa login. |
 
### **ORD-FR — Kartu Spesifikasi Functional Requirement 5**


| **Elemen** | **Isi / spesifikasi** |
| --- | --- |
| Requirement ID | ORD-FR-012 |
| Nama requirement | Submit custom order request |
| Tujuan dan deskripsi | Menyimpan kebutuhan custom pelanggan sebagai permintaan penawaran yang terstruktur, terpisah dari transaksi final, agar admin dapat meninjau dan memberi penawaran di PRD-05 (Master PRD 8.2 dan 9.3). |
| Aktor dan hak akses | Customer guest (pemicu) dan Sistem. |
| Precondition dan trigger | Formulir custom request terisi lengkap dan kotak persetujuan dicentang. Pemicu: pelanggan menekan Kirim request. |
| Input dan field | Produk/kategori (rujukan, opsional); snapshot opsi dari PRD-02 (opsional); deskripsi kebutuhan (teks, wajib, 20–2.000 karakter); perkiraan jumlah (bilangan bulat positif, wajib); spesifikasi (teks, opsional); tenggat yang diharapkan (tanggal, opsional, tidak di masa lalu); anggaran (angka Rupiah, opsional); lampiran (dari ORD-FR-011); nama, nomor WhatsApp (wajib), email (opsional); token formulir; persetujuan (wajib). |
| Alur utama | (1) Sistem memeriksa token. (2) Sistem memvalidasi ulang semua kolom di server dan menormalkan nomor WhatsApp. (3) Sistem membuat kode request unik. (4) Dalam satu transaksi database, sistem menyimpan atau mencocokkan pelanggan, membuat order jenis custom dengan kanal Website dan status request Baru diterima, menyimpan item request (nama produk atau "Custom", deskripsi, jumlah, harga kosong), dan menautkan lampiran. (5) Sistem menampilkan PAGE-ORD-006. (6) Sistem mengirim email bila diisi. |
| Alur alternatif | Datang dari PRD-02: produk, opsi, dan estimasi (bila ada) terisi otomatis dan tersimpan sebagai referensi, bukan harga. Pelanggan tidak memilih produk: request disimpan dengan kategori "Lainnya". |
| Validasi dan business rules | Request bukan transaksi final dan tidak dihitung dalam laporan penjualan sampai dikonversi admin (FIN-BR-001). Status pengerjaan dan status pembayaran belum diisi sampai dikonversi. Estimasi dari PRD-02 hanya disimpan sebagai informasi. Penyimpanan atomik. Satu token hanya menghasilkan satu request. |
| Output dan feedback | Request tersimpan di PRD-05; halaman konfirmasi dengan kode request, ringkasan, daftar file, dan penjelasan langkah berikutnya. |
| Error dan edge cases | Deskripsi terlalu pendek: pesan minimal 20 karakter. Jumlah nol atau bukan angka: pesan. Tenggat di masa lalu: pesan. File masih dalam proses unggah: tombol kirim nonaktif. Kiriman ganda: kiriman kedua ditolak dan diarahkan ke konfirmasi yang sama. Gagal simpan: tidak ada request parsial, data form dan file sementara dipertahankan. |
| Dependencies | ORD-FR-010, ORD-FR-011, ORD-FR-013, ORD-FR-014; PRD-02 (CAT-FR-010 paket request quotation); PRD-05 (FIN-FR-002, FIN-FR-003). |
| Priority | Must |
| Acceptance criteria | Given formulir custom terisi lengkap dengan 1 file, When pelanggan menekan Kirim request, Then satu request berstatus Baru diterima tersimpan dengan lampiran dan tampil di PRD-05. Given request baru tersimpan, When admin membuka laporan penjualan, Then request tidak dihitung sebagai penjualan. |
 
## I. Business rules domain


| **Rule ID** | **Aturan bisnis** | **Kondisi / rumus / batasan** | **Dampak jika tidak terpenuhi** |
| --- | --- | --- | --- |
| ORD-BR-001 | Pelanggan tidak wajib login untuk memesan. | Direct purchase dan custom request cukup dengan nama dan nomor WhatsApp; tidak ada akun atau password. | Hambatan pemesanan; melanggar prinsip guest-friendly Master PRD 3.2. |
| ORD-BR-002 | Hanya produk yang memenuhi syarat yang dapat dibeli langsung. | Produk aktif, ditandai boleh dibeli langsung di PRD-04, dan bermodel harga tetap, mulai dari, atau kalkulator. Request quotation selalu lewat custom request. | Produk masuk keranjang dan ditolak saat checkout; sistem menampilkan pesan dan mengarahkan ke custom request. |
| ORD-BR-003 | Harga dihitung ulang di server sebelum pesanan disimpan. | Harga dari tampilan atau keranjang tidak dipercaya; sistem memakai aturan harga terkini dari PRD-04/PRD-02. Bila berbeda, pelanggan wajib menyetujui ulang. | Harga dapat dimanipulasi dari sisi klien; order tidak disimpan. |
| ORD-BR-004 | Total pesanan website adalah estimasi berdasarkan harga produk, spesifikasi, dan jumlah yang dipilih. Pemenuhan pesanan hanya melalui pengambilan langsung. | Subtotal item: harga tetap = harga × jumlah; kalkulator = total estimasi PRD-02; mulai dari = harga awal × jumlah. Total estimasi = Σ subtotal item. Admin mengonfirmasi harga final di PRD-05 setelah meninjau pesanan. | Pelanggan mengira total sudah final; label Estimasi wajib tampil di keranjang, ringkasan, konfirmasi, dan email. |
| ORD-BR-005 | Setiap item membawa snapshot. | Nama produk, opsi, jumlah, model harga, dan harga/estimasi disalin saat pesanan dikirim. Perubahan katalog sesudahnya tidak mengubah item. | Pesanan lama berubah mengikuti katalog (melanggar INT-06). |
| ORD-BR-006 | Request berbeda dari order. | Custom request disimpan berstatus Baru diterima dan bukan transaksi final sampai dikonversi admin di PRD-05. | Laporan penjualan menghitung permintaan yang belum pasti. |
| ORD-BR-007 | Status awal pesanan website ditetapkan sistem. | Direct: status pengerjaan Menunggu Konfirmasi, pembayaran Belum Dibayar. Setelah Admin mengonfirmasi total final, status pengerjaan Menunggu Pembayaran. Custom: status request Baru Diterima; belum memiliki status pengerjaan/pembayaran sampai disetujui dan dikonversi. Pelanggan tidak dapat mengubah status. | Status tidak konsisten dengan FIN; request yang belum final tidak boleh dianggap sebagai penjualan. |
| ORD-BR-008 | Kode pesanan unik dan tidak dapat ditebak berurutan. | Usulan format: direct WEB-YYMMDD-XXXX, custom REQ-YYMMDD-XXXX, dengan XXXX 4 karakter acak huruf besar dan angka. Unik di tingkat database. Nomor transaksi resmi (TRX) tetap diberikan PRD-05. | Bentrok kode atau pelanggan dapat menebak kode pesanan orang lain. |
| ORD-BR-009 | Nomor WhatsApp dinormalkan. | Hapus spasi, tanda hubung, dan tanda +; awalan 0 diganti 62. Hasil 10–15 digit. | Pelanggan yang sama tercatat ganda di PRD-05 (FIN-BR-012). |
| ORD-BR-010 | File unggahan dibatasi dan diamankan. | JPG, PNG, PDF; ≤ 10 MB per file; ≤ 5 file; validasi isi file di server; nama acak; folder non-publik; file sementara dihapus setelah 24 jam. | Risiko file berbahaya dan kebocoran desain pelanggan. |
| ORD-BR-011 | Satu kiriman formulir menghasilkan satu pesanan. | Token sekali pakai, tombol nonaktif saat proses, penyimpanan atomik. | Order ganda dan data parsial (NFR-005). |
| ORD-BR-012 | Pembayaran dilakukan di luar sistem dan diverifikasi manual. | Checkout, ringkasan, dan halaman konfirmasi awal tidak menampilkan instruksi pembayaran. Metode versi pertama hanya QRIS. Instruksi dikirim Admin melalui WhatsApp setelah harga final dan nominal pembayaran disepakati. Konfigurasi QRIS dikelola Owner di FIN; Admin memverifikasi penerimaan dana secara manual sebelum mencatat pembayaran Valid. Tidak ada gateway atau unggah bukti pembayaran pelanggan ke website. | Pelanggan dapat membayar sebelum nilai tagihan disepakati atau mengira estimasi sebagai tagihan final. |
| ORD-BR-013 | Status pesanan hanya tersedia untuk pengguna internal. | Pelanggan tidak dapat mencari status melalui kode pesanan, nomor WhatsApp, atau halaman publik. Informasi kesiapan pickup disampaikan Admin melalui WhatsApp manual. | Kebocoran data atau kompleksitas tracking yang tidak diperlukan. |
| ORD-BR-014 | Halaman konfirmasi dan data pelanggan bersifat privat. | Halaman konfirmasi hanya dapat diakses oleh sesi pelanggan yang mengirim pengajuan. Kode pesanan hanya berfungsi sebagai bukti pengajuan/pengambilan, bukan kredensial untuk melihat status atau data pesanan. Submission Token tetap untuk mencegah kiriman ganda. | Data kontak dan rincian pesanan dapat diakses pihak lain. |
 
## J. Data requirements


| **Entitas / data** | **Field / atribut** | **Wajib?** | **Validasi / format** | **Sumber / relasi** |
| --- | --- | --- | --- | --- |
| Cart | id, token sesi, created_at, expires_at | Ya | Token acak; kedaluwarsa sesuai kebijakan sesi. | Milik PRD-03; satu Cart memiliki banyak Cart Item. |
| Cart Item | id, cart_id, product_id, snapshot nama/opsi, quantity, price_model, estimated_unit_price, estimated_subtotal, pricing_version | Ya | Jumlah bilangan bulat positif; harga tidak negatif. | Dibentuk dari paket konfigurasi PRD-02. |
| Customer | id, nama, nomor WhatsApp (dinormalkan), email, status aktif | Ya (nama, nomor WhatsApp) | Nama tidak kosong; nomor 10–15 digit format 62; email valid bila diisi. | Entitas bersama FIN; alamat pengiriman tidak diperlukan karena pickup-only. |
| Order / Request | id, kode pesanan, id pelanggan, jenis (direct/custom), kanal asal, status request, status pengerjaan, status pembayaran, estimasi total, catatan, id request asal, tanggal kirim, status pickup (tetap ambil di tempat) | Ya | Kode unik; status sesuai lifecycle; total tidak negatif. | Entitas bersama FIN; status detail, harga final, pembayaran/refund dan invoice dikelola FIN. |
| Order Item (field yang diisi PRD-03) | id, id order, id produk (opsional), snapshot nama produk, snapshot spesifikasi/opsi, jumlah, model harga, harga per pcs estimasi, subtotal estimasi, status harga (estimasi/tetap), deskripsi kebutuhan (custom) | Ya (order, nama, jumlah, model harga) | Jumlah bilangan bulat positif; harga kosong untuk request quotation | Entitas bersama PRD-05; harga final diisi admin di PRD-05 |
| Order Attachment (entitas baru) | id, id order, id order item (opsional), nama file asli, nama file tersimpan, lokasi file, tipe MIME, ukuran (byte), keterangan, status (sementara/terkirim), waktu unggah | Ya (lokasi, tipe, ukuran, status) | Tipe JPG/PNG/PDF; ≤ 10 MB; ≤ 5 per order/item; lokasi non-publik | Milik satu order atau satu item; dibaca admin di PRD-05 |
| Submission Token | id, token sesi, jenis formulir, used_at, order_id hasil | Ya | Sekali pakai; masa berlaku terbatas. | Hanya untuk mencegah kiriman ganda, bukan token lookup status. |
| Pengaturan katalog dan pickup (dibaca dari PRD-04) | Penanda produk direct purchase, penanda wajib file, lokasi/jam pengambilan, nomor WhatsApp usaha, teks persetujuan | Ya untuk pengaturan yang relevan | Hanya nilai aktif/terbit yang dibaca. | Milik PRD-04. Konfigurasi QRIS/instruksi pembayaran tidak menjadi data ORD; dimiliki FIN. |
 

**Relasi data (usulan untuk ERD bersama).** Satu Cart memiliki banyak Cart Item. Satu Customer memiliki banyak Order/Request. Satu Order/Request memiliki banyak Order Item dan banyak Order Attachment. Satu Order Item dapat memiliki beberapa Order Attachment. Satu Submission Token menghasilkan paling banyak satu Order/Request. Entitas baru yang diusulkan domain ini: Cart, Cart Item, Order Attachment, dan Submission Token. ERD final disepakati bersama anggota 4, anggota 5, dan koordinator (FIN-Q-008).

**Paket data ke FIN (kontrak lintas domain). Saat pelanggan mengirim, ORD menyimpan kode pesanan/request, jenis dan kanal, data pelanggan ternormalisasi, pickup langsung, daftar item beserta snapshot opsi/kuantitas/estimasi, total estimasi, catatan/tenggat/anggaran untuk custom, lampiran, dan status awal sesuai ORD-BR-007. Data konfigurasi QRIS tidak dikirim ke ORD dan tidak ditampilkan pada checkout.**
## K. Validation, error handling, dan edge cases


| **Case ID** | **Kondisi / skenario** | **Validasi atau respons sistem yang diharapkan** | **Severity** |
| --- | --- | --- | --- |
| ORD-EC-001 | Kolom wajib kosong atau format tidak valid (nama, nomor WhatsApp, deskripsi custom). | Pesan di bawah kolom terkait; pesanan tidak dikirim; data lain di form dipertahankan. Validasi diulang di server. | High |
| ORD-EC-002 | Jumlah item nol, negatif, desimal, bukan angka, atau di bawah minimum order | Pesan validasi dan jumlah sebelumnya dipertahankan; total tidak dihitung dari nilai salah. | High |
| ORD-EC-003 | Produk di keranjang dinonaktifkan atau dihapus admin sebelum checkout | Item ditandai "tidak lagi tersedia"; checkout nonaktif sampai item dihapus. | High |
| ORD-EC-004 | Tarif berubah antara item ditambahkan dan pesanan dikirim | Sistem menampilkan harga lama dan baru, total diperbarui, pelanggan wajib menyetujui ulang sebelum order disimpan. | High |
| ORD-EC-005 | Tombol kirim ditekan berkali-kali, halaman dimuat ulang, atau tombol back setelah kirim | Hanya satu order/request tersimpan; kiriman berikutnya diarahkan ke halaman konfirmasi yang sama. | High |
| ORD-EC-006 | Gagal simpan database atau jaringan terputus saat kirim | Tidak ada order, item, atau lampiran parsial; pesan "Pesanan belum terkirim, silakan coba lagi"; data form dan file sementara dipertahankan. | High |
| ORD-EC-007 | File salah format, melebihi 10 MB, lebih dari 5 file, kosong, atau isi tidak sesuai ekstensi | File ditolak dengan pesan spesifik; file lain yang valid tetap tersimpan. | High |
| ORD-EC-008 | Upload terputus di tengah | Status file "gagal" dengan tombol coba lagi; tombol kirim nonaktif selama ada file dalam proses. | Med |
| ORD-EC-009 | Nomor WhatsApp sama dengan pelanggan lama tetapi nama berbeda | Pesanan tetap diterima; pencocokan dan penggabungan diputuskan admin di PRD-05; pelanggan tidak melihat data pelanggan lama. | Med |
| ORD-EC-010 | Sesi/keranjang kedaluwarsa saat checkout | Pesan bahwa sesi berakhir; pelanggan diarahkan ke katalog; tidak ada order setengah jadi. | Med |
| ORD-EC-011 | Pihak lain mencoba membuka halaman konfirmasi pesanan atau memanipulasi parameter. | Akses ditolak atau diarahkan ke halaman publik. Tidak ada pengalihan ke halaman cek status; parameter harga dari klien diabaikan dan dihitung ulang di server. | High |
| ORD-EC-012 | Kiriman berulang dari bot atau satu IP dalam waktu singkat | Honeypot/captcha menolak bot; batas usulan 5 kiriman per IP per 10 menit; pesan "Terlalu banyak percobaan, coba beberapa saat lagi". | Med |
| ORD-EC-013 | Email notifikasi gagal terkirim | Order tetap tersimpan dan halaman konfirmasi tetap tampil; kegagalan dicatat untuk ditinjau admin. | Low |
| ORD-EC-015 | Konfigurasi QRIS atau instruksi belum tersedia di FIN. | Halaman konfirmasi tetap tampil tanpa instruksi pembayaran. Admin memeriksa konfigurasi di FIN dan menghubungi pelanggan setelah total final dan nominal disepakati. | Low |
 
## L. Acceptance criteria dan test scenarios


| **Test ID** | **Requirement terkait** | **Given (kondisi awal)** | **When (aksi)** | **Then (hasil yang diharapkan)** | **Status** |
| --- | --- | --- | --- | --- | --- |
| ORD-AT-001 | ORD-FR-001, ORD-FR-004 | Produk berkalkulator aktif dan boleh dibeli langsung; konfigurasi valid di PRD-02 | Pelanggan menekan Tambah ke keranjang | Keranjang memuat produk, opsi, jumlah, dan estimasi yang sama dengan PRD-02; ikon keranjang bertambah | Belum diuji |
| ORD-AT-002 | ORD-FR-004 | Produk bermodel request quotation | Pelanggan mencoba menambah ke keranjang | Item tidak masuk keranjang; pelanggan diarahkan ke formulir custom request dengan produk terisi | Belum diuji |
| ORD-AT-003 | ORD-FR-002 | Keranjang berisi item harga tetap Rp5.000 × 10 dan item kalkulator dengan estimasi Rp60.000 | Pelanggan membuka keranjang | Subtotal Rp50.000 dan Rp60.000, total estimasi Rp110.000, label Estimasi tampil | Belum diuji |
| ORD-AT-004 | ORD-FR-002 | Keranjang berisi satu item | Pelanggan mengubah jumlah menjadi 0, lalu -3, lalu "abc" | Ketiganya ditolak dengan pesan; jumlah lama dipertahankan | Belum diuji |
| ORD-AT-005 | ORD-FR-003 | Pelanggan menambah item lalu menutup peramban | Pelanggan membuka website lagi dalam 7 hari pada peramban yang sama | Keranjang masih berisi item yang sama | Belum diuji |
| ORD-AT-006 | ORD-FR-005 | Formulir checkout terbuka | Pelanggan mengisi nomor "0812-3456-7890" | Nomor tersimpan sebagai 6281234567890; tidak ada akun yang dibuat | Belum diuji |
| ORD-AT-007 | ORD-FR-006 | Checkout menampilkan pickup langsung saja. | Pelanggan membuka ringkasan checkout. | Lokasi/jam pickup tampil dari profil usaha; tidak ada pilihan kirim, alamat pengiriman, atau ongkir. | Belum diuji |
| ORD-AT-008 | ORD-FR-007, ORD-FR-008 | Data lengkap, kotak persetujuan belum dicentang | Pelanggan menekan Kirim pesanan | Pesanan tidak dikirim; sistem meminta persetujuan | Belum diuji |
| ORD-AT-009 | ORD-FR-008, ORD-FR-014 | Keranjang 2 item valid, data lengkap, dan persetujuan dicentang. | Pelanggan mengirim pesanan. | Satu order direct tersimpan dengan kode unik, 2 item snapshot, status Menunggu Konfirmasi, pembayaran Belum Dibayar; halaman konfirmasi tidak memuat QRIS/instruksi pembayaran; order tampil di FIN. | Belum diuji |
| ORD-AT-010 | ORD-FR-008 | Admin mengubah tarif setelah item masuk keranjang | Pelanggan menekan Kirim pesanan | Order tidak disimpan; harga baru ditandai dan pelanggan diminta menyetujui ulang | Belum diuji |
| ORD-AT-011 | ORD-FR-013 | Ringkasan pesanan siap disubmit | Pelanggan menekan Kirim dua kali cepat lalu memuat ulang halaman | Hanya satu order tersimpan di database | Belum diuji |
| ORD-AT-012 | ORD-FR-008 | Order sudah tersimpan | Admin mengubah harga katalog produk tersebut | Harga dan spesifikasi item order tidak berubah (INT-06) | Belum diuji |
| ORD-AT-013 | ORD-FR-009 | Order berhasil dikirim. | Halaman konfirmasi tampil. | Kode pesanan, ringkasan, status Menunggu Konfirmasi, informasi pickup, dan pemberitahuan bahwa Admin akan menghubungi pelanggan tampil. Tidak ada instruksi pembayaran. | Belum diuji |
| ORD-AT-014 | ORD-FR-010, ORD-FR-012 | Formulir custom terisi lengkap dengan 1 file PDF | Pelanggan menekan Kirim request | Request REQ- berstatus Baru diterima tersimpan dengan lampiran; tidak dihitung dalam laporan penjualan PRD-05 | Belum diuji |
| ORD-AT-015 | ORD-FR-011 | Formulir custom terbuka | Pelanggan mengunggah PNG 3 MB, PDF 12 MB, dan file .exe berganti nama .jpg | PNG diterima; PDF dan file .exe ditolak dengan pesan yang sesuai | Belum diuji |
| ORD-AT-016 | ORD-FR-011 | Request terkirim dengan 2 file | Seseorang tanpa login membuka URL file secara langsung | Akses ditolak; file hanya dapat diunduh admin di PRD-05 | Belum diuji |
| ORD-AT-017 | ORD-FR-015 | Pelanggan mengisi email saat checkout. | Order berhasil dikirim. | Email berisi kode pesanan dan ringkasan pengajuan diterima; tidak berisi instruksi pembayaran QRIS. | Belum diuji |
| ORD-AT-018 | ORD-FR-017 | Formulir publik terbuka | 6 kiriman dari IP yang sama dalam 10 menit | Kiriman ke-6 ditolak dengan pesan batas percobaan | Belum diuji |
| ORD-AT-019 | ORD-FR-001 s.d. ORD-FR-017 | Seluruh halaman ORD tersedia. | Dibuka di desktop dan mobile pada browser uji. | Tata letak terbaca dan seluruh alur yang termasuk scope dapat diselesaikan. | Belum diuji |
 
## M. Non-functional requirements domain


| **Kategori** | **Kebutuhan khusus domain** | **Ukuran / target uji** |
| --- | --- | --- |
| Usability | Checkout dapat diselesaikan tanpa instruksi tambahan; maksimal 3 langkah (keranjang → data → ringkasan); kolom wajib ditandai; pesan error menyebut penyebab dan cara memperbaiki; label Estimasi dan informasi pengambilan langsung selalu terlihat; data form tidak hilang saat terjadi error. | Uji dengan minimal 5 calon pelanggan; usulan target 80% menyelesaikan checkout dan custom request tanpa bantuan (final TBD tim) |
| Performance | Halaman keranjang dan checkout tampil dalam waktu wajar; hitung ulang total setelah ubah jumlah terasa langsung; kiriman pesanan diproses tanpa menunggu email terkirim; unggah file menampilkan progres. | Usulan: halaman ≤ 3 detik dan simpan pesanan ≤ 3 detik pada jaringan normal; unggah 10 MB ≤ 30 detik (target final TBD) |
| Security / privacy | Validasi dan hitung ulang harga di server; token anti-ganda dan proteksi CSRF pada semua formulir; batas kiriman per IP dan honeypot/captcha; file divalidasi isi, disimpan di luar folder publik, tidak dapat dieksekusi; halaman konfirmasi hanya untuk sesi pengirim; data kontak tidak tampil di halaman publik; koneksi HTTPS di lingkungan deployment. | Uji manipulasi harga dari klien, kiriman ganda, unggah file berbahaya, akses URL file dan konfirmasi tanpa hak; semua harus ditolak |
| Data integrity | Order/request, item, pelanggan, dan lampiran disimpan dalam satu transaksi database; kode pesanan unik di tingkat database; snapshot tidak berubah. | Uji gagal simpan di tengah proses: tidak ada data parsial |
| Compatibility / accessibility | Berjalan di desktop dan mobile pada browser modern yang disepakati; form dapat dipakai dengan keyboard; label terhubung ke kolom; kontras cukup; tipe input sesuai (tel untuk nomor, email untuk email) agar keyboard ponsel tepat. | Usulan: Chrome, Firefox, Edge versi terbaru dan Chrome Android; lebar layar 360 px dan 1366 px (final TBD) |
 
## N. Dependencies dan integrasi lintas domain


| **Domain terkait** | **Data / fungsi yang dibutuhkan** | **Data / fungsi yang diberikan** | **Kesepakatan interface** |
| --- | --- | --- | --- |
| PRD-02 Catalog & Customization | Paket konfigurasi: produk, snapshot nama dan spesifikasi, opsi terpilih, jumlah, estimasi per pcs dan total, model harga; fungsi hitung ulang estimasi di server | Halaman tujuan untuk tombol Tambah ke keranjang (/keranjang) dan Minta penawaran (/custom-order?produk={slug}); tautan Ubah opsi kembali ke detail produk | Paket diserahkan saat pelanggan menekan tombol aksi (CAT-FR-010); fungsi hitung harga dipakai bersama agar angka keranjang dan kalkulator identik (INT-03, CAT-Q-007) |
| PRD-04 Admin & CMS | Status produk, penanda direct purchase, penanda wajib file, master produk/tarif, profil usaha, lokasi/jam pickup, nomor WhatsApp. | Kebutuhan field pengaturan katalog/pickup yang dibaca ORD. | Data master aktif/terbit dipakai; perubahan tidak mengubah snapshot order. Tidak ada data QRIS di ADM. |
| PRD-05 Sales & Finance | Penerimaan order/request, customer, item snapshot, lampiran, kode dan status awal. | Status request/pengerjaan/pembayaran, harga final, konfigurasi QRIS, pembayaran/refund, invoice, histori status internal. | Semua status lanjutan dikelola internal di FIN. Admin memberi tahu Siap Diambil melalui WhatsApp manual; tidak ada endpoint/halaman lookup pelanggan. |
| PRD-01 Company Profile | Header, footer, gaya visual, navigasi publik, dan kontak. | Ikon keranjang, tautan Custom Order, CTA katalog dan kontak. | Tidak ada menu atau tautan Cek Pesanan. |
 

# 18. ADM — ADMIN DASHBOARD & CONTENT MANAGEMENT SYSTEM
Penanggung jawab: Anggota 4. Nama anggota: Taufik. Status: Selesai

A. Tujuan domain dan ringkasan solusi

Domain Admin Dashboard & Content Management System (ADM) bertujuan menyediakan sistem administrasi terpusat yang memungkinkan pengelola website mengatur dan memperbarui informasi yang ditampilkan kepada pengguna. Permasalahan yang ingin diselesaikan adalah kebutuhan pengelolaan konten, katalog produk, variasi produk, serta konfigurasi harga yang sebelumnya berpotensi memerlukan perubahan langsung pada sistem. Solusi yang diberikan berupa dashboard admin yang dilengkapi autentikasi, navigasi pengelolaan, fitur CRUD (Create, Read, Update, Delete), dan pengaturan status publikasi.

Melalui domain ini, admin dapat mengelola informasi website secara lebih mudah, terstruktur, dan efisien tanpa harus mengubah kode program secara langsung. Nilai utama yang dihasilkan adalah kemudahan pemeliharaan informasi, konsistensi data katalog, serta pengendalian konfigurasi harga yang terintegrasi dengan kalkulator pada PRD-02. Hasil akhir yang diharapkan adalah sebuah dashboard administrasi yang aman, mudah digunakan, dan mampu mengelola seluruh data master serta konten publik tanpa mengambil alih pengelolaan transaksi pada PRD-05.

B. Tujuan, scope, dan batasan domain

| **Aspek** | **Isi** |
| :---: | :---: |
| Tujuan domain | Menyediakan dashboard administrasi untuk mengelola konten website, katalog produk, kategori, variasi, konfigurasi harga, media, akun Admin, dan hak akses. |
| In Scope | Login/logout Owner dan Admin; dashboard overview; pengelolaan homepage, profil, kontak, produk, kategori, variasi, konfigurasi harga, testimoni, portofolio, galeri, status publikasi, upload media, Archive/Soft Delete produk/kategori, dan User Management khusus Owner. |
| Out of Scope | Pengelolaan pesanan, pembayaran, invoice, pengeluaran, laporan keuangan, serta proses operasional transaksi yang menjadi tanggung jawab PRD-05. |
| Asumsi | Sistem memiliki halaman publik yang mengambil data dari database. Pengguna internal menggunakan dua role, Owner dan Admin. Owner dapat mengelola akun Admin; Admin tidak dapat mengubah role atau akun pengguna lain. Perubahan konfigurasi harga oleh Owner/Admin langsung berlaku untuk estimasi berikutnya, sedangkan snapshot transaksi lama tidak berubah. |
| Stakeholder / aktor | Owner, Admin, Visitor, Customer Guest, pemilik usaha, dan sistem PRD-02 sebagai pengguna konfigurasi kalkulator. Owner dan Admin memiliki akses dashboard sesuai permission masing-masing. |
 

C. Daftar subfitur yang wajib dianalisis

| Subfitur awal | Spesifikasi dari anggota | Prioritas |
| :---: | :---: | :---: |
| Authentication internal | Owner dan Admin login menggunakan email dan password. Sistem memvalidasi akun aktif, membuat session, dan menyediakan logout. Seluruh halaman dashboard memeriksa role dan permission. | Must |
| Dashboard overview dan navigasi admin | Menampilkan ringkasan jumlah produk, kategori, konten, testimoni, dan status publikasi. Menyediakan navigasi menuju seluruh menu pengelolaan. | Must |
| Content management homepage, profil, kontak | Admin dapat mengubah judul, deskripsi, gambar banner, informasi profil usaha, alamat, nomor kontak, email, dan informasi lainnya yang ditampilkan pada website. | Must |
| Product/category management | Owner dan Admin dapat menambah, melihat, mengubah, mengaktifkan/menonaktifkan, dan mengarsipkan produk serta kategori. Produk yang telah direferensikan transaksi tidak dihapus permanen. Produk arsip tidak tersedia untuk order baru dan tetap dapat dilihat pada histori. | Must |
| Product variant/option management | Admin dapat mengatur pilihan variasi atau spesifikasi produk, termasuk nama variasi, pilihan, dan nilai tambahan yang berkaitan dengan produk. | Must |
| Pricing configuration dan formula kalkulator | Owner dan Admin dapat mengatur harga dasar, biaya tambahan, tiga kelompok parameter harga per produk, serta matriks/aturan perhitungan kalkulator PRD-02. Perubahan yang disimpan langsung berlaku untuk estimasi berikutnya tanpa approval berjenjang; snapshot transaksi lama tidak berubah. | Must |
| Testimonials/review management | Admin dapat menambahkan testimonial secara manual berdasarkan ulasan atau pengalaman pelanggan yang diperoleh melalui komunikasi langsung. Admin dapat melihat, menambahkan, mengubah, menghapus, serta mengatur status publikasi testimonial. Testimonial yang berstatus published akan ditampilkan pada halaman Ulasan & Kolaborasi di public website. Sistem tidak menyediakan form pengajuan atau approval testimonial langsung dari pelanggan. | Should |
| Portfolio/collaboration management | Admin dapat menambahkan informasi portofolio atau kolaborasi berupa judul, deskripsi, gambar, dan informasi pendukung. | Should |
| Gallery/media management | Admin dapat mengunggah, melihat, mengganti, dan menghapus gambar atau media yang digunakan pada website. | Should |
| Status publikasi, draft, active/inactive, upload media | Sistem menyediakan status draft, published, active, dan inactive untuk mengatur visibilitas konten dan ketersediaan produk. | Must |
| Role / User Management | Owner dapat membuat, melihat, mengubah informasi, mengaktifkan, dan menonaktifkan akun Admin. Admin tidak dapat mengelola akun pengguna lain atau mengubah role. Sistem hanya menggunakan dua role internal: Owner dan Admin. | Must |
Daftar di atas adalah titik awal, bukan batas jumlah fitur. Tambahkan baris jika diperlukan dan tandai subfitur yang dipindahkan/dihilangkan beserta alasannya.

D. User roles dan permission dalam domain

| Aktor/role | Melihat | Membuat | Mengubah | Menghapus/tindakan khusus |
| :---: | :---: | :---: | :---: | :---: |
| Owner | Ya: seluruh dashboard dan data administrasi. | Ya: seluruh data yang dikelola ADM. | Ya: seluruh konten, produk, variasi, harga, konfigurasi, dan akun Admin. | Memiliki seluruh hak akses Admin serta hak khusus User Management dan konfigurasi sistem. |
| Admin | Ya: seluruh menu operasional yang diizinkan. | Ya: konten, produk, kategori, variasi, media, testimonial, dan portfolio. | Ya: data operasional dan konfigurasi yang menjadi hak aksesnya. | Tidak dapat mengelola akun Admin, mengubah role, atau mengakses konfigurasi khusus Owner. |
| Visitor / Customer Guest | Tidak | Tidak | Tidak | Tidak memiliki akses ke dashboard administrasi. |
 

E. Page / screen specifications

| Page ID / nama | Route / akses | Tujuan dan isi utama | Aksi / interaksi utama |
| :---: | :---: | :---: | :---: |
| PAGE-ADM-001 Login Admin | /admin/login; Owner dan Admin dengan akun aktif | Form email dan password, tombol login, serta pesan validasi. | Mengisi kredensial, login, dan menerima pesan jika autentikasi gagal. |
| PAGE-ADM-002 Dashboard | /admin/dashboard; Owner dan Admin | Ringkasan jumlah produk, kategori, konten, testimoni, status publikasi, dan navigasi sesuai permission. | Melihat ringkasan dan berpindah ke halaman pengelolaan. |
| PAGE-ADM-003 Content Management | /admin/content; Owner dan Admin | Form pengelolaan homepage, profil, kontak, portofolio, dan testimoni. | Menambah, mengubah, menyimpan draft, dan memublikasikan konten. |
| PAGE-ADM-004 Product & Pricing Management | /admin/products dan /admin/pricing; Owner dan Admin | Daftar produk, kategori, variasi, harga dasar, biaya tambahan, konfigurasi kalkulator, dan status archive/inactive. | Menambah produk, mengubah variasi/harga, mengaktifkan/menonaktifkan, serta mengarsipkan produk/kategori. |
| PAGE-ADM-005 Media Management | /admin/media; Owner dan Admin | Galeri media dan daftar file yang digunakan pada website. | Upload, mengganti, mengarsipkan/menghapus media sesuai relasi dan kebutuhan konten. |
| PAGE-ADM-006 User Management | /admin/users; Owner saja | Daftar akun Admin, informasi akun, status aktif/nonaktif, dan role. Admin tidak memiliki akses ke halaman ini. | Owner membuat akun Admin, mengubah informasi, mengaktifkan/menonaktifkan akun. Sistem menolak akses Admin (403). |
 

F. User flow dan workflow

| Flow ID / nama | Trigger & precondition | Main flow (langkah) | Alternative / exception | Output |
| --- | --- | --- | --- | --- |
| FLOW-ADM-001 Login Owner/Admin | Owner atau Admin membuka halaman login dan memiliki akun aktif. | 1. Pengguna membuka halaman login. 2. Mengisi email dan password. 3. Sistem memvalidasi kredensial serta status akun. 4. Sistem membuat session dan mengarahkan pengguna ke dashboard sesuai role. | Kredensial salah atau akun nonaktif: akses ditolak dan pesan kesalahan ditampilkan. | Owner/Admin berhasil masuk ke dashboard sesuai permission. |
| FLOW-ADM-002 Pengelolaan Konten dan Produk | Owner atau Admin telah login dan memiliki permission modul. | 1. Pengguna membuka menu. 2. Sistem menampilkan data. 3. Pengguna membuat atau mengubah data. 4. Sistem memvalidasi dan menyimpan perubahan. | Data tidak valid: penyimpanan ditolak. Produk/kategori yang direferensikan transaksi harus diarsipkan, bukan dihapus permanen. | Data berhasil disimpan atau diperbarui. |
| FLOW-ADM-003 Pengaturan Harga | Owner atau Admin telah login dan memiliki permission konfigurasi harga. | 1. Pengguna membuka menu pricing. 2. Sistem menampilkan konfigurasi. 3. Pengguna mengubah tarif/parameter. 4. Sistem memvalidasi dan menyimpan konfigurasi. 5. Konfigurasi baru langsung digunakan oleh kalkulator PRD-02 untuk estimasi berikutnya. | Nilai negatif atau tidak valid: penyimpanan ditolak. Tidak ada approval berjenjang. Snapshot transaksi lama tetap. | Konfigurasi harga baru tersimpan dan digunakan untuk perhitungan berikutnya. |
| FLOW-ADM-004 Pengelolaan Akun Admin | Owner telah login dan membuka /admin/users. | 1. Owner melihat daftar akun Admin. 2. Owner membuat akun baru atau memilih akun yang ada. 3. Owner mengisi/mengubah nama, email, password bila diperlukan, dan status. 4. Sistem memvalidasi serta menyimpan data. 5. Owner dapat mengaktifkan/menonaktifkan akun Admin. | Admin mencoba membuka halaman: akses ditolak (403). Email duplikat atau data tidak valid: penyimpanan ditolak. | Akun Admin berhasil dikelola oleh Owner. |
 

G. Functional requirements register

| Requirement ID | Nama fungsi | Deskripsi ringkas | Aktor | Priority | Status |
| :---: | :---: | :---: | :---: | :---: | :---: |
| ADM-FR-001 | Admin Authentication | Sistem menyediakan login, logout, validasi akun, dan pengelolaan session admin. | Owner dan Admin | Must | Draft |
| ADM-FR-002 | Dashboard Overview | Sistem menampilkan ringkasan data dan navigasi pengelolaan. | Owner dan Admin | Must | Draft |
| ADM-FR-003 | Content Management | Sistem memungkinkan admin mengelola konten homepage, profil, kontak, dan informasi website. | Owner dan Admin | Must | Draft |
| ADM-FR-004 | Product & Category Management | Sistem menyediakan pengelolaan produk dan kategori, termasuk tambah, ubah, aktif/nonaktif, dan Archive/Soft Delete. Produk/kategori yang telah direferensikan transaksi tidak dapat dihapus permanen. | Owner dan Admin | Must | Draft |
| ADM-FR-005 | Product Variant Management | Sistem memungkinkan pengaturan variasi dan pilihan produk. | Owner dan Admin | Must | Draft |
| ADM-FR-006 | Pricing Configuration | Sistem memungkinkan pengaturan harga dasar, biaya tambahan, dan formula kalkulator. | Owner dan Admin | Must | Draft |
| ADM-FR-007 | Testimonial & Portfolio Management | Sistem menyediakan pengelolaan testimoni, ulasan, portofolio, dan kolaborasi. | Owner dan Admin | Should | Draft |
| ADM-FR-008 | Media & Publication Management | Sistem menyediakan upload media dan pengaturan status publikasi konten. | Owner dan Admin | Should | Draft |
| ADM-FR-009 | User & Role Management | Sistem menyediakan pengelolaan akun Admin yang hanya dapat diakses Owner. Owner dapat membuat, melihat, mengubah informasi, mengaktifkan, dan menonaktifkan akun Admin. Admin tidak dapat mengelola akun atau role pengguna lain. | Owner | Must | Draft |
 

H. Kartu spesifikasi rinci functional requirement

ADM-FR — Kartu Spesifikasi Functional Requirement 1

| Elemen | Isi / spesifikasi |
| :---: | :---: |
| Requirement ID | ADM-FR-001 |
| Nama requirement | Admin Authentication |
| Tujuan dan deskripsi | Menyediakan mekanisme autentikasi agar hanya pengguna yang memiliki akun dan izin yang dapat mengakses dashboard administrasi. |
| Aktor dan hak akses | Owner dan Admin yang memiliki akun terdaftar dan berstatus aktif. |
| Precondition dan trigger | Akun Owner/Admin telah terdaftar dan pengguna membuka halaman login. |
| Input dan field | Email (string, wajib), password (string, wajib). |
| Alur utama | 1. Owner atau Admin membuka halaman login. 2. Memasukkan email dan password. 3. Menekan tombol login. 4. Sistem memeriksa kecocokan kredensial serta status akun. 5. Sistem membuat session dan membuka dashboard sesuai role. |
| Alur alternatif | Jika kredensial salah, sistem menampilkan pesan kesalahan. Jika akun tidak aktif, sistem menolak akses. |
| Validasi dan business rules | Email harus memiliki format valid. Password wajib diisi. Session harus berakhir setelah logout atau masa berlaku habis. |
| Output dan feedback | Owner atau Admin berhasil masuk ke dashboard sesuai permission atau menerima pesan kesalahan autentikasi. |
| Error dan edge cases | Email kosong, password kosong, password salah, akun tidak aktif, dan session kedaluwarsa. |
| Dependencies | Database akun admin dan sistem autentikasi. |
| Priority | Must |
| Acceptance criteria | Given Owner atau Admin memiliki akun aktif, When memasukkan email dan password yang benar, Then sistem mengarahkan pengguna ke dashboard dengan permission sesuai role. |
 

ADM-FR — Kartu Spesifikasi Functional Requirement 2

| Elemen | Isi / spesifikasi |
| :---: | :---: |
| Requirement ID | ADM-FR-006 |
| Nama requirement | Pricing Configuration |
| Tujuan dan deskripsi | Memungkinkan admin mengatur harga dasar, biaya tambahan, dan parameter perhitungan yang digunakan dalam kalkulator produk atau layanan. |
| Aktor dan hak akses | Owner dan Admin yang telah login dan memiliki hak akses pengelolaan harga. |
| Precondition dan trigger | Owner/Admin telah login dan membuka halaman konfigurasi harga. |
| Input dan field | Nama konfigurasi/produk (wajib); model harga (tetap, mulai dari, kalkulator, atau quotation); tiga kelompok parameter yang memengaruhi harga beserta nama dan opsi per produk; matriks kombinasi opsi dan tarif; batas/tingkatan kuantitas bila digunakan; biaya tambahan bila digunakan; minimum order; tanggal mulai berlaku; status aktif/nonaktif. Jumlah pesanan merupakan input terpisah dan bukan salah satu dari tiga kelompok parameter harga. |
| Alur utama | (1) Owner atau Admin membuka halaman Pricing Configuration. (2) Sistem menampilkan daftar produk dan konfigurasi harga yang berlaku. (3) Pengguna memilih produk yang akan diatur. (4) Pengguna memasukkan atau mengubah tiga kelompok parameter harga beserta opsi masing-masing, matriks kombinasi tarif, dan aturan kuantitas/biaya tambahan bila digunakan. (5) Sistem memvalidasi kelengkapan kombinasi, format nilai, dan tarif. (6) Owner atau Admin menyimpan konfigurasi. (7) Sistem langsung menggunakan konfigurasi baru untuk estimasi pelanggan berikutnya. Perhitungan harga divalidasi di sisi server Laravel. |
| Alur alternatif | Owner atau Admin dapat membatalkan perubahan sebelum disimpan. Jika data tidak valid, sistem menolak penyimpanan dan mempertahankan konfigurasi aktif sebelumnya. Tidak ada proses persetujuan berjenjang. |
| Validasi dan business rules | Harga tidak boleh negatif. Perubahan harga yang disimpan Owner/Admin langsung berlaku untuk estimasi berikutnya tanpa approval berjenjang. Snapshot transaksi lama tidak berubah. |
| Output dan feedback | Konfigurasi harga berhasil disimpan dan tersedia untuk kalkulator. |
| Error dan edge cases | Harga kosong, nilai negatif, format tidak valid, perubahan bersamaan oleh admin lain, dan kegagalan penyimpanan. |
| Dependencies | Database konfigurasi harga dan integrasi kalkulator PRD-02. |
| Priority | Must |
| Acceptance criteria | Given Owner/Admin memiliki izin mengubah harga, When menyimpan konfigurasi valid, Then kalkulator memakai aturan baru untuk estimasi berikutnya dan transaksi sebelumnya tetap menggunakan snapshot. |
ADM-FR — Kartu Spesifikasi Functional Requirement 3

| Elemen | Isi / spesifikasi |
| :---: | :---: |
| Requirement ID | ADM-FR-009 |
| Nama requirement | User & Role Management |
| Tujuan dan deskripsi | Menyediakan pengelolaan akun Admin yang hanya dapat dilakukan oleh Owner. Sistem hanya memiliki dua role internal: Owner dan Admin. |
| Aktor dan hak akses | Owner saja. Admin tidak dapat membuka halaman User Management atau mengubah role/akun pengguna lain. |
| Precondition dan trigger | Owner telah login dan membuka /admin/users. |
| Input dan field | Nama Admin, email, role (tetap Admin), status akun (aktif/nonaktif), dan password awal atau mekanisme pengaturan password yang aman. |
| Alur utama | 1. Owner membuka daftar akun Admin. 2. Owner membuat akun atau memilih akun yang ada. 3. Owner mengisi/mengubah informasi akun. 4. Sistem memvalidasi email dan data akun. 5. Owner menyimpan perubahan atau mengaktifkan/menonaktifkan akun. 6. Sistem mencatat perubahan dalam audit log. |
| Alur alternatif | Admin mencoba membuka /admin/users: akses ditolak (403). Email duplikat atau data tidak valid: sistem menolak penyimpanan dan menampilkan pesan. |
| Validasi dan business rules | Email harus unik; password tidak disimpan sebagai teks biasa; role akun yang dibuat melalui fitur ini hanya Admin; akun nonaktif tidak dapat login. Pengelolaan akun Owner tidak dilakukan melalui halaman ini. |
| Output dan feedback | Daftar akun Admin dan statusnya diperbarui; perubahan tercatat dalam audit log. |
| Error dan edge cases | Email sudah terdaftar, akun nonaktif mencoba login, atau Admin mencoba mengakses halaman: tindakan ditolak tanpa mengubah data. |
| Dependencies | Autentikasi Laravel, role/permission Owner/Admin, database User/Role, audit log. |
| Priority | Must |
| Acceptance criteria | Given Owner login, When Owner membuat akun Admin dengan email valid, Then akun tersimpan dan dapat login sesuai permission Admin. Given Admin login, When Admin membuka /admin/users, Then sistem menolak akses. |
I. Business rules domain

| Rule ID | Aturan bisnis | Kondisi / rumus / batasan | Dampak jika tidak terpenuhi |
| :---: | :---: | :---: | :---: |
| ADM-BR-001 | Akses dashboard harus melalui autentikasi. | Setiap halaman admin memerlukan session yang valid dan hak akses yang sesuai. | Sistem menolak akses dan mengarahkan pengguna ke halaman login. |
| ADM-BR-002 | Konten harus memiliki status publikasi. | Konten berstatus draft tidak ditampilkan pada halaman publik. | Sistem tidak menampilkan konten yang belum dipublikasikan. |
| ADM-BR-003 | Data produk harus memiliki kategori. | Setiap produk wajib terhubung dengan kategori yang valid. | Produk tidak dapat disimpan jika kategori wajib belum dipilih. |
| ADM-BR-004 | Konfigurasi harga harus valid. | Harga dasar dan biaya tambahan tidak boleh bernilai negatif. | Sistem menolak konfigurasi yang tidak valid. |
| ADM-BR-005 | Perubahan data master tidak mengubah transaksi lama. | Transaksi menyimpan snapshot harga dan spesifikasi pada saat transaksi dibuat. | Sistem harus mempertahankan data transaksi sebelumnya. |
| ADM-BR-006 | Produk/kategori historis tidak dihapus permanen. | Produk yang telah digunakan transaksi diubah menjadi archived/inactive. Produk arsip disembunyikan dari katalog dan tidak dapat digunakan pada pesanan baru, tetapi tetap tersedia untuk histori/snapshot. Kategori yang masih memiliki produk harus dipindahkan atau produk terkait diarsipkan lebih dahulu. | Penghapusan permanen dapat memutus relasi dan merusak riwayat transaksi. |
 

J. Data requirements

| Entitas / data | Field / atribut | Wajib? | Validasi / format | Sumber / relasi |
| :---: | :---: | :---: | :---: | :---: |
| User | user_id, name, email, password_hash, role_id, status, created_at, updated_at | Ya | Email unik; password hash; role hanya owner/admin; status aktif/nonaktif. | Terhubung dengan Role. |
| Role | role_id, role_name, permissions | Ya | role_name hanya owner atau admin; unik; permission mengikuti Master PRD 5.2. | Terhubung dengan User; satu akun memiliki satu role. |
| Content | content_id, title, slug, description, content_type, image, status, updated_at | Ya | Judul wajib, slug unik, status draft/published. | Digunakan pada halaman publik. |
| Product | product_id, category_id, product_name, description, base_price, status, archived_at | Ya | Nama wajib, harga tidak negatif, kategori valid; status active/inactive/archived; archived_at terisi saat arsip. | Terhubung dengan Category dan Product Variant; referensi historis tetap valid. |
| Category | category_id, category_name, description, status, archived_at | Ya | Nama kategori wajib dan tidak duplikat; kategori aktif tidak boleh dihapus jika masih memiliki produk aktif. | Terhubung dengan Product; kategori digunakan histori tidak dihapus permanen. |
| Product Variant | variant_id, product_id, variant_name, option_value, additional_price, status | Sesuai kebutuhan | Harga tambahan tidak negatif dan produk harus tersedia. | Terhubung dengan Product. |
| Pricing Configuration | pricing_id, product_id, base_price, additional_cost, formula, effective_date, status | Ya | Nilai numerik valid dan tanggal berlaku harus jelas. | Digunakan PRD-02 |
| Media | media_id, file_name, file_type, file_size, file_path, uploaded_at | Ya | Format file dan ukuran harus sesuai ketentuan upload. | Terhubung dengan Content, Product, dan Gallery. |
| Testimonial | testimonial_id, customer_name, message, rating, status | Sesuai kebutuhan | Rating sesuai skala yang ditentukan dan status publikasi valid. | Ditampilkan pada halaman publik setelah disetujui. |
| Portfolio | portfolio_id, title, description, image, status, created_at | Sesuai kebutuhan | Judul dan informasi utama wajib. | Ditampilkan pada halaman portofolio publik. |
 

K. Validation, error handling, dan edge cases

| Case ID | Kondisi / skenario | Validasi atau respons sistem yang diharapkan | Severity |
| --- | --- | --- | --- |
| ADM-EC-001 | Admin mengirim form dengan field wajib kosong atau format tidak valid. | Sistem menolak penyimpanan dan menampilkan pesan pada field yang bermasalah. | High |
| ADM-EC-002 | Admin memasukkan nama kategori atau slug yang sudah digunakan. | Sistem menolak data duplikat dan meminta perubahan. | Medium |
| ADM-EC-003 | Upload gambar gagal karena format tidak didukung atau jaringan terputus. | Sistem menampilkan pesan kegagalan dan tidak menyimpan file yang tidak lengkap. | Medium |
| ADM-EC-004 | Pengguna mencoba mengakses dashboard tanpa autentikasi atau izin. | Sistem menolak akses dan mengarahkan pengguna ke halaman yang sesuai. | High |
| ADM-EC-005 | Dua admin mengubah konfigurasi harga secara bersamaan. | Sistem mendeteksi konflik perubahan dan meminta admin memuat ulang data sebelum menyimpan. | High |
| ADM-EC-006 | Admin mencoba menghapus kategori yang masih memiliki produk. | Sistem menolak penghapusan atau meminta produk dipindahkan terlebih dahulu. | Medium |
| ADM-EC-007 | Admin mencoba menghapus permanen produk yang telah direferensikan transaksi atau kategori yang masih memiliki produk aktif. | Sistem menolak penghapusan permanen dan menyediakan Archive/Soft Delete. Produk arsip tidak tampil untuk pesanan baru, tetapi snapshot historis tetap terbaca. | High |
 

L. Acceptance criteria dan test scenarios

| Test ID | Requirement terkait | Given (kondisi awal) | When (aksi) | Then (hasil yang diharapkan) | Status |
| :---: | :---: | :---: | :---: | :---: | :---: |
| ADM-AT-001 | ADM-FR-001 | Admin memiliki akun aktif. | Admin memasukkan email dan password yang benar. | Sistem berhasil mengarahkan admin ke dashboard. | Belum diuji |
| ADM-AT-002 | ADM-FR-003 | Admin telah login dan memiliki izin pengelolaan konten. | Admin menyimpan konten baru dengan data valid. | Konten tersimpan sebagai draft atau published sesuai pilihan admin. | Belum diuji |
| ADM-AT-003 | ADM-FR-004 | Admin berada pada halaman pengelolaan produk. | Admin menambahkan produk dengan kategori dan harga valid. | Produk berhasil tersimpan dan tampil pada daftar produk. | Belum diuji |
| ADM-AT-004 | ADM-FR-006 | Admin memiliki izin konfigurasi harga. | Admin mengubah harga dasar dengan nilai negatif. | Sistem menolak perubahan dan menampilkan pesan validasi. | Belum diuji |
| ADM-AT-005 | ADM-FR-008 | Admin telah login. | Admin mengunggah gambar dengan format yang didukung. | Gambar berhasil tersimpan dan dapat digunakan pada konten. | Belum diuji |
| ADM-AT-006 | ADM-FR-009 | Admin telah login. | Admin mencoba membuka /admin/users atau mengubah role pengguna. | Sistem menolak akses (403) dan tidak mengubah akun/role. | Belum diuji |
| ADM-AT-007 | ADM-FR-004 | Produk telah digunakan dalam transaksi. | Admin memilih Arsipkan Produk. | Produk tidak tampil di katalog dan tidak dapat dipakai order baru; snapshot transaksi lama tetap dapat dibuka. | Belum diuji |
| ADM-AT-008 | ADM-FR-004 | Kategori masih memiliki produk aktif. | Admin mencoba mengarsipkan atau menghapus kategori tanpa memindahkan produk. | Sistem menolak tindakan atau meminta produk dipindahkan/diarsipkan terlebih dahulu. | Belum diuji |
| ADM-AT-009 | ADM-FR-009 | Owner telah login. | Owner membuat akun Admin baru. | Akun Admin tersimpan dan dapat digunakan untuk login. | Belum diuji |
 

M. Non-functional requirements domain

| Kategori | Kebutuhan khusus domain | Ukuran / target uji |
| :---: | :---: | :---: |
| Usability | Dashboard harus memiliki navigasi yang jelas, konsisten, dan mudah dipahami oleh admin. | Admin dapat menemukan menu utama dalam maksimal 3 langkah navigasi. |
| Performance | Halaman dashboard dan proses penyimpanan data harus memberikan respons dengan cepat. | Waktu respons halaman utama maksimal 3 detik pada kondisi jaringan normal. |
| Security / privacy | Password harus disimpan dalam bentuk hash, session dilindungi, dan halaman admin hanya dapat diakses oleh pengguna berizin. | Seluruh route admin memeriksa autentikasi dan otorisasi. |
| Compatibility / accessibility | Dashboard dapat digunakan melalui browser desktop dan perangkat mobile dengan tampilan responsif. | Berfungsi pada versi terbaru Chrome, Firefox, dan Edge. |
 

N. Dependencies dan integrasi lintas domain

| Domain terkait | Data / fungsi yang dibutuhkan | Data / fungsi yang diberikan | Kesepakatan interface |
| --- | --- | --- | --- |
| PRD-02 – Calculator | Data produk, variasi, harga dasar, biaya tambahan, dan formula kalkulator. | Konfigurasi harga dan data master produk. | Data dikirim melalui API atau database bersama. Kalkulator mengambil konfigurasi yang sedang berlaku. |
| PRD-05 – Order & Finance | Data produk dan konfigurasi harga yang digunakan saat transaksi dibuat. | Data master produk dan konfigurasi harga terbaru. | PRD-05 mengambil data produk dan harga saat transaksi dibuat, kemudian menyimpan snapshot agar perubahan berikutnya tidak memengaruhi transaksi lama. |
| Domain Website Publik | Konten homepage, profil, kontak, produk, kategori, testimoni, portofolio, dan media. | Konten yang telah dipublikasikan dan produk yang berstatus aktif. | Website publik hanya menampilkan data yang telah dipublikasikan atau diaktifkan. |
 
# 19. FIN — SALES, TRANSACTION & FINANCIAL MANAGEMENT SYSTEM
Penanggung jawab: Anggota 5 — Zhaqtiawan Dimas Anugrah. Status: Selesai.

- A. Tujuan domain dan ringkasan solusi

Menyediakan sistem internal bagi admin untuk mengelola pesanan dan permintaan yang masuk dari website, mencatat transaksi manual dari WhatsApp, Instagram, dan kanal langsung, mengelola pembayaran (DP, bertahap, pelunasan), mencatat pembelian bahan dan pengeluaran, menerbitkan invoice PDF, serta menyajikan laporan sederhana berdasarkan periode. Seluruh modul ada di area admin dan hanya dapat diakses pengguna internal yang terautentikasi.

Catatan khusus domain: satu transaksi dapat berisi beberapa item (item katalog atau custom item). Satu transaksi dapat memiliki banyak catatan pembayaran. Sisa tagihan = total transaksi dikurangi total pembayaran valid. Status pengerjaan dan status pembayaran disimpan terpisah. Invoice dan item transaksi memakai snapshot. Pembelian bahan hanya dicatat sebagai pengeluaran, tanpa stok real-time.

Masalah pengguna. Pesanan datang dari banyak kanal (website, WhatsApp, Instagram, langsung). Tanpa pencatatan terpusat, admin sulit mengetahui pesanan mana yang sudah dibayar, berapa sisa tagihan, berapa pengeluaran bulan ini, dan harus membuat invoice secara manual. Harga custom yang disepakati lewat chat juga mudah hilang atau berubah.

Solusi domain. Domain FIN menjadi satu sumber data transaksi. Order dari PRD-03 masuk ke daftar yang sama dengan transaksi manual. Request custom ditinjau, diberi penawaran, lalu dikonversi menjadi order hanya setelah pelanggan setuju. Setiap order menyimpan item dengan snapshot nama, spesifikasi, jumlah, dan harga kesepakatan. Pembayaran dicatat bertahap dan sistem menghitung total dibayar, sisa tagihan, dan status pembayaran otomatis. Invoice PDF dibuat dari snapshot transaksi. Pengeluaran dicatat per kategori dan direkap bersama pembayaran diterima dalam laporan berfilter periode.

Nilai bagi produk. Mendukung OBJ-04, OBJ-05, dan OBJ-06 pada Master PRD: transaksi terpusat, pembayaran dan pengeluaran terekap, serta invoice konsisten dengan data transaksi.

Hasil akhir yang diharapkan.

1. Order website dan request custom tampil di dashboard dengan identitas pelanggan, item, dan estimasi yang benar.

2. Admin dapat membuat transaksi manual dengan pelanggan lama atau baru dan item katalog atau custom.

3. Total transaksi dihitung konsisten dan harga kesepakatan tersimpan sebagai snapshot.

4. Pembayaran parsial dan pelunasan menghasilkan total dibayar, sisa tagihan, dan status pembayaran yang benar.

5. Status pengerjaan dan status pembayaran dikelola terpisah.

6. Pengeluaran tercatat tanpa memengaruhi stok.

7. Invoice PDF terbit sesuai data transaksi dan pembayaran.

8. Laporan penjualan, pembayaran, dan pengeluaran dapat difilter berdasarkan periode.

- B. Tujuan, scope, dan batasan domain

| Aspek | Isi |
| --- | --- |
| Tujuan domain | Menyatukan pencatatan dan administrasi transaksi dari semua kanal, mulai dari peninjauan request, pembuatan order, pembayaran, pengeluaran, invoice, sampai laporan sederhana. |
| In scope | Manajemen pelanggan; peninjauan custom request dan konversi ke order; transaksi manual; item katalog/custom dan snapshot; perhitungan total; pencatatan pembayaran QRIS yang diverifikasi manual; konfigurasi QRIS oleh Owner; Void Owner-only; Refund manual parsial/penuh Owner-only; tiga kelompok status terpisah dan histori; pembelian bahan/pengeluaran; invoice PDF immutable; laporan operasional; pembatalan transaksi final oleh Owner dengan histori. |
| Out of scope | Cart, checkout, dan formulir custom request pelanggan (PRD-03); master produk, opsi, tarif, dan konten (PRD-04); katalog dan kalkulator publik (PRD-02); stok dan persediaan real-time; tracking pesanan real-time; payment gateway dan verifikasi otomatis; pajak, pembukuan, neraca, dan laporan standar akuntansi; integrasi ekspedisi; perhitungan biaya produksi kompleks. |
| Asumsi | (1) Order/request dari ORD tiba sesuai kontrak data. (2) Produk dan profil usaha tersedia dari PRD-04. (3) Mata uang Rupiah tanpa desimal. (4) Metode pembayaran versi pertama hanya QRIS dan diverifikasi manual. (5) DP ditentukan per transaksi tanpa persentase/minimum global. (6) FIN mengelola konfigurasi QRIS; Owner mengubah, Admin hanya membaca. (7) Refund manual parsial/penuh dicatat Owner setelah dana dikembalikan. (8) Status request, pengerjaan order, dan pembayaran disimpan terpisah. |
| Stakeholder / aktor | Admin sebagai pengguna utama operasional transaksi, pelanggan, order, pembayaran, pengeluaran, invoice awal, dan laporan; Owner sebagai pemilik role dengan akses penuh serta kewenangan konfigurasi QRIS, void/refund, pembatalan transaksi final, dan pembatalan/revisi invoice; pelanggan sebagai penerima informasi/invoice; PRD-03 dan PRD-04 sebagai domain integrasi. |
- C. Daftar subfitur yang wajib dianalisis

| Subfitur awal | Spesifikasi | Prioritas |
| --- | --- | --- |
| Customer management dan customer history | Daftar pelanggan dengan pencarian nama atau nomor kontak. Pelanggan dibuat dari guest checkout (PRD-03) atau input manual. Halaman detail memuat kontak, riwayat order, total transaksi, dan total sisa tagihan. Pelanggan dengan riwayat tidak dihapus permanen, hanya dinonaktifkan. Keluaran: data pelanggan tunggal tanpa duplikasi. | Must |
| Order/request review dan order management | Daftar order dan request dari website dengan filter jenis, status, tanggal, dan pencarian. Admin meninjau detail, mengubah status request, memasukkan penawaran, dan mengonversi request yang disetujui menjadi order. Request belum dianggap transaksi final. Keluaran: order terkonfirmasi atau request tertutup (ditolak/kedaluwarsa). | Must |
| Manual sales/transaction entry dari berbagai kanal | Form transaksi untuk pesanan dari WhatsApp, Instagram, atau langsung. Menyimpan kanal asal, pelanggan, item, catatan, dan tanggal. Keluaran: transaksi dengan nomor unik. | Must |
| Transaction items: pilih katalog atau custom item | Item katalog dipilih dari data PRD-04 dengan nama dan spesifikasi disalin sebagai snapshot. Custom item diisi nama, deskripsi, kuantitas, dan harga. Satu transaksi memuat banyak item. Keluaran: daftar item dengan snapshot. | Must |
| Harga satuan, kuantitas, diskon/biaya tambahan, total | Subtotal item = kuantitas × harga satuan. Total = jumlah subtotal item − diskon + biaya tambahan. Harga satuan dapat diubah admin untuk harga kesepakatan dan menjadi nilai transaksi yang tidak berubah otomatis. Keluaran: total tagihan. | Must |
| Payment management: DP, pembayaran bertahap, pelunasan, dan Void | Admin dan Owner mencatat pembayaran QRIS setelah verifikasi manual. Sistem menghitung Pembayaran Valid, Refund Selesai, Pembayaran Bersih, sisa tagihan, dan status pembayaran. Void untuk catatan salah hanya dapat dilakukan Owner; riwayat tidak dihapus. | Must |
| Status request, pengerjaan, dan pembayaran | FIN mengelola tiga lifecycle terpisah: status custom request, status pengerjaan order, dan status pembayaran. Status request/pengerjaan diperbarui sesuai transisi yang valid dan dicatat pada histori; status pembayaran dihitung otomatis dari Pembayaran Bersih. | Must |
| Purchase record pembelian bahan | Catatan pembelian bahan berisi tanggal, kategori, nama bahan, kuantitas, harga satuan, pemasok, dan keterangan. Total dihitung sistem. Tidak mengubah stok. | Must |
| Expense management biaya operasional | Catatan biaya non-bahan (listrik, transport, sewa, dan sebagainya) dengan kategori dan tanggal. Dapat dicari, difilter, dan direkap. | Must |
| Invoice PDF dan payment receipt jika diperlukan | Invoice PDF dibuat dari snapshot transaksi, pelanggan, identitas usaha, item, total, Pembayaran Valid, Refund Selesai, Pembayaran Bersih, dan sisa tagihan pada waktu penerbitan. Invoice awal dapat diterbitkan Admin/Owner; pembatalan/revisi oleh Owner. Bukti pembayaran per pembayaran tetap opsional. | Invoice: Must; bukti pembayaran: Could |
| Laporan penjualan, pembayaran, refund, dan pengeluaran | Laporan memisahkan penjualan, Pembayaran Valid, Refund Selesai, Pembayaran Bersih, pembelian bahan, dan pengeluaran operasional dengan filter periode/kategori. Selisih Kas Sederhana bukan laba akuntansi. Ekspor CSV opsional. | Must (ringkasan); Could (ekspor) |
| Pembatalan transaksi final dan riwayat perubahan | Hanya Owner dapat membatalkan transaksi final dengan alasan wajib. Transaksi, pembayaran, dan refund tetap disimpan. Pembayaran yang telah diterima ditangani melalui Refund bila dana benar-benar dikembalikan. | Must |
| Dashboard ringkas keuangan (subfitur tambahan) | Kartu ringkasan: pesanan baru, tagihan belum lunas, pembayaran bulan ini, pengeluaran bulan ini. Alasan: bahan pantauan harian untuk Owner. Tampilan dikoordinasikan dengan PRD-04. | Could |
| Refund Management | Owner mencatat refund manual parsial/penuh atas payment valid setelah pengembalian dana dilakukan. Sistem memvalidasi maksimum refund, menyimpan alasan/referensi, dan menghitung ulang Pembayaran Bersih, Sisa Tagihan, dan Status Refund. | Must |
| Payment Method & Payment Instruction Settings | Owner mengelola konfigurasi QRIS dan teks instruksi; Admin hanya dapat melihat konfigurasi aktif untuk mengirimkannya secara manual melalui WhatsApp setelah harga final dan nominal pembayaran disepakati. | Must |
- D. User roles dan permission dalam domain

| Aktor / role | Melihat | Membuat | Mengubah | Menghapus / tindakan khusus |
| --- | --- | --- | --- | --- |
| Owner | Ya: seluruh data dan laporan. | Ya: seluruh transaksi dan data keuangan. | Ya: seluruh data operasional dan konfigurasi. | Dapat mengelola akun Admin, konfigurasi QRIS, Void, Refund, pembatalan transaksi final, serta pembatalan/revisi invoice. |
| Admin | Ya: order, request, pelanggan, transaksi, pembayaran, pengeluaran, invoice, laporan. | Ya: transaksi manual, pelanggan, pembayaran, pengeluaran, dan invoice awal. | Ya: data operasional sesuai aturan, status pengerjaan, dan data pengeluaran. | Tidak dapat mengelola akun/role, mengubah konfigurasi QRIS, melakukan Void/Refund, membatalkan transaksi final, atau membatalkan/merevisi invoice. |
| Customer Guest | Tidak memiliki akses ke dashboard atau data transaksi internal. | Tidak | Tidak | Hanya menerima informasi dan dokumen yang dikirim Admin/Owner. |
| Visitor | Tidak memiliki akses ke dashboard internal. | Tidak | Tidak | Tidak memiliki akses. |
- E. Page / screen specifications

| Page ID / nama | Route / akses | Tujuan dan isi utama | Aksi / interaksi utama |
| --- | --- | --- | --- |
| PAGE-FIN-001 Daftar Order & Request | /admin/orders; Admin, Owner | Daftar semua order dan request. Kolom: nomor, tanggal, pelanggan, kanal, jenis, total, status request/pengerjaan, status pembayaran. Filter jenis, status, kanal, tanggal. Pencarian nama, nomor kontak, nomor transaksi. Kondisi: loading, kosong, error, pembagian halaman. | Cari, filter, urutkan, buka detail, buat transaksi manual. |
| PAGE-FIN-002 Detail Order / Request | /admin/orders/{id}; Admin, Owner | Identitas pelanggan; item snapshot; file referensi; harga/total final; status request (bila masih request); status pengerjaan order; status pembayaran hasil perhitungan; histori perubahan status; histori pembayaran dan refund; catatan internal; tautan invoice. Status request tidak ditampilkan sebagai status aktif setelah konversi. | Ubah status yang valid, isi penawaran, konversi request, edit item sesuai permission, catat pembayaran, catat refund (Owner), terbitkan invoice, batalkan transaksi (Owner). |
| PAGE-FIN-003 Form Transaksi Manual | /admin/orders/create dan /admin/orders/{id}/edit; Admin, Owner | Pilih/buat pelanggan, kanal asal, tanggal, item katalog/custom, diskon, biaya tambahan, catatan, informasi pickup saja, total otomatis. Tidak ada pilihan pengiriman atau ongkir. | Tambah/hapus item, hitung total, simpan, batal sesuai permission. |
| PAGE-FIN-004 Daftar & Detail Pelanggan | /admin/customers dan /admin/customers/{id}; Admin, Owner | Daftar pelanggan dan detail: kontak, riwayat order, total transaksi, total sisa tagihan. | Cari, tambah, ubah, nonaktifkan, buka order terkait. |
| PAGE-FIN-005 Panel & Form Pembayaran | Bagian PAGE-FIN-002; modal form; Admin dan Owner | Riwayat payment (tanggal, nominal, metode QRIS, referensi, status Valid/Void), refund terkait, total Pembayaran Valid, Refund Selesai, Pembayaran Bersih, dan Sisa Tagihan. Form pembayaran: tanggal, nominal, metode QRIS, referensi, keterangan. | Admin/Owner menambahkan pembayaran setelah verifikasi manual. Void dan Refund hanya Owner; alasan wajib. |
| PAGE-FIN-006 Daftar Pembelian & Pengeluaran | /admin/expenses; Admin, Owner | Daftar catatan dengan filter tanggal, kategori, pemasok, dan total per filter. | Cari, filter, tambah, ubah, buka detail, hapus-arsip. |
| PAGE-FIN-007 Form Pembelian / Pengeluaran | /admin/expenses/create dan /edit; Admin, Owner | Tanggal, jenis (bahan atau operasional), kategori, nama barang, kuantitas, harga satuan, pemasok, keterangan, total otomatis, lampiran nota opsional. | Simpan, batal, tambah baris barang. |
| PAGE-FIN-008 Daftar & Pratinjau Invoice | /admin/invoices dan /admin/invoices/{id}; Admin, Owner | Daftar invoice (nomor, tanggal, pelanggan, total, status, invoice yang direvisi). Pratinjau PDF dari snapshot immutable. | Admin/Owner menerbitkan invoice awal dan mengunduh PDF; hanya Owner membatalkan invoice dan menerbitkan revisi. |
| PAGE-FIN-009 Laporan | /admin/reports; Admin, Owner | Tab Penjualan, Pembayaran, Refund, Pembelian Bahan, Pengeluaran Operasional. Menampilkan Pembayaran Valid, Refund Selesai, Pembayaran Bersih, sisa tagihan, total pengeluaran, dan Selisih Kas Sederhana. | Pilih periode/filter kategori/kanal/metode, lihat rincian, ekspor CSV bila digunakan. |
| PAGE-FIN-010 Payment Method & Payment Instruction Settings | /admin/payment-settings; Owner dapat melihat/mengubah; Admin read-only | Mengelola satu metode pembayaran versi pertama yaitu QRIS. Informasi: media/gambar atau identitas QRIS, teks instruksi, status aktif, pengguna pengubah, dan waktu perubahan. | Owner unggah/ganti informasi QRIS dan teks instruksi; Admin hanya melihat konfigurasi aktif untuk mengirim pesan WhatsApp manual. Tidak ada payment gateway, QRIS dinamis, atau verifikasi otomatis. |
- F. User flow dan workflow

| Flow ID / nama | Trigger & precondition | Main flow (langkah) | Alternative / exception | Output |
| --- | --- | --- | --- | --- |
| FLOW-FIN-001 Review Request dan Konversi ke Order | Custom request masuk dari ORD; Admin/Owner login. | 1. Pengguna membuka request Baru Diterima. 2. Meninjau kebutuhan, spesifikasi, jumlah, dan lampiran. 3. Mengubah status ke Ditinjau Admin; bila perlu meminta informasi tambahan. 4. Memasukkan penawaran harga/ketentuan dan mengubah ke Penawaran Dikirim. 5. Setelah pelanggan setuju, status menjadi Disetujui Pelanggan. 6. Admin/Owner mengonversi request menjadi order dengan snapshot harga final; status pengerjaan Menunggu Pembayaran dan pembayaran Belum Dibayar. 7. Setelah nominal DP/pelunasan disepakati, Admin mengambil QRIS aktif dari FIN dan mengirim instruksi manual melalui WhatsApp. | Request dapat Ditolak atau Kedaluwarsa dengan alasan. Request yang belum disetujui tidak menjadi penjualan dan tidak memperoleh instruksi pembayaran. Konversi ganda ditolak. | Order custom terbentuk setelah persetujuan; instruksi QRIS dikirim hanya sesudah konversi dan kesepakatan pembayaran. |
| FLOW-FIN-002 Transaksi manual | Pesanan diterima lewat WhatsApp, Instagram, atau langsung. | 1. Admin membuka form transaksi. 2. Memilih pelanggan lama atau mengisi pelanggan baru. 3. Memilih kanal asal. 4. Menambah item katalog atau custom. 5. Mengisi kuantitas, harga satuan, diskon, biaya tambahan, catatan. 6. Sistem menghitung total. 7. Admin menyimpan; sistem membuat nomor transaksi. | Data wajib kosong: validasi per kolom. Nomor kontak sudah ada: sistem menawarkan memakai pelanggan yang ada. Gagal simpan: data form tidak hilang. | Transaksi tersimpan dengan status awal. |
| FLOW-FIN-003 Pembayaran QRIS bertahap | Order aktif, status Menunggu Pembayaran/Diproses, dan memiliki sisa tagihan. | 1. Admin memeriksa dana QRIS secara manual. 2. Setelah dana diterima, Admin membuka detail dan memilih Tambah Pembayaran. 3. Mengisi tanggal, nominal, metode QRIS, referensi bila ada, dan keterangan DP/angsuran/pelunasan. 4. Sistem memvalidasi nominal terhadap sisa tagihan bersih. 5. Sistem menyimpan payment Valid, lalu menghitung Pembayaran Bersih, Sisa Tagihan, dan status pembayaran. Status pengerjaan tidak berubah otomatis. | Jika dana belum terverifikasi, payment tidak dapat dicatat Valid. Catatan yang salah di-void oleh Owner dengan alasan; Void bukan refund. | Status pembayaran dan sisa tagihan konsisten berdasarkan payment dan refund. |
| FLOW-FIN-004 Penerbitan, Pembatalan, dan Revisi Invoice | Transaksi tersimpan, tidak dibatalkan, minimal satu item. | 1. Admin/Owner meminta penerbitan invoice awal. 2. Sistem menampilkan preview. 3. Sistem membuat nomor unik dan menyimpan snapshot identitas usaha/pelanggan, item, total, payment, refund, Pembayaran Bersih, dan sisa tagihan. 4. Sistem menghasilkan PDF. 5. Jika koreksi diperlukan, hanya Owner yang dapat membatalkan invoice dengan alasan dan menerbitkan invoice baru dengan nomor baru serta referensi ke invoice sebelumnya. | Gagal PDF: invoice tidak dianggap terbit. Invoice lama tidak dihapus atau ditimpa; revisi tidak mengubah snapshot lama. | Invoice PDF konsisten dengan snapshot; riwayat invoice awal/revisi tetap tersimpan. |
| FLOW-FIN-005 Pencatatan pengeluaran | Admin membeli bahan atau membayar biaya operasional. | 1. Membuka form. 2. Mengisi tanggal, jenis, kategori, barang, kuantitas, harga, pemasok. 3. Sistem menghitung total. 4. Admin menyimpan. | Kuantitas atau harga tidak valid: ditolak. Entri tampak ganda: peringatan konfirmasi. | Catatan pengeluaran tersimpan dan masuk rekap. |
| FLOW-FIN-006 Laporan periode | Data transaksi, payment, refund, dan pengeluaran tersedia. | 1. Admin/Owner membuka laporan dan memilih periode/filter. 2. Sistem menghitung penjualan dari transaksi tidak dibatalkan; Pembayaran Valid menurut tanggal bayar; Refund Selesai menurut tanggal refund; Pembayaran Bersih = Pembayaran Valid − Refund Selesai; pembelian bahan/pengeluaran menurut tanggal catatan; sisa tagihan; Selisih Kas Sederhana = Pembayaran Bersih − Total Pengeluaran. 3. Sistem menampilkan ringkasan dan rincian. | Tanggal tidak valid: validasi. Tidak ada data: angka nol. Transaksi batal tidak masuk penjualan, tetapi payment/refund tetap direkap terpisah. | Laporan operasional sederhana sesuai hasil perhitungan sumber; bukan laba akuntansi. |
| FLOW-FIN-007 Perubahan Status Pengerjaan | Admin/Owner telah login dan membuka detail order. | 1. Pengguna memilih status berikutnya yang valid. 2. Sistem memeriksa transisi status. 3. Pengguna mengisi catatan bila diperlukan. 4. Sistem menyimpan status baru dan Order Status History (status lama/baru, pengguna, waktu, catatan). 5. Saat Siap Diambil, Admin menghubungi pelanggan melalui WhatsApp manual. 6. Setelah pelanggan mengambil barang, Admin mengubah status ke Selesai. Status Dibatalkan untuk transaksi final hanya Owner. | Transisi tidak valid atau pengguna tidak memiliki permission: sistem menolak dan tidak mengubah status. Status tidak dipublikasikan ke pelanggan. | Status internal dan histori tercatat; tidak ada halaman cek status pelanggan. |
| FLOW-FIN-008 Kelola Konfigurasi QRIS | Owner telah login dan membuka /admin/payment-settings. | 1. Owner melihat konfigurasi QRIS aktif. 2. Owner mengunggah/mengganti media/identitas QRIS dan teks instruksi. 3. Sistem memvalidasi dan menyimpan konfigurasi. 4. Sistem mencatat Owner, waktu, dan perubahan dalam audit log. 5. Admin dapat melihat konfigurasi aktif dalam mode read-only. | Admin mencoba mengubah konfigurasi: ditolak. Berkas/data tidak valid atau gagal simpan: konfigurasi lama tetap aktif. | Konfigurasi QRIS aktif tersedia untuk Admin; digunakan hanya setelah harga final dan nominal pembayaran disepakati. |
| FLOW-FIN-009 Refund Management | Owner telah login; pembayaran sumber berstatus Valid dan memiliki saldo yang belum dikembalikan. | 1. Owner membuka detail payment. 2. Owner memilih Refund dan mengisi nominal, tanggal, metode pengembalian, referensi, serta alasan. 3. Owner melakukan pengembalian dana secara manual di luar sistem. 4. Setelah dana benar-benar dikembalikan, Owner mencatat refund sebagai Selesai. 5. Sistem memvalidasi batas refund, menyimpan refund terpisah, dan menghitung ulang Pembayaran Bersih/Sisa Tagihan/Status Refund. | Nominal melebihi saldo yang dapat dikembalikan, payment sumber Void, atau data tidak lengkap: sistem menolak. Refund tidak memindahkan uang otomatis. | Refund parsial/penuh tercatat terpisah dari Payment dan terhubung ke payment sumber. |
- G. Functional requirements register

| Requirement ID | Nama fungsi | Deskripsi ringkas | Aktor | Priority | Status |
| --- | --- | --- | --- | --- | --- |
| FIN-FR-001 | Daftar dan pencarian order/request | Menampilkan order dan request dari semua kanal dengan filter dan pencarian. | Admin, Owner | M | Draft |
| FIN-FR-002 | Penerimaan order dan request dari PRD-03 | Menyimpan data kiriman PRD-03 sebagai request atau order dengan pelanggan, kode, item, lampiran, dan snapshot sesuai kontrak data. | Sistem | M | Draft |
| FIN-FR-003 | Peninjauan request dan penawaran | Mengubah status request, meninjau kebutuhan/lampiran, serta menyimpan penawaran harga dan persyaratan yang disampaikan kepada pelanggan. | Admin, Owner | M | Draft |
| FIN-FR-004 | Konversi request ke order | Membuat satu order dari custom request yang disetujui pelanggan, menyimpan snapshot harga final, dan mencegah konversi ganda. | Admin, Owner | M | Draft |
| FIN-FR-005 | Transaksi manual | Membuat transaksi dari kanal luar website seperti WhatsApp, Instagram, atau pemesanan langsung. | Admin, Owner | M | Draft |
| FIN-FR-006 | Manajemen item transaksi | Menambah, mengubah, atau mengarsipkan item katalog/custom dengan snapshot nama, spesifikasi, kuantitas, dan harga. | Admin, Owner | M | Draft |
| FIN-FR-007 | Perhitungan total transaksi | Menghitung subtotal item, diskon, biaya tambahan, total tagihan, Pembayaran Bersih, dan Sisa Tagihan di sisi server. | Sistem | M | Draft |
| FIN-FR-008 | Pencatatan pembayaran (DP, bertahap, pelunasan) | Mencatat pembayaran QRIS yang telah diverifikasi manual oleh Admin, menghitung Pembayaran Valid, Pembayaran Bersih, Sisa Tagihan, dan status pembayaran. | Admin, Owner | M | Draft |
| FIN-FR-009 | Void pembayaran | Membatalkan catatan pembayaran yang salah dengan alasan wajib tanpa menghapus riwayat. Payment Void dikecualikan dari Pembayaran Valid dan Pembayaran Bersih. | Owner | M | Draft |
| FIN-FR-010 | Manajemen status request, pengerjaan, dan pembayaran | Mengelola status request dan status pengerjaan secara terpisah, memvalidasi transisi, menyimpan histori, serta menghitung status pembayaran dari Pembayaran Bersih secara otomatis. | Admin, Owner, Sistem | M | Draft |
| FIN-FR-011 | Manajemen pelanggan dan riwayat | Membuat, mencari, mengubah, menonaktifkan pelanggan, serta melihat riwayat order dan transaksi terkait. | Admin, Owner | M | Draft |
| FIN-FR-012 | Pencatatan pembelian bahan dan pengeluaran | Mencatat pembelian bahan dan biaya operasional dengan kategori, kuantitas, harga, tanggal, dan total otomatis. Tidak mengelola stok. | Admin, Owner | M | Draft |
| FIN-FR-013 | Invoice PDF dengan snapshot | Menerbitkan invoice PDF dari transaksi tersimpan dengan nomor unik dan snapshot identitas, item, total, pembayaran, refund, serta sisa tagihan pada waktu terbit. | Admin, Owner | M | Draft |
| FIN-FR-014 | Pembatalan dan revisi invoice | Membatalkan invoice yang telah terbit dengan alasan wajib dan menerbitkan invoice baru bernomor unik yang merujuk invoice sebelumnya. Invoice lama tidak dihapus. | Owner | S | Draft |
| FIN-FR-015 | Laporan penjualan, pembayaran, refund, dan pengeluaran | Menyajikan laporan operasional dengan filter periode/kategori, memisahkan penjualan, Pembayaran Valid, Refund Selesai, Pembayaran Bersih, pembelian bahan, dan pengeluaran operasional. | Admin, Owner | M | Draft |
| FIN-FR-016 | Pembatalan transaksi final dan riwayat perubahan | Membatalkan transaksi final dengan alasan wajib, mempertahankan semua histori, dan memastikan transaksi batal tidak dihitung sebagai penjualan. Pengembalian dana dicatat melalui FIN-FR-019. | Owner | S | Draft |
| FIN-FR-017 | Bukti pembayaran | Membuat dokumen bukti pembayaran per catatan pembayaran sebagai fitur tambahan bila digunakan. | Admin, Owner | C | Draft |
| FIN-FR-018 | Ekspor laporan CSV | Mengunduh hasil laporan yang telah difilter ke format CSV sebagai fitur opsional. | Admin, Owner | C | Draft |
| FIN-FR-019 | Refund Management | Memungkinkan Owner mencatat pengembalian dana manual secara parsial atau penuh atas pembayaran valid. Sistem memvalidasi batas refund, menyimpan alasan/referensi, dan menghitung ulang Pembayaran Bersih, Sisa Tagihan, dan Status Refund. | Owner | M | Draft |
| FIN-FR-020 | Payment Method Configuration | Menyediakan pengaturan informasi QRIS dan teks instruksi pembayaran yang dikelola Owner. Admin hanya dapat melihat konfigurasi aktif untuk mengirim instruksi secara manual setelah harga final dan nominal pembayaran disepakati. | Owner (ubah), Admin (lihat) | M | Draft |
 

- H. Kartu spesifikasi rinci functional requirement

FIN-FR-004 — Konversi request ke order

| Elemen | Isi / spesifikasi |
| --- | --- |
| Requirement ID | FIN-FR-004 |
| Nama requirement | Konversi request ke order |
| Tujuan dan deskripsi | Mengubah request custom yang disetujui pelanggan menjadi order/transaksi resmi tanpa kehilangan detail dan tanpa membuat duplikasi. Request yang belum disetujui tidak boleh dianggap transaksi. |
| Aktor dan hak akses | Admin dan Owner yang terautentikasi. |
| Precondition dan trigger | Request berstatus Disetujui, memiliki minimal satu item dengan harga penawaran, dan belum pernah dikonversi. Pemicu: admin menekan Konversi ke order. |
| Input dan field | ID request (rujukan, wajib); item dan harga penawaran (angka, wajib); diskon dan biaya tambahan (angka, opsional); informasi pengambilan langsung (mengikuti profil usaha PRD-04); syarat pembayaran atau DP yang disepakati (teks/angka, opsional); catatan (teks, opsional). Tidak ada pilihan pengiriman atau alamat pengiriman. Sumber: data request dari PRD-03 dan penawaran dari Admin. |
| Alur utama | 1. Admin meninjau request dan mengirim penawaran. 2. Setelah pelanggan menyetujui harga serta ketentuan DP/pelunasan, Admin mengubah status menjadi Disetujui Pelanggan. 3. Admin memilih Konversi ke Order. 4. Sistem membuat order dan snapshot harga final dengan status pengerjaan Menunggu Pembayaran serta status pembayaran Belum Dibayar. 5. Setelah kesepakatan pembayaran tersimpan, Admin melihat konfigurasi QRIS aktif di FIN dan mengirim instruksi secara manual melalui WhatsApp. |
| Alur alternatif | Admin dapat menyesuaikan item atau harga pada ringkasan sebelum konfirmasi. Admin dapat membatalkan proses dan request tetap Disetujui. |
| Validasi dan business rules | Request belum dianggap penjualan sampai disetujui dan dikonversi. Order hasil konversi custom dimulai dari Menunggu Pembayaran. Tidak ada instruksi QRIS saat request baru masuk atau masih dalam tahap penawaran. |
| Output dan feedback | Order baru terbuka di PRD-FIN-002 dengan notifikasi sukses; request menampilkan tautan ke order. |
| Error dan edge cases | Request sudah dikonversi: tombol nonaktif dan tautan ke order ditampilkan. Harga item kosong: validasi per baris. Gagal menyimpan: tidak ada order parsial dan data form dipertahankan. Dua admin menekan bersamaan: hanya satu berhasil, yang lain diberi pesan konflik. |
| Dependencies | PRD-03 (data request dan file referensi); PRD-04 (data produk bila item berasal dari katalog). |
| Priority | Must |
| Acceptance criteria | Given request berstatus Disetujui dengan harga penawaran, When admin mengonfirmasi konversi, Then terbentuk satu order dengan item, spesifikasi, dan harga yang sama dan status awal yang benar. Given request yang sudah dikonversi, When admin mencoba konversi lagi, Then sistem menolak dan tidak membuat order kedua. |
FIN-FR-007 — Perhitungan total transaksi

| Elemen | Isi / spesifikasi |
| --- | --- |
| Requirement ID | FIN-FR-007 |
| Nama requirement | Perhitungan total transaksi |
| Tujuan dan deskripsi | Menghitung subtotal item, diskon, biaya tambahan, dan total tagihan secara konsisten pada form transaksi, detail transaksi, invoice, dan laporan. |
| Aktor dan hak akses | Sistem; dipicu oleh Admin dan Owner saat mengisi atau mengubah transaksi. |
| Precondition dan trigger | Transaksi memiliki minimal satu item. Pemicu: perubahan kuantitas, harga satuan, diskon, biaya tambahan, atau penambahan/penghapusan item. |
| Input dan field | Kuantitas (bilangan bulat positif, wajib); harga satuan (Rupiah bulat, tidak negatif, wajib); diskon (nominal atau persen, opsional); biaya tambahan (nominal, opsional, dengan label). |
| Alur utama | (1) Sistem menghitung subtotal item = kuantitas × harga satuan. (2) Sistem menjumlahkan subtotal seluruh item. (3) Sistem mengurangi diskon dan menambah biaya tambahan. (4) Sistem menampilkan total dan menyimpannya saat transaksi disimpan. (5) Setiap perubahan setelah ada pembayaran memicu perhitungan ulang total dibayar dan sisa tagihan. |
| Alur alternatif | Diskon dalam persen dikonversi ke nominal dengan pembulatan sesuai kebijakan yang disepakati lalu nominal disimpan. |
| Validasi dan business rules | Total = Σ(kuantitas × harga satuan) − diskon + biaya tambahan. Total tidak boleh negatif; diskon tidak boleh melebihi subtotal. Perubahan total baru tidak boleh lebih kecil daripada Pembayaran Bersih (Pembayaran Valid − Refund Selesai). Jika lebih kecil, sistem menolak penyimpanan dan mempertahankan total lama. Refund Selesai harus dicatat Owner terlebih dahulu bila dana memang dikembalikan. |
| Output dan feedback | Subtotal per item, subtotal transaksi, diskon, biaya tambahan, total, dan indikator perubahan. |
| Error dan edge cases | Kuantitas nol/negatif ditolak. Harga kosong ditolak. Diskon melebihi subtotal ditolak. Total baru lebih kecil dari Pembayaran Bersih ditolak dan total lama dipertahankan. Angka bukan numerik divalidasi. |
| Dependencies | FIN-FR-006 (item); FIN-FR-008 (pembayaran). |
| Priority | Must |
| Acceptance criteria | Given item A (2 × Rp25.000) dan item B (1 × Rp40.000), diskon Rp10.000, biaya tambahan Rp5.000, When disimpan, Then total = Rp85.000. Given tarif katalog berubah, When transaksi lama dibuka, Then total dan harga satuan tetap. |
FIN-FR-008 — Pencatatan pembayaran

| Elemen | Isi / spesifikasi |
| --- | --- |
| Requirement ID | FIN-FR-008 |
| Nama requirement | Pencatatan pembayaran QRIS (DP, pembayaran bertahap, dan pelunasan) |
| Tujuan dan deskripsi | Mencatat pembayaran QRIS yang telah diterima dan diverifikasi secara manual serta menjaga Pembayaran Valid, Refund Selesai, Pembayaran Bersih, Sisa Tagihan, dan status pembayaran tetap akurat. |
| Aktor dan hak akses | Admin dan Owner. |
| Precondition dan trigger | Transaksi aktif (tidak dibatalkan), total lebih dari nol, dan masih memiliki sisa tagihan. Pemicu: Admin memilih Tambah Pembayaran setelah memeriksa penerimaan QRIS secara manual. |
| Input dan field | Tanggal bayar (wajib); nominal Rupiah bulat positif (wajib); metode (QRIS saja, wajib); referensi transaksi QRIS (opsional); keterangan (opsional: DP, pembayaran bertahap, atau pelunasan). |
| Alur utama | (1) Admin memeriksa penerimaan dana QRIS secara manual. (2) Setelah dana terkonfirmasi, Admin membuka form pembayaran. (3) Admin mengisi tanggal, nominal, metode QRIS, referensi bila tersedia, dan keterangan. (4) Sistem memvalidasi nominal terhadap Sisa Tagihan berdasarkan Pembayaran Bersih. (5) Sistem menyimpan Payment berstatus Valid. (6) Sistem menghitung Pembayaran Bersih = Σ Payment Valid − Σ Refund Selesai dan Sisa Tagihan = Total Tagihan − Pembayaran Bersih, kemudian menghitung status pembayaran. |
| Alur alternatif | Jika QRIS belum terverifikasi, pembayaran tidak boleh dicatat sebagai Valid. Jika catatan pembayaran salah, hanya Owner yang dapat melakukan Void dengan alasan wajib. Refund dilakukan melalui FIN-FR-019, bukan melalui Void. |
| Validasi dan business rules | Status pembayaran: Belum Dibayar jika Pembayaran Bersih = 0; Dibayar Sebagian jika 0 < Pembayaran Bersih < Total Tagihan; Lunas jika Pembayaran Bersih = Total Tagihan. Status Refund dihitung terpisah: Tidak Ada Refund, Refund Sebagian, atau Refund Penuh. Pembayaran baru tidak boleh melebihi Sisa Tagihan. DP ditentukan per transaksi dan tidak otomatis mengubah status pengerjaan. |
| Output dan feedback | Baris riwayat pembayaran, total dibayar, sisa tagihan, status pembayaran baru, dan pesan sukses. |
| Error dan edge cases | Nominal nol, negatif, atau bukan angka: ditolak. Nominal melebihi sisa: pesan menyebut sisa tagihan. Transaksi dibatalkan: pembayaran baru ditolak. Pengiriman ganda: pencegahan duplikasi dengan peringatan. Gagal simpan: tidak ada perubahan parsial. |
| Dependencies | FIN-FR-007 (total); FIN-FR-013 (invoice menampilkan pembayaran). |
| Priority | Must |
| Acceptance criteria | Given transaksi total Rp200.000, When Admin mencatat DP QRIS Rp50.000 setelah verifikasi manual, Then Pembayaran Valid Rp50.000, Pembayaran Bersih Rp50.000, sisa Rp150.000, status Dibayar Sebagian. Given sisa Rp150.000, When pembayaran QRIS Rp150.000 diverifikasi dan dicatat, Then sisa Rp0 dan status Lunas. Void hanya Owner dan angka dihitung ulang. |
FIN-FR-013 — Invoice PDF

| Elemen | Isi / spesifikasi |
| --- | --- |
| Requirement ID | FIN-FR-013 |
| Nama requirement | Invoice PDF dengan snapshot |
| Tujuan dan deskripsi | Menerbitkan dokumen tagihan resmi dari data transaksi yang tersimpan, dalam bentuk PDF yang dapat dibuka dan konsisten dengan transaksi saat diterbitkan. |
| Aktor dan hak akses | Admin dan Owner. |
| Precondition dan trigger | Transaksi tersimpan, tidak dibatalkan, minimal satu item. Pemicu: admin menekan Terbitkan invoice. |
| Input dan field | Transaksi (rujukan, wajib); tanggal terbit; jatuh tempo (opsional); catatan/ketentuan; snapshot identitas usaha dan pelanggan; item dan spesifikasi; kuantitas/harga/subtotal/diskon/biaya tambahan/total; Pembayaran Valid; Refund Selesai; Pembayaran Bersih; Sisa Tagihan. |
| Alur utama | (1) Admin/Owner menampilkan pratinjau. (2) Admin/Owner mengonfirmasi penerbitan awal. (3) Sistem membuat nomor invoice unik. (4) Sistem menyimpan snapshot immutable identitas usaha/pelanggan, item, nilai transaksi, payment/refund hingga waktu terbit, Pembayaran Bersih, dan Sisa Tagihan. (5) Sistem membuat PDF dari snapshot. (6) Admin/Owner mengunduh PDF. |
| Alur alternatif | Invoice lama tetap tersimpan dan tidak berubah. Hanya Owner dapat membatalkan invoice yang telah terbit dengan alasan dan menerbitkan invoice revisi bernomor baru yang merujuk invoice sebelumnya. |
| Validasi dan business rules | Nomor invoice unik dan tidak digunakan ulang. Isi invoice berasal dari snapshot immutable. Invoice berbeda dari bukti pembayaran. Pajak, logo final, identitas legal, dan jatuh tempo tetap menunggu konfirmasi pemilik usaha. Invoice yang dibatalkan tetap tersimpan dan diberi status Dibatalkan. |
| Output dan feedback | File PDF yang dapat dibuka, nomor invoice, status Terbit, dan pesan sukses. |
| Error dan edge cases | Gagal membuat PDF: pesan error dan invoice tidak dianggap terbit. Data pelanggan tidak lengkap: peringatan sebelum terbit. Nomor ganda akibat penerbitan bersamaan: dicegah di tingkat data. Transaksi dibatalkan: penerbitan ditolak. |
| Dependencies | FIN-FR-007, FIN-FR-008; pustaka PDF sesuai stack (Bab 12.1 Master PRD). |
| Priority | Must |
| Acceptance criteria | Given transaksi dengan beberapa item dan satu payment/refund, When invoice diterbitkan, Then PDF menampilkan item, total, Pembayaran Valid, Refund Selesai, Pembayaran Bersih, dan Sisa Tagihan sesuai snapshot. Setelah data berubah/revisi, invoice lama tidak berubah. |
FIN-FR-015 — Laporan penjualan, pembayaran, dan pengeluaran

| Elemen | Isi / spesifikasi |
| --- | --- |
| Requirement ID | FIN-FR-015 |
| Nama requirement | Laporan dengan filter periode |
| Tujuan dan deskripsi | Memberi Admin dan Owner rekap operasional penjualan, Pembayaran Valid, Refund Selesai, Pembayaran Bersih, pembelian bahan, dan pengeluaran operasional. Bukan laporan laba rugi atau sistem akuntansi lengkap. |
| Aktor dan hak akses | Admin dan Owner. |
| Precondition dan trigger | Pengguna terautentikasi membuka halaman laporan. Pemicu: memilih periode atau filter. |
| Input dan field | Tanggal awal/akhir; filter kategori pengeluaran, kanal, metode QRIS (opsional sesuai laporan). |
| Alur utama | (1) Sistem menghitung penjualan dari transaksi yang tidak dibatalkan berdasarkan tanggal transaksi. (2) Pembayaran Valid berdasarkan tanggal bayar. (3) Refund Selesai berdasarkan tanggal refund. (4) Pembayaran Bersih = Pembayaran Valid − Refund Selesai. (5) Pembelian bahan dan biaya operasional berdasarkan tanggal pencatatan. (6) Sisa tagihan dan Selisih Kas Sederhana = Pembayaran Bersih − Total Pengeluaran. (7) Sistem menampilkan ringkasan dan rincian. |
| Alur alternatif | Ekspor hasil ke CSV (Could). Perubahan filter memperbarui hasil tanpa memuat ulang seluruh halaman. |
| Validasi dan business rules | Transaksi batal tidak dihitung sebagai penjualan. Payment Void tidak dihitung sebagai Pembayaran Valid. Refund belum selesai tidak mengurangi Pembayaran Bersih. Setiap kelompok laporan memakai tanggal basis masing-masing. Selisih Kas Sederhana bukan laba/profit/laporan laba rugi. |
| Output dan feedback | Kartu ringkasan, tabel rincian, jumlah data, dan indikator periode aktif. |
| Error dan edge cases | Tanggal akhir sebelum awal: validasi. Tidak ada data: kondisi kosong dengan angka nol. Periode sangat panjang: pembagian halaman dan peringatan performa. Gagal memuat: pesan error dan tombol coba lagi. |
| Dependencies | FIN-FR-005 sampai FIN-FR-012. |
| Priority | Must |
| Acceptance criteria | Given transaksi, payment, refund, dan pengeluaran untuk dua bulan, When Admin/Owner memfilter satu bulan, Then laporan memisahkan Penjualan, Pembayaran Valid, Refund Selesai, Pembayaran Bersih, dan pengeluaran sesuai tanggal masing-masing. Transaksi batal tidak dihitung sebagai penjualan. |
FIN-FR-009 — Kartu Spesifikasi Void Pembayaran

| Elemen | Isi / spesifikasi |
| --- | --- |
| Requirement ID | FIN-FR-009 |
| Nama requirement | Void Payment |
| Tujuan dan deskripsi | Membatalkan catatan pembayaran yang salah tanpa menghapus histori dan tanpa menyatakan bahwa uang telah dikembalikan. |
| Aktor dan hak akses | Owner saja. Admin tidak dapat melakukan Void. |
| Precondition dan trigger | Owner telah login; payment sumber berstatus Valid; Owner membuka detail pembayaran dan memilih Void. |
| Input dan field | Payment ID dan alasan Void (wajib). Pengguna dan waktu tindakan dicatat otomatis. |
| Alur utama | 1. Owner membuka detail payment. 2. Memilih Void. 3. Mengisi alasan. 4. Sistem memeriksa permission dan status payment. 5. Owner mengonfirmasi. 6. Sistem mengubah status menjadi Void/Dibatalkan, menyimpan alasan/pengguna/waktu, dan menghitung ulang Pembayaran Valid, Pembayaran Bersih, serta Sisa Tagihan. |
| Alur alternatif | Admin mencoba Void: akses ditolak (403). Payment sudah Void atau tidak Valid: tindakan ditolak. Alasan kosong: sistem meminta alasan. |
| Validasi dan business rules | Hanya Owner; hanya payment Valid yang dapat di-void; alasan wajib; payment tidak dihapus permanen; payment Void tidak dihitung sebagai Pembayaran Valid. Void bukan Refund. |
| Output dan feedback | Payment berstatus Void, histori tetap tersimpan, dan perhitungan keuangan diperbarui. |
| Error dan edge cases | Pengguna tidak berizin, payment sudah Void, alasan kosong, atau transaksi tidak ditemukan: tidak ada perubahan data. |
| Dependencies | FIN-FR-008 pencatatan pembayaran; role/permission Owner; audit log. |
| Priority | Must |
| Acceptance criteria | Given Owner login dan payment Valid, When Owner melakukan Void dengan alasan, Then payment berstatus Void dan perhitungan diulang. Given Admin login, When Admin mencoba Void, Then sistem menolak. |
FIN-FR-019 — Kartu Spesifikasi Refund Management

| Elemen | Isi / spesifikasi |
| --- | --- |
| Requirement ID | FIN-FR-019 |
| Nama requirement | Refund Management |
| Tujuan dan deskripsi | Mencatat pengembalian uang yang benar-benar dilakukan usaha kepada pelanggan, baik parsial maupun penuh, secara terpisah dari catatan pembayaran awal. |
| Aktor dan hak akses | Owner saja. Admin tidak dapat mencatat atau mengubah Refund. |
| Precondition dan trigger | Owner telah login; payment sumber berstatus Valid dan masih memiliki saldo yang belum dikembalikan; pengembalian dana dilakukan secara manual di luar sistem. |
| Input dan field | Payment sumber, tanggal refund, nominal positif, metode pengembalian, referensi bila tersedia, alasan wajib, dan status refund. |
| Alur utama | 1. Owner membuka payment sumber. 2. Owner memilih Refund. 3. Mengisi tanggal, nominal, metode pengembalian, referensi, dan alasan. 4. Owner mengembalikan dana secara manual di luar sistem. 5. Setelah dana benar-benar dikembalikan, Owner menyimpan Refund sebagai Selesai. 6. Sistem memvalidasi batas refund, menyimpan entitas Refund terpisah, lalu menghitung ulang Pembayaran Bersih, Sisa Tagihan, dan Status Refund. |
| Alur alternatif | Nominal melebihi saldo yang belum dikembalikan, payment sumber Void, data tidak lengkap, atau pengguna bukan Owner: sistem menolak. Refund yang belum selesai/dibatalkan tidak mengurangi Pembayaran Bersih. |
| Validasi dan business rules | Maksimum refund = Payment Valid terkait − Refund Selesai sebelumnya. Total Refund Selesai tidak boleh melebihi nominal Payment Valid sumber. Status Refund: Tidak Ada Refund, Refund Sebagian, atau Refund Penuh. Sistem tidak melakukan transfer uang otomatis. |
| Output dan feedback | Refund tersimpan terpisah dan terhubung ke payment sumber; nilai Pembayaran Bersih, Sisa Tagihan, dan Status Refund diperbarui. |
| Error dan edge cases | Refund ganda/berlebih, payment sumber Void, nominal nol/negatif, alasan kosong, atau Admin mencoba mencatat: ditolak dan data lama dipertahankan. |
| Dependencies | FIN-FR-008 Payment, FIN-FR-016 pembatalan transaksi, Payment/Refund data entities, audit log. |
| Priority | Must |
| Acceptance criteria | Given Payment Valid Rp100.000, When Owner mencatat Refund Selesai Rp40.000, Then Pembayaran Bersih menjadi Rp60.000. Given saldo refund Rp60.000, When Owner memasukkan Rp70.000, Then sistem menolak. |
FIN-FR-020 — Kartu Spesifikasi Payment Method Configuration

| Elemen | Isi / spesifikasi |
| --- | --- |
| Requirement ID | FIN-FR-020 |
| Nama requirement | Payment Method Configuration |
| Tujuan dan deskripsi | Mengelola informasi QRIS dan teks instruksi pembayaran yang digunakan Admin setelah total final dan nominal pembayaran disepakati. Fitur ini tidak memproses pembayaran. |
| Aktor dan hak akses | Owner dapat melihat dan mengubah konfigurasi. Admin hanya dapat melihat konfigurasi aktif (read-only). |
| Precondition dan trigger | Owner telah login dan membuka /admin/payment-settings. |
| Input dan field | Metode tetap QRIS untuk versi pertama; media/gambar atau identitas QRIS dari usaha; teks instruksi; status aktif; pengguna pengubah dan waktu perubahan otomatis. |
| Alur utama | 1. Owner membuka pengaturan QRIS. 2. Sistem menampilkan konfigurasi aktif. 3. Owner mengganti media/identitas QRIS atau teks instruksi. 4. Sistem memvalidasi dan menyimpan konfigurasi. 5. Sistem mencatat perubahan ke audit log. 6. Admin membuka halaman yang sama secara read-only untuk melihat informasi aktif. 7. Admin mengirim QRIS secara manual melalui WhatsApp setelah harga final dan nominal pembayaran disepakati. |
| Alur alternatif | Admin mencoba mengubah/menonaktifkan konfigurasi: akses perubahan ditolak. Media/identitas tidak valid atau penyimpanan gagal: konfigurasi lama tetap aktif. |
| Validasi dan business rules | Hanya QRIS yang tersedia pada versi pertama; hanya satu konfigurasi QRIS aktif. Tidak ada rekening bank, tunai, e-wallet lain, QRIS dinamis, payment gateway, atau verifikasi otomatis. QRIS tidak ditampilkan pada checkout/konfirmasi awal. |
| Output dan feedback | Konfigurasi QRIS aktif dapat dibaca Admin; perubahan Owner tercatat dalam audit log. |
| Error dan edge cases | Admin mencoba mengubah konfigurasi, file QRIS tidak valid, atau gagal simpan: perubahan ditolak dan konfigurasi lama dipertahankan. |
| Dependencies | Autentikasi dan permission Owner/Admin; Laravel Filesystem untuk media; audit log; FIN order/payment workflow. |
| Priority | Must |
| Acceptance criteria | Given Owner login, When memperbarui QRIS dan teks instruksi, Then konfigurasi aktif berubah dan audit log tercatat. Given Admin login, When membuka halaman, Then konfigurasi aktif dapat dilihat tanpa kontrol ubah. |
- I. Business rules domain

| Rule ID | Aturan bisnis | Kondisi / rumus / Batasan | Dampak jika tidak terpenuhi |
| --- | --- | --- | --- |
| FIN-BR-001 | Request belum dianggap transaksi final. | Request menjadi order hanya setelah berstatus Disetujui dan dikonversi admin. | Laporan penjualan menghitung permintaan yang belum pasti. Sistem tidak menghitung request dalam total penjualan. |
| FIN-BR-002 | Satu transaksi berisi banyak item dan satu pelanggan. | Item berasal dari katalog atau custom (nama, deskripsi, kuantitas, harga). | Transaksi tidak dapat disimpan tanpa minimal satu item. |
| FIN-BR-003 | Total transaksi dihitung dari item. | Total = Σ(kuantitas × harga satuan) − diskon + biaya tambahan; tidak negatif. | Angka tidak konsisten; simpan ditolak. |
| FIN-BR-004 | Harga kesepakatan disimpan sebagai snapshot. | Nama, spesifikasi, kuantitas, dan harga item disalin saat dibuat; perubahan katalog tidak mengubahnya. | Transaksi dan invoice lama berubah; perubahan data master tidak boleh memengaruhi. |
| FIN-BR-005 | Pembayaran Bersih dan Sisa Tagihan dihitung dari Payment dan Refund. | Pembayaran Bersih = Σ Pembayaran Valid − Σ Refund Selesai. Sisa Tagihan = Total Tagihan − Pembayaran Bersih. Payment Void dan Refund yang belum selesai tidak dihitung. | Sisa tagihan/status pembayaran tidak akurat. |
| FIN-BR-006 | Status pembayaran diturunkan otomatis dari Pembayaran Bersih. | Belum Dibayar jika Pembayaran Bersih = 0; Dibayar Sebagian jika 0 < Pembayaran Bersih < Total Tagihan; Lunas jika Pembayaran Bersih = Total Tagihan. | Status tidak sesuai. Admin/Owner tidak mengubah status pembayaran secara manual. |
| FIN-BR-007 | Status request, pengerjaan order, dan pembayaran terpisah. | Request: Baru Diterima, Ditinjau Admin, Menunggu Informasi, Penawaran Dikirim, Disetujui Pelanggan, Ditolak, Kedaluwarsa, Dikonversi Menjadi Order. Order: Menunggu Konfirmasi, Menunggu Pembayaran, Diproses, Siap Diambil, Selesai, Dibatalkan. Status pembayaran dihitung dari Pembayaran Bersih. Perubahan status request/order mengikuti transisi dan tercatat dalam histori. | Salah satu status disimpulkan dari yang lain; status Siap Dikirim tidak digunakan. |
| FIN-BR-008 | Nominal pembayaran tidak melebihi sisa tagihan berdasarkan Pembayaran Bersih. | Nominal pembayaran baru ≤ Sisa Tagihan. Sisa Tagihan = Total Tagihan − Pembayaran Bersih. | Kelebihan pembayaran tidak tercatat; sistem menolak. |
| FIN-BR-009 | Tidak ada hapus permanen untuk data keuangan. | Transaksi dibatalkan, pembayaran di-void, invoice dibatalkan, dengan alasan wajib dan log. | Riwayat hilang. Data diarsipkan, bukan dihapus. |
| FIN-BR-010 | Invoice memakai snapshot immutable dan nomor unik. | Snapshot memuat identitas usaha/pelanggan, nomor/tanggal, item, spesifikasi, jumlah, harga, diskon/biaya tambahan, total, Pembayaran Valid, Refund Selesai, Pembayaran Bersih, dan Sisa Tagihan pada waktu penerbitan. Pembatalan/revisi oleh Owner; revisi mendapat nomor baru dan referensi invoice sebelumnya. | Invoice lama tidak konsisten atau berubah setelah penerbitan. |
| FIN-BR-011 | Pembelian bahan hanya pengeluaran. | Tidak menambah atau mengurangi stok; tidak ada saldo stok. | Sistem menyiratkan stok real-time (di luar scope). |
| FIN-BR-012 | Pelanggan dicatat tanpa duplikasi. | Nomor kontak menjadi kunci pencocokan; bila sudah ada, admin diminta memakai pelanggan yang ada. | Riwayat pelanggan terpecah. |
| FIN-BR-013 | Transaksi dibatalkan tidak dihitung dalam laporan penjualan. | Payment dan Refund atas transaksi yang dibatalkan tetap disimpan dan direkap terpisah berdasarkan tanggal pembayaran/refund. Refund hanya mengurangi Pembayaran Bersih setelah Selesai. | Laporan keliru atau histori keuangan hilang. |
| FIN-BR-014 | Data keuangan dan pelanggan hanya untuk pengguna internal. | Semua route admin memerlukan autentikasi dan izin. | Kebocoran data; akses ditolak. |
| FIN-BR-015 | Instruksi pembayaran hanya diberikan setelah harga final dikonfirmasi. | Order direct ditinjau Admin dan total final disimpan; custom request disetujui dan dikonversi. Setelah nominal DP/pelunasan disepakati dan status menjadi Menunggu Pembayaran, Admin mengirim instruksi QRIS manual melalui WhatsApp. | Pelanggan dapat membayar sebelum tagihan final disepakati. |
| FIN-BR-016 | Metode pembayaran versi pertama hanya QRIS dan diverifikasi manual. | Tidak ada transfer bank, tunai, e-wallet lain, payment gateway, atau verifikasi QRIS otomatis. Payment hanya dicatat Valid setelah Admin memeriksa penerimaan dana. | Metode dan catatan transaksi tidak konsisten. |
| FIN-BR-017 | Nominal DP ditentukan per transaksi berdasarkan kesepakatan. | Tidak ada persentase atau nominal minimum DP global. Nilai DP dan payment terms dicatat pada order terkait. | Sistem menetapkan kewajiban DP yang tidak sesuai kesepakatan. |
| FIN-BR-018 | Konfigurasi QRIS hanya dapat diubah Owner. | Owner memiliki hak tulis; Admin hanya read-only terhadap konfigurasi QRIS aktif. Setiap perubahan tercatat dalam audit log. | Informasi pembayaran dapat diubah oleh role yang tidak berwenang. |
| FIN-BR-019 | Void dan Refund merupakan tindakan berbeda dan Owner-only. | Void membatalkan catatan Payment yang salah dengan alasan wajib. Refund mencatat dana yang benar-benar dikembalikan secara manual, parsial/penuh, dan tidak boleh melebihi saldo pembayaran sumber yang belum dikembalikan. | Riwayat pembayaran atau pengembalian dana tidak akurat. |
| FIN-BR-020 | Total transaksi baru tidak boleh lebih kecil daripada Pembayaran Bersih. | Jika total baru < Pembayaran Bersih, sistem menolak penyimpanan dan mempertahankan total lama. Jika dana telah dikembalikan, Owner mencatat Refund Selesai terlebih dahulu. | Sisa tagihan menjadi negatif atau catatan dana tidak konsisten. |
- J. Data requirements

| Entitas / data | Field / atribut | Wajib? | Validasi / format | Sumber / relasi |
| --- | --- | --- | --- | --- |
| Customer | id, nama, nomor kontak, email, catatan, status aktif | Ya (id, nama, nomor kontak) | Nama tidak kosong; nomor kontak format telepon dan unik; email format valid bila diisi. | Dibuat dari PRD-03 atau manual; satu pelanggan memiliki banyak order |
| Order / Request | id, nomor transaksi, customer_id, jenis, kanal asal, status request, status pengerjaan, status pembayaran, subtotal, diskon, biaya tambahan, total, total_pembayaran_valid, total_refund_selesai, pembayaran_bersih, sisa_tagihan, agreed_dp_amount (nullable), payment_terms_note, metode pemenuhan (pickup), catatan, source_request_id, tanggal, alasan pembatalan | Ya (id, nomor, customer, jenis, status, total) | Nomor unik; amount tidak negatif; status sesuai lifecycle; agreed_dp_amount tidak wajib dan tidak memakai persentase global; fulfillment hanya pickup. | Customer memiliki banyak order; order memiliki banyak item, payment, refund, invoice, dan status history. |
| Order Item | id, id order, id produk (opsional), nama (snapshot), deskripsi/spesifikasi (snapshot), kuantitas, harga satuan, subtotal, file referensi | Ya (order, nama, kuantitas, harga) | Kuantitas bilangan bulat positif; harga tidak negatif | Milik satu order; rujukan produk dari PRD-04 bersifat opsional |
| Payment | id, order_id, tanggal_bayar, nominal, metode (QRIS), referensi, keterangan, status (Valid/Void), void_reason, voided_by_user_id, voided_at, dicatat_oleh | Ya (order, tanggal, nominal, metode, status) | Nominal positif; metode hanya QRIS; Payment Valid setelah verifikasi manual; Void oleh Owner dengan alasan; Payment Void tidak dihapus. | Milik satu order; dapat menjadi sumber Refund. |
| Expense / Purchase Record | id, tanggal, jenis (bahan/operasional), kategori, nama barang, kuantitas, harga satuan, total, pemasok, keterangan, lampiran nota, status arsip | Ya (tanggal, jenis, kategori, nama, kuantitas, harga) | Kuantitas dan harga tidak negatif; total dihitung sistem | Berdiri sendiri; tidak terhubung ke stok |
| Expense Category | id, nama, jenis, status aktif | Ya (nama) | Nama unik | Dikelola admin; dipakai banyak pengeluaran |
| Invoice | id, nomor_invoice, order_id, tanggal_terbit, jatuh_tempo, status, revises_invoice_id (nullable), cancelled_by_user_id, cancelled_at, cancellation_reason, pdf_file_path | Ya (nomor, order, tanggal, status) | Nomor unik dan tidak digunakan ulang; pembatalan/revisi hanya Owner dengan alasan. | Terkait satu order; invoice revisi merujuk invoice sebelumnya. |
| Invoice Snapshot | id, invoice_id, identitas_usaha_snapshot, customer_snapshot, nomor/tanggal invoice, item/spec/qty/harga, subtotal, diskon, biaya tambahan, total, pembayaran_valid, refund_selesai, pembayaran_bersih, sisa_tagihan, ketentuan yang ditampilkan | Ya | Disalin sekali saat terbit; immutable dan tidak diperbarui setelahnya. | Relasi 1:1 dengan Invoice. |
| Audit Log | id, entity_type, entity_id, perubahan, actor_user_id, waktu, alasan | Ya | Append-only; tidak dihapus/ditimpa. | Merujuk tindakan penting keuangan, konfigurasi, dan user. |
| Refund | refund_id, order_id, payment_id, tanggal_refund, nominal, metode_pengembalian, referensi, alasan, status (Selesai/Dibatalkan), dicatat_oleh, created_at | Ya | Nominal positif; refund Selesai per payment tidak melebihi nominal Payment Valid dikurangi refund sebelumnya; payment sumber harus Valid; pencatatan oleh Owner. | Refund terpisah dari Payment; satu Payment dapat memiliki banyak Refund. |
| Payment Method Configuration | id, method_code (QRIS), qris_media_or_identifier, instruction_text, status, updated_by_user_id, updated_at | Ya | Hanya QRIS untuk versi pertama; satu konfigurasi aktif; perubahan oleh Owner dan tercatat dalam audit log. | Dikelola FIN; Admin hanya membaca untuk instruksi WhatsApp. |
| Order Status History | id, order_id, status_sebelumnya, status_baru, changed_by_user_id, changed_at, catatan | Ya | Status baru dan transisi harus valid; append-only, tidak dihapus/ditimpa. | Satu order memiliki banyak histori; terhubung ke user yang mengubah. |
ERD final disepakati bersama anggota 4 dan koordinator. Entitas baru yang diusulkan domain ini: Invoice Snapshot, Status / Audit Log, dan Expense Category.

- K. Validation, error handling, dan edge cases

| Case ID | Kondisi / skenario | Validasi atau respons sistem yang diharapkan | Severity |
| --- | --- | --- | --- |
| FIN-EC-001 | Input kosong atau tidak valid (kuantitas nol, harga negatif, nominal bukan angka) | Pesan validasi per kolom; data tidak disimpan; data form dipertahankan. | High |
| FIN-EC-002 | Pelanggan duplikat atau request sudah dikonversi | Sistem menawarkan pelanggan yang ada; konversi ganda ditolak dengan tautan ke order. | Med |
| FIN-EC-003 | Gagal simpan, gagal membuat PDF, atau jaringan terputus | Pesan error yang dapat dimengerti; tidak ada data parsial; invoice tidak dianggap terbit. | High |
| FIN-EC-004 | Akses tanpa login atau tanpa izin ke route admin | Diarahkan ke halaman login atau ditolak (403); tidak ada data tampil. | High |
| FIN-EC-005 | Dua admin mengubah transaksi yang sama | Perubahan kedua diberi peringatan konflik dan diminta memuat ulang. | Med |
| FIN-EC-006 | Pembayaran melebihi sisa tagihan | Ditolak dengan pesan menyebut sisa tagihan. | High |
| FIN-EC-007 | Total diturunkan di bawah pembayaran yang telah diterima | Peringatan; perubahan ditolak sampai koreksi pembayaran atau refund dicatat. | High |
| FIN-EC-008 | Pembatalan transaksi yang sudah memiliki pembayaran | Konfirmasi dan alasan wajib; peringatan soal refund; semua tercatat di log. | High |
| FIN-EC-009 | Produk katalog dinonaktifkan atau diubah setelah item dibuat | Item tetap memakai snapshot dan tidak berubah. | Med |
| FIN-EC-010 | Rentang tanggal laporan tidak valid atau tidak ada data | Validasi tanggal; kondisi kosong dengan angka nol. | Low |
| FIN-EC-011 | Unggah nota atau bukti melebihi batas atau tipe tidak diizinkan | File ditolak dengan pesan; tidak dieksekusi sebagai kode. | Med |
| FIN-EC-012 | Admin mencoba melakukan Void pembayaran. | Sistem menolak dengan 403/permission; status Payment dan angka keuangan tidak berubah. | High |
| FIN-EC-013 | Nominal refund melebihi saldo yang masih dapat dikembalikan. | Sistem menolak dan menampilkan maksimum nominal refund yang masih tersedia. | High |
| FIN-EC-014 | Refund kedua menyebabkan total refund Selesai melebihi Payment sumber. | Pencatatan kedua ditolak; total refund dan Payment sumber tetap. | High |
| FIN-EC-015 | Refund diajukan pada Payment yang berstatus Void. | Sistem menolak karena Payment sumber tidak valid. | High |
| FIN-EC-016 | Refund masih berstatus belum selesai/dibatalkan. | Refund belum mengurangi Pembayaran Bersih; status pembayaran dihitung dari Payment Valid dan Refund Selesai saja. | High |
| FIN-EC-017 | Nomor invoice bentrok atau pembuatan PDF gagal saat revisi. | Sistem mencegah nomor ganda; invoice baru tidak dianggap terbit bila PDF gagal; invoice lama tetap utuh. | High |
- L. Acceptance criteria dan test scenarios

| Test ID | Requirement terkait | Given (kondisi awal) | When (aksi) | Then (hasil yang diharapkan) | Status |
| --- | --- | --- | --- | --- | --- |
| FIN-AT-001 | FIN-FR-001, FIN-FR-002 | Pesanan dikirim dari PRD-03 | Admin membuka daftar order | Order tampil dengan pelanggan, item, dan estimasi yang benar | Belum diuji |
| FIN-AT-002 | FIN-FR-003, FIN-FR-004 | Request berstatus Disetujui | Admin mengonversi ke order | Satu order terbentuk dengan snapshot dan status awal benar; konversi kedua ditolak | Belum diuji |
| FIN-AT-003 | FIN-FR-005, FIN-FR-006 | Admin login | Membuat transaksi manual dengan satu item katalog dan satu custom item | Transaksi tersimpan dengan nomor unik dan kedua item | Belum diuji |
| FIN-AT-004 | FIN-FR-007 | Dua item, diskon, biaya tambahan | Menyimpan transaksi | Total sesuai rumus dan tetap saat harga katalog berubah | Belum diuji |
| FIN-AT-005 | FIN-FR-008 | Transaksi total Rp200.000 dan dana DP QRIS Rp50.000 telah diterima serta diverifikasi manual. | Admin mencatat DP Rp50.000 dengan metode QRIS. | Pembayaran tersimpan sebagai Valid; Pembayaran Bersih Rp50.000, sisa Rp150.000, dan status Dibayar Sebagian. | Belum diuji |
| FIN-AT-006 | FIN-FR-008 | Sisa tagihan Rp100.000 | Mencatat nominal Rp120.000 | Ditolak dengan pesan | Belum diuji |
| FIN-AT-007 | FIN-FR-009 | Admin login dan tersedia payment Valid. | Admin mencoba melakukan Void dengan alasan. | Sistem menolak akses dan payment tetap Valid; histori tidak berubah. | Belum diuji |
| FIN-AT-008 | FIN-FR-010 | Transaksi dengan status Diproses | Pembayaran dilunasi | Status pembayaran Lunas, status pengerjaan tidak berubah otomatis | Belum diuji |
| FIN-AT-009 | FIN-FR-011 | Pelanggan dengan nomor kontak sama | Admin membuat transaksi | Sistem menawarkan pelanggan yang ada; riwayat tidak terpecah | Belum diuji |
| FIN-AT-010 | FIN-FR-012 | Admin login | Mencatat pembelian bahan | Total terhitung, catatan tersimpan, tidak ada perubahan stok | Belum diuji |
| FIN-AT-011 | FIN-FR-013 | Transaksi dengan pembayaran sebagian | Menerbitkan invoice | PDF dapat dibuka dan memuat item, total, pembayaran, sisa sesuai transaksi | Belum diuji |
| FIN-AT-012 | FIN-FR-013, FIN-FR-014 | Invoice terbit lalu transaksi/katalog berubah. | Owner membatalkan invoice dengan alasan dan menerbitkan revisi. | Invoice lama tetap immutable; invoice revisi bernomor baru merujuk invoice lama. | Belum diuji |
| FIN-AT-013 | FIN-FR-015 | Data dua bulan | Memfilter satu bulan | Angka sesuai perhitungan manual; transaksi batal tidak dihitung | Belum diuji |
| FIN-AT-014 | FIN-FR-016 | Transaksi final dengan pembayaran. | Owner membatalkan transaksi dengan alasan. | Transaksi berstatus Dibatalkan dan tercatat di audit log; tidak masuk laporan penjualan; payment/refund tetap disimpan terpisah. | Belum diuji |
| FIN-AT-015 | Seluruh FIN-FR | Pengguna tanpa login | Membuka route admin | Akses ditolak | Belum diuji |
| FIN-AT-016 | FIN-FR-009 | Owner login dan payment Valid. | Owner melakukan Void dengan alasan. | Payment menjadi Void, histori tetap tersimpan, Pembayaran Bersih dan Sisa Tagihan dihitung ulang. | Belum diuji |
| FIN-AT-017 | FIN-FR-019 | Payment QRIS Valid Rp100.000. | Owner mencatat refund selesai Rp40.000. | Refund tercatat terpisah; Pembayaran Bersih menjadi Rp60.000. | Belum diuji |
| FIN-AT-018 | FIN-FR-019 | Payment QRIS Valid Rp100.000. | Owner mencatat refund penuh Rp100.000 selesai. | Pembayaran Bersih menjadi Rp0; Status Refund menjadi Refund Penuh. | Belum diuji |
| FIN-AT-019 | FIN-FR-019 | Saldo refund yang dapat dikembalikan Rp60.000. | Owner mencoba mencatat refund Rp70.000. | Sistem menolak dan menampilkan batas maksimum Rp60.000. | Belum diuji |
| FIN-AT-020 | FIN-FR-007 | Pembayaran Bersih Rp200.000 dan total tagihan Rp300.000. | Admin mencoba menyimpan total baru Rp150.000. | Sistem menolak dan mempertahankan total sebelumnya. | Belum diuji |
| FIN-AT-021 | FIN-FR-020 | Owner telah login. | Owner memperbarui media/identitas QRIS dan teks instruksi. | Konfigurasi aktif berubah dan audit log tercatat. | Belum diuji |
| FIN-AT-022 | FIN-FR-020 | Admin telah login. | Admin membuka /admin/payment-settings. | Admin dapat melihat konfigurasi QRIS aktif tetapi tidak dapat mengubah/menonaktifkannya. | Belum diuji |
| FIN-AT-023 | FIN-FR-002, FIN-FR-020 | Order masih Menunggu Konfirmasi dan harga final belum ditetapkan. | Admin membuka detail order. | Instruksi QRIS belum ditampilkan sebagai instruksi pembayaran pelanggan; setelah harga final/nominal disepakati dan status Menunggu Pembayaran, Admin dapat mengirimnya secara manual. | Belum diuji |
| FIN-AT-024 | FIN-FR-010 | Order berstatus Diproses. | Admin mengubah status menjadi Siap Diambil. | Histori perubahan tersimpan; status hanya tampil di dashboard internal dan Admin dapat menghubungi pelanggan lewat WhatsApp manual. | Belum diuji |
- M. Non-functional requirements domain

| Kategori | Kebutuhan khusus domain | Ukuran / target uji |
| --- | --- | --- |
| Usability | Form transaksi dan pembayaran dapat diisi tanpa instruksi tambahan; total dan sisa tagihan selalu terlihat; konfirmasi untuk tindakan destruktif; pencarian dan filter pada daftar panjang. | Uji dengan admin usaha; target keberhasilan ditentukan tim (TBD) |
| Performance | Daftar order, penyimpanan pembayaran, pembuatan PDF, dan laporan merespons dalam waktu wajar; daftar memakai pembagian halaman. | Target detik dan jumlah data uji (TBD) |
| Security / privacy | Seluruh modul memerlukan autentikasi dan otorisasi; validasi di server; data kontak dan pembayaran tidak tampil di halaman publik; file nota dan bukti tidak dapat dieksekusi; operasi keuangan atomik dan tercatat di log. | Uji akses tanpa login, manipulasi parameter, dan unggah file berbahaya |
| Compatibility / accessibility | Dashboard terbaca di desktop dan mobile pada browser modern; PDF invoice dapat dibuka di pembaca PDF umum; label form jelas dan kontras cukup. | Daftar browser dan ukuran layar (TBD) |
| Document / report integrity | Invoice PDF dihasilkan dari snapshot immutable; laporan memisahkan penjualan, Pembayaran Valid, Refund Selesai, Pembayaran Bersih, pembelian bahan, dan biaya operasional serta dapat ditelusuri ke data sumber. | Uji invoice awal/revisi, payment/refund, transaksi batal, dan laporan periode; seluruh nilai cocok dengan perhitungan manual. |
- N. Dependencies dan integrasi lintas domain

| Domain terkait | Data / fungsi yang dibutuhkan | Data / fungsi yang diberikan | Kesepakatan interface |
| --- | --- | --- | --- |
| PRD-03 | Data pelanggan, item snapshot, estimasi, jenis/kanal, file referensi, kode pesanan, informasi pickup, dan status awal. | FIN mengelola status request/order/payment dan histori secara internal. Admin menyampaikan Siap Diambil lewat WhatsApp manual. | Tidak ada endpoint/halaman cek status pelanggan; kontrak data dan snapshot digunakan untuk INT-04. |
| PRD-04 | Autentikasi/permission Owner/Admin; master produk/kategori; identitas usaha dan lokasi/jam pickup. | FIN memberikan kebutuhan menu dan ringkasan transaksi; mengambil identitas usaha untuk snapshot invoice. | PRD-04 tidak mengelola rekening/QRIS/instruksi pembayaran. Item transaksi tetap memakai snapshot. |
| PRD-02 | Konfigurasi dan estimasi yang telah diteruskan melalui PRD-03 | Tidak ada pertukaran langsung | Data sampai ke PRD-05 melalui PRD-03. |
| PRD-01 | Tidak ada pertukaran langsung | Tidak ada | Identitas usaha pada invoice mengikuti konten profil dari PRD-04. |
| FIN internal — QRIS Configuration | Owner mengelola konfigurasi QRIS dan teks instruksi. | Admin membaca konfigurasi aktif untuk mengirim instruksi manual melalui WhatsApp setelah total final dan nominal pembayaran disepakati. | Konfigurasi dimiliki FIN-FR-020; Admin read-only, perubahan dicatat audit log. |

