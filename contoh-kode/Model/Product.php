<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * CONTOH MODEL - Product
 * 
 * Model ini merepresentasikan tabel 'products' di database.
 * 
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property float $price
 * @property int $stock
 * @property bool $is_active
 * @property int $category_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 */
class Product extends Model
{
    /**
     * Mengaktifkan Soft Deletes
     * Data tidak benar-benar dihapus, hanya ditandai dengan deleted_at
     */
    use SoftDeletes;

    /**
     * Nama tabel di database
     * Jika tidak didefinisikan, Laravel akan menggunakan plural dari nama model
     * Product -> products (otomatis)
     */
    protected $table = 'products';

    /**
     * Primary key
     * Default: 'id'
     */
    protected $primaryKey = 'id';

    /**
     * Kolom yang boleh diisi secara mass assignment
     * Contoh: Product::create(['name' => 'Test', ...])
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'is_active',
        'category_id',
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi ke JSON/array
     */
    protected $hidden = [
        // 'secret_field',
    ];

    /**
     * Casting tipe data otomatis
     */
    protected $casts = [
        'price' => 'float',
        'stock' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Default values untuk atribut
     */
    protected $attributes = [
        'is_active' => true,
        'stock' => 0,
    ];

    // ==========================================
    // RELATIONSHIPS (Relasi)
    // ==========================================

    /**
     * Relasi: Product belongs to Category (Many to One)
     * Satu product hanya bisa punya satu category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi: Product has many OrderItems (One to Many)
     * Satu product bisa ada di banyak order items
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relasi: Product belongs to many Tags (Many to Many)
     * Satu product bisa punya banyak tags
     * Menggunakan pivot table 'product_tag'
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    // ==========================================
    // SCOPES (Query Scopes)
    // ==========================================

    /**
     * Scope: Hanya product yang aktif
     * Penggunaan: Product::active()->get()
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Hanya product yang ada stok
     * Penggunaan: Product::inStock()->get()
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Scope: Filter berdasarkan range harga
     * Penggunaan: Product::priceRange(100000, 500000)->get()
     */
    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    /**
     * Scope: Search berdasarkan nama
     * Penggunaan: Product::search('laptop')->get()
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->where('name', 'like', "%{$keyword}%");
    }

    // ==========================================
    // ACCESSORS (Cara mengambil data)
    // ==========================================

    /**
     * Accessor: Format harga ke Rupiah
     * Penggunaan: $product->formatted_price
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Accessor: Status stok
     * Penggunaan: $product->stock_status
     */
    public function getStockStatusAttribute()
    {
        if ($this->stock <= 0) {
            return 'Habis';
        } elseif ($this->stock < 10) {
            return 'Hampir Habis';
        }
        return 'Tersedia';
    }

    // ==========================================
    // MUTATORS (Cara menyimpan data)
    // ==========================================

    /**
     * Mutator: Otomatis generate slug dari name
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = \Str::slug($value);
    }
}
