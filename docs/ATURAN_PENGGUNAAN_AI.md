# Aturan Penggunaan AI

Dokumen ini wajib diberikan sebagai konteks kepada AI sebelum anggota meminta bantuan untuk coding, debugging, refactoring, atau pembuatan pengujian di proyek SiaGa Bencana.

**Urutan wajib:** berikan `CONTRIBUTING.md` dan `ATURAN_PENGGUNAAN_AI.md` terlebih dahulu, minta AI membaca dan mengonfirmasi pemahamannya, kemudian kirim prompt pekerjaan beserta file kode yang relevan. Jangan meminta AI menulis atau mengubah kode sebelum dua dokumen aturan tersebut diberikan.

## Aturan Wajib

AI wajib membaca dan mengikuti dokumen ini serta [CONTRIBUTING.md](CONTRIBUTING.md) terlebih dahulu. Jika konteks belum cukup, AI harus meminta informasi tambahan dan tidak boleh mengarang nama file, tabel, kolom, model, route, view, dependency, atau aturan bisnis.

Setiap anggota tetap bertanggung jawab atas kode yang dihasilkan AI.

Sebelum menulis kode, AI wajib membuat ringkasan scope yang berisi:

- tujuan perubahan sesuai PBI;
- daftar file yang akan dibuat atau diubah;
- alasan setiap file berkaitan dengan PBI;
- file atau bagian yang sengaja tidak diubah.

Jika AI menemukan kebutuhan untuk mengubah file, tabel, atau bagian di luar scope PBI, AI harus berhenti dan meminta persetujuan anggota terlebih dahulu.

Jika AI membantu membuat perubahan, pesan commit tetap wajib mengikuti format Jira:

```text
DAHIF-<nomor> <judul PBI persis dari Jira>: <ringkasan perubahan>
```

Contoh:

```text
DAHIF-3 Kelola Data Pengungsi: tambah validasi form
```

## Konteks Proyek

- Nama proyek: **SiaGa Bencana**.
- Tujuan: aplikasi web untuk pengelolaan dan koordinasi tanggap darurat bencana.
- Backend: Laravel 12 dan PHP 8.2 atau lebih baru.
- Frontend: Blade, HTML, CSS, Bootstrap, dan JavaScript.
- Database: MySQL, bukan SQLite.
- Web server lokal: XAMPP atau server PHP lokal yang kompatibel.
- Package manager PHP: Composer.
- Package manager frontend: npm.
- Build tool frontend: Vite.
- Testing: PHPUnit melalui Laravel.
- Version control: Git.
- Repository dan Pull Request: GitHub.
- Manajemen pekerjaan: Jira dengan kode proyek `DAHIF`.
- Aktor: Warga/Pelapor, Relawan Posko, dan Admin BPBD.
- Branch utama: `main`.
- Branch deploy: `production`.

## Batasan Tools dan Database

AI dan anggota wajib mengikuti ketentuan berikut:

- Jangan mengganti MySQL dengan SQLite tanpa persetujuan tim.
- Jangan mengganti Laravel, PHP, Bootstrap, Vite, atau dependency utama tanpa persetujuan.
- Jangan menambahkan package Composer atau npm jika tidak diperlukan oleh PBI.
- Jangan mengubah struktur database, migration, atau relasi tabel di luar scope PBI.
- Migration baru hanya dibuat melalui branch `ibnu` sesuai aturan kontribusi.
- Jangan mengubah file `.env` atau membagikan isinya. Gunakan `.env.example` untuk dokumentasi konfigurasi.
- Jalankan perintah sesuai tools proyek, misalnya `composer install`, `npm install`, `npm run build`, `php artisan migrate`, dan `php artisan test` jika relevan.
- Jangan mengarang konfigurasi XAMPP, MySQL, Composer, npm, atau Vite yang tidak sesuai dengan repository.

## Informasi yang Wajib Diberikan ke AI

Setiap prompt minimal mencantumkan:

- kode dan judul Jira/PBI;
- tujuan perubahan dan aktor pengguna;
- file kode serta tabel atau model yang terkait;
- aturan atau batasan yang harus dipatuhi;
- pesan error atau perilaku yang sedang terjadi;
- hasil yang diharapkan.

## Template Prompt

```text
Saya akan memberikan dua dokumen aturan terlebih dahulu. Baca dan pahami
CONTRIBUTING.md serta ATURAN_PENGGUNAAN_AI.md, lalu konfirmasi bahwa Anda sudah
memahaminya. Jangan menulis kode sebelum kedua dokumen tersebut dibaca.

Setelah itu, gunakan aturan tersebut untuk menjawab prompt berikut. Jangan
membuat asumsi tentang nama file, tabel, kolom, route, atau aturan bisnis yang
belum diberikan. Jika konteks kurang, tanyakan terlebih dahulu.

Proyek: SiaGa Bencana
Teknologi: Laravel 12, PHP 8.2+, Blade, Bootstrap, JavaScript, MySQL
Jira/PBI: DAHIF-<nomor> <judul persis dari Jira>
Aktor: <Warga/Pelapor, Relawan Posko, atau Admin BPBD>
Tujuan: <jelaskan perubahan>
File terkait: <daftar file>
Struktur data terkait: <tabel/model/kolom>
Aturan tambahan: <batasan khusus>
Masalah/error: <jelaskan masalah>
Hasil yang diharapkan: <jelaskan hasil>

Berikan solusi yang sesuai dengan konteks proyek. Tampilkan asumsi jika ada,
buat ringkasan scope sebelum menulis kode, sertakan langkah pengujian, dan
jangan mengubah bagian di luar cakupan PBI.
```

## Pemeriksaan Hasil AI

Sebelum commit, anggota wajib:

- memahami kode yang dihasilkan;
- memeriksa kesesuaian dengan Jira dan struktur proyek;
- memeriksa keamanan, validasi, dan otorisasi;
- meninjau perubahan dengan `git diff`;
- menjalankan aplikasi dan pengujian yang relevan;
- memastikan tidak ada credential, data pribadi, atau isi `.env` yang diberikan kepada AI atau di-commit.
