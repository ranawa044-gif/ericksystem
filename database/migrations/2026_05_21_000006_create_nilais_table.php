<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('krs_id')->unique()->constrained('krs')->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('nilai_angka', 5, 2);
            $table->string('nilai_huruf', 3);
            $table->decimal('bobot', 3, 2);
            $table->boolean('status_lulus')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};

