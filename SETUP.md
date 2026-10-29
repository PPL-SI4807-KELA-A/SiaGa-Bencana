# Panduan Anggota SiaGa Bencana: dari Clone sampai Pull Request

Panduan ini untuk semua anggota Kelompok PPL-SI4807-KELA-A. Ikuti langkahnya **berurutan dari atas**. Proyek memakai **Laravel 12**, **PHP 8.2**, dan **MySQL dari XAMPP** (bukan SQLite).

Aturan branch lengkap ada di [CONTRIBUTING.md](CONTRIBUTING.md).

## Ringkasan langkah

| No | Langkah | Dilakukan |
| --- | --- | --- |
| 0 | Terima undangan repo di GitHub | Sekali |
| 1 | Pasang GitHub Desktop, VS Code, XAMPP, dan Composer | Sekali |
| 2 | Clone repo | Sekali |
| 3 | Jalankan Laravel dengan MySQL | Sekali |
| 4 | Buat branch dengan nama sendiri | Sekali |
| 5 | Ambil update dari `main` | Setiap mulai kerja |
| 6 | Kerjakan PBI, commit, lalu push | Setiap selesai satu bagian |
| 7 | Pull Request ke `main`, lalu merge | Setiap satu PBI selesai |

---

## Langkah 0. Terima undangan repo

1. Buka email dari GitHub, atau buka https://github.com/PPL-SI4807-KELA-A, lalu klik **Accept invitation**.
2. Pastikan kamu bisa membuka https://github.com/PPL-SI4807-KELA-A/SiaGa-Bencana. Kalau muncul 404, berarti undangan belum diterima atau belum dikirim. Hubungi Dhafa.

---

## Langkah 1. Pasang alat yang dibutuhkan

Lewati alat yang sudah terpasang.

### 1.1 GitHub Desktop dan VS Code

1. Pasang **GitHub Desktop** dari https://desktop.github.com, lalu login dengan akun GitHub kamu.
2. Buka **File → Options → Git**. Pastikan **Name** berisi nama lengkap kamu dan **Email** sama dengan email akun GitHub. Dengan begitu, commit tercatat atas nama kamu.
3. Pasang **VS Code** dari https://code.visualstudio.com.

### 1.2 XAMPP (PHP 8.2 dan MySQL)

1. Unduh dari https://www.apachefriends.org (pilih versi **PHP 8.2** atau lebih baru), lalu pasang ke `C:\xampp`.
2. Buka file `C:\xampp\php\php.ini` dengan VS Code. Cari baris berikut, lalu **hapus tanda titik koma (`;`) di depannya**:

   ```
   extension=zip
   extension=pdo_mysql
   extension=fileinfo
   extension=mbstring
   extension=openssl
   extension=curl
   ```

3. Simpan file. Buka **XAMPP Control Panel**, lalu klik **Start** pada **Apache** dan **MySQL**.

### 1.3 Composer

1. Unduh **Composer-Setup.exe** dari https://getcomposer.org/download/.
2. Pilih **Install for all users**.
3. Saat diminta lokasi PHP, pilih **`C:\xampp\php\php.exe`** dan centang **Add this PHP to your path**.
4. Klik **Next** sampai selesai.

### 1.4 Git (disarankan)

GitHub Desktop sudah membawa Git sendiri, tetapi Composer dan terminal VS Code butuh Git terpasang di Windows.

1. Unduh dari https://git-scm.com/download/win, lalu klik **Next** dengan pilihan bawaan.
2. Di layar **Adjusting the name of the initial branch**, pilih **Override** dan isi `main`.

### 1.5 Cek instalasi

**Tutup semua jendela VS Code (File → Exit), lalu buka lagi.** Buka terminal (**Terminal → New Terminal**), lalu jalankan:

```bash
php -v
composer --version
git --version
```

