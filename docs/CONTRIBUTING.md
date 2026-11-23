# Panduan Kontribusi SiaGa Bencana

Aturan kerja Git untuk Kelompok PPL-SI4807-KELA-A. Sejak 23 November 2026 repo ini public, dan branch `main` serta `production` dilindungi ruleset GitHub `proteksi-main-production`. Sebagian aturan di bawah ditegakkan otomatis oleh GitHub, sisanya adalah kesepakatan tim yang wajib diikuti semua anggota.

## Branch

| Branch | Fungsi | Siapa yang merge |
| --- | --- | --- |
| `main` (default) | Branch utama pengembangan, tempat semua PBI digabung | Semua anggota, langsung tanpa approval |
| `production` | Branch deploy. Server hosting mengambil kode dari sini | Dhafa (Product Owner), hanya dari `main` di akhir Sprint |
| `dhafa`, `amrin`, `arkan`, `ibnu`, `achmad`, `fiqih` | Branch kerja masing-masing anggota | Pemilik branch, lewat PR ke `main` |

```
<nama-anggota>  ──PR──▶  main  ──(akhir Sprint)──▶  production  ──▶  hosting
```

**Dilarang push langsung ke `main` dan `production`.** Semua perubahan lewat Pull Request dari branch anggota. Aturan ini ditegakkan oleh ruleset `proteksi-main-production`:

- Push langsung ke `main` dan `production` **ditolak** oleh GitHub. Perubahan hanya bisa masuk lewat PR.
- PR hanya bisa di-merge jika check `Laravel tests` dan `Validate PR title and commits` lolos.
- Metode merge yang diizinkan hanya **Create a merge commit**; Squash dan Rebase dinonaktifkan.
- Force push dan penghapusan branch `main` serta `production` diblokir.
- Tidak perlu approval dari anggota lain (required approvals 0); pemilik PR tetap merge sendiri.

Sebelum ruleset aktif, push langsung pernah terjadi di Sprint 1 (DAHIF-5, commit `3eee375`) dan harus diperbaiki lewat PR susulan. Jika `git push` ditolak dengan pesan seperti `push declined due to repository rule violations`, berarti kamu sedang berada di `main`. Pindahkan pekerjaan ke branch sendiri, lalu buat PR.

Setiap anggota hanya bekerja di branch miliknya sendiri. Branch anggota **tidak dihapus** setelah merge, dan dipakai terus sampai Sprint 2 selesai.

## Membuat branch anggota (sekali saja)

```bash
git checkout main
git pull origin main
git checkout -b amrin
git push -u origin amrin
```

Ganti `amrin` dengan nama masing-masing.

## Urutan awal Sprint 1

1. Dhafa: setup Laravel di `main`, lalu buat `production` dari `main`.
2. Ibnu membantu menyiapkan dasar skema awal. Setiap PIC tetap dapat langsung mengerjakan PBI dan migration untuk tabel milik PBI-nya sendiri; tidak ada PBI yang harus menunggu migration atau data PBI lain.
3. Arkan: layout Blade dan Bootstrap bersama di branch `arkan`, dikirim lewat PR berjudul `CHORE: Layout Bersama`.
4. Setiap anggota menarik `main` terbaru ke branch masing-masing, lalu mengerjakan PBI dengan data dummy sendiri. Tidak perlu menunggu layout Arkan atau implementasi PBI lain.

## Alur kerja harian

```bash
# 1. Pindah ke branch sendiri
git checkout amrin

# 2. Ambil perubahan terbaru dari main
git pull --no-edit origin main
php artisan migrate

# 3. Kerjakan PBI, lalu commit dengan kode dan nama Jira di awal pesan
git add .
git commit -m "DAHIF-3 Kelola Data Pengungsi: tambah form pengungsi"

# 4. Push ke branch sendiri
git push origin amrin
```

Karena branch anggota dipakai lama, **wajib menjalankan `git pull --no-edit origin main` setiap mulai kerja**. Tanpa ini, branch tertinggal jauh dari `main` dan konfliknya menumpuk. Opsi `--no-edit` membuat Git langsung memakai pesan merge bawaan tanpa membuka editor.

## Login, peran, dan layout

