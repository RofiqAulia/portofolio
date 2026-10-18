# Dokumentasi Pengujian Portofolio

Pengujian pada proyek ini dibagi ke dalam **5 Lapisan Pengujian** dengan pola penamaan deskriptif: `test_<unit>_<kondisi>_<hasil>`.

---

## 1. Lima Lapisan Pengujian

| Lapisan | Direktori | Komponen yang Diuji | Karakteristik |
|---|---|---|---|
| **1. Unit** | `tests/Unit/` | Enums, Actions, Services, VisitorHasher, Attachment Handlers | Terisolasi, dependensi di-mock, tanpa HTTP request |
| **2. Feature** | `tests/Feature/` | Form kontak publik, validasi honeypot, filter visibilitas draft/terbit, pencatatan page view | HTTP request publik, validasi session & view assertions |
| **3. Admin Filament** | `tests/Feature/Admin/` | Hak akses otorisasi panel admin, UserPolicy, SiteSettingPolicy | Verifikasi role Admin vs Editor (403 forbidden vs 200 ok) |
| **4. API Integration** | `tests/Feature/Api/` | Endpoint `/api/v1/*` (profile, skills, projects, experiences, messages) | Format JSON standar (`assertJsonStructure`), status code (200, 201, 404, 422, 429), paginasi, pencegahan kebocoran draft |
| **5. Cross-Component Integration** | `tests/Integration/` | Alur lintas komponen: penerbitan proyek ke publik & API; penggantian lampiran membersihkan berkas lama di storage | `Storage::fake()`, Observer lifecycle, end-to-end data pipeline |

---

## 2. Cara Menjalankan Pengujian

1. **Jalankan semua test:**
   ```bash
   php artisan test
   ```

2. **Jalankan test suite per kategori:**
   ```bash
   php artisan test tests/Unit
   php artisan test tests/Feature
   php artisan test tests/Feature/Api
   php artisan test tests/Feature/Admin
   php artisan test tests/Integration
   ```

3. **Verifikasi standar kode PSR-12:**
   ```bash
   vendor/bin/pint --test
   ```

---

## 3. Database Pengujian MySQL

Pengujian menggunakan database MySQL terpisah (`portfolio_test`) yang berjalan di port **3307** MySQL Laragon. Hal ini memastikan konsistensi enum database, foreign key constraints, dan cascading delete identik dengan lingkungan produksi.

Konfigurasi di `phpunit.xml`:
```xml
<env name="DB_CONNECTION" value="mysql"/>
<env name="DB_HOST" value="127.0.0.1"/>
<env name="DB_PORT" value="3307"/>
<env name="DB_DATABASE" value="portfolio_test"/>
<env name="DB_USERNAME" value="root"/>
<env name="DB_PASSWORD" value=""/>
```

---

## 4. Daftar Kasus Uji Per Modul

### Unit Tests
- `VisitorHasherTest`:
  - `test_hasher_generates_consistent_sha256_hash_for_same_day`
  - `test_hasher_generates_different_hash_for_different_days`
- `AttachmentServiceTest`:
  - `test_attachment_service_resolves_correct_handler_for_file_type`
  - `test_attachment_service_resolves_correct_handler_for_link_type`
  - `test_link_handler_throws_exception_when_url_is_empty`
  - `test_link_handler_clears_file_fields_and_keeps_url`
- `EnumTest`:
  - Validasi seluruh enum backed value dan label (`UserRole`, `SkillType`, `ProjectStatus`, `ExperienceType`, `AttachmentType`, `MediaType`, `MessageStatus`).

### Feature Tests
- `ContactFormTest`:
  - `test_user_can_submit_contact_form_successfully`
  - `test_contact_form_fails_validation_with_missing_name_or_email`
  - `test_contact_form_rejects_spambot_when_honeypot_is_filled`
- `ContentVisibilityTest`:
  - `test_landing_page_only_displays_published_projects`
  - `test_landing_page_only_displays_published_experiences`
- `DetailPagesTest`:
  - `test_user_can_view_published_project_detail`
  - `test_user_cannot_view_draft_project_detail_returns_404`
  - `test_user_can_view_published_experience_detail`
- `PageViewTrackingTest`:
  - `test_visiting_home_page_records_anonymized_page_view`

### Admin Panel Tests
- `AdminPanelTest`:
  - `test_unauthenticated_user_is_redirected_to_admin_login`
  - `test_authenticated_admin_can_access_dashboard`
  - `test_editor_cannot_access_users_management`
  - `test_admin_can_access_users_management`
  - `test_admin_can_access_site_settings_page`
  - `test_editor_cannot_access_site_settings_page`

### API Integration Tests
- `PortfolioApiTest`:
  - `test_get_profile_returns_correct_json_structure`
  - `test_get_social_links_returns_only_active_links`
  - `test_get_skills_returns_categories_with_visible_skills`
  - `test_get_projects_returns_paginated_published_projects_only`
  - `test_get_project_by_slug_returns_404_when_draft_or_missing`
  - `test_get_experiences_can_filter_by_type`
  - `test_post_message_creates_message_and_returns_201`
  - `test_post_message_returns_422_when_validation_fails`

### Cross-Component Integration Tests
- `PortfolioIntegrationTest`:
  - `test_publishing_draft_project_makes_it_available_on_landing_page_and_api`
  - `test_replacing_or_deleting_attachment_cleans_up_files_in_storage`
