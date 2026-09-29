<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesanMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'pesan_mahasiswas';

    protected $fillable = [
        'mahasiswa_id',
        'dosen_id',
        'pesan',
        'tipe',
        'balasan_dosen',
        'dibalas_at',
    ];

    protected $casts = [
        'dibalas_at' => 'datetime',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }
}
