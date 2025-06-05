<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Catalogue extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'catalogue';
    protected $dates = ['deleted_at'];

    protected $guarded = [];

    public function brands()
    {
        return $this->belongsToMany(Brands::class, 'brands_catalogue', 'catalog_id', 'brand_id');
    }

}
