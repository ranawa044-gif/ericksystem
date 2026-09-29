<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestasi_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('nama_kegiatan');
            $table->string('jenis_kegiatan')->nullable();
            $table->string('tempat')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('pembimbing')->nullable();
            $table->string('bukti_file')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestasi_mahasiswas');
    }
};
