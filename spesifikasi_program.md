# DOKUMEN SPESIFIKASI PROGRAM
## SIMPEL — Sistem Informasi Manajemen Pelajar

**Nama Mahasiswa** : Christian Daniel Wijaya
**NPM**            : 50422359
**Kelas**          : 4IA03
**Mata Kuliah**    : Laboratorium Pemrograman (LSP)
**Teknologi**      : PHP 8.3 + Laravel 13.x + MySQL + Bootstrap 5

---

## Daftar Isi

1. [Latar Belakang](#1-latar-belakang)
2. [Tujuan Pengembangan](#2-tujuan-pengembangan)
3. [Ruang Lingkup Sistem](#3-ruang-lingkup-sistem)
4. [Analisis Masalah](#4-analisis-masalah)
5. [Solusi yang Diusulkan](#5-solusi-yang-diusulkan)
6. [Pengguna Sistem (Stakeholder)](#6-pengguna-sistem-stakeholder)
7. [Spesifikasi Fungsional](#7-spesifikasi-fungsional)
8. [Spesifikasi Non-Fungsional](#8-spesifikasi-non-fungsional)
9. [Batasan Sistem](#9-batasan-sistem)
10. [Asumsi dan Ketergantungan](#10-asumsi-dan-ketergantungan)
11. [Teknologi yang Digunakan](#11-teknologi-yang-digunakan)
12. [Paradigma Pemrograman](#12-paradigma-pemrograman)

---

## 1. Latar Belakang

Pengelolaan data nilai siswa di lingkungan pendidikan merupakan proses yang krusial namun sering kali masih dilakukan secara manual. Guru mencatat nilai pada kertas atau spreadsheet yang tidak terintegrasi, administrasi sekolah harus merekap ulang secara manual, dan siswa tidak memiliki akses real-time untuk memantau perkembangan nilai mereka sendiri.

Kondisi ini menimbulkan berbagai permasalahan:

- **Risiko kehilangan data** akibat pencatatan berbasis kertas atau file yang tidak terkelola dengan baik.
- **Inkonsistensi perhitungan** karena rumus nilai akhir dihitung secara manual dan rawan kesalahan manusia (*human error*).
- **Ketidaktransparanan informasi** bagi siswa yang tidak dapat memantau nilainya secara mandiri.
- **Proses pelaporan yang lambat** karena Admin/Kepala Sekolah harus menunggu rekapitulasi manual dari masing-masing guru.
- **Tidak ada sistem otorisasi** yang memisahkan hak akses antara Admin, Guru, dan Siswa.

Perkembangan teknologi web modern, khususnya framework **Laravel** berbasis **PHP**, membuka peluang untuk membangun sistem informasi akademik yang terkomputerisasi, terpusat, aman, dan mudah digunakan oleh seluruh pemangku kepentingan sekolah.

---
Pengelolaan data nilai siswa di lingkungan pendidikan merupakan proses krusial yang sering kali masih dilakukan secara manual, di mana guru mencatat nilai pada kertas atau spreadsheet yang tidak terintegrasi, administrasi sekolah harus merekap ulang, dan siswa tidak memiliki akses real-time untuk memantau perkembangan mereka. Kondisi ini menimbulkan berbagai rentetan permasalahan, mulai dari tingginya risiko kehilangan data akibat pengelolaan file yang buruk, inkonsistensi perhitungan yang rawan kesalahan manusia (human error), hingga kurangnya transparansi informasi bagi siswa yang tidak dapat memantau nilainya secara mandiri. Selain itu, proses pelaporan menjadi sangat lambat karena pihak sekolah harus menunggu rekapitulasi manual dari masing-masing guru, ditambah lagi dengan ketiadaan sistem otorisasi yang mampu memisahkan hak akses secara tegas antara Admin, Guru, dan Siswa. Untuk mengatasi kendala-kendala tersebut, perkembangan teknologi web modern—khususnya pemanfaatan framework Laravel berbasis PHP—membuka peluang besar untuk membangun sebuah sistem informasi akademik yang terkomputerisasi, terpusat, aman, dan mudah digunakan oleh seluruh pemangku kepentingan di sekolah.

---

## 2. Tujuan Pengembangan

Tujuan utama pengembangan sistem **SIMPEL** adalah:

### 2.1 Tujuan Umum
Membangun sebuah aplikasi web berbasis **PHP dan Framework Laravel** yang mampu mendigitalisasi dan mengotomatisasi proses pengelolaan nilai siswa di lingkungan pendidikan, dengan menerapkan dua paradigma pemrograman secara terintegrasi: **Pemrograman Terstruktur** dan **Pemrograman Berorientasi Objek (OOP)**.

### 2.2 Tujuan Khusus

| No | Tujuan | Indikator Keberhasilan |
|----|--------|------------------------|
| 1 | Mengotomatisasi **perhitungan nilai akhir** siswa berdasarkan bobot komponen | Nilai akhir terhitung secara otomatis saat guru menyimpan nilai |
| 2 | Menerapkan sistem **autentikasi berbasis role** (Admin, Guru, Siswa) | Setiap role hanya mengakses menu sesuai haknya |
| 3 | Menyediakan **akses mandiri bagi siswa** untuk memantau nilainya | Siswa dapat login dan melihat nilai serta status kelulusannya |
| 4 | Memfasilitasi **cetak laporan nilai** dalam format PDF | Admin dapat mengunduh PDF laporan nilai yang terfilter |
| 5 | Menerapkan **validasi ganda** (client-side & server-side) pada input nilai | Nilai di luar rentang 0–100 ditolak oleh sistem |
| 6 | Mengintegrasikan **Pemrograman Terstruktur** dan **OOP** dalam satu sistem | Fungsi prosedural di `NilaiHelper.php` dipanggil oleh class `Nilai` (OOP) |

---

## 3. Ruang Lingkup Sistem

### 3.1 Yang Termasuk dalam Sistem (In-Scope)

- ✅ Manajemen akun pengguna (Admin, Guru, Siswa) dengan satu tabel `users` berbasis kolom `role`
- ✅ Manajemen data master: Siswa, Guru, Mata Pelajaran
- ✅ Penugasan guru ke kelas mengajar (tabel pivot `guru_kelas`)
- ✅ Input, validasi, dan kalkulasi otomatis nilai siswa (Tugas, UTS, UAS → Nilai Akhir)
- ✅ Penentuan status kelulusan berdasarkan KKM (Nilai Akhir ≥ 70)
- ✅ Laporan nilai per siswa, per kelas, dan per mata pelajaran dengan fitur filter
- ✅ Ekspor laporan nilai ke format PDF
- ✅ Dashboard informatif untuk masing-masing role

### 3.2 Yang Tidak Termasuk dalam Sistem (Out-of-Scope)

- ❌ Manajemen tahun ajaran atau semester
- ❌ Sistem absensi siswa
- ❌ Komunikasi antar pengguna (chat/notifikasi)
- ❌ Registrasi akun mandiri oleh siswa atau guru (akun dibuat oleh Admin)
- ❌ Integrasi dengan sistem eksternal (DAPODIK, e-rapor)
- ❌ Aplikasi mobile (iOS/Android)

---

## 4. Analisis Masalah

### 4.1 Identifikasi Masalah

Berdasarkan kondisi pengelolaan nilai yang masih manual, berikut adalah masalah-masalah yang teridentifikasi:

| ID | Masalah | Dampak | Prioritas |
|----|---------|--------|-----------|
| M-01 | Perhitungan nilai akhir dilakukan manual sehingga rawan salah hitung | Ketidakakuratan data nilai siswa | Tinggi |
| M-02 | Data nilai tersebar di banyak file (Excel/kertas) tanpa sentralisasi | Sulit diakses, rentan hilang | Tinggi |
| M-03 | Tidak ada pembatasan akses; siapa saja bisa mengubah data nilai | Risiko manipulasi data | Tinggi |
| M-04 | Siswa tidak dapat memantau nilainya secara mandiri | Ketergantungan pada guru/TU | Sedang |
| M-05 | Proses pembuatan laporan nilai membutuhkan waktu lama | Kepala sekolah lambat menerima data | Sedang |
| M-06 | Tidak ada validasi rentang nilai; nilai tidak wajar bisa masuk | Integritas data tidak terjamin | Sedang |

### 4.2 Akar Permasalahan

```
Tidak adanya sistem informasi akademik yang terpusat
    │
    ├── Pencatatan manual → Rentan human error
    ├── Tidak ada otentikasi → Tidak ada kontrol akses
    ├── Tidak ada otomatisasi → Perhitungan manual
    └── Tidak ada portal siswa → Informasi tidak transparan
```

---

## 5. Solusi yang Diusulkan

Sistem **SIMPEL** dibangun sebagai solusi digital dengan pendekatan berikut:

### 5.1 Arsitektur Single-Table Authentication
Seluruh pengguna (Admin, Guru, Siswa) disimpan dalam **satu tabel `users`** dengan kolom `role` sebagai pembeda hak akses. Ini menyederhanakan sistem autentikasi tanpa mengorbankan keamanan.

```
Tabel users
┌─────────┬──────────┬───────────────────────────┐
│ name    │ email    │ role                      │
├─────────┼──────────┼───────────────────────────┤
│ Admin   │ admin@.. │ 'admin'  → akses penuh    │
│ Budi    │ guru1@.. │ 'guru'   → input nilai    │
│ Andi    │ siswa1@..│ 'siswa'  → lihat nilai    │
└─────────┴──────────┴───────────────────────────┘
```

### 5.2 Kalkulasi Otomatis via Model Event
Nilai akhir **tidak pernah** dihitung secara manual. Setiap kali data nilai disimpan ke database, Model `Nilai` secara otomatis memanggil fungsi prosedural `hitungNilaiAkhir()` melalui *Laravel Model Event* (`static::saving`).

### 5.3 Validasi Berlapis (Double Validation)
- **Client-side**: JavaScript memberikan *preview* real-time dan menandai input merah jika nilai di luar 0–100 *sebelum* form disubmit.
- **Server-side**: Laravel Form Request memvalidasi ulang di server sebagai lapisan keamanan kedua.

### 5.4 Role-Based Access Control (RBAC)
`RoleMiddleware` pada Laravel memastikan setiap URL hanya dapat diakses oleh role yang sesuai. Guru yang mencoba mengakses `/admin/dashboard` akan diredirect ke dashboardnya sendiri.

---

## 6. Pengguna Sistem (Stakeholder)

### 6.1 Deskripsi Pengguna

| Role | Deskripsi | Cara Mendapat Akun |
|------|-----------|--------------------|
| **Admin** | Pengelola sistem. Memiliki akses penuh terhadap seluruh data dan fitur. Biasanya adalah staf Tata Usaha atau Kepala Sekolah. | Dibuat saat pertama kali sistem di-*seed* |
| **Guru** | Pengajar yang bertanggung jawab menginput nilai untuk mata pelajaran yang diampu di kelas tertentu. | Dibuat oleh Admin melalui menu Data Guru |
| **Siswa** | Peserta didik yang dapat memantau nilai dan status kelulusannya secara mandiri. | Dibuat oleh Admin melalui menu Data Siswa |

### 6.2 Matriks Hak Akses

| Fitur | Admin | Guru | Siswa |
|-------|:-----:|:----:|:-----:|
| Login / Logout | ✅ | ✅ | ✅ |
| CRUD Data Siswa | ✅ | ❌ | ❌ |
| CRUD Data Guru | ✅ | ❌ | ❌ |
| CRUD Mata Pelajaran | ✅ | ❌ | ❌ |
| Input Nilai Siswa | ❌ | ✅ | ❌ |
| Lihat Rekap Nilai (semua) | ✅ | ✅ (kelas sendiri) | ❌ |
| Lihat Nilai Pribadi | ❌ | ❌ | ✅ |
| Cetak Laporan PDF | ✅ | ❌ | ❌ |
| Dashboard Statistik | ✅ | ✅ | ✅ |

---

## 7. Spesifikasi Fungsional

### 7.1 Modul Autentikasi

| ID | Nama Fungsi | Deskripsi | Aktor | Prioritas |
|----|-------------|-----------|-------|-----------|
| F-01 | Login | Masuk ke sistem menggunakan email dan password. Sistem mengecek kredensial di tabel `users` lalu meredirect berdasarkan `role`. | Semua | Wajib |
| F-02 | Logout | Keluar dari sistem. Session dihapus dan CSRF token diregenerasi. | Semua | Wajib |
| F-03 | Proteksi Halaman | Halaman yang memerlukan autentikasi dijaga oleh middleware `auth`. Halaman spesifik role dijaga oleh `RoleMiddleware`. | Sistem | Wajib |

### 7.2 Modul Manajemen Data (Admin)

| ID | Nama Fungsi | Deskripsi | Aktor | Prioritas |
|----|-------------|-----------|-------|-----------|
| F-04 | Tambah Siswa | Admin membuat akun siswa baru (data `users` + data `siswas`). Validasi NIS unik dan email unik. | Admin | Wajib |
| F-05 | Lihat Daftar Siswa | Menampilkan tabel seluruh siswa dengan fitur pencarian dan pagination. | Admin | Wajib |
| F-06 | Edit Data Siswa | Admin mengubah data profil siswa (nama, kelas, angkatan) dan data akun (email). | Admin | Wajib |
| F-07 | Hapus Siswa | Menghapus akun siswa. Data nilai terkait ikut terhapus otomatis (*cascade delete*). | Admin | Wajib |
| F-08 | Tambah Guru | Admin membuat akun guru baru beserta penugasan mata pelajaran dan kelas mengajar. | Admin | Wajib |
| F-09 | Edit & Hapus Guru | Admin mengubah atau menghapus data guru. | Admin | Wajib |
| F-10 | CRUD Mata Pelajaran | Admin menambah, mengubah, atau menghapus mata pelajaran. Mata pelajaran yang masih memiliki data nilai tidak dapat dihapus. | Admin | Wajib |

### 7.3 Modul Nilai (Guru)

| ID | Nama Fungsi | Deskripsi | Aktor | Prioritas |
|----|-------------|-----------|-------|-----------|
| F-11 | Pilih Kelas Mengajar | Guru memilih kelas dari dropdown yang hanya menampilkan kelas yang ditugaskan padanya (dari tabel `guru_kelas`). | Guru | Wajib |
| F-12 | Input Nilai | Guru menginput nilai Tugas, UTS, dan UAS untuk setiap siswa di kelas yang dipilih dalam tampilan tabel satu halaman. | Guru | Wajib |
| F-13 | Validasi Nilai (Client-Side) | JavaScript menampilkan *preview* nilai akhir secara real-time dan mewarnai merah kolom yang nilainya di luar 0–100. | Sistem | Wajib |
| F-14 | Validasi Nilai (Server-Side) | Laravel memvalidasi ulang bahwa nilai Tugas, UTS, UAS berada di rentang 0–100. | Sistem | Wajib |
| F-15 | Hitung Nilai Akhir Otomatis | Saat nilai disimpan, Model Event memanggil fungsi `hitungNilaiAkhir()` untuk menghitung: `NA = (30%×Tugas) + (30%×UTS) + (40%×UAS)`. | Sistem | Wajib |
| F-16 | Tentukan Status Kelulusan | Otomatis menentukan `LULUS` (NA ≥ 70) atau `TIDAK LULUS` (NA < 70) menggunakan fungsi `tentukanKelulusan()`. | Sistem | Wajib |
| F-17 | Konversi Grade Huruf | Nilai akhir dikonversi ke grade huruf: A (≥90), B (≥80), C (≥70), D (≥60), E (<60). | Sistem | Wajib |
| F-18 | Lihat Rekap Nilai | Guru melihat rekap semua nilai yang sudah diinput beserta filter per kelas. | Guru | Wajib |

### 7.4 Modul Siswa

| ID | Nama Fungsi | Deskripsi | Aktor | Prioritas |
|----|-------------|-----------|-------|-----------|
| F-19 | Dashboard Siswa | Menampilkan ringkasan informasi: NIS, kelas, angkatan, jumlah mata pelajaran, rata-rata nilai akhir, dan status kelulusan keseluruhan. | Siswa | Wajib |
| F-20 | Lihat Nilai Pribadi | Menampilkan tabel nilai per mata pelajaran: Tugas, UTS, UAS, Nilai Akhir, Grade, dan Status. | Siswa | Wajib |

### 7.5 Modul Laporan (Admin)

| ID | Nama Fungsi | Deskripsi | Aktor | Prioritas |
|----|-------------|-----------|-------|-----------|
| F-21 | Tampil Laporan Nilai | Menampilkan rekap nilai semua siswa dengan filter kelas dan/atau mata pelajaran, disertai statistik ringkasan (total, lulus, tidak lulus, %). | Admin | Wajib |
| F-22 | Download PDF Laporan | Mengekspor laporan nilai (sesuai filter aktif) ke file PDF format A4 Landscape menggunakan library `barryvdh/laravel-dompdf`. | Admin | Wajib |

---

## 8. Spesifikasi Non-Fungsional

| ID | Aspek | Deskripsi | Target |
|----|-------|-----------|--------|
| NF-01 | **Keamanan** | Password di-hash menggunakan bcrypt. Form dilindungi token CSRF. Akses URL dijaga middleware role. | 100% form pakai `@csrf`, semua route sensitif pakai middleware |
| NF-02 | **Performa** | Halaman harus dapat dimuat dengan cepat meski dengan data besar. | Load time < 3 detik dengan 180+ siswa |
| NF-03 | **Usability** | Antarmuka intuitif dan responsif. Feedback visual instan untuk validasi. | Navigasi ≤ 3 klik untuk semua fitur utama |
| NF-04 | **Kompatibilitas** | Dapat diakses dari browser modern tanpa plugin tambahan. | Chrome, Firefox, Edge (versi terbaru) |
| NF-05 | **Maintainability** | Kode mengikuti standar PSR-12, arsitektur MVC, dan DRY principle. | Setiap file punya tanggung jawab tunggal (SRP) |
| NF-06 | **Skalabilitas** | Database dirancang ternormalisasi (3NF) sehingga mudah dikembangkan tanpa restrukturisasi besar. | Tabel ternormalisasi, relasi via Foreign Key |
| NF-07 | **Reliabilitas** | Sistem mampu menangani input tidak valid tanpa crash. | Validasi di dua layer (client + server), error ditampilkan ramah |

---

## 9. Batasan Sistem

Sistem **SIMPEL** dibangun dengan batasan-batasan berikut yang perlu dipahami sebelum digunakan:

1. **Satu Tahun Ajaran**: Sistem tidak memiliki manajemen tahun ajaran atau semester. Semua data nilai dianggap untuk satu periode aktif yang sama.

2. **Satu Mapel per Guru**: Setiap guru hanya dapat mengampu **satu mata pelajaran**, meskipun dapat mengajar di beberapa kelas berbeda (diatur melalui tabel `guru_kelas`).

3. **Bobot Nilai Tetap**: Perhitungan nilai akhir menggunakan bobot yang **tidak dapat dikonfigurasi**: Tugas 30%, UTS 30%, UAS 40%. Perubahan bobot memerlukan modifikasi fungsi `hitungNilaiAkhir()` di file `NilaiHelper.php`.

4. **KKM Tetap**: Kriteria Ketuntasan Minimum (KKM) ditetapkan tetap pada nilai **70**. Perubahan KKM memerlukan modifikasi fungsi `tentukanKelulusan()`.

5. **Tidak Ada Registrasi Mandiri**: Akun untuk Guru dan Siswa **hanya dapat dibuat oleh Admin** melalui aplikasi. Tidak ada fitur pendaftaran mandiri.

6. **Satu Nilai per Siswa per Mapel**: Setiap siswa hanya memiliki **satu set nilai** untuk setiap mata pelajaran (tidak ada nilai per pertemuan atau per tugas individual). Kombinasi `siswa_id + mata_pelajaran_id` bersifat UNIQUE.

7. **Format Laporan**: Laporan hanya dapat dicetak dalam **format PDF**. Ekspor ke Excel atau format lain tidak didukung.

8. **Koneksi Lokal**: Aplikasi dirancang untuk berjalan di server lokal (**Laragon/XAMPP**) menggunakan MySQL. Deployment ke server cloud memerlukan konfigurasi tambahan.

---

## 10. Asumsi dan Ketergantungan

### 10.1 Asumsi

- Setiap siswa yang terdaftar di sistem **pasti memiliki akun user** (tidak ada siswa tanpa akun login).
- Setiap guru **mengampu tepat satu mata pelajaran**, tetapi dapat ditugaskan ke satu atau lebih kelas.
- Data nilai yang diinput oleh guru dianggap **final dan sudah diverifikasi** oleh guru tersebut sebelum disimpan.
- Email pengguna bersifat **unik global** — tidak ada dua pengguna dengan email yang sama, terlepas dari rolenya.

### 10.2 Ketergantungan

| Ketergantungan | Versi | Keterangan |
|----------------|-------|------------|
| PHP | 8.3+ | Runtime bahasa pemrograman utama |
| Laravel Framework | 13.x | Framework MVC utama |
| MySQL | 8.0+ | Database server (via Laragon) |
| Composer | 2.x | Package manager PHP |
| barryvdh/laravel-dompdf | 3.x | Library generasi PDF |
| Bootstrap | 5.3 | Framework CSS/JS untuk UI |
| Laragon / XAMPP | Terbaru | Local development server stack |

---

## 11. Teknologi yang Digunakan

### 11.1 Stack Teknologi

```
┌─────────────────────────────────────────────────┐
│              SIMPEL — Tech Stack                 │
├──────────────┬──────────────────────────────────┤
│ Layer        │ Teknologi                         │
├──────────────┼──────────────────────────────────┤
│ Frontend     │ Blade Templates + Bootstrap 5     │
│              │ + Vanilla JavaScript              │
├──────────────┼──────────────────────────────────┤
│ Backend      │ PHP 8.3 + Laravel 13.x (MVC)     │
│              │ + Eloquent ORM                    │
├──────────────┼──────────────────────────────────┤
│ Database     │ MySQL 8.0 via Laragon             │
├──────────────┼──────────────────────────────────┤
│ Auth         │ Laravel Auth + Custom Middleware  │
├──────────────┼──────────────────────────────────┤
│ PDF          │ barryvdh/laravel-dompdf 3.x       │
└──────────────┴──────────────────────────────────┘
```

### 11.2 Struktur Database

Sistem menggunakan **6 tabel utama** yang saling berelasi:

| No | Tabel | Fungsi |
|----|-------|--------|
| 1 | `users` | Menyimpan akun semua pengguna (Admin, Guru, Siswa) dengan pembeda kolom `role` |
| 2 | `siswas` | Profil akademik siswa (NIS, kelas, angkatan), terhubung ke `users` via `user_id` |
| 3 | `gurus` | Profil guru (NIP, mata pelajaran yang diampu), terhubung ke `users` via `user_id` |
| 4 | `guru_kelas` | Tabel pivot penugasan guru ke kelas mengajar (UNIQUE: `guru_id + kelas`) |
| 5 | `mata_pelajarans` | Data master mata pelajaran (kode, nama) |
| 6 | `nilais` | Data nilai siswa per mata pelajaran (Tugas, UTS, UAS, Nilai Akhir, Status) |

---

## 12. Paradigma Pemrograman

Sistem SIMPEL secara eksplisit mengintegrasikan **dua paradigma pemrograman** sebagai bagian dari persyaratan akademik:

### 12.1 Pemrograman Terstruktur (Prosedural)

Diimplementasikan melalui **fungsi-fungsi global** di file `app/Helpers/NilaiHelper.php` yang dimuat secara otomatis via `composer.json`.

| No | Fungsi | Deskripsi |
|----|--------|-----------|
| 1 | `validasiNilai($nilai)` | Memvalidasi apakah nilai berupa angka dalam rentang 0–100 |
| 2 | `hitungNilaiAkhir($tugas, $uts, $uas)` | Menghitung NA = (0.30×Tugas) + (0.30×UTS) + (0.40×UAS) |
| 3 | `tentukanKelulusan($nilaiAkhir)` | Mengembalikan `"LULUS"` jika NA ≥ 70, `"TIDAK LULUS"` jika NA < 70 |
| 4 | `konversiNilaiKeHuruf($nilaiAkhir)` | Mengonversi nilai ke grade huruf A/B/C/D/E |
| 5 | `formatLaporan($dataNilai)` | Memformat data nilai untuk template PDF |
| 6 | `getWarnaBadge($status)` | Mengembalikan class CSS Bootstrap untuk pewarnaan badge |

### 12.2 Pemrograman Berorientasi Objek (OOP)

Diimplementasikan melalui **class-class Eloquent Model** yang mewarisi (`extends`) class `Model` dari Laravel, menerapkan konsep: *Inheritance*, *Encapsulation*, *Polymorphism*, dan *Association*.

| No | Class | File | Prinsip OOP |
|----|-------|------|-------------|
| 1 | `User` | `app/Models/User.php` | Inheritance, Encapsulation |
| 2 | `Siswa` | `app/Models/Siswa.php` | Association (→ User, → Nilai) |
| 3 | `Guru` | `app/Models/Guru.php` | Association (→ User, → MataPelajaran, → GuruKelas) |
| 4 | `GuruKelas` | `app/Models/GuruKelas.php` | Association (→ Guru) |
| 5 | `MataPelajaran` | `app/Models/MataPelajaran.php` | Association (→ Guru, → Nilai) |
| 6 | `Nilai` | `app/Models/Nilai.php` | **Integrasi OOP+Prosedural** via Model Event `boot()` |

### 12.3 Titik Integrasi Kedua Paradigma

Integrasi paling kuat terlihat pada **Model `Nilai`**. Setiap kali data nilai akan disimpan ke database, *method OOP* `boot()` secara otomatis memanggil *fungsi prosedural* dari `NilaiHelper.php`:

```php
// Di dalam Class Nilai (OOP) — app/Models/Nilai.php
protected static function boot(): void
{
    parent::boot();

    static::saving(function (Nilai $nilai): void {
        // Memanggil fungsi PROSEDURAL dari NilaiHelper.php
        $nilai->nilai_akhir = hitungNilaiAkhir(        // ← Fungsi Prosedural
            $nilai->nilai_tugas,
            $nilai->nilai_uts,
            $nilai->nilai_uas
        );
        $nilai->status = tentukanKelulusan($nilai->nilai_akhir); // ← Fungsi Prosedural
    });
}
```

> **Kesimpulan:** Fungsi prosedural dari `NilaiHelper.php` murni bertugas sebagai *"mesin hitung"* yang independen, sedangkan class OOP (`Nilai`, `Guru`, `Siswa`, dst.) bertugas mengorkestrasi data, relasi, dan perilaku objek. Keduanya saling melengkapi dalam satu sistem yang kohesif.

---

*Dokumen ini merupakan spesifikasi lengkap sistem SIMPEL sebagai panduan analisis dan perancangan sebelum memasuki fase implementasi.*

*Terakhir diperbarui: Juni 2026*
