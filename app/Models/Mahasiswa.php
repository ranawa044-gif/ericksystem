<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nim',
        'nama',
        'program_studi',
        'angkatan',
        'dosen_wali_id',
        'foto',
    ];

    public function dosenWali(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'dosen_wali_id');
    }

    public function krs(): HasMany
    {
        return $this->hasMany(Krs::class);
    }

    public function catatanDosenWali(): HasMany
    {
        return $this->hasMany(CatatanDosenWali::class)->latest('tanggal');
    }

    public function pesanKeMahasiswa(): HasMany
    {
        return $this->hasMany(PesanMahasiswa::class)->latest('created_at');
    }

    public function prestasi(): HasMany
    {
        return $this->hasMany(PrestasiMahasiswa::class)->latest('tanggal')->latest('created_at');
    }
}
