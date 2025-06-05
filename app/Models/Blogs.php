<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blogs extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'blogs';
    protected $dates = ['deleted_at'];

    protected $guarded = [];

    public function categories()
    {
        return $this->belongsToMany(Categories::class, 'blog_category', 'blog_id', 'category_id');
    }
}
