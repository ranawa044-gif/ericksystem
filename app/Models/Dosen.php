<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Dosen extends Model
{
    use HasFactory;

    protected $fillable = [
        'nidn',
        'nama',
        'jabatan',
    ];

    public function mahasiswaBimbingan(): HasMany
    {
        return $this->hasMany(Mahasiswa::class, 'dosen_wali_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'dosen_id');
    }
}

