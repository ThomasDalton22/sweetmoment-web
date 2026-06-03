# 🍰 SweetMoment Web

Website platform pemesanan layanan event/vendor berbasis web yang dibangun menggunakan **Laravel 10**. Project ini mendukung fitur pembayaran online melalui **Midtrans** dan **Xendit**, manajemen pesanan, hingga sistem review dan notifikasi.

---

## 🛠️ Tech Stack

- **Framework:** Laravel 10
- **Language:** PHP ^8.1
- **Database:** MySQL
- **Payment Gateway:** Pakasir
- **Library Tambahan:**
  - `barryvdh/laravel-dompdf` — generate PDF
  - `yajra/laravel-datatables-oracle` — datatable server-side
  - `laravel/socialite` — login sosial media
  - `realrashid/sweet-alert` — notifikasi alert

---

## ⚙️ Cara Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/ThomasDalton22/sweetmoment-web.git
cd sweetmoment-web
```

### 2. Install Dependency

```bash
composer install
npm install
```

### 3. Setting Environment

```bash
cp .env.example .env
php artisan key:generate
```

Lalu buka file `.env` dan sesuaikan konfigurasi berikut:

```env
DB_DATABASE=nama_database_kamu
DB_USERNAME=root
DB_PASSWORD=

PAKASIR_PROJECT=isi_project_slug_kamu
PAKASIR_API_KEY=isi_api_key_kamu
```

### 4. Migrasi Database

```bash
php artisan migrate
php artisan db:seed
```

### 5. Jalankan Aplikasi

```bash
php artisan serve
npm run dev
```

Akses di browser: `http://localhost:8000`

---

## 🔐 Akun Admin

| Role  | Username | Password |
|-------|----------|----------|
| Admin | admin    | password |

---

## 📁 Struktur Folder Utama

```
sweetmoment-web/
├── app/
│   ├── Http/Controllers/   # Logic controller (Admin, Payment, Session, dll)
│   ├── Models/             # Model database
│   └── Http/Middleware/    # Middleware autentikasi & role
├── resources/views/        # Tampilan Blade template
├── routes/                 # Routing web & API
├── database/migrations/    # Migrasi tabel database
└── public/                 # Asset publik
```

---

## 📌 Fitur

- Autentikasi & manajemen role (Admin, Vendor, User)
- Pemesanan layanan vendor/event
- Pembayaran online via Pakasir (support QRIS & metode lainnya)
- Manajemen pesanan & notifikasi real-time
- Review & testimoni
- Generate laporan PDF
- Datatable server-side

---

## 📝 Catatan

- Pastikan ekstensi PHP yang dibutuhkan sudah aktif (`php-mbstring`, `php-xml`, `php-curl`)
- File `.env` **jangan di-push** ke repository (sudah ada di `.gitignore`)
- Untuk integrasi payment gateway Pakasir, isi `PAKASIR_PROJECT` dan `PAKASIR_API_KEY` di file `.env`
