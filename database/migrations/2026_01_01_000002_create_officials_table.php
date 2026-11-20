<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('officials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->string('nama');
            $table->string('pangkat')->nullable();
            $table->string('jabatan');
            $table->enum('level', ['kapolres', 'wakapolres', 'pejabat'])->default('pejabat')->index();
            $table->string('foto')->nullable();
            $table->longText('tugas')->nullable();
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('officials');
    }
};
