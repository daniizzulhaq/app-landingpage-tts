# Website Profil Polres TTS

Website profil resmi **Polres Timor Tengah Selatan**: halaman publik model landing page + panel admin dengan alur persetujuan konten.

- Laravel 12 · PHP 8.2+ · MySQL/MariaDB · Blade
- Bootstrap 5.3 + Bootstrap Icons — aset lokal pada halaman publik, CDN pada admin; **tanpa npm/Vite**, siap untuk cPanel
- `spatie/laravel-permission` (role), `intervention/image` v4 (resize + kompres WebP), `mews/purifier` (pembersih HTML editor)
- Editor teks Quill 2 via CDN (tanpa API key)

---

## 1. Instalasi lokal

Kebutuhan: PHP 8.2+ dengan ekstensi `gd`, `fileinfo`, `pdo_mysql`, `mbstring`, `intl`; Composer; MySQL/MariaDB (mis. XAMPP).

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Buat database kosong (contoh `polres_tts`, collation `utf8mb4_unicode_ci`), sesuaikan `DB_*` di `.env`, lalu:

```bash
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Buka http://127.0.0.1:8000 (publik) dan http://127.0.0.1:8000/admin (panel admin).

### Gambar demo (opsional)

Untuk presentasi/demo, isi logo + foto dummy (slider, thumbnail berita, galeri, album, foto unit):

```bash
php artisan db:seed --class=DemoMediaSeeder
php artisan db:seed --class=DemoProfileSeeder   # teks contoh profil, pimpinan, pejabat & unit (nama fiktif)
```

Logo diambil dari `database/seeders/assets/logo-polres-tts.png`; foto dummy diunduh dari [Lorem Picsum](https://picsum.photos) (foto Unsplash berlisensi bebas). **Ganti semua foto dummy dengan foto resmi sebelum situs dipublikasikan.** Foto pejabat sengaja tidak diisi foto orang acak.

### Akun bawaan (seeder)

| Role        | Email                             | Kata sandi |
|-------------|-----------------------------------|------------|
| Super Admin | `admin@polrestts.test`            | `password` |
| Admin Unit (Sat Lantas) | `operator.lantas@polrestts.test` | `password` |

> **Segera ganti kata sandi** kedua akun lewat menu *Ganti Kata Sandi* sebelum situs online.

### Menjalankan test

Buat database `polres_tts_test` lalu:

```bash
php artisan test
```

---

## 2. Deploy ke cPanel

### Melalui GitHub

Proyek ini memakai CSS/JS statis di `public/`, sehingga **tidak perlu `npm install` atau `npm run build`**. `.gitignore` mengizinkan aset `public/css`, `public/js`, `public/fonts`, `public/img`, dan `public/vendor` ikut ke GitHub; `public/build` juga diizinkan jika nantinya digunakan.

Jika folder proyek belum menjadi repository Git, jalankan:

```bash
git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin https://github.com/USERNAME/NAMA-REPOSITORY.git
git push -u origin main
```

Setelah clone/pull repository ke hosting, jalankan `composer install --no-dev --optimize-autoloader`, buat `.env` dari `.env.example`, lalu ikuti pengaturan document root, database, dan perintah artisan di bawah. Hosting harus mendukung PHP 8.2+ dan database MySQL/MariaDB; GitHub Pages tidak menjalankan aplikasi Laravel ini.

File `.env`, dependensi PHP di `/vendor`, serta foto/dokumen unggahan di `storage/app/public` tidak ikut GitHub. Untuk memindahkan situs yang sudah berisi konten, pindahkan juga database dan isi `storage/app/public` secara terpisah, lalu buat ulang tautan `public/storage` di hosting. Jangan timpa `APP_KEY` yang sudah digunakan saat memperbarui situs.

### a. Siapkan file
Di komputer lokal (folder proyek):

```bash
composer install --no-dev --optimize-autoloader
```

Kompres seluruh folder proyek menjadi `.zip` **termasuk folder `vendor`** (kecuali `.env`, `node_modules`, `storage/logs/*`).

### b. Unggah
1. cPanel → **File Manager** → unggah zip ke folder di luar `public_html`, misalnya `/home/USER/polres-tts`, lalu Extract.
2. Arahkan web ke folder `public`. Pilih salah satu:
   - **Disarankan:** cPanel → *Domains* → ubah **Document Root** domain menjadi `/home/USER/polres-tts/public`.
   - **Jika document root tidak bisa diubah:** pindahkan **isi** folder `public/` ke `public_html/`, lalu edit `public_html/index.php`:
     ```php
     require __DIR__.'/../polres-tts/vendor/autoload.php';
     $app = require_once __DIR__.'/../polres-tts/bootstrap/app.php';
     ```
     (sesuaikan bagian `__DIR__.'/../polres-tts/...'` di semua baris yang merujuk `vendor`, `bootstrap`, dan `storage/framework/maintenance.php`).

### c. Database
1. cPanel → **MySQL® Databases**: buat database + user, beri user *ALL PRIVILEGES*.
2. Salin `.env.example` menjadi `.env` di folder proyek, isi:
   ```dotenv
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://domain-anda.go.id
   DB_DATABASE=namauser_polres
   DB_USERNAME=namauser_dbuser
   DB_PASSWORD=********
   ```

### d. Perintah artisan
Jika cPanel menyediakan **Terminal** (atau SSH):

```bash
cd ~/polres-tts
php artisan key:generate
php artisan migrate --seed --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Pastikan versi PHP CLI ≥ 8.2 (cPanel → *MultiPHP Manager* / *Select PHP Version*; aktifkan ekstensi `gd`, `fileinfo`, `intl`, `pdo_mysql`). Jika perintah `php` di terminal masih versi lama, gunakan path lengkap, mis. `/opt/cpanel/ea-php82/root/usr/bin/php artisan ...`.

**Tanpa terminal:** jalankan migrate di komputer lokal, ekspor database lewat phpMyAdmin lokal, lalu impor ke phpMyAdmin hosting. Isi `APP_KEY` di `.env` dengan nilai dari `.env` lokal.

### e. `storage:link` tidak bisa dijalankan?
Sebagian hosting menonaktifkan fungsi `symlink()`. Buat symlink lewat cron sekali jalan (cPanel → *Cron Jobs*):

```
ln -s /home/USER/polres-tts/storage/app/public /home/USER/public_html/storage
```

Hapus cron setelah symlink terbentuk. Foto upload harus bisa diakses di `https://domain/storage/...`.

### f. Izin folder
`storage/` dan `bootstrap/cache/` harus dapat ditulis (umumnya 755 untuk folder, 644 untuk file).

### g. Setelah online
- Login `/admin` → ganti kata sandi akun bawaan, hapus akun operator contoh bila tidak dipakai.
- `robots.txt` hanya mengizinkan indeks bila `APP_ENV=production`. Daftarkan `https://domain/sitemap.xml` ke Google Search Console.
- Setiap memperbarui kode: unggah file baru, lalu `php artisan migrate --force` dan `php artisan optimize:clear && php artisan optimize`.

---

## 3. Peran & alur kerja

| Peran | Hak akses |
|---|---|
| **Super Admin** (Sie Humas/TIK) | Semua menu, menyetujui konten, kelola pengguna, unit, pejabat, layanan, PPID, pengaturan. Konten buatannya langsung tayang. |
| **Admin Unit** (operator bag/sat/sie/polsek) | Hanya data unitnya: Berita, Kegiatan, Edukasi, Album, Agenda, Profil Unit. Pilihan unit terkunci. Tidak bisa membuat Pengumuman. |
| Pengunjung | Halaman publik, tanpa login. |

Pembatasan memakai **Policy + middleware** (bukan sekadar menyembunyikan menu).

**Alur approval** (Berita/Kegiatan/Edukasi/Pengumuman, Album, Agenda):
`Simpan Draft` → `Kirim untuk Persetujuan` (pending) → Super Admin **Setujui** (tayang) atau **Kembalikan** + catatan revisi → Admin Unit memperbaiki dan mengirim ulang.
Jika *Pengaturan → Sistem → wajib persetujuan* dimatikan, konten Admin Unit langsung tayang.

---

## 4. Struktur penting

```
app/Http/Controllers/Admin   Panel admin
app/Http/Controllers/Site    Halaman publik, pencarian, sitemap
app/Http/Requests            Validasi semua form (Form Request)
app/Policies                 Pembatasan akses per unit
app/Services/ImageService    Resize 1600px + thumbnail 600px (WebP)
app/Services/ContentWorkflow Alur draft/pending/published/rejected
config/site.php              Daftar field menu Pengaturan
resources/views/layouts      layouts/public & layouts/admin
public/css, public/js        CSS/JS statis (tanpa build)
public/css/theme.css         Identitas visual bersama dan bagian khusus beranda
public/css/pages.css         Tampilan halaman publik selain beranda
public/vendor                Bootstrap 5.3.8 + Bootstrap Icons 1.13.2 untuk halaman publik
public/fonts                 Plus Jakarta Sans lokal untuk seluruh halaman publik (lisensi disertakan)
```

## 5. Keamanan

- CSRF aktif di semua form; login dibatasi 5 percobaan/menit; form PPID dibatasi 3 kiriman/menit + honeypot.
- Upload dibatasi tipe (jpg, png, webp, pdf) dan ukuran (gambar 5 MB, berkas 10 MB); gambar diproses ulang (metadata EXIF hilang).
- Isi editor dibersihkan HTML Purifier sebelum disimpan; embed peta hanya menerima URL Google Maps.
