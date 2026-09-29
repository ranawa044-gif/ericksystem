<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['mahasiswa', 'dosen', 'admin'])->default('mahasiswa')->after('password');
            $table->foreignId('mahasiswa_id')->nullable()->after('role')->constrained('mahasiswas')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('dosen_id')->nullable()->after('mahasiswa_id')->constrained('dosens')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('dosen_id');
            $table->dropConstrainedForeignId('mahasiswa_id');
            $table->dropColumn('role');
        });
    }
};
