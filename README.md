# SIMPEL - Sistem Informasi Pengolahan Nilai Siswa

SIMPEL adalah aplikasi berbasis web yang dibangun menggunakan **PHP** dan **Laravel 11** untuk membantu institusi pendidikan mengelola data nilai siswa secara cepat, rapi, dan terstruktur.

Aplikasi ini mendemonstrasikan perpaduan antara **Pemrograman Berorientasi Objek (OOP)** dan **Pemrograman Terstruktur**, di mana *controller* dan *model* menggunakan konsep OOP, sedangkan proses perhitungan nilai akhir dan validasi dipisahkan ke dalam *helper functions* terstruktur.

## Prasyarat (Requirements)
* PHP >= 8.2
* Composer
* SQLite (Sudah bawaan PHP, tidak perlu server database terpisah)

## Panduan Instalasi (Cara Install)

Ikuti langkah-langkah berikut untuk menginstall dan menjalankan aplikasi di komputer Anda:

1. **Buka Terminal / Command Prompt**
   Pastikan Anda berada di dalam folder project `student-grading`.

2. **Install Dependensi Composer**
   Jalankan perintah berikut untuk mengunduh semua library yang dibutuhkan (termasuk Laravel dan DomPDF):
   ```bash
   composer install
   ```

3. **Salin File Environment**
   Ganti nama file `.env.example` menjadi `.env` atau jalankan perintah:
   ```bash
   cp .env.example .env
   ```
   *(Catatan: Konfigurasi default sudah menggunakan SQLite, sehingga Anda tidak perlu repot mengatur database MySQL lokal).*

4. **Generate Application Key**
   Jalankan perintah ini untuk membuat *security key* aplikasi:
   ```bash
   php artisan key:generate
   ```

5. **Siapkan Database SQLite**
   Buat file database kosong untuk SQLite dengan perintah:
   ```bash
   # Di Windows (PowerShell):
   New-Item -Path database\database.sqlite -ItemType File -Force

   # Di Mac/Linux:
   touch database/database.sqlite
   ```

6. **Migrasi dan Seeding Database**
   Jalankan perintah berikut untuk membuat tabel dan mengisi *dummy data* (data awal untuk pengujian):
   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Jalankan Server Lokal**
   Mulai jalankan aplikasi dengan perintah:
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui browser di alamat: **`http://localhost:8000`**

---

## Akun Demo (Untuk Pengujian)
Setelah proses *seeding* selesai, Anda bisa login menggunakan akun-akun berikut:

| Role | Email | Password |
|---|---|---|
| **Admin** | `admin@SIMPEL.com` | `password123` |
| **Guru (Matematika)** | `ahmad@SIMPEL.com` | `password123` |
| **Guru (Bahasa Indonesia)** | `sari@SIMPEL.com` | `password123` |
| **Siswa (Budi Santoso)** | `budi@SIMPEL.com` | `password123` |

## Fitur Berdasarkan Aktor (Role)

### 1. Admin (Administrator)
**Tugas Utama:** Mengelola data master dan memantau keseluruhan sistem.
* **Kelola Siswa:** Menambah, mengedit, dan menghapus data siswa beserta akun login-nya.
* **Kelola Guru:** Menambah, mengedit, dan menghapus data guru beserta akun login-nya.
* **Kelola Mata Pelajaran:** Menambah, mengedit, dan menghapus data mata pelajaran.
* **Cetak Laporan:** Mencetak laporan nilai seluruh siswa dalam bentuk file PDF.

### 2. Guru
**Tugas Utama:** Memberikan dan mengelola nilai siswa.
* **Input Nilai:** Memasukkan nilai Tugas (30%), UTS (30%), dan UAS (40%) untuk siswa pada kelas tertentu. Guru *hanya* dapat menginput nilai untuk mata pelajaran yang ia ampu.
* **Rekap Nilai:** Melihat daftar nilai akhir siswa yang telah diinput beserta grade (A, B, C, D, E) dan status kelulusan (Lulus jika nilai >= 70).

### 3. Siswa
**Tugas Utama:** Melihat hasil belajar.
* **Dashboard:** Melihat rangkuman status kelulusan dan nilai rata-rata.
* **Nilai Saya:** Melihat rincian detail perolehan nilai setiap mata pelajaran (Tugas, UTS, UAS, Nilai Akhir, Grade, Status Lulus).

---

## Tentang Source Code & Komentar
Setiap baris kode penting, *controller*, *model*, *middleware*, dan *helper* telah dilengkapi dengan **komentar Bahasa Indonesia** yang jelas. Hal ini ditujukan untuk mempermudah pemahaman alur aplikasi, sesuai dengan *coding guidelines* dan *best practices* pengembangan Laravel.
