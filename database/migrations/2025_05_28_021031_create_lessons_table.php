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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->onDelete('cascade'); // Relasi ke tabel modules
            $table->string('title');
            $table->string('slug')->unique()->nullable(); // Opsional, bisa dibuat dari title
            
            // Jenis konten pelajaran
            $table->enum('content_type', [
                'text',         // Konten teks biasa atau Markdown
                'video_embed',  // URL embed video (YouTube, Vimeo)
                'image_url',    // URL ke gambar
                'pdf_url',      // URL ke file PDF atau path jika di-host sendiri
                'file_upload',  // Path ke file yang diunggah (misal: PPT, DOC, PDF)
                // 'quiz_id'    // Nanti jika ada fitur kuis, bisa merujuk ke ID kuis
            ])->default('text');
            
            $table->text('content_url_or_text')->nullable(); // Menyimpan URL, path, atau teks konten
            
            $table->integer('duration_minutes')->nullable()->comment('Estimasi durasi pelajaran dalam menit');
            $table->integer('order')->default(0)->comment('Untuk urutan pelajaran dalam modul');
            $table->boolean('is_previewable')->default(false)->comment('Apakah pelajaran ini bisa dilihat tanpa enroll?');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
