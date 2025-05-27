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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('learning_objectives')->nullable(); // Tujuan Pembelajaran
            $table->string('cover_image_path')->nullable(); // Path ke gambar sampul

            $table->foreignId('user_id')->comment('Instructor ID'); // ID Instruktur
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->foreignId('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');

            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            // $table->decimal('price', 8, 2)->default(0.00);
            $table->decimal('price', 12, 2)->default(0.00); // Contoh jika ada harga
            // Tambahkan kolom lain sesuai kebutuhan, misal: durasi, level kesulitan, dll.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
