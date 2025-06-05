<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeoPage extends Model
{
    protected $fillable = ['page', 'seo_setting'];
    protected $table = 'seo_pages';
    // Automatically cast 'seo_setting' column to array
    protected $casts = [
        'seo_setting' => 'array',
    ];
}
