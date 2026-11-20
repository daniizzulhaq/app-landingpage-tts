<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->string('ikon', 50)->default('bi-card-checklist');
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->text('deskripsi')->nullable();
            $table->text('persyaratan')->nullable();
            $table->text('prosedur')->nullable();
            $table->text('biaya')->nullable();
            $table->text('jadwal')->nullable();
            $table->text('lokasi')->nullable();
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });

        Schema::create('service_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->string('nama');
            $table->string('path');
            $table->timestamps();
        });

        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('lokasi')->nullable();
            $table->dateTime('mulai')->index();
            $table->dateTime('selesai')->nullable();
            // Mengikuti alur approval yang sama dengan konten lain.
            $table->enum('status', ['draft', 'pending', 'published', 'rejected'])->default('draft')->index();
            $table->text('catatan_revisi')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('youtube_url', 500);
            $table->text('deskripsi')->nullable();
            $table->date('tanggal')->nullable();
            $table->timestamps();
        });

        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori', 50)->index();
            $table->text('deskripsi')->nullable();
            $table->string('path');
            $table->unsignedSmallInteger('tahun')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('information_requests', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kontak');
            $table->text('informasi_diminta');
            $table->text('tujuan');
            $table->enum('status', ['baru', 'diproses', 'selesai', 'ditolak'])->default('baru')->index();
            $table->text('catatan')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('information_requests');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('videos');
        Schema::dropIfExists('agendas');
        Schema::dropIfExists('service_files');
        Schema::dropIfExists('services');
    }
};
