REQRUITMENT

PHP Min 7.2.x
Database MariaDB
laravel reqruitment

Install composer

Install Git

INSTALATION


Clone repository ke dalam working directory anda:

git clone https://github.com/Jeffsitorus/bts-test


Masuk ke dalam folder bts-test dan install laravel dependencies dengan menjalankan perintah CLI berikut :

composer install


Copy file .env.example ➡ .env. Dan pastikan konfigurasi database sesuai dengan akun DBMS anda


Buat database di local (sesuai konfigurasi di file .env)
CREATE DATABASE shoppingdb;


Inisialisasi Database dan data awal dengan menjalankan migrasi
composer dump-autoload (Jaga-jaga composer autoload error)
php artisan migrate (Eksekusi file migrasi database\migrations)
php artisan passport:install

Kunjungi aplikasi anda di browser. localhost/bts-test/public untuk memastikan projek sudah terinstall dengan benar
