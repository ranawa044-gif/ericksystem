<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicPrediction extends Model
{
    use HasFactory;

    protected $fillable = ['mahasiswa_id', 'score', 'category', 'recommendation', 'details'];

    protected $casts = [
        'details' => 'array'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
