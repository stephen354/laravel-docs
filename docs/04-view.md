# 🖼️ View - Blade Template Engine

## Apa itu View?

**View** adalah bagian dari MVC yang bertanggung jawab untuk menampilkan data ke user. Laravel menggunakan **Blade** sebagai template engine.

---

## Lokasi File

```
resources/views/
├── welcome.blade.php           # View default
├── layouts/
│   └── app.blade.php           # Layout utama
├── users/
│   ├── index.blade.php         # Daftar users
│   ├── show.blade.php          # Detail user
│   ├── create.blade.php        # Form create
│   └── edit.blade.php          # Form edit
└── components/
    └── button.blade.php        # Reusable component
```

---

## Membuat View

### Menggunakan Artisan:

```bash
php artisan make:view users.index    # resources/views/users/index.blade.php
php artisan make:view layouts.app    # resources/views/layouts/app.blade.php
```

### Manual:

Buat file dengan ekstensi `.blade.php` di folder `resources/views/`

---

## Memanggil View dari Controller

```php
class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        // Memanggil view dengan data
        return view('users.index', ['users' => $users]);

        // Atau menggunakan compact()
        return view('users.index', compact('users'));

        // Atau menggunakan with()
        return view('users.index')->with('users', $users);
    }
}
```

---

## Sintaks Blade Dasar

### 1. Menampilkan Data

```html
{{-- Menampilkan variable (escaped) --}}
<h1>Hello, {{ $name }}!</h1>

{{-- Menampilkan HTML mentah (hati-hati XSS!) --}} {!! $htmlContent !!} {{--
Default value --}} {{ $name ?? 'Guest' }} {{ $user->name ?? 'Anonymous' }}
```

### 2. Komentar

```html
{{-- Ini adalah komentar Blade (tidak muncul di HTML) --}}

<!-- Ini adalah komentar HTML (muncul di source code) -->
```

### 3. Kondisi IF

```html
@if ($user->isAdmin())
<p>Selamat datang, Admin!</p>
@elseif ($user->isModerator())
<p>Selamat datang, Moderator!</p>
@else
<p>Selamat datang, User!</p>
@endif {{-- Shorthand untuk cek empty --}} @empty($users)
<p>Tidak ada user</p>
@endempty {{-- Shorthand untuk cek isset --}} @isset($record) {{ $record->name
}} @endisset {{-- Unless (kebalikan dari if) --}} @unless ($user->isGuest())
<p>Kamu sudah login</p>
@endunless
```

### 4. Switch Case

```html
@switch($userRole) @case('admin')
<p>Admin Dashboard</p>
@break @case('editor')
<p>Editor Dashboard</p>
@break @default
<p>User Dashboard</p>
@endswitch
```

### 5. Looping

```html
{{-- Foreach --}} @foreach ($users as $user)
<li>{{ $user->name }}</li>
@endforeach {{-- Foreach dengan empty check --}} @forelse ($users as $user)
<li>{{ $user->name }}</li>
@empty
<li>Tidak ada user</li>
@endforelse {{-- For Loop --}} @for ($i = 0; $i < 10; $i++)
<p>Iterasi ke-{{ $i }}</p>
@endfor {{-- While Loop --}} @while (true)
<p>Ini akan loop terus...</p>
@endwhile
```

### 6. Loop Variable

Di dalam loop, Blade menyediakan variable `$loop`:

```html
@foreach ($users as $user) @if ($loop->first)
<p>Ini item pertama</p>
@endif

<p>{{ $loop->iteration }}: {{ $user->name }}</p>

@if ($loop->last)
<p>Ini item terakhir</p>
@endif @endforeach
```

Properti `$loop`:
| Property | Deskripsi |
|----------|-----------|
| `$loop->index` | Index saat ini (mulai dari 0) |
| `$loop->iteration` | Iterasi saat ini (mulai dari 1) |
| `$loop->remaining` | Sisa iterasi |
| `$loop->count` | Total item |
| `$loop->first` | Apakah item pertama |
| `$loop->last` | Apakah item terakhir |
| `$loop->even` | Apakah iterasi genap |
| `$loop->odd` | Apakah iterasi ganjil |
| `$loop->depth` | Level kedalaman nested loop |
| `$loop->parent` | Variable loop parent |

---

## Layouts & Inheritance

### 1. Membuat Layout Utama

```html
{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Laravel App')</title>

    {{-- CSS --}} @stack('styles')
  </head>
  <body>
    {{-- Header --}} @include('layouts.partials.header') {{-- Main Content --}}
    <main class="container">@yield('content')</main>

    {{-- Footer --}} @include('layouts.partials.footer') {{-- JavaScript --}}
    @stack('scripts')
  </body>
</html>
```

### 2. Menggunakan Layout

```html
{{-- resources/views/users/index.blade.php --}} @extends('layouts.app')
@section('title', 'Daftar Users') @section('content')
<h1>Daftar Users</h1>

@foreach ($users as $user)
<div class="user-card">
  <h3>{{ $user->name }}</h3>
  <p>{{ $user->email }}</p>
</div>
@endforeach @endsection @push('styles')
<style>
  .user-card {
    padding: 1rem;
    border: 1px solid #ccc;
  }
</style>
@endpush @push('scripts')
<script>
  console.log("Page loaded");
</script>
@endpush
```

---

## Include dan Components

### 1. Include Partial

```html
{{-- Include partial --}} @include('partials.alert') {{-- Include dengan data
--}} @include('partials.user-card', ['user' => $user]) {{-- Include jika ada
--}} @includeIf('partials.optional-section') {{-- Include berdasarkan kondisi
--}} @includeWhen($user->isAdmin(), 'partials.admin-menu')
```

