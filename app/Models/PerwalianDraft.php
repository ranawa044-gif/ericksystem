<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerwalianDraft extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'form_data'];

    protected $casts = [
        'form_data' => 'array',
    ];
}
