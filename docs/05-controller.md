# 🎮 Controller

## Apa itu Controller?

**Controller** menghubungkan **Model** dan **View**. Controller menerima request, memproses logic, dan mengirimkan response.

---

## Membuat Controller

```bash
# Controller biasa
php artisan make:controller NamaController

# Resource Controller (7 method CRUD)
php artisan make:controller NamaController -r

# Resource Controller dengan Model
php artisan make:controller NamaController -r -m User
```

---

## Resource Controller

```php
<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // GET /posts - Daftar posts
    public function index()
    {
        $posts = Post::latest()->paginate(10);
        return view('posts.index', compact('posts'));
    }

    // GET /posts/create - Form create , method ini akan memanggil view create.blade.php
    public function create()
    {
        return view('posts.create');
    }

    // POST /posts - Simpan post baru, method ini akan menyimpan data ke database (POST itu untuk mengirimkan data ke server/database)
    // flownya setelah dari form create kemudian klik submit maka data akan dikirim ke method store ini
    public function store(Request $request)
    {
        // $request->validate() digunakan untuk memvalidasi data yang dikirim dari form
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        Post::create($validated);
        return redirect()->route('posts.index')->with('success', 'Post dibuat!');
    }

    // GET /posts/{post} - Detail post
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    // GET /posts/{post}/edit - Form edit
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    // PUT /posts/{post} - Update post
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $post->update($validated);
        return redirect()->route('posts.show', $post)->with('success', 'Post diupdate!');
    }

    // DELETE /posts/{post} - Hapus post
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post dihapus!');
    }
}
```

---

## Request Object

```php
public function store(Request $request)
{
    // Semua input
    $all = $request->all();

    // Input spesifik
    $name = $request->input('name');
    $name = $request->name;

    // Default value
    $name = $request->input('name', 'Guest');

    // Hanya field tertentu
    $data = $request->only(['name', 'email']);

    // Cek input ada
    if ($request->has('name')) { }
    if ($request->filled('name')) { }

    // File upload
    if ($request->hasFile('photo')) {
        $path = $request->file('photo')->store('photos');
    }
}
```

---

## Validation Rules

```php
$validated = $request->validate([
    'title' => 'required|string|max:255',
    'email' => 'required|email|unique:users',
    'password' => 'required|min:8|confirmed',
    'age' => 'nullable|integer|min:18',
    'avatar' => 'nullable|image|max:2048',
]);
```

### Common Rules:

| Rule           | Deskripsi                      |
| -------------- | ------------------------------ |
| `required`     | Wajib diisi                    |
| `nullable`     | Boleh null                     |
| `string`       | Harus string                   |
| `email`        | Format email                   |
| `unique:table` | Unik di table                  |
| `min:n`        | Minimum                        |
| `max:n`        | Maximum                        |
| `confirmed`    | Ada field {name}\_confirmation |
| `image`        | File gambar                    |

---

## Response Types

```php
// View
return view('users.index', compact('users'));

// Redirect
return redirect()->route('users.index');
return redirect()->back()->with('success', 'Berhasil!');

// JSON
return response()->json(['data' => $users]);
return response()->json(['error' => 'Not found'], 404);
```

---

## Best Practices

1. **Gunakan Form Request** untuk validasi
2. **Jaga Controller tetap "skinny"** - pindahkan logic ke Service classes
3. **Gunakan Route Model Binding** - otomatis fetch model

---

Lanjut ke: [06-routing.md](./06-routing.md)
