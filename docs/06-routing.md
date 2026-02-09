# 🛤️ Routing

## Apa itu Routing?

**Routing** adalah sistem yang menghubungkan URL dengan Controller atau fungsi tertentu.

---

## Lokasi File

```
routes/
├── web.php    # Routes untuk web (dengan session, CSRF)
├── api.php    # Routes untuk API (prefix /api)
└── console.php # Artisan commands
```

---

## Basic Routing

```php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Route dengan Closure
Route::get('/hello', function () {
    return 'Hello World';
});

// Route ke Controller
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
```

---

## HTTP Methods

```php
Route::get($uri, $callback);     // Ambil data
Route::post($uri, $callback);    // Kirim data baru
Route::put($uri, $callback);     // Update semua field
Route::patch($uri, $callback);   // Update sebagian field
Route::delete($uri, $callback);  // Hapus data
```

---

## Route Parameters

```php
// Required parameter
Route::get('/users/{id}', function ($id) {
    return "User ID: $id";
});

// Multiple parameters
Route::get('/posts/{post}/comments/{comment}', function ($postId, $commentId) {
    return "Post: $postId, Comment: $commentId";
});

// Optional parameter
Route::get('/users/{name?}', function ($name = 'Guest') {
    return "Hello, $name";
});
```

---

## Named Routes

```php
// Memberi nama route
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

// Menggunakan named route
$url = route('users.index');              // /users
$url = route('users.show', ['user' => 1]); // /users/1
$url = route('users.show', $user);        // /users/1

// Redirect ke named route
return redirect()->route('users.index');
```

---

## Resource Routes

```php
// Membuat 7 route CRUD otomatis
Route::resource('users', UserController::class);

// Equivalent to:
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
```

### Partial Resource:

```php
// Hanya routes tertentu
Route::resource('users', UserController::class)->only(['index', 'show']);

// Kecuali routes tertentu
Route::resource('users', UserController::class)->except(['destroy']);
```

---

## Route Groups

### Middleware Group:

```php
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/profile', [ProfileController::class, 'show']);
});
```

### Prefix Group:

```php
Route::prefix('admin')->group(function () {
    Route::get('/users', [AdminController::class, 'users']);
    // URL: /admin/users
});
```

### Name Prefix:

```php
Route::name('admin.')->group(function () {
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    // Route name: admin.users
});
```

### Combined:

```php
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::resource('users', AdminUserController::class);
    });
```

---

## Route Model Binding

```php
// Laravel otomatis fetch User berdasarkan ID
Route::get('/users/{user}', function (User $user) {
    return $user;
});

// Di Controller
public function show(User $user)
{
    return view('users.show', compact('user'));
}
```

---

## API Routes

```php
// routes/api.php
Route::get('/users', [ApiUserController::class, 'index']);

// Akses: /api/users (otomatis prefix /api)
```

### API Resource:

```php
Route::apiResource('users', ApiUserController::class);

// Tanpa create & edit (karena API tidak butuh form)
```

---

## Melihat Routes

```bash
# Lihat semua routes
php artisan route:list

# Filter by name
php artisan route:list --name=users

# Filter by path
php artisan route:list --path=admin
```

---

## Contoh routes/web.php Lengkap

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Admin\DashboardController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');

// Auth routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Protected routes (auth required)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('posts', PostController::class);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Admin routes
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', AdminUserController::class);
    });
```

---

Lanjut ke contoh project: Lihat README.md
