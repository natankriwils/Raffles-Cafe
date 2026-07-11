# TODO - Raffles-Cafe POS Dashboard

## Step 1: Gather & confirm current routes/views
- [x] Cek `routes/web.php`
- [x] Cek `layouts/app.blade.php` (sidebar masih href `#`, logout belum pakai form POST)
- [x] Cek `KasirController` (checkout sukses simpan ke tabel orders/order_details)


## Step 2: Tambah halaman Dashboard & Riwayat Transaksi
- [x] Tambah route `GET /dashboard` dan `GET /riwayat-transaksi`
- [x] Buat controller untuk dashboard & riwayat
- [x] Buat view `dashboard` (metrik hari ini + grafik pendapatan harian + total pendapatan bulan ini)
- [x] Buat view `riwayat-transaksi` (tabel transaksi + filter singkat)


## Step 3: Update Layout Sidebar
- [x] Update sidebar link: Kasir (/kasir), Riwayat Transaksi, Dashboard
- [x] Perbaiki tombol Logout jadi `<form method="POST" action="{{ route('logout') }}">`


## Step 4: Grafik pendapatan harian
- [x] Implement grafik menggunakan canvas + Chart.js (CDN)
- [x] Query pendapatan per hari untuk 7 hari terakhir (success) dan tampilkan di dashboard


## Step 5: Testing
- [ ] Jalankan `php artisan migrate --seed`
- [ ] Jalankan `php artisan serve`
- [ ] Test navigasi login -> kasir -> dashboard -> riwayat
- [ ] Test transaksi checkout lalu pastikan riwayat/dashboard ikut berubah