Sejak PR `CHORE: Login dan Dashboard Monitoring`, aplikasi memakai login untuk petugas. Ikuti ketentuan berikut saat membuat atau mengubah halaman PBI.

- **Akun petugas** dibuat dengan `php artisan db:seed --class=PetugasSeeder` (kata sandi `password`, hanya untuk lokal): `admin@siagabencana.test` (Admin BPBD), `relawan.bojongsoang@siagabencana.test` (Relawan Posko, posko 1), dan `relawan.dayeuhkolot@siagabencana.test` (Relawan Posko, posko 2). Tidak ada fitur register.
- **Alur masuk:** `/` diarahkan ke Posko Aktif (`/posko`) untuk warga, petugas yang login diarahkan ke `/dasbor`, dan Keluar kembali ke `/posko`.
- **Route petugas** dibungkus middleware di blok komentar PBI sendiri pada `routes/web.php`, misalnya `Route::middleware(['auth', 'peran:Admin BPBD'])->group(...)`. Gunakan `peran:Relawan Posko` atau `peran:Admin BPBD,Relawan Posko` sesuai aktor PBI. Halaman Warga/Pelapor tidak dibungkus.
- **Data per posko:** posko milik relawan dibaca dari `auth()->user()->posko_id`. Jangan memakai ID posko tetap (misalnya konstanta `POSKO_DUMMY_ID = 1`).
- **Test** untuk halaman petugas harus login terlebih dahulu dengan `$this->actingAs(User::factory()->create())` (Admin BPBD) atau `User::factory()->relawanPosko(1)->create()` (Relawan Posko).
- **Layout:** halaman petugas wajib memakai `@extends('layouts.app')` dengan `@section('title')`, `@section('judul')`, dan `@section('menu_aktif')`. Halaman warga wajib memakai `@extends('layouts.guest')`. Jangan membuat halaman HTML mandiri (`<!DOCTYPE html>` sendiri), karena halaman itu kehilangan sidebar dan tombol Keluar.

## Pengujian sebelum PR

`php artisan test` memakai SQLite in-memory dan mengirim data langsung ke route, sehingga tidak menangkap form yang rusak, layout yang salah, atau masalah migration di MySQL. Di Sprint 1, form Lapor Kejadian lolos semua test tetapi tidak bisa dikirim di browser. Karena itu, sebelum membuat PR:

1. Jalankan `php artisan test` sampai semua lolos.
2. Jalankan `php artisan migrate` di MySQL lokal dan pastikan tidak ada error.
3. Buka fitur di browser dengan peran yang sesuai: tambah, ubah, hapus, validasi gagal, dan pesan sukses. Untuk halaman warga, uji dalam keadaan belum login.
4. Tulis di deskripsi PR peran apa yang dipakai dan langkah apa yang sudah diuji.

## Pull Request dan merge

- Buat PR dari branch sendiri ke `main` setiap satu PBI (atau satu bagian CRUD) selesai. Jangan menunggu semua PBI selesai baru membuat PR.
- Target PR selalu `main`. **Jangan pernah membuat PR ke `production`.**
- Judul PR wajib sama persis dengan judul pekerjaan di Jira: `DAHIF-<nomor> <Nama PBI>`, contoh `DAHIF-3 Kelola Data Pengungsi`. Jangan menambahkan `[WIP]`, emoji, tanda baca, atau keterangan lain.
- Pekerjaan yang bukan PBI memakai judul CHORE khusus berikut. Judul lain di luar daftar ini akan ditolak.

  | Judul PR | Branch | Boleh mengubah |
  | --- | --- | --- |
  | `CHORE: Aturan Kontribusi dan Validasi Repository` | `dhafa` | Hanya `docs/`, `.github/`, dan `README.md` |
  | `CHORE: Login dan Dashboard Monitoring` | `dhafa` | File aplikasi untuk login/logout dan Dashboard Monitoring |
  | `CHORE: Layout Bersama` | `arkan` | File aplikasi untuk layout Blade dan Bootstrap bersama |

  Aturan file terbatas (`.env`, workflow, dan `StokService`) tetap berlaku untuk PR CHORE.
