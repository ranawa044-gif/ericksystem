<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nilai extends Model
{
    use HasFactory;

    public const RANGE_NILAI = [
        ['huruf' => 'A', 'min' => 80.00, 'max' => 100.00, 'bobot' => 4.00],
        ['huruf' => 'AB', 'min' => 73.00, 'max' => 79.99, 'bobot' => 3.50],
        ['huruf' => 'B', 'min' => 66.00, 'max' => 72.99, 'bobot' => 3.00],
        ['huruf' => 'BC', 'min' => 59.00, 'max' => 65.99, 'bobot' => 2.50],
        ['huruf' => 'C', 'min' => 52.00, 'max' => 58.99, 'bobot' => 2.00],
        ['huruf' => 'D', 'min' => 38.00, 'max' => 51.99, 'bobot' => 1.00],
        ['huruf' => 'E', 'min' => 0.00, 'max' => 37.99, 'bobot' => 0.00],
    ];

    protected $fillable = [
        'krs_id',
        'nilai_angka',
        'nilai_huruf',
        'bobot',
        'status_lulus',
    ];

    protected $casts = [
        'nilai_angka' => 'decimal:2',
        'bobot' => 'decimal:2',
        'status_lulus' => 'boolean',
    ];

    public function krs(): BelongsTo
    {
        return $this->belongsTo(Krs::class);
    }

    public static function konversiDariAngka(float $nilaiAngka): array
    {
        foreach (self::RANGE_NILAI as $range) {
            if ($nilaiAngka >= $range['min'] && $nilaiAngka <= $range['max']) {
                return [
                    'nilai_huruf' => $range['huruf'],
                    'bobot' => $range['bobot'],
                    'status_lulus' => $range['bobot'] >= 2.00,
                ];
            }
        }

        return [
            'nilai_huruf' => 'E',
            'bobot' => 0.00,
            'status_lulus' => false,
        ];
    }
}
