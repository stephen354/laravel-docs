<?php

/**
 * CONTOH MIGRATION
 * 
 * Migration adalah cara Laravel untuk mendefinisikan struktur database.
 * File ini akan dijalankan dengan: php artisan migrate
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration (membuat tabel)
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            // Primary Key
            $table->id();  // Auto increment bigint
            
            // Kolom string
            $table->string('name');           // VARCHAR(255)
            $table->string('slug')->unique(); // VARCHAR(255) + UNIQUE
            
            // Kolom text
            $table->text('description')->nullable();  // TEXT, boleh null
            
            // Kolom numeric
            $table->decimal('price', 12, 2);   // DECIMAL(12,2) untuk harga
            $table->integer('stock')->default(0);  // INT dengan default 0
            
            // Kolom boolean
            $table->boolean('is_active')->default(true);
            
            // Foreign Key
            $table->foreignId('category_id')
                  ->constrained()                    // Otomatis ke tabel categories
                  ->onDelete('cascade');             // Hapus product jika category dihapus
            
            // Timestamps
            $table->timestamps();    // created_at & updated_at
            $table->softDeletes();   // deleted_at (untuk soft delete)
        });
    }

    /**
     * Rollback migration (hapus tabel)
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

/*
|--------------------------------------------------------------------------
| TIPE DATA YANG SERING DIGUNAKAN
|--------------------------------------------------------------------------

$table->id();                    // BIGINT auto increment
$table->uuid('id')->primary();   // UUID sebagai primary key
$table->string('name');          // VARCHAR(255)
$table->string('code', 50);      // VARCHAR(50)
$table->text('content');         // TEXT
$table->longText('content');     // LONGTEXT
$table->integer('quantity');     // INT
$table->bigInteger('total');     // BIGINT
$table->decimal('price', 10, 2); // DECIMAL (10 digit, 2 desimal)
$table->float('rating');         // FLOAT
$table->boolean('is_active');    // TINYINT(1)
$table->date('birth_date');      // DATE
$table->dateTime('published_at');// DATETIME
$table->time('open_time');       // TIME
$table->timestamp('verified_at');// TIMESTAMP
$table->json('metadata');        // JSON
$table->enum('status', ['draft', 'published', 'archived']);

|--------------------------------------------------------------------------
| MODIFIERS
|--------------------------------------------------------------------------

->nullable()           // Boleh NULL
->default('value')     // Default value
->unsigned()           // Untuk integer, tidak boleh negatif
->unique()             // Nilai harus unik
->index()              // Tambah index
->after('column')      // Letakkan setelah kolom tertentu
->comment('...')       // Komentar pada kolom

|--------------------------------------------------------------------------
| FOREIGN KEY
|--------------------------------------------------------------------------

// Cara 1: Otomatis
$table->foreignId('user_id')->constrained();

// Cara 2: Manual
$table->unsignedBigInteger('user_id');
$table->foreign('user_id')->references('id')->on('users');

// Dengan cascade
$table->foreignId('user_id')
      ->constrained()
      ->onUpdate('cascade')
      ->onDelete('cascade');

*/
