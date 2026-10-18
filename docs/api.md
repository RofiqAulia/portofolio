# Dokumentasi REST API Portofolio (v1)

Semua endpoint API publik berada di bawah prefix `/api/v1`. Endpoint ini read-only untuk publik, kecuali endpoint pengiriman pesan kontak.

---

## 1. Rate Limiting (Throttle)

- **GET Endpoints (Read-Only):** Maksimal **60 requests per menit**.
- **POST `/messages` (Kontak):** Maksimal **5 requests per menit**.

Jika melebihi kuota, server mengembalikan respon HTTP status `429 Too Many Requests`.

---

## 2. Format Respon JSON Standar

Respon sukses dibungkus dalam objek `data`, dengan `meta` dan `links` untuk endpoint terpaginasi:

```json
{
  "data": [ ... ],
  "links": {
    "first": "http://localhost:8000/api/v1/projects?page=1",
    "last": "http://localhost:8000/api/v1/projects?page=2",
    "prev": null,
    "next": "http://localhost:8000/api/v1/projects?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 2,
    "per_page": 10,
    "total": 12
  }
}
```

Format respon error (404, 422, 429):
```json
{
  "message": "Pesan validasi gagal.",
  "errors": {
    "email": [
      "Format email tidak valid."
    ]
  }
}
```

---

## 3. Daftar Endpoint

### 1. `GET /api/v1/profile`
Mengambil data profil pemilik portofolio beserta tautan media sosial aktif.
- **Respon Sukses (200 OK):**
```json
{
  "data": {
    "id": 1,
    "full_name": "M. Rofiq Aulia",
    "headline": "Senior Full-Stack Developer & Software Architect",
    "tagline": "Membangun aplikasi web berkinerja tinggi...",
    "bio": "Pengembang perangkat lunak dengan pengalaman dalam ekosistem PHP/Laravel...",
    "photo_url": "http://localhost:8000/storage/profiles/photos/avatar.jpg",
    "cv_url": "http://localhost:8000/storage/profiles/cv/cv.pdf",
    "email": "rofiq@rofiq.dev",
    "phone": "+62 812-3456-7890",
    "location": "Jakarta, Indonesia",
    "quote_text": "Kerapian arsitektur kode hari ini adalah kemudahan inovasi di masa depan.",
    "quote_author": "M. Rofiq Aulia",
    "is_open_to_work": true,
    "social_links": [
      {
        "id": 1,
        "platform": "GitHub",
        "icon": "bi-github",
        "url": "https://github.com",
        "sort_order": 1
      }
    ]
  }
}
```

---

### 2. `GET /api/v1/social-links`
Mengambil daftar tautan sosial yang berstatus aktif (`is_active = 1`).
- **Respon Sukses (200 OK):**
```json
{
  "data": [
    {
      "id": 1,
      "platform": "GitHub",
      "icon": "bi-github",
      "url": "https://github.com",
      "sort_order": 1
    }
  ]
}
```

---

### 3. `GET /api/v1/skills`
Mengambil kategori skill beserta daftar skill yang tampil (`is_visible = 1`).
- **Respon Sukses (200 OK):**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Hard Skills",
      "type": "hard",
      "type_label": "Hard Skill",
      "sort_order": 1,
      "skills": [
        {
          "id": 1,
          "name": "Laravel & PHP 8.3",
          "level": 95,
          "level_label": "Expert",
          "sort_order": 1
        }
      ]
    }
  ]
}
```

---

### 4. `GET /api/v1/projects`
Mengambil daftar proyek terbit (`status = 'published'`), terpaginasi 10 item per halaman.
- **Respon Sukses (200 OK):** Berisi koleksi data proyek dengan array teknologi dan link paginasi.

---

### 5. `GET /api/v1/projects/{slug}`
Mengambil detail satu proyek terbit berdasarkan slug unik.
- **Parameter:** `slug` (string)
- **Status 404:** Jika proyek tidak ditemukan atau masih berstatus draft.

---

### 6. `GET /api/v1/experiences`
Mengambil daftar pengalaman magang dan organisasi yang terbit.
- **Query Parameter Opsional:**
  - `?type=internship` (Hanya mengambil magang)
  - `?type=organization` (Hanya mengambil organisasi)

---

### 7. `GET /api/v1/experiences/{id}`
Mengambil detail satu pengalaman terbit, lengkap dengan highlights, teknologi, lampiran, dan media.

---

### 8. `POST /api/v1/messages`
Mengirimkan pesan dari form kontak.
- **Header:** `Content-Type: application/json`, `Accept: application/json`
- **Request Body:**
```json
{
  "name": "Budi Santoso",
  "email": "budi@perusahaan.com",
  "subject": "Tawaran Proyek",
  "body": "Halo, kami ingin mendiskusikan peluang kerja sama sistem web enterprise.",
  "company_website": ""
}
```
- **Catatan:** `company_website` adalah honeypot anti-spam yang harus dikosongkan.
- **Respon Sukses (201 Created):**
```json
{
  "data": {
    "id": 12,
    "name": "Budi Santoso",
    "email": "budi@perusahaan.com",
    "subject": "Tawaran Proyek",
    "status": "new",
    "created_at": "2026-10-14T08:30:00+00:00"
  }
}
```
