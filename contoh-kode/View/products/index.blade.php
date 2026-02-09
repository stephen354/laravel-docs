{{--
    CONTOH VIEW - products/index.blade.php
    
    View untuk menampilkan daftar products.
    Menggunakan Blade template engine.
--}}

{{-- Extend layout utama --}}
@extends('layouts.app')

{{-- Set judul halaman --}}
@section('title', 'Daftar Products')

{{-- Konten utama --}}
@section('content')
<div class="container py-4">
    
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>📦 Daftar Products</h1>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            + Tambah Product
        </a>
    </div>

    {{-- FLASH MESSAGE SUCCESS --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- FLASH MESSAGE ERROR --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- FORM SEARCH & FILTER --}}
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('products.index') }}" method="GET" class="row g-3">
                {{-- Search --}}
                <div class="col-md-4">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Cari product..."
                           value="{{ request('search') }}">
                </div>
                
                {{-- Filter Category --}}
                <div class="col-md-3">
                    <select name="category_id" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category->id }}" 
                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                {{-- Filter Active Only --}}
                <div class="col-md-3">
                    <div class="form-check mt-2">
                        <input type="checkbox" 
                               name="active_only" 
                               value="1" 
                               class="form-check-input"
                               id="activeOnly"
                               {{ request('active_only') ? 'checked' : '' }}>
                        <label class="form-check-label" for="activeOnly">
                            Hanya yang aktif
                        </label>
                    </div>
                </div>
                
                {{-- Submit --}}
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary w-100">
                        🔍 Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- TABEL PRODUCTS --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th width="200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Loop menggunakan @forelse (dengan empty state) --}}
                        @forelse ($products as $product)
                            <tr>
                                {{-- Nomor urut dengan pagination --}}
                                <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                                
                                {{-- Nama (dengan link ke detail) --}}
                                <td>
                                    <a href="{{ route('products.show', $product) }}" class="text-decoration-none">
                                        {{ $product->name }}
                                    </a>
                                </td>
                                
                                {{-- Kategori (relasi) --}}
                                <td>{{ $product->category->name ?? '-' }}</td>
                                
                                {{-- Harga (menggunakan accessor) --}}
                                <td>{{ $product->formatted_price }}</td>
                                
                                {{-- Stok dengan warna --}}
                                <td>
                                    @if($product->stock <= 0)
                                        <span class="badge bg-danger">Habis</span>
                                    @elseif($product->stock < 10)
                                        <span class="badge bg-warning">{{ $product->stock }}</span>
                                    @else
                                        <span class="badge bg-success">{{ $product->stock }}</span>
                                    @endif
                                </td>
                                
                                {{-- Status aktif --}}
                                <td>
                                    @if($product->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                
                                {{-- Tombol aksi --}}
                                <td>
                                    {{-- Lihat Detail --}}
                                    <a href="{{ route('products.show', $product) }}" 
                                       class="btn btn-sm btn-info"
                                       title="Lihat Detail">
                                        👁️
                                    </a>
                                    
                                    {{-- Edit --}}
                                    <a href="{{ route('products.edit', $product) }}" 
                                       class="btn btn-sm btn-warning"
                                       title="Edit">
                                        ✏️
                                    </a>
                                    
                                    {{-- Toggle Active --}}
                                    <form action="{{ route('products.toggle-active', $product) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-secondary"
                                                title="{{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            {{ $product->is_active ? '🔕' : '🔔' }}
                                        </button>
                                    </form>
                                    
                                    {{-- Hapus --}}
                                    <form action="{{ route('products.destroy', $product) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus product ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger"
                                                title="Hapus">
                                            🗑️
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            {{-- Tampilan jika tidak ada data --}}
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <p class="mb-2">📦 Belum ada product</p>
                                        <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
                                            + Tambah Product Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Menampilkan {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} 
                    dari {{ $products->total() }} products
                </div>
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

{{-- OPTIONAL: Push custom scripts --}}
@push('scripts')
<script>
    // Contoh: Auto-submit form saat filter berubah
    document.querySelector('select[name="category_id"]').addEventListener('change', function() {
        this.form.submit();
    });
</script>
@endpush
