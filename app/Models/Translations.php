<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Translations extends Model
{
    protected $fillable = ['key', 'translations'];

    // Automatically cast 'translations' column to array
    protected $casts = [
        'translations' => 'array',
    ];
}
