<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Krs extends Model
{
    use HasFactory;

    protected $table = 'krs';

    protected $fillable = [
        'mahasiswa_id',
        'mata_kuliah_id',
        'semester_id',
        'status',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function nilai(): HasOne
    {
        return $this->hasOne(Nilai::class);
    }
}

