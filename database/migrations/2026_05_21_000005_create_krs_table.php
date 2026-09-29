<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('krs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('semester_id')->constrained('semesters')->cascadeOnUpdate()->restrictOnDelete();
            $table->enum('status', ['diambil', 'batal'])->default('diambil');
            $table->timestamps();

            $table->unique(['mahasiswa_id', 'mata_kuliah_id', 'semester_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('krs');
    }
};

