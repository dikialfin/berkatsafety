<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandsCatalogue extends Model
{
    use HasFactory;

    protected $table = 'brands_catalogue';

    protected $guarded = [];

    public function catalogues()
{
        return $this->belongsToMany(
            Catalogue::class,
            'brands_catalogue',
            'brand_id',               
            'catalog_id'    
        );
    }

}
