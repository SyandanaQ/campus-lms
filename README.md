# Campus LMS

Learning Management System (LMS) untuk jenjang perguruan tinggi, dibangun sebagai proyek pembelajaran pribadi. Aplikasi ini mendukung 3 peran pengguna (Admin, Dosen, Mahasiswa) dengan alur kerja akademik yang lengkap — mulai dari manajemen data akademik, KRS, konten pembelajaran, penilaian, hingga komunikasi antar pengguna.

## Fitur Utama

### Admin
- Manajemen data master: Fakultas, Program Studi, Mata Kuliah, Tahun Ajaran
- Manajemen akun Dosen & Mahasiswa (otomatis generate akun login)
- Manajemen Kelas (kelas paralel per mata kuliah, per tahun ajaran)
- Pengumuman global untuk seluruh pengguna
- Dashboard statistik sistem

### Dosen
- Kelola materi pembelajaran (upload PDF, embed video YouTube, link eksternal)
- Buat & nilai tugas (dengan validasi deadline dan dukungan submit ulang)
- Buat kuis pilihan ganda dengan auto-grading
- Input nilai akhir (konversi otomatis ke huruf A–E)
- Pengumuman & forum diskusi per kelas
- Export nilai akhir ke CSV
- Dashboard ringkasan kelas yang diampu

### Mahasiswa
- Kartu Rencana Studi (KRS) — lihat & ambil kelas tersedia sesuai kapasitas
- Akses materi pembelajaran per kelas
- Submit tugas & kerjakan kuis
- Lihat nilai akhir per kelas
- Forum diskusi & pengumuman
- Dashboard progres akademik pribadi

## Tech Stack

- **Backend**: Laravel 13 (PHP 8.5)
- **Database**: MySQL
- **Frontend**: Blade + Alpine.js + Tailwind CSS
- **Auth & Permission**: Laravel Breeze + Spatie Laravel-Permission
- **Testing**: Pest
- **Environment**: WSL2 (Ubuntu) untuk development

## Status Project

MVP fungsional penuh — seluruh alur akademik inti (dari administrasi data hingga penilaian dan komunikasi) sudah selesai dan teruji. Saat ini dalam tahap pengembangan lanjutan (UI polish, testing tambahan).

## Instalasi & Setup Lokal

```bash
# Clone repository
git clone https://github.com/SyandanaQ/campus-lms.git
cd campus-lms

# Install dependency PHP
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Konfigurasi database di .env, lalu jalankan migration + seeder
php artisan migrate --seed

# Install dependency frontend
npm install
npm run build

# Jalankan storage symlink (untuk upload file)
php artisan storage:link

# Jalankan server
php artisan serve
```

## Akun Default (dari Seeder)

| Role | Email | Password |
|---|---|---|
| Admin | admin@lms.test | password |
| Dosen | dosen1@lms.test | password |
| Mahasiswa | budi@lms.test | password |