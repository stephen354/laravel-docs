# 📦 Model - Eloquent ORM

## Apa itu Model?

**Model** adalah representasi dari tabel database dalam bentuk class PHP. Laravel menggunakan **Eloquent ORM** (Object-Relational Mapping) untuk berinteraksi dengan database.

```
Tabel Database     <---->     Model Laravel
    users                        User
    posts                        Post
    categories                   Category
```

---

## Lokasi File

```
app/Models/
├── User.php        # Model bawaan Laravel
├── Post.php        # Model custom
└── Category.php    # Model custom
```

---

## Membuat Model

### Menggunakan Artisan:

```bash
# Model saja
php artisan make:model NamaModel

# Model + Migration
php artisan make:model NamaModel -m

# Model + Migration + Controller
php artisan make:model NamaModel -mc

# Model + semua (migration, controller, seeder, factory)
php artisan make:model NamaModel -mcsf

# Complete package (+ resource controller + form requests)
php artisan make:model NamaModel -a
```

---

## Struktur Model Dasar

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // Nama tabel (jika tidak mengikuti konvensi)
    protected $table = 'posts';

    // Primary key (default: id)
    protected $primaryKey = 'id';

    // Tipe primary key (default: int)
    protected $keyType = 'int';

    // Auto increment (default: true)
    public $incrementing = true;

    // Timestamps (default: true)
    public $timestamps = true;

    // Format timestamps
    protected $dateFormat = 'Y-m-d H:i:s';

    // Kolom yang boleh diisi (mass assignment)
    protected $fillable = [
        'title',
        'content',
        'user_id',
    ];

    // Kolom yang TIDAK boleh diisi
    // protected $guarded = ['id'];

    // Kolom yang disembunyikan dari JSON/array
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casting tipe data
    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'metadata' => 'array',
    ];
}
```

---

## Konvensi Penamaan

Laravel mengikuti konvensi untuk menentukan nama tabel:

| Model Class     | Nama Tabel         |
| --------------- | ------------------ |
| `User`          | `users`            |
| `Post`          | `posts`            |
| `Category`      | `categories`       |
| `ProductItem`   | `product_items`    |
| `PersonAddress` | `person_addresses` |

### Jika tidak mengikuti konvensi:

```php
class Artikel extends Model
{
    protected $table = 'artikel_berita'; // Custom nama tabel
}
```

---

## Operasi CRUD

### CREATE - Membuat Data Baru

```php
// Metode 1: new + save
$post = new Post();
$post->title = 'Judul Artikel';
$post->content = 'Isi artikel...';
$post->save();

// Metode 2: create (mass assignment)
$post = Post::create([
    'title' => 'Judul Artikel',
    'content' => 'Isi artikel...',
    'user_id' => 1,
]);

// Metode 3: firstOrCreate (create jika belum ada)
$post = Post::firstOrCreate(
    ['title' => 'Judul Artikel'],    // Kondisi pencarian
    ['content' => 'Isi artikel...']   // Data jika create
);
```

### READ - Mengambil Data

```php
// Mengambil semua data
$posts = Post::all();

// Mengambil berdasarkan ID
$post = Post::find(1);
$post = Post::findOrFail(1); // Throw 404 jika tidak ada

// Mengambil satu data pertama
$post = Post::first();
$post = Post::firstOrFail();

// Dengan kondisi WHERE
$posts = Post::where('status', 'published')->get();
$posts = Post::where('user_id', 1)->get();

// WHERE dengan operator
$posts = Post::where('views', '>', 100)->get();
$posts = Post::where('created_at', '>=', now()->subDays(7))->get();

// Multiple WHERE
$posts = Post::where('status', 'published')
             ->where('user_id', 1)
             ->get();

// WHERE IN
$posts = Post::whereIn('category_id', [1, 2, 3])->get();

// ORDER BY
$posts = Post::orderBy('created_at', 'desc')->get();
$posts = Post::latest()->get();  // shortcut untuk orderBy('created_at', 'desc')
$posts = Post::oldest()->get();  // shortcut untuk orderBy('created_at', 'asc')

// LIMIT
$posts = Post::take(10)->get();
$posts = Post::limit(10)->get();

// PAGINATION
$posts = Post::paginate(15);        // 15 per halaman
$posts = Post::simplePaginate(15);  // Tanpa total count

// SELECT specific columns
$posts = Post::select('id', 'title')->get();

// COUNT, SUM, AVG, MAX, MIN
$count = Post::count();
$total = Post::sum('views');
$average = Post::avg('rating');
```

### UPDATE - Mengubah Data

```php
// Metode 1: find + save
$post = Post::find(1);
$post->title = 'Judul Baru';
$post->save();

