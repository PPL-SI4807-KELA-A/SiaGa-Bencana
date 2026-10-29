# Tutorial Setup SiaGa Bencana untuk Anggota Tim

Panduan ini untuk anggota yang baru pertama kali menjalankan proyek SiaGa Bencana di laptopnya. Ikuti dari atas ke bawah. Proyek memakai **Laravel 12**, **PHP 8.2**, dan **MySQL dari XAMPP** (bukan SQLite).

Aturan branch dan alur kerja lengkap ada di [CONTRIBUTING.md](CONTRIBUTING.md).

---

## Bagian 1. Pasang alat yang dibutuhkan

Lewati alat yang sudah terpasang.

### 1.1 XAMPP (PHP 8.2 dan MySQL)

1. Unduh dari https://www.apachefriends.org, pilih versi dengan **PHP 8.2** atau lebih baru.
2. Pasang ke lokasi bawaan `C:\xampp`.
3. Buka **XAMPP Control Panel**, lalu klik **Start** pada **Apache** dan **MySQL**.

### 1.2 Aktifkan ekstensi PHP

1. Buka file `C:\xampp\php\php.ini` dengan VS Code atau Notepad.
2. Cari baris-baris berikut, lalu **hapus tanda titik koma (`;`) di depannya**:

   ```
   extension=zip
   extension=pdo_mysql
   extension=fileinfo
   extension=mbstring
   extension=openssl
   extension=curl
   ```

3. Simpan file, lalu **Stop** dan **Start** ulang Apache di XAMPP Control Panel.

### 1.3 Git

1. Unduh dari https://git-scm.com/download/win, lalu jalankan installer.
2. Klik **Next** dengan pilihan bawaan, kecuali:
   - **Choosing the default editor**: pilih **Use Visual Studio Code as Git's default editor**.
   - **Adjusting the name of the initial branch**: pilih **Override**, isi `main`.
3. Atur identitas Git (pakai email yang sama dengan akun GitHub):

   ```bash
   git config --global user.name "Nama Lengkap Kamu"
   git config --global user.email "email-github-kamu@contoh.com"
   ```

### 1.4 Composer

1. Unduh **Composer-Setup.exe** dari https://getcomposer.org/download/.
2. Pilih **Install for all users**.
3. Saat diminta lokasi PHP, pilih **`C:\xampp\php\php.exe`** dan centang **Add this PHP to your path**.
4. Klik **Next** sampai selesai.

### 1.5 Cek instalasi

**Tutup semua jendela VS Code dan terminal (File → Exit), lalu buka lagi.** Setelah itu jalankan:

```bash
git --version
php -v
composer --version
```

Ketiganya harus menampilkan nomor versi. Kalau ada yang muncul `is not recognized`, lihat bagian **Masalah yang sering muncul** di bawah.

---

## Bagian 2. Ambil proyek dari GitHub

Pastikan kamu sudah diundang ke repo **PPL-SI4807-KELA-A/SiaGa-Bencana** dan sudah menerima undangannya (cek email atau notifikasi GitHub).

### Pilihan A: lewat GitHub Desktop

1. **File → Clone repository → URL**.
2. Isi `https://github.com/PPL-SI4807-KELA-A/SiaGa-Bencana.git`.
3. Pilih folder penyimpanan, lalu klik **Clone**.
4. Klik **Open in Visual Studio Code**.

### Pilihan B: lewat terminal

```bash
cd $env:USERPROFILE\Documents
git clone https://github.com/PPL-SI4807-KELA-A/SiaGa-Bencana.git
cd SiaGa-Bencana
code .
```

Saat clone pertama kali, akan muncul jendela login GitHub. Login dengan akun GitHub kamu.

---

## Bagian 3. Jalankan proyek dengan MySQL

Semua perintah di bawah dijalankan di **terminal VS Code** (Terminal → New Terminal), di dalam folder `SiaGa-Bencana`.

### 3.1 Pasang dependensi Laravel

```bash
composer install
```

Tunggu sampai selesai. Folder `vendor` akan muncul.

### 3.2 Buat file `.env`

```bash
copy .env.example .env
php artisan key:generate
```

### 3.3 Atur `.env` agar memakai MySQL