### 2. Anonymous Components

```html
{{-- resources/views/components/button.blade.php --}}
<button {{ $attributes->
  merge(['class' => 'btn btn-primary']) }}> {{ $slot }}
</button>

{{-- Penggunaan --}}
<x-button>Click Me</x-button>
<x-button class="btn-danger">Delete</x-button>
```

### 3. Class-based Components

```bash
php artisan make:component Alert
```

```php
// app/View/Components/Alert.php
class Alert extends Component
{
    public function __construct(
        public string $type = 'info',
        public string $message = ''
    ) {}

    public function render()
    {
        return view('components.alert');
    }
}
```

```html
{{-- resources/views/components/alert.blade.php --}}
<div class="alert alert-{{ $type }}">{{ $message }}</div>

{{-- Penggunaan --}}
<x-alert type="success" message="Data berhasil disimpan!" />
<x-alert type="error" message="Terjadi kesalahan" />
```

---

## Form Helpers

### 1. CSRF Token

```html
<form method="POST" action="/users">
  @csrf

  <!-- Form fields -->
</form>
```

### 2. Method Spoofing

HTML form hanya support GET dan POST. Untuk PUT, PATCH, DELETE:

```html
<form method="POST" action="/users/1">
  @csrf @method('PUT')

  <!-- Form fields -->
</form>

<form method="POST" action="/users/1">
  @csrf @method('DELETE')

  <button type="submit">Delete</button>
</form>
```

### 3. Error Messages

```html
{{-- Menampilkan semua error --}} @if ($errors->any())
<div class="alert alert-danger">
  <ul>
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif {{-- Error untuk field spesifik --}}
<input type="text" name="email" class="@error('email') is-invalid @enderror" />
@error('email')
<span class="error">{{ $message }}</span>
@enderror
```

### 4. Old Input

```html
{{-- Menampilkan nilai lama setelah validasi gagal --}}
<input type="text" name="name" value="{{ old('name') }}">
<input type="email" name="email" value="{{ old('email', $user->email ?? '') }}">

<select name="category">
    <option value="1" {{ old('category') == 1 ? 'selected' : '' }}>Kategori 1</option>
    <option value="2" {{ old('category') == 2 ? 'selected' : '' }}>Kategori 2</option>
</select>
```

---

## Flash Messages

### Di Controller:

```php
return redirect()->route('users.index')
    ->with('success', 'User berhasil dibuat!');

return redirect()->back()
    ->with('error', 'Terjadi kesalahan');
```

### Di View:

```html
@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif @if (session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif
```

---

## Assets (CSS & JavaScript)

### 1. Asset Helper

```html
{{-- Mengambil dari folder public --}}
<link rel="stylesheet" href="{{ asset('css/app.css') }}" />
<script src="{{ asset('js/app.js') }}"></script>
<img src="{{ asset('images/logo.png') }}" alt="Logo" />
```

### 2. Vite (Laravel 10+)

```html
{{-- Di layout --}} @vite(['resources/css/app.css', 'resources/js/app.js'])
```

### 3. CDN (Cepat untuk development)

```html
{{-- TailwindCSS CDN --}}
<script src="https://cdn.tailwindcss.com"></script>

{{-- AlpineJS --}}
<script
  defer
  src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"
></script>
```

---

## Contoh Lengkap: CRUD View

### 1. Index (Daftar)

```html
{{-- resources/views/users/index.blade.php --}} @extends('layouts.app')
@section('title', 'Daftar Users') @section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Daftar Users</h1>
    <a href="{{ route('users.create') }}" class="btn btn-primary">
      + Tambah User
    </a>
  </div>

  @if (session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($users as $user)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>
          <a
            href="{{ route('users.show', $user) }}"
            class="btn btn-sm btn-info"
          >
            Lihat
          </a>
          <a
            href="{{ route('users.edit', $user) }}"
            class="btn btn-sm btn-warning"
          >
            Edit
          </a>
          <form
            action="{{ route('users.destroy', $user) }}"
            method="POST"
            class="d-inline"
          >
            @csrf @method('DELETE')
            <button
              type="submit"
              class="btn btn-sm btn-danger"
              onclick="return confirm('Yakin hapus?')"
            >
              Hapus
            </button>
          </form>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="4" class="text-center">Tidak ada data</td>
      </tr>
      @endforelse
    </tbody>
  </table>

  {{ $users->links() }}
</div>
@endsection
```

### 2. Create (Form Tambah)

```html
{{-- resources/views/users/create.blade.php --}} @extends('layouts.app')
@section('title', 'Tambah User') @section('content')
<div class="container py-4">
  <h1 class="mb-4">Tambah User Baru</h1>

  <form action="{{ route('users.store') }}" method="POST">
    @csrf

    <div class="mb-3">
      <label for="name" class="form-label">Nama</label>
      <input
        type="text"
        name="name"
        id="name"
        class="form-control @error('name') is-invalid @enderror"
        value="{{ old('name') }}"
        required
      />
      @error('name')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input
        type="email"
        name="email"
        id="email"
        class="form-control @error('email') is-invalid @enderror"
        value="{{ old('email') }}"
        required
      />
      @error('email')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input
        type="password"
        name="password"
        id="password"
        class="form-control @error('password') is-invalid @enderror"
        required
      />
      @error('password')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
  </form>
</div>
@endsection
```

---

## Langkah Selanjutnya

Lanjut ke:

- [05-controller.md](./05-controller.md) - Memahami Controller