// Metode 2: update langsung
Post::where('id', 1)->update(['title' => 'Judul Baru']);

// Metode 3: updateOrCreate
$post = Post::updateOrCreate(
    ['slug' => 'artikel-satu'],        // Kondisi pencarian
    ['title' => 'Judul Baru']          // Data yang diupdate
);

// Increment / Decrement
Post::where('id', 1)->increment('views');      // views + 1
Post::where('id', 1)->increment('views', 5);   // views + 5
Post::where('id', 1)->decrement('stock');      // stock - 1
```

### DELETE - Menghapus Data

```php
// Metode 1: find + delete
$post = Post::find(1);
$post->delete();

// Metode 2: destroy dengan ID
Post::destroy(1);
Post::destroy([1, 2, 3]);

// Metode 3: delete dengan kondisi
Post::where('status', 'draft')->delete();
```

---

## Soft Deletes

Soft delete tidak benar-benar menghapus data, hanya menandai dengan `deleted_at`.

### Setup Migration:

```php
Schema::table('posts', function (Blueprint $table) {
    $table->softDeletes(); // Menambah kolom deleted_at
});
```

### Setup Model:

```php
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
}
```

### Operasi Soft Delete:

```php
// Hapus (soft delete)
$post->delete();

// Query termasuk yang sudah dihapus
$posts = Post::withTrashed()->get();

// Query HANYA yang sudah dihapus
$posts = Post::onlyTrashed()->get();

// Restore data yang sudah dihapus
Post::withTrashed()->where('id', 1)->restore();

// Hapus permanen
Post::withTrashed()->where('id', 1)->forceDelete();
```

---

## Relationships (Relasi)

### One to Many (Satu ke Banyak)

```
User ----< Posts
Satu user memiliki banyak posts
```

```php
// Model User
class User extends Model
{
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}

// Model Post
class Post extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

// Penggunaan
$user = User::find(1);
$posts = $user->posts;          // Semua posts milik user

$post = Post::find(1);
$author = $post->user;          // User pemilik post
```

### Many to Many (Banyak ke Banyak)

```
Posts ----< post_tag >---- Tags
Post bisa punya banyak tags, Tag bisa di banyak posts
```

```php
// Model Post
class Post extends Model
{
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}

// Model Tag
class Tag extends Model
{
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}

// Penggunaan
$post = Post::find(1);
$tags = $post->tags;

// Attach/Detach
$post->tags()->attach($tagId);
$post->tags()->detach($tagId);
$post->tags()->sync([1, 2, 3]);   // Hanya tag 1, 2, 3 yang tersisa
```

### One to One (Satu ke Satu)

```php
// Model User
class User extends Model
{
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
}

// Model Profile
class Profile extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

---

## Eager Loading (Menghindari N+1 Problem)

### Problem N+1:

```php
// ❌ N+1 Query Problem
$posts = Post::all();
foreach ($posts as $post) {
    echo $post->user->name;  // Query tambahan untuk setiap post!
}
```

### Solusi dengan Eager Loading:

```php
// ✅ Eager Loading
$posts = Post::with('user')->get();
foreach ($posts as $post) {
    echo $post->user->name;  // Tidak ada query tambahan
}

// Multiple relations
$posts = Post::with(['user', 'tags', 'comments'])->get();

// Nested relations
$posts = Post::with('comments.user')->get();
```

---

## Query Scopes

### Local Scopes:

```php
class Post extends Model
{
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopePopular($query, $minViews = 1000)
    {
        return $query->where('views', '>=', $minViews);
    }
}

// Penggunaan
$posts = Post::published()->get();
$posts = Post::published()->popular(500)->get();
```

### Global Scopes:

```php
// Otomatis diterapkan ke semua query
protected static function booted()
{
    static::addGlobalScope('active', function ($query) {
        $query->where('is_active', true);
    });
}
```

---

## Accessors & Mutators (Laravel 11+)

```php
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Model
{
    // Accessor: cara mengambil data
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->first_name . ' ' . $this->last_name,
        );
    }

    // Mutator: cara menyimpan data
    protected function email(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => strtolower($value),
        );
    }

    // Accessor + Mutator
    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => bcrypt($value),
        );
    }
}

// Penggunaan
$user->full_name;       // Output: "John Doe"
$user->email = 'TEST@EMAIL.COM';  // Disimpan: test@email.com
```

---

## Langkah Selanjutnya

Lanjut ke:

- [04-view.md](./04-view.md) - Memahami View dan Blade Template
