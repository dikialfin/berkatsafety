<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    // use HasFactory;
    use SoftDeletes;

    protected $table = 'products';
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    public function categories()
    {
        return $this->belongsToMany(Categories::class, 'product_category', 'product_id', 'category_id');
    }

    public function brands()
    {
        return $this->belongsToMany(Brands::class, 'product_brand', 'product_id', 'brand_id');
    }

    public function productMedia()
    {
        return $this->hasMany(ProductMedia::class, 'product_id', 'id');
    }
    
}
