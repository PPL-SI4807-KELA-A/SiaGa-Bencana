# Panduan Kontribusi SiaGa Bencana

Aturan kerja Git untuk Kelompok PPL-SI4807-KELA-A. Repo ini private di organisasi paket Free, jadi proteksi branch **tidak dipaksa oleh GitHub**. Aturan di bawah adalah kesepakatan tim dan wajib diikuti semua anggota.

## Branch

| Branch | Fungsi | Siapa yang merge |
| --- | --- | --- |
| `main` (default) | Branch utama pengembangan, tempat semua PBI digabung | Semua anggota, langsung tanpa approval |
| `production` | Branch deploy. Server hosting mengambil kode dari sini | Dhafa (Product Owner), hanya dari `main` di akhir Sprint |
| `dhafa`, `amrin`, `arkan`, `ibnu`, `achmad`, `fiqih` | Branch kerja masing-masing anggota | Pemilik branch, lewat PR ke `main` |

```
<nama-anggota>  ──PR──▶  main  ──(akhir Sprint)──▶  production  ──▶  hosting
```

**Dilarang push langsung ke `main` dan `production`.** Semua perubahan lewat Pull Request dari branch anggota.

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
2. Ibnu: migration 19 tabel di branch `ibnu`, di-merge ke `main` di hari pertama.
3. Arkan: layout Blade dan Bootstrap bersama di branch `arkan`.
4. Anggota lain menarik `main` ke branchnya setelah poin 2 masuk, lalu langsung mengerjakan PBI dengan data dummy sendiri. Tidak perlu menunggu layout Arkan.

## Alur kerja harian

```bash
# 1. Pindah ke branch sendiri
git checkout amrin

# 2. Ambil perubahan terbaru dari main
git pull origin main

# 3. Kerjakan PBI, lalu commit dengan key Jira di awal pesan
git add .
git commit -m "DAHIF-3 tambah form tambah pengungsi"

# 4. Push ke branch sendiri
git push origin amrin
```

Karena branch anggota dipakai lama, **wajib menjalankan `git pull origin main` setiap mulai kerja**. Tanpa ini, branch tertinggal jauh dari `main` dan konfliknya menumpuk.

## Pull Request dan merge

- Buat PR dari branch sendiri ke `main` setiap satu PBI (atau satu bagian CRUD) selesai. Jangan menunggu semua PBI selesai baru membuat PR.
- Target PR selalu `main`. **Jangan pernah membuat PR ke `production`.**
- Judul PR: `DAHIF-<nomor> <Nama PBI>`, contoh `DAHIF-3 Kelola Data Pengungsi`.
- Deskripsi PR mencantumkan apa yang dikerjakan dan cara mengujinya.
- **Tidak perlu approval.** Setiap anggota langsung merge PR miliknya sendiri.
- Sebelum merge, pastikan branch sudah memuat `main` terbaru, tidak ada konflik, dan aplikasi masih bisa dijalankan (`php artisan serve`).
- Setelah merge, **jangan hapus branch**. Lanjutkan PBI berikutnya di branch yang sama.

## Rilis ke production

Di akhir setiap Sprint (setelah pekan pengujian dan integrasi), Dhafa membuat PR dari `main` ke `production`, lalu merge:

- Sprint 1: 21 November 2026
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

- **Migration hanya lewat Ibnu.** Butuh kolom atau tabel tambahan? Minta Ibnu membuat migration baru. Jangan mengubah file migration yang sudah di-merge.
- **Data dummy dibuat sendiri.** Setiap anggota membuat seeder untuk tabel PBI-nya (`php artisan make:seeder PengungsiSeeder`), lalu menambah satu baris `$this->call(...)` di `DatabaseSeeder.php`. Kalau butuh data dari tabel anggota lain yang belum ada, buat baris minimal dengan `updateOrInsert` agar tidak dobel.
- **Jangan push file `.env`.** Bagikan konfigurasi lewat `.env.example`.
- **Satu PBI hanya menyentuh tabel miliknya sendiri**, sesuai pembagian 18 PBI.
- **StokService** (PBI-05, 15, 16) hanya diubah oleh Ibnu.

## Pembagian PBI

| Anggota | Branch | Sprint 1 | Sprint 2 |
| --- | --- | --- | --- |
| Dhafa | `dhafa` | DAHIF-1, DAHIF-2 (+ login, dashboard) | DAHIF-10 |
| Amrin | `amrin` | DAHIF-3 | DAHIF-11, DAHIF-12 |
| Arkan | `arkan` | DAHIF-4 | DAHIF-13, DAHIF-14 |
| Ibnu | `ibnu` | DAHIF-5 | DAHIF-15, DAHIF-16 |
| Achmad | `achmad` | DAHIF-6, DAHIF-7 | DAHIF-17 |
| Fiqih | `fiqih` | DAHIF-8, DAHIF-9 | DAHIF-18 |
