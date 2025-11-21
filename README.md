# Ternak Anda 🐄  
**Sistem Informasi Manajemen Peternakan**

_Ternak Anda_ adalah aplikasi web berbasis **Laravel** untuk membantu pemilik/pengelola peternakan dalam mengelola data ternak, kandang, pakan, dan aktivitas operasional lainnya secara lebih terstruktur dan terdokumentasi.

> Catatan: README ini adalah versi awal. Silakan sesuaikan bagian **Fitur** dan **Alur Bisnis** dengan implementasi akhir dan kebutuhan skripsi kamu.

---

## ✨ Fitur Utama (Rencana / Implementasi)

Beberapa modul yang dapat (atau akan) tersedia dalam sistem:

- **Manajemen Ternak**
  - Pendataan ternak: ID ternak, jenis (sapi, kambing, ayam, dll.), umur, bobot, status kesehatan, riwayat masuk/keluar.
- **Manajemen Kandang**
  - Pendataan kandang, kapasitas ternak per kandang, dan penempatan ternak.
- **Pakan & Kesehatan**
  - Pencatatan jadwal dan jenis pakan.
  - Pencatatan vaksinasi, obat, dan tindakan kesehatan lainnya.
- **Transaksi / Produksi (Opsional)**
  - Pencatatan penjualan ternak / hasil ternak (susu, telur, dll.).
  - Rekap pendapatan & pengeluaran sederhana.
- **Laporan & Monitoring**
  - Rekap jumlah ternak aktif, ternak masuk/keluar per periode.
  - Ringkasan data untuk kebutuhan analisis dan pelaporan skripsi.

> Sesuaikan poin-poin di atas dengan modul yang benar-benar kamu buat di dalam aplikasi.

---

## 🧱 Teknologi yang Digunakan

Proyek ini dibangun dengan:

- **Backend**
  - [Laravel](https://laravel.com/) (PHP)
  - Blade Templates
  - Eloquent ORM (MySQL/MariaDB)
- **Frontend**
  - Blade + Vite (bawaan Laravel)
  - (Opsional) Tailwind CSS / Bootstrap jika kamu pakai
- **Tools Pendukung**
  - Composer
  - NPM
  - Git & GitHub

---

## 📁 Struktur Direktori (Ringkas)

Beberapa direktori penting:

```text
app/
  Http/
    Controllers/   # Controller utama aplikasi
  Models/          # Eloquent models (Ternak, Kandang, dll)
bootstrap/
config/
database/
  migrations/      # File migrasi tabel
  seeders/         # Seeder data awal (jika ada)
public/
resources/
  views/           # Blade views (UI)
routes/
  web.php          # Route untuk halaman web
  api.php          # Route untuk API (jika digunakan)
```

---

## ✅ Prasyarat

Sebelum menjalankan proyek ini, pastikan:

- **PHP** 8.1+ (sesuai requirement Laravel)
- **Composer**
- **MySQL / MariaDB**
- **Node.js** & **NPM**
- **Git** (opsional, untuk clone repo)

---

## 🚀 Cara Menjalankan Project (Local Development)

### 1. Clone Repository

```bash
git clone https://github.com/kiraascoder/ternak-anda.git
cd ternak-anda
```

### 2. Install Dependency PHP

```bash
composer install
```

### 3. Konfigurasi Environment

Salin file `.env`:

```bash
cp .env.example .env
# atau di Windows:
# copy .env.example .env
```

Lalu atur konfigurasi di `.env`:

```env
APP_NAME="Ternak Anda"
APP_ENV=local
APP_KEY=          # akan di-generate nanti
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ternak_anda
DB_USERNAME=root
DB_PASSWORD=
```

Generate application key:

```bash
php artisan key:generate
```

Buat database `ternak_anda` di MySQL (lewat phpMyAdmin / HeidiSQL / dll), lalu jalankan migrasi:

```bash
php artisan migrate
# php artisan db:seed  # jika sudah menyiapkan seeder
```

### 4. Install Dependency Frontend

```bash
npm install
```

### 5. Jalankan Aplikasi

Di terminal pertama (backend):

```bash
php artisan serve
# biasanya: http://127.0.0.1:8000
```

Di terminal kedua (frontend/assets melalui Vite):

```bash
npm run dev
```

Akses aplikasi melalui:

```text
http://127.0.0.1:8000
```

---

## 🔌 Routing & Modul Utama

Routing utama dapat ditemukan di:

- `routes/web.php` → untuk halaman web (dashboard, data ternak, kandang, dsb)
- `routes/api.php` → untuk API (jika kamu sediakan endpoint REST)

Contoh pola route (disesuaikan):

```php
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('ternak', TernakController::class);
Route::resource('kandang', KandangController::class);
// Route lain: pakan, kesehatan, laporan, dll.
```

---

## 📚 Catatan untuk Skripsi

Repo ini dapat digunakan sebagai:

- **Artefak implementasi** pada BAB III–IV (perancangan & implementasi sistem).
- **Bahan dokumentasi**:
  - Screenshot halaman: dashboard, manajemen ternak, kandang, laporan.
  - Cuplikan kode: model, controller, migrasi, dan view penting.
- **Bahan analisis**:
  - Struktur database (ERD → migrasi).
  - Alur proses bisnis (diagram aktivitas/sequence berdasarkan route & controller).

---

## 🤝 Kontribusi

Untuk saat ini, repositori ini fokus pada kebutuhan pengembangan pribadi dan penelitian skripsi.  
Namun struktur kode mengikuti standar Laravel sehingga di masa depan:

- Modul baru (misal: notifikasi, integrasi IoT, laporan PDF) dapat ditambahkan dengan mudah.
- Pengembang lain dapat melakukan fork dan mengembangkan fitur tambahan.

---

## 📝 Lisensi

Jika tidak ditentukan lain, proyek ini mengikuti lisensi **MIT** (default Laravel).  
Silakan sesuaikan sesuai kebutuhanmu.
