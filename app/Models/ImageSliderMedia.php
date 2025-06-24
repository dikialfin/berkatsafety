<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImageSliderMedia extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'image_slider_media';
    protected $dates = ['deleted_at'];

}
