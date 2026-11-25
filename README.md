<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## YOLOO Merch

Aplikasi Laravel untuk website publik dan panel admin YOLOO Merch. Halaman admin berada di `/admin/login`.

### Akun login admin (development)

Kredensial ini dipakai tim untuk mencoba panel admin di komputer masing-masing:

| Role | Email | Password | Seeder |
| --- | --- | --- | --- |
| Owner | `owner@yoloo.test` | `owner12345` | `OwnerUserSeeder` |
| Admin | `admin@yoloo.test` | `admin12345` | `AdminUserSeeder` |

Keduanya dibuat oleh seeder yang membaca `OWNER_*` dan `ADMIN_*` dari `.env`. Akun Admin berguna untuk menguji tampilan dan hak akses role admin tanpa memakai akun Owner. Kalau login ditolak, pastikan variabelnya terisi lalu jalankan:

```bash
php artisan config:clear
php artisan migrate --seed
```

> Kredensial di atas khusus development. Ganti `OWNER_PASSWORD` dan `ADMIN_PASSWORD` sebelum aplikasi dipakai di server produksi.

### Menyiapkan proyek di Windows

Prasyarat: Laragon sudah terpasang (PHP, MySQL, Composer), Node.js LTS, dan Git. Jalankan **Laragon → Start All** sebelum mulai.

#### Pilihan 1 — Git Bash / Laragon Terminal (disarankan)

Buka **Laragon → Terminal** (sudah Git Bash, `cp` dan `mysql` dikenali):

```bash
git clone https://github.com/zsaayyew/yoloo-merch.git
cd yoloo-merch
composer install
cp .env.example .env
php artisan key:generate
mysql -u root < database/dumps/yoloo_merch.sql
npm install && npm run build
php artisan serve
```

#### Pilihan 2 — CMD / PowerShell

Di CMD, `cp` dan `mysql` tidak dikenali, jadi pakai perintah Windows berikut:

```bat
git clone https://github.com/zsaayyew/yoloo-merch.git
cd yoloo-merch
composer install
copy .env.example .env
php artisan key:generate
"C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysql.exe" -u root < database\dumps\yoloo_merch.sql
npm install && npm run build
php artisan serve
```

Kalau versi MySQL di komputermu berbeda, cek nama foldernya di `C:\laragon\bin\mysql\` lalu sesuaikan path di atas. Alternatif tanpa `mysql` sama sekali: buka **HeidiSQL** (tombol *Database* di Laragon), klik kanan koneksinya, pilih **Run SQL file**, lalu pilih `database\dumps\yoloo_merch.sql`.

#### Kalau tidak memakai dump

Ganti baris `mysql ...` dengan migrasi biasa — hasilnya sama (tabel + role + akun Owner dan Admin):

```bash
php artisan migrate --seed
```

#### Selesai

Buka `http://localhost:8000`, panel admin di `http://localhost:8000/admin/login`. Login dengan salah satu akun pada tabel di atas.

Catatan: proyek memakai MySQL dengan nama database `yoloo_merch` dan user `root` tanpa password (standar Laragon). Sesuaikan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di `.env` bila konfigurasi MySQL di komputermu berbeda.

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
