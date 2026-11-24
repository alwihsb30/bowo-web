# BOWO - Bank of Waste Online

BOWO (Bank of Waste Online) adalah aplikasi pengelolaan setoran bank sampah berbasis web menggunakan **Native PHP MVC** dan **MySQL (PDO)**. Aplikasi ini memudahkan nasabah menyetorkan sampah daur ulang, memantau saldo tabungan, dan mengumpulkan poin kontribusi.

## Anggota Kelompok 03 (PTI-D)

| Nama | NIM |
| :--- | :--- |
| Achmad Zidni Riziq | 255150601111027 |
| Muhammad Syauqi Arif Rabbani | 255150607111038 |
| Muhammad Ridwan | 255150601111022 |
| Rehan Abzari Hauda | 255150607111032 |
| Alsanawi Hasibuan | 255150600111038 |

## Fitur Utama

- Autentikasi nasabah dan admin menggunakan `$_SESSION`, hash `Bcrypt`, serta cookie `remember_username`.
- Beranda publik berisi statistik bank sampah, 3 jenis sampah terbanyak disetor, katalog harga, dan Leaderboard Top 10.
- Pengajuan setoran sampah nasabah dengan estimasi nilai/poin real-time serta unggah foto bukti (`public/uploads/`).
- Riwayat pengajuan setoran fisik, mutasi saldo tabungan Rupiah, dan filter pencarian tabel langsung.
- Verifikasi setoran Admin (otomatis memperbarui saldo, poin, dan mutasi), CRUD & pencarian AJAX jenis sampah, serta ekspor `.csv`.

## Struktur Proyek & Basis Data (`bowo_db`)

- `index.php` — Front Controller pengatur routing parameter `?c=` dan `?a=`.
- `app/` — Berisi `config/Database.php`, `core/` (`Controller.php`, `Model.php`), `models/` (`User`, `WasteType`, `Deposit`, `Transaction`), `controllers/` (`Auth`, `Home`, `Deposit`, `WasteType`, `Transaction`), dan `views/`.
- `public/` — Berisi `assets/css/style.css`, `assets/images/bowo.png`, `js/script.js`, dan `uploads/`.
- `database_bowo.sql` — Skema **4 tabel** (`users`, `waste_types`, `deposits`, `transactions`) beserta data awal demo.

## Cara Instalasi & Akun Demo

1. Salin folder proyek ke `htdocs/bowo-web` di XAMPP, lalu aktifkan **Apache** dan **MySQL**.
2. Buat database `bowo_db` melalui `http://localhost/phpmyadmin`, kemudian impor `database_bowo.sql`.
3. Akses aplikasi di browser melalui `http://localhost/bowo-web/`.

| Peran | Username | Password |
| :--- | :--- | :--- |
| Admin | `admin` | `admin123` |
| Nasabah | `budi` | `password123` |
| Nasabah | `siti` | `password123` |
