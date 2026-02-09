<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

/**
 * CONTOH CONTROLLER - ProductController
 * 
 * Ini adalah Resource Controller yang menyediakan 7 method CRUD:
 * - index   : Menampilkan daftar products
 * - create  : Menampilkan form create
 * - store   : Menyimpan product baru
 * - show    : Menampilkan detail product
 * - edit    : Menampilkan form edit
 * - update  : Update product
 * - destroy : Hapus product
 */
class ProductController extends Controller
{
    /**
     * GET /products
     * 
     * Menampilkan daftar semua products dengan pagination
     */
    public function index(Request $request)
    {
        // Query builder dengan fitur search dan filter
        $query = Product::query();

        // Search berdasarkan nama (jika ada parameter search)
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter berdasarkan category (jika ada)
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter hanya yang aktif (opsional)
        if ($request->boolean('active_only')) {
            $query->active();
        }

        // Eager loading untuk menghindari N+1 problem
        $query->with('category');

        // Order dan pagination
        $products = $query->latest()->paginate(15);

        // Kirim data ke view
        return view('products.index', compact('products'));
    }

    /**
     * GET /products/create
     * 
     * Menampilkan form untuk membuat product baru
     */
    public function create()
    {
        // Ambil daftar categories untuk dropdown
        $categories = \App\Models\Category::all();

        return view('products.create', compact('categories'));
    }

    /**
     * POST /products
     * 
     * Menyimpan product baru ke database
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_active'   => 'boolean',
        ], [
            // Custom error messages (opsional)
            'name.required'        => 'Nama product wajib diisi',
            'price.required'       => 'Harga wajib diisi',
            'price.min'            => 'Harga tidak boleh negatif',
            'category_id.exists'   => 'Kategori tidak valid',
        ]);

        // Buat product baru
        $product = Product::create($validated);

        // Redirect dengan flash message
        return redirect()
            ->route('products.show', $product)
            ->with('success', 'Product berhasil ditambahkan!');
    }

    /**
     * GET /products/{product}
     * 
     * Menampilkan detail satu product
     * 
     * Note: Parameter $product sudah otomatis di-fetch oleh Laravel
     * berkat Route Model Binding
     */
    public function show(Product $product)
    {
        // Load relationships yang dibutuhkan
        $product->load(['category', 'tags']);

        return view('products.show', compact('product'));
    }

    /**
     * GET /products/{product}/edit
     * 
     * Menampilkan form untuk edit product
     */
    public function edit(Product $product)
    {
        $categories = \App\Models\Category::all();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * PUT/PATCH /products/{product}
     * 
     * Update product di database
     */
    public function update(Request $request, Product $product)
    {
        // Validasi
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_active'   => 'boolean',
        ]);

        // Update product
        $product->update($validated);

        return redirect()
            ->route('products.show', $product)
            ->with('success', 'Product berhasil diupdate!');
    }

    /**
     * DELETE /products/{product}
     * 
     * Hapus product dari database
     */
    public function destroy(Product $product)
    {
        // Hapus product (soft delete jika model menggunakan SoftDeletes)
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product berhasil dihapus!');
    }

    // ==========================================
    // CONTOH METHOD TAMBAHAN
    // ==========================================

    /**
     * PATCH /products/{product}/toggle-active
     * 
     * Toggle status aktif product
     */
    public function toggleActive(Product $product)
    {
        $product->update([
            'is_active' => !$product->is_active,
        ]);

        $status = $product->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()
            ->back()
            ->with('success', "Product berhasil {$status}!");
    }

    /**
     * POST /products/{product}/duplicate
     * 
     * Duplikasi product
     */
    public function duplicate(Product $product)
    {
        $newProduct = $product->replicate();
        $newProduct->name = $product->name . ' (Copy)';
        $newProduct->save();

        return redirect()
            ->route('products.edit', $newProduct)
            ->with('success', 'Product berhasil diduplikasi!');
    }
}
