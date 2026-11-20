<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['bag', 'sat', 'sie', 'polsek'])->index();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->longText('tugas_pokok')->nullable();
            $table->longText('layanan_unit')->nullable();
            $table->string('alamat')->nullable();
            $table->string('telepon', 50)->nullable();
            $table->string('email')->nullable();
            $table->text('wilayah_pelayanan')->nullable();
            $table->string('maps_url', 1000)->nullable();
            $table->string('foto')->nullable();
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('unit_id')->nullable()->after('password')->constrained('units')->nullOnDelete();
            $table->boolean('is_active')->default(true)->after('unit_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('unit_id');
            $table->dropColumn('is_active');
        });
        Schema::dropIfExists('units');
    }
};
