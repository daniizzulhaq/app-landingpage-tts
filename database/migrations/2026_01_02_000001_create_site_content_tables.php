<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('judul');
            $table->longText('isi')->nullable();
            $table->string('gambar')->nullable();
            $table->timestamps();
        });

        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('subjudul', 500)->nullable();
            $table->string('gambar');
            $table->string('link', 500)->nullable();
            $table->unsignedInteger('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('pertanyaan', 500);
            $table->longText('jawaban');
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });

        Schema::create('complaint_channels', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->string('url', 500)->nullable();
            $table->string('ikon', 50)->default('bi-link-45deg');
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaint_channels');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('sliders');
        Schema::dropIfExists('pages');
    }
};
