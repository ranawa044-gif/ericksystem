<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catatan_dosen_walis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('dosen_id')->constrained('dosens')->cascadeOnUpdate()->restrictOnDelete();
            $table->date('tanggal');
            $table->text('catatan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catatan_dosen_walis');
    }
};

