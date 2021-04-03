# Sistem Informasi Eksekutif (SIE)

Berikut adalah tutorial cara installasi / clone aplikasi
1. Buka cmd / terminal dan arahkan ke folder xampp/htdocs atau var/www/html
2. Clone project dengan memasukkan perintah di berikut ini
```
git clone https://github.com/eko10/php-sistem-informasi-eksekutif.git
```
3. Setelah itu masuk ke direktori project dengan menjalankan perintah berikut ini
```
cd php-sistem-informasi-eksekutif
```
4. Kemudian install dependency, pastikan sudah menginstall `composer` di laptop/pc sebelum menjalankan perintah berikut ini
```
composer install
```
5. Lalu jalankan perintah berikut ini
```
composer self-update
```
6. Kemudian jalankan perintah berikut ini
```
composer update
```
7. Setelah composer install selesai, kemudian kita butuh membuat file .env di folder aplikasi kita, dengan menjalankan perintah berikut ini
```
cp .env.example .env
```
8. Kemudian isikan semua pengaturan yang diperlukan, biasanya yang penting adalah pengaturan koneksi database yang ada dalam file `.env`. Kemudian jalankan perintah berikut ini
```
php artisan key:generate
```
9. Langkah selanjutnya adalah melakukan migrate & seed dengan menjalankan perintah berikut ini
```
php artisan migrate --seed
```
10. Untuk menjalankan program ketik perintah berikut ini
```
php artisan serve
```
9.	happy coding :grin:
