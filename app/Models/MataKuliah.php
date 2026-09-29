<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliahs';

    protected $fillable = [
        'kode',
        'nama',
        'sks',
        'semester_default',
    ];

    public function krs(): HasMany
    {
        return $this->hasMany(Krs::class);
    }
}