- Daftar judul Jira yang valid mengikuti daftar PBI di [README.md](../README.md). GitHub Actions akan menolak check jika judul tidak cocok.
- Pesan commit wajib diawali kode dan nama PBI Jira yang sama persis dengan judul PR, lalu boleh ditambahkan ringkasan teknis setelah tanda titik dua. Contoh: `DAHIF-3 Kelola Data Pengungsi: tambah validasi form`.
- Untuk PR CHORE, pesan commit wajib diawali judul CHORE yang sama persis dengan judul PR, lalu boleh ditambahkan ringkasan perubahan. Contoh: `CHORE: Layout Bersama: tambah navbar per peran`.
- Merge commit hasil `git pull origin main` (misalnya `Merge branch 'main' of ...`) tidak diperiksa, jadi tidak perlu diubah.
- Jangan menggunakan pesan commit umum seperti `Update`, `Fix`, atau `Perubahan` tanpa kode dan nama PBI.
- Deskripsi PR mencantumkan apa yang dikerjakan dan cara mengujinya.
- PR wajib lolos GitHub Action `Laravel tests`. Jika test gagal, perbaiki terlebih dahulu sebelum meminta merge.
- **Tidak perlu approval.** Setiap anggota langsung merge PR miliknya sendiri.
- Merge PR **hanya dengan tombol "Create a merge commit"**. Jangan memakai "Squash and merge" atau "Rebase and merge", karena branch anggota dipakai terus dan commit lama akan muncul lagi di PR berikutnya sehingga PR ditolak.
- Sebelum merge, pastikan branch sudah memuat `main` terbaru, tidak ada konflik, dan aplikasi masih bisa dijalankan (`php artisan serve`).
- Setelah merge, **jangan hapus branch**. Lanjutkan PBI berikutnya di branch yang sama.

## Batas merge dan pembekuan main

Semua PBI dalam satu Sprint wajib sudah di-merge ke `main` paling lambat **hari Jumat sebelum Sprint Review pukul 18.00 WIB** (Sprint 2: Jumat, 25 Desember 2026), karena hari Sabtu hanya dipakai untuk Sprint Review dan Retrospective. Setelah batas itu, `main` dibekukan untuk pengujian integrasi oleh Dhafa:

- Anggota tidak merge PR apa pun ke `main` sampai Dhafa mengumumkan bahwa `main` sudah dibuka lagi.
- Bug yang ditemukan saat integrasi diperbaiki oleh PIC PBI tersebut. Jika PIC tidak bisa dihubungi sebelum rilis, Dhafa boleh memperbaikinya lewat PR `CHORE: Login dan Dashboard Monitoring` **setelah mendapat izin PIC**, dan perubahannya dicatat di deskripsi PR. Perbaikan dibatasi pada hal yang menghalangi demo; sisanya dicatat untuk Sprint berikutnya.
- Bug yang ditemukan anggota saat menguji `main` jangan langsung diperbaiki dan di-push. Laporkan dulu ke grup dengan langkah dan screenshot.

## Rilis ke production

Di akhir setiap Sprint (setelah pekan pengujian dan integrasi), Dhafa membuat PR dari `main` ke `production`, lalu merge:

- Judul PR rilis wajib menggunakan format `Release Sprint <nomor>`, misalnya `Release Sprint 1`.