Ketiganya harus menampilkan nomor versi. Kalau muncul `is not recognized`, lihat bagian [Masalah yang sering muncul](#masalah-yang-sering-muncul).

---

## Langkah 2. Clone repo

### Lewat GitHub Desktop (disarankan)

1. **File → Clone repository → tab GitHub.com**.
2. Pilih **PPL-SI4807-KELA-A/SiaGa-Bencana**. Kalau tidak muncul, buka tab **URL** dan isi `https://github.com/PPL-SI4807-KELA-A/SiaGa-Bencana.git`.
3. Di **Local path**, pilih folder penyimpanan, misalnya `Documents\GitHub`.
4. Klik **Clone**, lalu tunggu sampai selesai.
5. Klik **Open in Visual Studio Code**.

### Lewat terminal

```bash
cd $env:USERPROFILE\Documents
git clone https://github.com/PPL-SI4807-KELA-A/SiaGa-Bencana.git
cd SiaGa-Bencana
code .
```

Setelah clone, **Current branch** di GitHub Desktop harus menampilkan **main**.

---

## Langkah 3. Jalankan Laravel dengan MySQL

Semua perintah dijalankan di **terminal VS Code** di dalam folder `SiaGa-Bencana`.

### 3.1 Pasang dependensi

```bash
composer install
```

Tunggu sampai selesai. Folder `vendor` akan muncul. Folder ini tidak ikut di-commit.

### 3.2 Buat file `.env`

```bash
copy .env.example .env
php artisan key:generate
```

### 3.3 Cek pengaturan database di `.env`

Buka file `.env`, lalu pastikan bagian database berisi seperti ini:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siaga_bencana
DB_USERNAME=root
DB_PASSWORD=
```

`DB_PASSWORD` dibiarkan kosong karena user `root` di XAMPP bawaannya tanpa password. **Jangan ubah menjadi `sqlite`.**

### 3.4 Buat database

1. Pastikan **MySQL** di XAMPP Control Panel berstatus **Running** (hijau).
2. Buka http://localhost/phpmyadmin.
3. Klik **New** di sidebar kiri.
4. Isi nama `siaga_bencana`, pilih collation **utf8mb4_unicode_ci**, lalu klik **Create**.

### 3.5 Buat tabel dan data awal

```bash
php artisan migrate --seed
```

Kalau migration dari Ibnu belum masuk ke `main`, yang terbentuk baru tabel bawaan Laravel (`users`, `cache`, `jobs`). Itu normal.

Untuk mengecek tabel yang sudah dibuat:

```bash
php artisan migrate:status
```

### 3.6 Jalankan aplikasi

```bash
php artisan serve
```

Buka http://127.0.0.1:8000 di browser. Kalau halaman Laravel muncul, setup berhasil. Tekan **Ctrl + C** di terminal untuk menghentikan server.

---

## Langkah 4. Buat branch dengan nama sendiri (sekali saja)

Setiap anggota bekerja **hanya** di branch miliknya. Gunakan **huruf kecil semua**:

| Anggota | Branch |
| --- | --- |
| Dhafa | `dhafa` |
| Amrin | `amrin` |
| Arkan | `arkan` |
| Ibnu | `ibnu` |
| Achmad | `achmad` |
| Fiqih | `fiqih` |

### Lewat GitHub Desktop

1. Pastikan **Current branch** adalah **main**, lalu klik **Fetch origin**.
2. Klik **Current branch → New branch**.
3. Isi nama kamu (misalnya `amrin`). Di **Create branch based on**, pilih **main** (bukan `production`).
4. Klik **Create branch**.
5. Klik **Publish branch** di bagian atas.

### Lewat terminal

```bash
git checkout main
git pull origin main
git checkout -b amrin
git push -u origin amrin
```

Ganti `amrin` dengan nama kamu. Setelah ini, **Current branch** harus selalu menampilkan nama kamu saat bekerja.

---

## Langkah 5. Setiap mulai kerja: ambil update dari `main`

Lakukan ini **setiap kali** akan mulai mengerjakan sesuatu, supaya pekerjaan anggota lain ikut masuk ke branch kamu.

### Lewat GitHub Desktop

1. Pastikan **Current branch** adalah branch kamu (misalnya `amrin`).
2. Klik **Fetch origin**.
3. Klik menu **Branch → Update from main**.
4. Kalau ada perubahan, klik **Push origin**.

### Lewat terminal

```bash
git checkout amrin
git pull origin main
```

Setelah itu, jalankan migration baru (kalau ada) dari Ibnu:

```bash
php artisan migrate
```

---

## Langkah 6. Kerjakan PBI, commit, lalu push

1. Kerjakan PBI kamu di VS Code. Lihat pembagian PBI di [CONTRIBUTING.md](CONTRIBUTING.md#pembagian-pbi).
2. Buka GitHub Desktop. Perubahan akan muncul di tab **Changes**.
3. Cek daftar file. **File `.env`, folder `vendor/`, dan `database.sqlite` tidak boleh ada.** Kalau muncul, hilangkan centangnya.
4. Isi **Summary** dengan key Jira di depan, contohnya:

   ```
   DAHIF-3 tambah form tambah pengungsi
   ```

5. Klik **Commit to amrin**.
6. Klik **Push origin**.

Commit dan push sesering mungkin, minimal setiap selesai satu bagian (misalnya selesai form tambah, lalu selesai halaman edit).

Lewat terminal:

```bash
git add .
git commit -m "DAHIF-3 tambah form tambah pengungsi"
git push origin amrin
```

---

## Langkah 7. Satu PBI selesai: Pull Request ke `main`

1. Lakukan **Langkah 5** dulu agar branch kamu memuat `main` terbaru. Selesaikan konflik kalau ada.
2. Jalankan `php artisan serve` dan pastikan aplikasi masih berjalan tanpa error.
3. Di GitHub Desktop, klik **Branch → Create pull request**. Halaman GitHub akan terbuka.
4. Pastikan **base: `main`** dan **compare: `amrin`**.
5. Judul PR: `DAHIF-<nomor> <Nama PBI>`, contohnya `DAHIF-3 Kelola Data Pengungsi`.
6. Di deskripsi, tulis apa yang dikerjakan dan cara mengujinya.
7. Klik **Create pull request**.
8. Langsung klik **Merge pull request**, lalu **Confirm merge**. Tidak perlu approval.
9. **Jangan klik Delete branch.** Lanjutkan PBI berikutnya di branch yang sama.

> **Jangan pernah membuat PR ke `production`.** Branch itu khusus deploy dan hanya diisi Dhafa di akhir Sprint.

---

## Tugas pertama di Sprint 1

| Anggota | Yang dikerjakan pertama |
| --- | --- |
| Ibnu | Migration semua tabel dan seeder di branch `ibnu`, lalu PR ke `main` secepatnya, karena anggota lain menunggu tabel ini. |
| Arkan | Layout Blade dan Bootstrap bersama (navbar, sidebar, template halaman) di branch `arkan`, lalu PR ke `main`. |
| Dhafa | Login/logout, lalu DAHIF-1 dan DAHIF-2. |
| Amrin, Achmad, Fiqih | Selesaikan Langkah 0 sampai 4. Setelah migration Ibnu dan layout Arkan masuk ke `main`, lakukan Langkah 5, lalu mulai PBI masing-masing. |

---

## Aturan penting

- **Jangan push file `.env`.** Kalau `.env` muncul di tab Changes GitHub Desktop, jangan di-commit.
- **Jangan bekerja langsung di `main` atau `production`.** Cek **Current branch** sebelum commit.
- **Jangan mengubah file migration.** Kalau butuh kolom atau tabel tambahan, minta Ibnu.
- **Kerjakan hanya tabel dan file milik PBI kamu.**
- **Jangan hapus branch kamu** setelah merge.

---

## Masalah yang sering muncul

| Pesan error | Penyebab | Solusi |
| --- | --- | --- |
| Repo tidak muncul saat clone, atau 404 | Undangan belum diterima | Lakukan Langkah 0 |
| `composer` / `php` / `git` `is not recognized` | Terminal dibuka sebelum alat dipasang | Tutup **semua** jendela VS Code (File → Exit), lalu buka lagi. Atau jalankan perintah di bawah tabel ini. |
| `The zip extension and unzip/7s commands are both missing` | Ekstensi zip belum aktif | Aktifkan `extension=zip` di `php.ini` (Langkah 1.2) |
| `could not find driver` | Ekstensi pdo_mysql belum aktif | Aktifkan `extension=pdo_mysql` di `php.ini` |
| `SQLSTATE[HY000] [2002] No connection could be made` | MySQL XAMPP belum menyala | Klik **Start** pada MySQL di XAMPP Control Panel |
| `SQLSTATE[HY000] [1049] Unknown database 'siaga_bencana'` | Database belum dibuat | Lakukan Langkah 3.4 |
| `SQLSTATE[HY000] [1045] Access denied for user 'root'` | Password MySQL tidak cocok | Pastikan `DB_PASSWORD=` kosong |
| `Database file at path [...database.sqlite] does not exist` | `.env` masih memakai SQLite | Ubah `DB_CONNECTION=mysql`, lalu jalankan `php artisan config:clear` |
| `Nothing to migrate` padahal tabel belum ada | File `.env` belum disimpan | Simpan `.env` (Ctrl + S), jalankan `php artisan config:clear`, lalu migrate lagi |
| `No application encryption key has been specified` | Belum generate key | Jalankan `php artisan key:generate` |
| `php artisan db:show` error `performance_schema` | Keterbatasan MariaDB di XAMPP | Abaikan. Pakai `php artisan migrate:status` |
| MySQL XAMPP tidak mau Start (port 3306 dipakai) | Ada MySQL lain yang berjalan | Matikan layanan MySQL lain di **Services** Windows, atau tanyakan di grup |
| Konflik saat Update from main | Kamu dan anggota lain mengubah file yang sama | Buka file berkonflik di VS Code, pilih perubahan yang benar, simpan, lalu commit. Tanyakan di grup kalau ragu. |

Perintah untuk menyegarkan PATH tanpa menutup VS Code:

```bash
$env:Path = [Environment]::GetEnvironmentVariable('Path','Machine') + ';' + [Environment]::GetEnvironmentVariable('Path','User')
```

Kalau masih bermasalah, kirim screenshot error ke grup kelompok.
