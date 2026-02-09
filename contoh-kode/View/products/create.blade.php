{{--
    CONTOH VIEW - products/create.blade.php
    
    Form untuk membuat product baru.
--}}

@extends('layouts.app')

@section('title', 'Tambah Product')

@section('content')
    <div class="container py-4">
        {{-- BREADCRUMB --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('products.index') }}">Products</a>
                </li>
                <li class="breadcrumb-item active">Tambah Baru</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">📦 Tambah Product Baru</h4>
                    </div>
                    <div class="card-body">

                        {{-- MENAMPILKAN SEMUA ERROR --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Oops!</strong> Ada beberapa masalah dengan input Anda:
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- FORM --}}
                        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                            {{-- CSRF Token (WAJIB untuk form POST) --}}
                            @csrf

                            {{-- NAMA --}}
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    Nama Product <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    placeholder="Masukkan nama product" required>
                                {{-- Error message untuk field spesifik --}}
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- KATEGORI --}}
                            <div class="mb-3">
                                <label for="category_id" class="form-label">
                                    Kategori <span class="text-danger">*</span>
                                </label>
                                <select name="category_id" id="category_id"
                                    class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- HARGA --}}
                            <div class="mb-3">
                                <label for="price" class="form-label">
                                    Harga <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="price" id="price"
                                        class="form-control @error('price') is-invalid @enderror"
                                        value="{{ old('price') }}" min="0" step="1000" placeholder="0" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- STOK --}}
                            <div class="mb-3">
                                <label for="stock" class="form-label">
                                    Stok <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="stock" id="stock"
                                    class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', 0) }}"
                                    min="0" placeholder="0" required>
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- DESKRIPSI --}}
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                    rows="4" placeholder="Deskripsi product (opsional)">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- STATUS AKTIF --}}
                            <div class="mb-4">
                                <div class="form-check">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" id="is_active" value="1"
                                        class="form-check-input" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Product Aktif
                                    </label>
                                </div>
                                <small class="text-muted">
                                    Product yang aktif akan ditampilkan di katalog
                                </small>
                            </div>

                            {{-- TOMBOL --}}
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    💾 Simpan
                                </button>
                                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                    Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
