<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrestasiMahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'nama_kegiatan',
        'jenis_kegiatan',
        'tempat',
        'tanggal',
        'pembimbing',
        'bukti_file',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
