<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 150);                  // 1. judul buku
            $table->string('author', 100);                 // 2. penulis
            $table->string('publisher', 100);              // 3. penerbit
            $table->string('isbn', 20)->unique();          // 4. nomor ISBN
            $table->integer('year_published');             // 5. tahun terbit
            $table->decimal('price', 10, 2);               // 6. harga
            $table->integer('stock')->default(0);          // 7. stok
            $table->enum('status', ['available', 'unavailable'])->default('available'); // 8. status
            $table->timestamps();                          // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
