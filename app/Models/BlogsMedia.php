<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpParser\Node\Expr\FuncCall;

class BlogsMedia extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'blogs_media';
    protected $dates = ['deleted_at'];

    public function blog() {
        return $this->belongsTo(Blogs::class,'blogs_id','id');
    }
}
