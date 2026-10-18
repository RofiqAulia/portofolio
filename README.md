# Website Portofolio Individu Profesional & Panel Admin

Website portofolio individu berstandar industri yang dibangun dengan arsitektur **OOP SOLID**, **Laravel 12 / 13**, **Bootstrap 5**, **MySQL (Laragon)**, dan **Filament Panel**.

---

## 🚀 Fitur Utama

- **Landing Page Publik (Blade + Bootstrap 5 + Vite):**
  - Desain visual Gen Z Neo-Brutalism modern (biru muda `#EAF2FF`, biru elektrik `#2F5BFF`, navy `#0A1B3D`, stiker miring `#FFE14D`).
  - Sticky pill navbar di bagian atas.
  - Hero banner dengan headline kata tersorot dan status open to work.
  - Strip keahlian teknologi dan kartu statistik.
  - Banner ungkapan (quotes).
  - Indikator bar tingkat kemahiran hard skill dan stiker soft skill & analisis.
  - Kartu portofolio proyek lengkap dengan tag teknologi dan tautan live demo / GitHub.
  - Linimasa pengalaman Magang (*Internship*) dan Organisasi (*Organization*).
  - Halaman detail proyek (`/proyek/{slug}`) dan pengalaman (`/pengalaman/{id}`).
  - Form kontak publik ke tabel `messages` dilengkapi honeypot anti-spam dan rate-limit throttle.
  - Pencatatan analitik `page_views` anonim (hash IP + User Agent + salt harian).
  - Metadata SEO terintegrasi dari tabel `site_settings`.

- **Panel Admin (/admin - Filament):**
  - Login panel admin, lupa password, dan edit profil.
  - Dashboard statistik: ringkasan pengunjung bulan ini, proyek terbit, pesan baru, draft konten, serta grafik tren 30 hari dan tabel pesan terbaru.
  - Pengelolaan Profil tunggal pemilik, foto, berkas CV, dan relasi multi tautan sosial (*social links*).
  - Resource Proyek (*Project*): slug otomatis, multi-select teknologi, upload galeri foto polimorfik, status draft/terbit.
  - Resource Pengalaman (*Experience*): tab filter Magang & Organisasi, repeater poin pencapaian (*highlights*), berkas lampiran (berkas/tautan).
  - Resource Keahlian (*Skills & Skill Categories*).
  - Resource Pesan (*Messages*): baca dan ubah status pesan dengan indikator badge pesan baru di navigasi.
  - Halaman Pengaturan Situs & SEO (*SiteSettings*).
  - Otorisasi berbasis Policy (Admin akses penuh, Editor dibatasi dari pengelolaan pengguna & pengaturan).

- **Arsitektur OOP & Standar Kualitas:**
  - Pola *Strategy* untuk penanganan lampiran (`FileAttachmentHandler` & `LinkAttachmentHandler`).
  - Single-purpose *Action* use cases (`CreateMessage`, `PublishProject`, `ReplaceExperienceAttachment`, `TrackPageView`).
  - Data Transfer Objects (*DTO*) dengan `readonly class`.
  - PHP 8.3 Backed Enums untuk semua atribut status.
  - Observer model untuk pembersihan berkas di storage secara otomatis saat record dihapus atau diganti.
  - RESTful API Publik v1 (`/api/v1/*`) dengan throttling dan format JSON terstruktur.
  - 100% PSR-12 dengan Laravel Pint dan rangkaian 5 lapisan pengujian (Unit, Feature, Admin, API, Integration).

---

## 🛠️ Persyaratan Sistem

- PHP >= 8.3 (dengan ekstensi `pdo_mysql`, `fileinfo`, `mbstring`)
- Composer >= 2.6
- Node.js >= 18 & npm
- MySQL >= 8.0 (Laragon MySQL port 3307 atau default 3306)

---

## 📦 Panduan Instalasi

1. **Clone repositori dan masuk ke folder:**
   ```bash
   cd c:\laragon\www\portofolio-new
   ```

2. **Konfigurasi Environment `.env`:**
   Pastikan pengaturan database di `.env` telah sesuai:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3307
   DB_DATABASE=portofolio_new
   DB_USERNAME=root
   DB_PASSWORD=
   ```

3. **Jalankan Migrasi & Database Seeder:**
   ```bash
   php artisan migrate:fresh --seed
   ```

4. **Hubungkan Storage Publik:**
   ```bash
   php artisan storage:link
   ```

5. **Kompilasi Aset Frontend (Bootstrap & Vite):**
   ```bash
   npm install
   npm run build
   ```

6. **Jalankan Aplikasi:**
   - Akses via web server Laragon: `http://portofolio-new.test` atau jalankan:
   ```bash
   php artisan serve
   ```
   - Halaman Publik: `http://127.0.0.1:8000`
   - Panel Admin: `http://127.0.0.1:8000/admin`

---

## 🔑 Akun Masuk Panel Admin

- **Admin (Akses Penuh):**
  - **Email:** `admin@rofiq.dev`
  - **Password:** `password123`
- **Editor (Terbatas):**
  - **Email:** `editor@rofiq.dev`
  - **Password:** `password123`

---

## 🧪 Menjalankan Pengujian (5 Lapisan)

Jalankan seluruh test suite otomatis:
```bash
php artisan test
```

Verifikasi standar penulisan kode (Pint):
```bash
vendor/bin/pint --test
```

---

## 📚 Dokumentasi Lanjutan

- [Struktur Arsitektur OOP & Diagram Kelas (docs/architecture.md)](docs/architecture.md)
- [Strategi & Inventaris Pengujian 5 Lapisan (docs/testing.md)](docs/testing.md)
- [Dokumentasi Lengkap REST API v1 (docs/api.md)](docs/api.md)
