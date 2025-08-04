# Mitus Stationery

Mitus Stationery adalah website toko alat tulis modern yang memudahkan user untuk belanja perlengkapan sekolah secara online, dengan proses checkout yang terhubung langsung ke WhatsApp admin untuk konfirmasi pembayaran.

## Fitur Utama
- Browse produk alat tulis, print, fotokopi, dan aksesoris
- Keranjang belanja dan checkout manual via WhatsApp
- Status pesanan untuk user
- Admin dashboard: kelola produk & pesanan
- Responsive UI dengan Tailwind CSS
- Notifikasi stok dan update status pesanan

## Teknologi
- PHP (backend & routing)
- MySQL (database)
- Tailwind CSS & Google Fonts (frontend)
- JavaScript (interaksi UI)

## Struktur Folder
- `frontend/` : HTML, JS, dan asset tampilan
- `backend/` : Logic PHP, controller, model, dan konfigurasi database
- `views/`   : File view PHP untuk halaman utama, produk, cart, admin, dll
- `assets/`  : Gambar produk, CSS, JS

## Cara Instalasi
1. Clone repository ini ke folder web server (misal: `c:/laragon/www/Mitus_Web_proyek`)
2. Import file `config/database.sql` ke MySQL
3. Atur koneksi database di `backend/config/database.php`
4. Jalankan web server (Apache/Nginx) dan akses melalui browser

## Cara Penggunaan
- User dapat register/login, memilih produk, menambah ke keranjang, dan checkout
- Admin login untuk kelola produk dan pesanan
- Semua pembayaran dikonfirmasi manual via WhatsApp admin

## Kontribusi
Silakan buat pull request atau issue untuk saran dan perbaikan.

## Lisensi
MIT