<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesan_mahasiswas', function (Blueprint $table) {
            $table->foreignId('dosen_id')->nullable()->after('mahasiswa_id')->constrained('dosens')->cascadeOnUpdate()->nullOnDelete();
            $table->text('balasan_dosen')->nullable()->after('tipe');
            $table->timestamp('dibalas_at')->nullable()->after('balasan_dosen');
        });
    }

    public function down(): void
    {
        Schema::table('pesan_mahasiswas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('dosen_id');
            $table->dropColumn(['balasan_dosen', 'dibalas_at']);
        });
    }
};