Buka file `.env`, cari bagian database, lalu pastikan isinya seperti ini. Hapus tanda `#` di depan baris kalau ada:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siaga_bencana
DB_USERNAME=root
DB_PASSWORD=
```

> **Jangan pakai `DB_CONNECTION=sqlite`.** Proyek ini memakai MySQL. Kalau di folder `database` ada file `database.sqlite`, abaikan saja.

`DB_PASSWORD` dikosongkan karena user `root` di XAMPP bawaannya tanpa password.

### 3.4 Buat database di phpMyAdmin

1. Pastikan **MySQL** di XAMPP Control Panel berstatus **Running** (hijau).
2. Buka http://localhost/phpmyadmin di browser.
3. Klik **New** di sidebar kiri.
4. Isi nama database `siaga_bencana`, pilih collation **utf8mb4_unicode_ci**, lalu klik **Create**.

### 3.5 Buat tabel dan isi data awal

```bash
php artisan migrate --seed
```

Perintah ini membuat semua tabel dan mengisi data awal (akun petugas, posko, kategori bencana, barang) dari seeder Ibnu. Kalau migration Ibnu belum di-merge ke `main`, yang terbentuk baru tabel bawaan Laravel. Itu normal.

Kalau nanti ada migration baru dari Ibnu, jalankan ulang:

```bash
php artisan migrate
```

Kalau ingin mengulang database dari nol (semua data hilang):

```bash
php artisan migrate:fresh --seed
```

### 3.6 Jalankan aplikasi

```bash
php artisan serve
```

Buka http://127.0.0.1:8000 di browser. Kalau halaman Laravel atau halaman SiaGa Bencana muncul, setup berhasil. Tekan **Ctrl + C** di terminal untuk menghentikan server.

---

## Bagian 4. Buat branch dengan nama sendiri (sekali saja)

Setiap anggota bekerja di branch dengan namanya sendiri:

| Anggota | Branch |
| --- | --- |
| Dhafa | `dhafa` |
| Amrin | `amrin` |
| Arkan | `arkan` |
| Ibnu | `ibnu` |
| Achmad | `achmad` |
| Fiqih | `fiqih` |

### Lewat GitHub Desktop

1. Pastikan **Current branch** adalah **main**, lalu klik **Fetch origin** atau **Pull origin**.
2. Klik **Current branch → New branch**.
3. Isi nama kamu (misalnya `amrin`), pastikan dibuat dari **main**, lalu klik **Create branch**.
4. Klik **Publish branch**.

### Lewat terminal

```bash
git checkout main
git pull origin main
git checkout -b amrin
git push -u origin amrin
```

Ganti `amrin` dengan nama kamu.

---

## Bagian 5. Alur kerja harian

### Setiap mulai kerja

```bash
git checkout amrin
git pull origin main
php artisan migrate
```

- `git pull origin main` mengambil pekerjaan anggota lain yang sudah di-merge.
- `php artisan migrate` menjalankan migration baru kalau ada.

### Setelah mengerjakan sesuatu

```bash
git add .
git commit -m "DAHIF-3 tambah form tambah pengungsi"
git push origin amrin
```

Selalu awali pesan commit dengan **key Jira** PBI yang dikerjakan (DAHIF-1 sampai DAHIF-18).

Di GitHub Desktop: tulis Summary, klik **Commit to amrin**, lalu **Push origin**.

### Setelah satu PBI selesai: gabung ke `main`

1. Jalankan `git pull origin main` dan pastikan tidak ada konflik.
2. Jalankan `php artisan serve` dan pastikan aplikasi masih berjalan.
3. Buka repo di GitHub, klik **Compare & pull request** (atau **Pull requests → New pull request**).
4. Pastikan **base: `main`** dan **compare: `amrin`**.
5. Judul PR: `DAHIF-3 Kelola Data Pengungsi`. Tulis singkat apa yang dikerjakan.
6. Klik **Create pull request**, lalu langsung klik **Merge pull request** dan **Confirm merge**.
7. **Jangan hapus branch kamu.** Lanjutkan PBI berikutnya di branch yang sama.

> **Jangan pernah membuat PR ke `production`.** Branch itu khusus untuk deploy dan hanya diisi Dhafa di akhir Sprint.

---

## Bagian 6. Aturan penting

- **Jangan push file `.env`.** File ini sudah diabaikan oleh `.gitignore`. Kalau `.env` muncul di daftar perubahan GitHub Desktop, jangan di-commit.
- **Jangan push langsung ke `main` atau `production`.** Selalu lewat Pull Request dari branch sendiri.
- **Jangan mengubah file migration.** Kalau butuh kolom atau tabel tambahan, minta Ibnu.
- **Kerjakan hanya tabel milik PBI kamu.** Lihat pembagian PBI di [CONTRIBUTING.md](CONTRIBUTING.md).

---

## Masalah yang sering muncul

| Pesan error | Penyebab | Solusi |
| --- | --- | --- |
| `composer` / `php` / `git` `is not recognized` | Terminal dibuka sebelum alat dipasang | Tutup **semua** jendela VS Code (File → Exit), lalu buka lagi. Atau jalankan perintah di bawah tabel ini. |
| `The zip extension and unzip/7s commands are both missing` | Ekstensi zip belum aktif | Aktifkan `extension=zip` di `php.ini` (Bagian 1.2) |
| `could not find driver` | Ekstensi pdo_mysql belum aktif | Aktifkan `extension=pdo_mysql` di `php.ini` |
| `SQLSTATE[HY000] [2002] No connection could be made` | MySQL XAMPP belum menyala | Klik **Start** pada MySQL di XAMPP Control Panel |
| `SQLSTATE[HY000] [1049] Unknown database 'siaga_bencana'` | Database belum dibuat | Buat database di phpMyAdmin (Bagian 3.4) |
| `SQLSTATE[HY000] [1045] Access denied for user 'root'` | Password MySQL tidak cocok | Pastikan `DB_PASSWORD=` kosong, atau isi sesuai password MySQL kamu |
| `Database file at path [...database.sqlite] does not exist` | `.env` masih memakai SQLite | Ubah `DB_CONNECTION=mysql` (Bagian 3.3), lalu jalankan `php artisan config:clear` |
| `No application encryption key has been specified` | Belum generate key | Jalankan `php artisan key:generate` |
| MySQL XAMPP tidak mau Start (port 3306 dipakai) | Ada MySQL lain yang berjalan | Matikan layanan MySQL lain di **Services** Windows, atau tanyakan di grup |
| Konflik saat `git pull origin main` | Kamu dan anggota lain mengubah file yang sama | Buka file berkonflik di VS Code, pilih perubahan yang benar, simpan, lalu commit. Tanyakan di grup kalau ragu. |

Perintah untuk menyegarkan PATH tanpa menutup VS Code:

```bash
$env:Path = [Environment]::GetEnvironmentVariable('Path','Machine') + ';' + [Environment]::GetEnvironmentVariable('Path','User')
```

Kalau masih bermasalah, kirim screenshot error ke grup kelompok.
