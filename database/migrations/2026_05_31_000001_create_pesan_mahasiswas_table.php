<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesan_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->cascadeOnUpdate()->cascadeOnDelete();
            $table->text('pesan');
            $table->string('tipe')->default('umum');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesan_mahasiswas');
    }
};
