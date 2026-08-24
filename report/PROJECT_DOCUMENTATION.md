# Dokumentasi Teknis & Riwayat Pengembangan: TerabasHRIS

Dokumen ini merangkum seluruh arsitektur, riwayat perbaikan, konfigurasi lingkungan, peningkatan keamanan, akun uji coba, serta panduan operasional sistem **TerabasHRIS**.

---

## 1. Ringkasan Eksekutif Sistem

* **Nama Aplikasi**: TerabasHRIS (Modern Human Resource Information System)
* **Arsitektur**: Decoupled Client-Server (RESTful API + Single Page Application)
* **Backend**: Laravel 12 (PHP 8.2), MySQL, Spatie Laravel-Permission, Laravel Sanctum
* **Frontend**: Vue 3 (Composition API `<script setup>`), Vite 5, Tailwind CSS, Pinia, Lucide Icons
* **Status Kualitas**: ✅ **100% Passed (34 Skenario Pengujian QA Lolos)**
* **Status Keamanan**: 🛡️ **Hardened (Rate Limiting, AES-256 Encryption, Non-Executable Storage)**

---

## 2. Riwayat Masalah & Solusi Teknis (Changelog Perbaikan)

| Masalah yang Ditemukan | Penyebab Utama | Solusi & Tindakan yang Telah Diterapkan |
| :--- | :--- | :--- |
| **Error Login 401 & Payload Rusak** | Header `multipart/form-data` dipaksa global pada Axios. | Menghapus header default Axios di `axios.js` dan menambahkan `@submit.prevent` pada form login. |
| **Menu Sidebar Hilang / Tidak Merespons** | Array `permissions` kosong di response `UserResource.php` dan seeder belum terpanggil. | Memperbaiki serialisasi permissions di `UserResource`, menambahkan `PermissionSeeder` & `RolePermissionSeeder` di `DatabaseSeeder`, dan menjalankan `php artisan migrate:fresh --seed`. |
| **Konflik Rute Edit vs Detail** | Rute `/projects/:id` mendahului dan menduplikasi `/projects/:id/edit`. | Memperbaiki susunan rute di `src/router/project.js` dan `src/router/team.js`. |
| **Aset Gambar / Avatar Tidak Muncul** | File dummy `public/storage` dari macOS zip memblokir pembuatan symlink asli. | Menghapus dummy file, menyalin aset ke `storage/app/public/`, dan menghubungkan symlink resmi dengan `php artisan storage:link`. |
| **Section Upgrade to Pro** | Mockup statis template SaaS yang tidak fungsional. | Menggantinya dengan Card Bantuan & HR Helpdesk interaktif beserta badge versi `TerabasHRIS v1.0.0`. |
| **Tombol Quick Actions Tidak Sinkron** | Tombol statis tanpa link dan tanpa pengecekan role pengguna. | Mengubah `QuickActions.vue` menjadi dinamis berbasis RBAC (`can()`) dengan style presisi sesuai FE SKILL. |
| **Endpoint Slip Gaji Karyawan Belum Terhubung** | Rute `/my-payslips` belum terdaftar di backend. | Mengimplementasikan `getMyPayslips` & `getMyPayslip` di Controller, Repository, dan mendaftarkan rutenya di `routes/api.php`. |

---

## 3. Peningkatan Keamanan yang Diterapkan (*Security Hardening*)

1. **Anti-Brute Force Login (Rate Limiter)**:
   * Menggunakan `RateLimiter::for('login')` dengan batas 5 percobaan per menit. Percobaan ke-6 menghasilkan `HTTP 429 Too Many Requests`.
2. **Enkripsi Rekening Bank (AES-256-CBC at Rest)**:
   * Menggunakan model casting `'account_number' => 'encrypted'` pada model `BankInformation.php`. Data tersimpan sebagai ciphertext acak di database MySQL.
3. **Isolasi Folder Upload (`.htaccess`)**:
   * Menambahkan proteksi `<FilesMatch>` pada `storage/app/public/` dan `public/storage/` untuk memblokir eksekusi skrip PHP jika ada file mencurigakan yang di-upload.
4. **Isolasi Hak Akses Granular (RBAC 4 Tingkat)**:
   * Manager (41 permissions), HR (30 permissions), Finance (17 permissions), Employee (22 permissions).

---

## 4. Daftar Akun Uji Coba (Test Accounts Directory)

| Role Pengguna | Email | Password | Hak Akses Utama |
| :--- | :--- | :--- | :--- |
| 👑 **Manager** | `manager@gmail.com` | `password` | Akses penuh, approval gaji, pembentukan tim & project baru, metrik eksekutif. |
| 📋 **HR** | `hr@gmail.com` | `password` | Manajemen data karyawan, approval cuti, rekap absensi harian. |
| 💳 **Finance** | `finance@gmail.com` | `password` | Generate periode penggajian, penyesuaian gaji, ekspor Excel laporan keuangan. |
| 👤 **Employee** | `employee@gmail.com` | `password` | Clock In/Out absensi, pengajuan cuti pribadi, unduh slip gaji bulanan. |

---

## 5. SKILL Agent AI yang Tersedia di Project

Folder [`.agents/skills/`](../.agents/skills/) menyimpan panduan standar arsitektur untuk pengembangan berkelanjutan:

1. 🛠️ [**`hris-api`**](../.agents/skills/hris-api/SKILL.md): Panduan standar backend Laravel 12, Controller, Repository Pattern, Resource, dan Migrations.
2. 🎨 [**`hris-fe`**](../.agents/skills/hris-fe/SKILL.md): Panduan frontend Vue 3, Tailwind CSS design system (`#0C51D9`), Pinia store conventions, dan RBAC `can()`.
3. 🧪 [**`hris-qa`**](../.agents/skills/hris-qa/SKILL.md): Panduan matriks pengujian kualitas, skenario pengujian Q&A per modul, dan automated health check scripts.

---

## 6. Panduan Menjalankan Project di Lingkungan Lokal

### Backend (Laravel 12 API)
```powershell
cd "c:\Users\muham\Web project\hris_app\hris-api-main"
php artisan serve --port=8000
```

### Frontend (Vue 3 + Vite)
```powershell
cd "c:\Users\muham\Web project\hris_app\hris-fe-main"
npm run dev
```
*Akses aplikasi di browser:* **`http://localhost:5173`**

### Menjalankan Automated QA Test Suite
```powershell
cd "c:\Users\muham\Web project\hris_app\hris-api-main"
php "C:\Users\muham\.gemini\antigravity\brain\67982ac8-ea3c-4c69-8494-4883233f4875\scratch\run_full_qa_suite.php"
```
