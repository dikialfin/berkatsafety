<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categories extends Model
{
    // use HasFactory;
    use SoftDeletes;

    protected $table = 'categories';
    protected $guarded = [];


    public function children()
    {
        return $this->hasMany(Categories::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Categories::class, 'parent_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(Products::class, 'product_category', 'category_id', 'product_id')
                    ->withTimestamps();
    }
}
