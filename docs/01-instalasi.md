# 🔧 Panduan Instalasi Laravel

## Persyaratan Sistem

Sebelum menginstall Laravel, pastikan komputer kamu sudah memiliki:

| Software      | Versi Minimum   | Cara Cek          |
| ------------- | --------------- | ----------------- |
| PHP           | 8.2             | `php -v`          |
| Composer      | 2.x             | `composer -V`     |
| Node.js       | 18.x (opsional) | `node -v`         |
| NPM           | 9.x (opsional)  | `npm -v`          |
| MySQL/MariaDB | 5.7+            | `mysql --version` |

---

## Step 1: Install PHP (Windows)

### Opsi A: Menggunakan XAMPP (Recommended untuk Pemula)

1. Download XAMPP dari [apachefriends.org](https://www.apachefriends.org/)
2. Install dan aktifkan Apache + MySQL
3. Tambahkan PHP ke PATH:
   - Buka System Properties > Environment Variables
   - Edit `Path` di System Variables
   - Tambahkan: `C:\xampp\php`
4. Restart terminal dan cek: `php -v`

### Opsi B: Menggunakan Laravel Herd (Modern & Simple)

1. Download dari [herd.laravel.com](https://herd.laravel.com)
2. Install dan jalankan
3. Herd otomatis menginstall PHP, Composer, dan nginx

---

## Step 2: Install Composer

Composer adalah package manager untuk PHP.

### Windows:

1. Download installer dari [getcomposer.org](https://getcomposer.org/download/)
2. Jalankan `Composer-Setup.exe`
3. Ikuti wizard instalasi
4. Cek instalasi: `composer -V`

### Linux/Mac:

```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
sudo mv composer.phar /usr/local/bin/composer
composer -V
```

---

## Step 3: Install Laravel

### Metode 1: Laravel Installer (Recommended)

```bash
# Install Laravel Installer global
composer global require laravel/installer

# Buat project baru
laravel new nama-project
```

Saat diminta, pilih:

- **Starter Kit**: None (untuk belajar dasar)
- **Testing**: PHPUnit
- **Database**: MySQL
- **Run migrations**: Yes

### Metode 2: Menggunakan Composer Langsung

```bash
composer create-project laravel/laravel nama-project
```

---

## Step 4: Konfigurasi Database

### Edit file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=
```

### Buat database:

```sql
-- Di MySQL/phpMyAdmin
CREATE DATABASE nama_database;
```

### Jalankan migration:

```bash
php artisan migrate
```

---

## Step 5: Jalankan Server

```bash
cd nama-project
php artisan serve
```

Buka browser: **http://localhost:8000**

🎉 Laravel berhasil terinstall!

---

## Troubleshooting

### Error: "php is not recognized"

- Tambahkan PHP ke PATH environment variable

### Error: "composer is not recognized"

- Restart terminal setelah install Composer

### Error: "SQLSTATE[HY000] [2002]"

- Pastikan MySQL/XAMPP sudah berjalan
- Cek konfigurasi `.env`

### Error: "No application encryption key"

```bash
php artisan key:generate
```

### Error: "Permission denied" (Linux/Mac)

```bash
chmod -R 775 storage bootstrap/cache
```

---

## Langkah Selanjutnya

Setelah instalasi berhasil, lanjut ke:

- [02-struktur-folder.md](./02-struktur-folder.md) - Memahami struktur folder Laravel
