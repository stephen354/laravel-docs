# 📁 Struktur Folder Laravel

Memahami struktur folder adalah langkah penting untuk bekerja dengan Laravel secara efektif.

---

## Overview Struktur

```
laravel-project/
│
├── 📂 app/                 # Kode inti aplikasi
├── 📂 bootstrap/           # File bootstrap framework
├── 📂 config/              # File konfigurasi
├── 📂 database/            # Database migrations, factories, seeders
├── 📂 public/              # Document root (index.php, assets)
├── 📂 resources/           # Views, assets mentah (CSS, JS)
├── 📂 routes/              # Definisi routes
├── 📂 storage/             # File yang di-generate
├── 📂 tests/               # Unit & Feature tests
├── 📂 vendor/              # Dependencies (jangan di-edit!)
│
├── 📄 .env                 # Environment variables
├── 📄 .env.example         # Template environment
├── 📄 artisan              # CLI tool
├── 📄 composer.json        # PHP dependencies
├── 📄 package.json         # NPM dependencies
└── 📄 phpunit.xml          # Testing configuration
```

---

## 📂 Folder `app/` - Inti Aplikasi

```
app/
├── 📂 Console/
│   └── Commands/           # Custom Artisan commands
│
├── 📂 Exceptions/
│   └── Handler.php         # Error handling
│
├── 📂 Http/
│   ├── Controllers/        # ⭐ Controller (C di MVC)
│   ├── Middleware/         # Filter HTTP requests
│   └── Requests/           # Form validation classes
│
├── 📂 Models/              # ⭐ Model (M di MVC)
│   └── User.php            # Default User model
│
└── 📂 Providers/           # Service providers
    └── AppServiceProvider.php
```

### Yang Perlu Diketahui:

- **Controllers/** → Tempat logic aplikasi
- **Models/** → Interaksi dengan database
- **Middleware/** → Filter sebelum request masuk ke controller

---

## 📂 Folder `config/` - Konfigurasi

```
config/
├── app.php         # Konfigurasi aplikasi (name, timezone)
├── auth.php        # Konfigurasi authentication
├── cache.php       # Konfigurasi cache (file, redis)
├── database.php    # Konfigurasi database connections
├── filesystems.php # Konfigurasi file storage
├── logging.php     # Konfigurasi logging
├── mail.php        # Konfigurasi email
├── queue.php       # Konfigurasi queue/jobs
└── session.php     # Konfigurasi session
```

### Cara Akses Config:

```php
// Mengambil nilai config
$timezone = config('app.timezone');

// Dengan default value
$value = config('app.custom_key', 'default');
```

---

## 📂 Folder `database/` - Database

```
database/
├── 📂 factories/           # Factory untuk testing/seeding
│   └── UserFactory.php
│
├── 📂 migrations/          # Struktur tabel database
│   ├── 0001_01_01_000000_create_users_table.php
│   └── 0001_01_01_000001_create_cache_table.php
│
└── 📂 seeders/             # Data dummy
    └── DatabaseSeeder.php
```

### Perintah Penting:

```bash
# Jalankan migration
php artisan migrate

# Rollback migration terakhir
php artisan migrate:rollback

# Fresh migration + seeding
php artisan migrate:fresh --seed
```

---

## 📂 Folder `public/` - Document Root

```
public/
├── .htaccess       # Apache config
├── favicon.ico     # Favicon website
├── index.php       # Entry point aplikasi
├── robots.txt      # SEO robots
│
└── 📂 assets/      # (opsional) Static assets
    ├── css/
    ├── js/
    └── images/
```

### Yang Perlu Diketahui:

- **index.php** adalah entry point semua request
- Semua file di `public/` bisa diakses langsung via URL
- Letakkan file statis (gambar, PDF) di sini

---

## 📂 Folder `resources/` - Views & Assets

```
resources/
├── 📂 css/                 # Source CSS
│   └── app.css
│
├── 📂 js/                  # Source JavaScript
│   └── app.js
│
└── 📂 views/               # ⭐ View Blade (V di MVC)
    ├── 📂 layouts/         # Layout templates
    │   └── app.blade.php
    │
    ├── 📂 components/      # Blade components
    │
    └── welcome.blade.php   # View default
```

### Penamaan View:

```php
// menggunakan dot notation
return view('users.index');     // resources/views/users/index.blade.php
return view('admin.users.edit'); // resources/views/admin/users/edit.blade.php
```

---

## 📂 Folder `routes/` - Routing

```
routes/
├── api.php         # Routes untuk API (prefix: /api)
├── console.php     # Artisan commands berbasis Closure
└── web.php         # ⭐ Routes utama untuk web
```

### Perbedaan web.php vs api.php:

| File    | Prefix | Middleware | Session  | CSRF     |
| ------- | ------ | ---------- | -------- | -------- |
| web.php | -      | web        | ✅ Aktif | ✅ Aktif |
| api.php | /api   | api        | ❌ Tidak | ❌ Tidak |

---

## 📂 Folder `storage/` - File Generated

```
storage/
├── 📂 app/                 # File upload aplikasi
│   └── 📂 public/          # File publik (perlu link)
│
├── 📂 framework/           # File framework (cache, sessions)
│   ├── cache/
│   ├── sessions/
│   └── views/              # Compiled Blade views
│
└── 📂 logs/                # Log files
    └── laravel.log
```

### Membuat Symbolic Link:

```bash
php artisan storage:link
```

Ini membuat link dari `public/storage` ke `storage/app/public`

---

## 📄 File `.env` - Environment

```env
APP_NAME=LaravelApp
APP_ENV=local
APP_KEY=base64:xxx
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
```

### Yang Perlu Diketahui:

- **Jangan commit file `.env` ke Git!**
- File `.env.example` adalah template untuk tim
- Akses via `env('DB_DATABASE')` atau `config('database.connections.mysql.database')`

---

## 🎯 Cheat Sheet Lokasi File

| Jenis File | Lokasi                                 |
| ---------- | -------------------------------------- |
| Controller | `app/Http/Controllers/`                |
| Model      | `app/Models/`                          |
| View       | `resources/views/`                     |
| Migration  | `database/migrations/`                 |
| Seeder     | `database/seeders/`                    |
| Route      | `routes/web.php` atau `routes/api.php` |
| Config     | `config/`                              |
| Middleware | `app/Http/Middleware/`                 |
| Request    | `app/Http/Requests/`                   |
| Provider   | `app/Providers/`                       |

---

## Langkah Selanjutnya

Lanjut ke:

- [03-model.md](./03-model.md) - Memahami Model dan Eloquent ORM