- Sprint 1: 21 November 2026 (sudah dirilis lewat PR #27)
- Sprint 2: 26 Desember 2026

Server hosting kemudian mengambil versi terbaru dari `production`:

```bash
git checkout production
git pull origin production
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize
```

Jangan mengembangkan fitur langsung di `production`. Kalau ada bug di produksi, perbaiki di branch anggota yang memegang PBI tersebut, merge ke `main`, lalu rilis ulang dari `main` ke `production`.

## Aturan khusus

- **Setiap PBI harus dapat dikembangkan dan diuji secara mandiri.** Jangan menunggu PBI anggota lain selesai untuk mulai coding atau menguji fitur sendiri.
- **Data dummy dibuat dan dikelola sendiri oleh PIC PBI.** Setiap PBI harus dapat dimigrasikan, dijalankan, dan diuji tanpa migration, tabel, atau data dari PBI lain. Jangan membuat relasi database wajib yang membuat satu PBI tidak bisa berjalan tanpa PBI lain.
- **PIC PBI membuat migration baru di branch miliknya untuk tabel dalam scope PBI tersebut.** Migration hanya mengelola tabel PBI sendiri dan harus dapat dijalankan tanpa migration PBI lain. Jangan mengubah migration yang sudah di-merge atau tabel milik PBI lain. Ibnu dapat membantu menjaga konsistensi rancangan skema, tetapi bukan prasyarat atau antrean persetujuan untuk mulai mengerjakan PBI.
- **Data dummy untuk demo diisi lewat phpMyAdmin**, memakai file SQL bersama (misalnya `data_demo_sprint1.sql`) yang dibagikan Dhafa, agar semua anggota menguji dengan data yang sama. Seeder baru hanya dibuat untuk kebutuhan yang tidak bisa diisi lewat SQL, seperti akun petugas di `PetugasSeeder`. Seeder PBI yang sudah ada tetap boleh dipakai untuk lokal, dijalankan langsung dengan `php artisan db:seed --class=NamaSeeder`. Hindari mengedit `DatabaseSeeder.php`.
- **Nama tabel stok di kode adalah `stok_poskos`**, berbeda dengan rancangan proposal (`stok_posko`). Gunakan nama yang ada di kode; penyeragaman, jika diputuskan, dilakukan lewat migration baru, bukan dengan mengubah migration lama.
- **Jangan push file `.env`.** Bagikan konfigurasi lewat `.env.example`.
- **Satu PBI hanya mengubah tabel yang menjadi tanggung jawabnya**, sesuai pembagian PBI. Jangan mengubah tabel atau migration milik PBI lain. Jangan membuat foreign key atau ketergantungan skema wajib antar-PBI; siapkan data dummy lokal agar PBI tetap dapat diuji sendiri.
- **StokService** (PBI-05, 15, 16) hanya diubah oleh Ibnu.
- PR wajib dibuat dari branch PIC sesuai pembagian PBI. GitHub Actions akan menutup PR jika branch tidak sesuai dengan PBI.
- Migration baru untuk tabel PBI boleh ditambahkan dari branch PIC PBI terkait. GitHub Actions memeriksa bahwa PR berasal dari branch PIC yang sesuai dengan kode PBI pada judulnya.
- `app/Services/StokService.php` hanya boleh diubah dari branch `ibnu`.
- File `.env` dan file environment lain yang berisi konfigurasi lokal tidak boleh diubah atau di-commit. `.env.example` boleh diperbarui jika diperlukan.
- File `.github/workflows/` hanya boleh diubah dari branch `dhafa` agar aturan Action tidak berubah sembarangan.
- **Penggunaan AI wajib mengikuti [Aturan Penggunaan AI](ATURAN_PENGGUNAAN_AI.md).** Sebelum mengirim prompt pekerjaan, anggota wajib memberikan `CONTRIBUTING.md` dan `ATURAN_PENGGUNAAN_AI.md` kepada AI, lalu meminta AI mengonfirmasi bahwa kedua dokumen sudah dibaca dan dipahami. Pesan commit dan judul PR tetap diketik sendiri sesuai format, bukan memakai pesan buatan AI seperti `feat: ...`.
- **Akun GitHub wajib memakai autentikasi dua faktor (2FA)** paling lambat 24 November 2026. Tanpa 2FA, akses ke organisasi bisa dibatasi oleh GitHub.

## Pembagian PBI

| Anggota | Branch | Sprint 1 | Sprint 2 |
| --- | --- | --- | --- |
| Dhafa | `dhafa` | DAHIF-1, DAHIF-2 (+ login, dashboard) | DAHIF-10 |
| Amrin | `amrin` | DAHIF-3 | DAHIF-11, DAHIF-12 |
| Arkan | `arkan` | DAHIF-4 | DAHIF-13, DAHIF-14 |
| Ibnu | `ibnu` | DAHIF-5 | DAHIF-15, DAHIF-16 |
| Achmad (Atsir) | `achmad` | DAHIF-6, DAHIF-7 | DAHIF-17 |
| Fiqih | `fiqih` | DAHIF-8, DAHIF-9 | DAHIF-18 |
