# Rupaint Gallery

Website jual beli dan custom lukisan berbasis Laravel + MySQL.

## Fitur
- Login dan pendaftaran pengguna tersimpan di MySQL
- Landing page bertema biru gelap dengan pilihan karya
- Gallery karya pelukis sebagai halaman portfolio
- Detail lukisan
- Riwayat pesanan privat untuk setiap pengguna
- Admin dapat membuat, melihat, mengedit, mengubah status, dan menghapus pesanan
- Formulir permintaan lukisan custom dengan enam ukuran kanvas
- Halaman sertifikat pelukis berisi satu foto, deskripsi, serta riwayat pameran
- Formulir pemesanan menyimpan data pesanan dan menyiapkan draf WhatsApp
- Checkout lama dengan QRIS atau virtual account bank melalui Midtrans Snap (untuk pesanan terdahulu)
- Verifikasi status pembayaran melalui notifikasi Midtrans bertanda tangan
- Custom painting
- Penyimpanan order + order items + custom request + payment
- Deep-link WhatsApp khusus setelah pesanan dibuat dengan ringkasan pesanan yang sudah terisi
- Dashboard admin untuk mengelola pesanan, sertifikat pelukis, dan CRUD karya galeri
- MySQL transaction dengan `DB::transaction()` / rollback otomatis
- SQL Stored Procedure, Stored Function, dan Trigger

## Instalasi

1. Pastikan PHP 8.2+, Composer, MySQL, dan Node.js tersedia.
2. Buat database MySQL bernama `rupaint_gallery`.
3. Copy `.env.example` menjadi `.env` lalu sesuaikan DB.
4. Jalankan:

```bash
composer install
php artisan key:generate
php artisan storage:link
php artisan migrate --seed
php artisan serve
```

Buka `http://127.0.0.1:8000`, lalu buat akun atau masuk.

Setelah menjalankan seeder, akun admin dummy lokal tersedia:

- Email: `admin@rupaint.local`
- Kata sandi: `admin123`

Gunakan tautan **Login sebagai Admin** di halaman masuk. Ganti kata sandi dummy sebelum aplikasi dipublikasikan.

## Pembayaran Midtrans

1. Buat/siapkan akun Midtrans dan ambil Server Key serta Client Key dari dashboard.
2. Isi `MIDTRANS_SERVER_KEY` dan `MIDTRANS_CLIENT_KEY` di `.env`. Mulai dari Sandbox dengan `MIDTRANS_IS_PRODUCTION=false`; ubah menjadi `true` hanya setelah akun Production disetujui.
3. Atur URL notifikasi pembayaran di dashboard Midtrans ke `https://domain-publik-anda/payments/midtrans/notification`. URL harus dapat diakses Midtrans melalui HTTPS.
4. Jalankan ulang konfigurasi cache bila digunakan (`php artisan config:clear`).

Kunci pembayaran hanya disimpan di `.env` dan tidak dikirim ke browser. Pembayaran belum dapat diproses hingga akun dan kunci Midtrans dikonfigurasi. Untuk transaksi sungguhan, domain publik, notifikasi Midtrans aktif, serta aktivasi metode QRIS/virtual account pada akun merchant diperlukan.

Seeder menandai akun admin dummy menggunakan kolom `users.is_admin`. Untuk memberi akses admin kepada akun lain, jalankan perintah berikut melalui database tepercaya:

```sql
UPDATE users SET is_admin = 1 WHERE email = 'admin@domain-anda.id';
```

## Jika memakai XAMPP

Pastikan MySQL aktif. Jika port MySQL bukan 3306, ubah `DB_PORT` di `.env`.

## SQL tambahan

File `sql/rupaint_procedures_functions_triggers.sql` berisi procedure, function, dan trigger. Migration juga memasang versi trigger utama secara otomatis.

## Pemesanan via WhatsApp

Aplikasi menggunakan deep-link WhatsApp `wa.me` dengan nomor `WHATSAPP_NUMBER` dari `.env`. Tombol ini hanya tersedia setelah pesanan custom dibuat dan membuka draf berisi data pelanggan, detail lukisan, ukuran kanvas, dan catatan; pengguna tetap harus meninjau dan mengirimkan pesannya sendiri. Ini bukan pengiriman otomatis melalui WhatsApp Business Platform/API. Pengiriman pesan tanpa interaksi pengguna memerlukan WhatsApp Business Platform/API serta token, nomor bisnis, dan konfigurasi template resmi.

## Administrasi galeri

Admin dapat mengelola lukisan melalui menu **Dashboard Admin → Manajemen Galeri**. Foto disimpan pada disk `public`; jalankan `php artisan storage:link` agar dapat ditampilkan di galeri.

## Riwayat pesanan dan sertifikat

Setelah memperbarui kode, jalankan `php artisan migrate`. Pesanan yang dibuat saat pengguna masuk akan otomatis dikaitkan ke akun tersebut dan dapat dilihat melalui menu **Riwayat Pesanan**. Admin dapat mengelola pesanan melalui **Dashboard Admin → Pesanan**.

Galeri dan detail karya hanya menampilkan informasi karya tanpa harga atau tombol pemesanan langsung. Formulir pesanan custom tetap tersedia dari halaman Home. Admin dapat mengisi satu sertifikat pelukis, deskripsi, dan pengalaman pameran melalui **Kelola Sertifikat**. Unggah foto sertifikat yang benar dan masukkan riwayat pameran faktual; halaman sertifikat akan menampilkan petunjuk jika informasinya belum diisi. Foto disimpan di disk `public`, sehingga `php artisan storage:link` perlu tersedia.

## Contoh transaksi MySQL manual

```sql
START TRANSACTION;
-- INSERT/UPDATE order dan detail order
COMMIT;
```

Jika terjadi error:

```sql
ROLLBACK;
```

Di aplikasi Laravel, proses checkout memakai `DB::transaction()`, sehingga Laravel melakukan commit saat callback berhasil dan rollback otomatis jika exception terjadi.
