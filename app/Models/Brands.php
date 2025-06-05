<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brands extends Model
{
    // use HasFactory;
    use SoftDeletes;

    protected $table = 'brands';
    protected $guarded = [];

    public function products()
    {
        return $this->belongsToMany(Products::class, 'product_brand', 'brand_id', 'product_id');
    }
}
