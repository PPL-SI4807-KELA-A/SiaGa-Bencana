# SiaGa Bencana

Aplikasi web untuk membantu pengelolaan dan koordinasi tanggap darurat bencana di wilayah perkotaan. Proyek ini mengangkat **SDG 11 (Sustainable Cities and Communities)**, khususnya target 11.5 dan 11.b.

SiaGa Bencana bukan alat prediksi bencana. Fokusnya adalah pengelolaan informasi tanggap darurat: posko dan kapasitasnya, pengungsi, logistik, permintaan kebutuhan mendesak, laporan kejadian bencana beserta verifikasinya, serta dashboard monitoring.

Tugas Besar mata kuliah Proyek Perangkat Lunak, Kelompok PPL-SI4807-KELA-A.

> **Anggota tim:** ikuti [Panduan Anggota SiaGa Bencana](https://claude.ai/artifact/9YnawB7kUNGnYNAr6gSrA5) langkah demi langkah, mulai dari clone sampai Pull Request. Aturan branch ada di [CONTRIBUTING.md](docs/CONTRIBUTING.md).

## Aktor

| Aktor | Peran singkat |
| --- | --- |
| Warga/Pelapor | Tanpa akun. Melaporkan kejadian bencana, mengajukan permintaan kebutuhan mendesak, melacak laporan dengan kode, dan melihat posko aktif, pengumuman, serta kontak layanan darurat. |
| Relawan Posko | Mengelola fasilitas posko, pengungsi, stok, penerimaan logistik, dan distribusi bantuan di poskonya. |
| Admin BPBD | Mengelola wilayah, posko, kategori bencana, data barang, pengumuman, dan kontak layanan; memverifikasi laporan dan memantau dashboard seluruh posko. |

## Teknologi

- Laravel 12 (PHP 8.2 atau lebih baru)
- Blade, HTML, CSS, JavaScript, Bootstrap
- MySQL (bukan SQLite)
- Pengembangan lokal: XAMPP
- Manajemen Sprint: Jira (proyek DAHIF)

## Cakupan dan batasan

- Simulasi di Kecamatan Bojongsoang dan Dayeuhkolot, dengan banjir sebagai skenario utama.
- Web responsif, tanpa aplikasi mobile native.
- Tanpa prediksi atau peringatan dini, IoT, peta interaktif/GPS, payment gateway, dan integrasi dengan sistem BPBD/BMKG.
- Notifikasi hanya di dalam aplikasi. Data uji berupa data simulasi.

## Product Backlog (18 PBI)

| Jira | PBI | PIC | Sprint |
| --- | --- | --- | --- |
| DAHIF-1 | Kelola Data Wilayah Terdampak | Dhafa | 1 |
| DAHIF-2 | Kelola Data Posko Pengungsian | Dhafa | 1 |
| DAHIF-3 | Kelola Data Pengungsi | Amrin | 1 |
| DAHIF-4 | Kelola Sumber Bantuan | Arkan | 1 |
| DAHIF-5 | Kelola Stok Logistik Posko | Ibnu | 1 |
| DAHIF-6 | Kelola Data Barang Logistik | Achmad | 1 |
| DAHIF-7 | Kelola Permintaan Kebutuhan Mendesak | Achmad | 1 |
| DAHIF-8 | Kelola Kategori Bencana | Fiqih | 1 |
| DAHIF-9 | Kelola Laporan Kejadian Bencana | Fiqih | 1 |
| DAHIF-10 | Kelola Fasilitas Posko | Dhafa | 2 |
| DAHIF-11 | Kelola Kondisi Medis Pengungsi | Amrin | 2 |
| DAHIF-12 | Kelola Kontak Darurat Pengungsi | Amrin | 2 |
| DAHIF-13 | Kelola Pengumuman Darurat | Arkan | 2 |
| DAHIF-14 | Kelola Kontak Layanan Darurat | Arkan | 2 |
| DAHIF-15 | Kelola Penerimaan Logistik | Ibnu | 2 |
| DAHIF-16 | Kelola Distribusi Bantuan | Ibnu | 2 |
| DAHIF-17 | Kelola Tindak Lanjut Permintaan | Achmad | 2 |
| DAHIF-18 | Kelola Verifikasi Laporan | Fiqih | 2 |

Login/logout dan Dashboard Monitoring dikerjakan Dhafa dan tidak dihitung sebagai PBI.

## Jadwal

| Sprint | Periode | PBI |
| --- | --- | --- |
| Sprint 1 | 26 Oktober – 21 November 2026 | DAHIF-1 s.d. DAHIF-9 |
| Sprint 2 | 23 November – 26 Desember 2026 | DAHIF-10 s.d. DAHIF-18 |

## Tim Pengembang

| Nama | NIM | Peran |
| --- | --- | --- |
| Dhafa Haditya Ramadhan | 102022400169 | Product Owner, Project Manager, Full-Stack Developer |
| Amrin Rasyida Utami | 102022430061 | Scrum Master, Full-Stack Developer |
| Hadyan Arkananta Ryandi | 102022400309 | Full-Stack Developer (kerangka tampilan) |
| Ibnu Rival Jabir | 102022400012 | Full-Stack Developer (struktur basis data) |
| Achmad Atsir Hadiid Alhariz | 102022400175 | Full-Stack Developer |
| Muhammad Fiqih Rahman | 102022400070 | QA, Full-Stack Developer |

## Aturan branch

- `main` (default branch): branch utama pengembangan. Semua PBI digabung ke sini lewat Pull Request. Semua anggota boleh langsung merge PR miliknya sendiri.
- `production`: branch untuk deploy. Hanya diisi dari `main` di akhir setiap Sprint, setelah lolos pengujian. Server hosting mengambil kode dari branch ini.
- Branch anggota: satu branch per anggota sesuai nama, dengan huruf kecil: `dhafa`, `amrin`, `arkan`, `ibnu`, `achmad`, dan `fiqih`.
- Dilarang push langsung ke `main` dan `production`. Semua perubahan lewat Pull Request ke `main`, tanpa perlu approval.
