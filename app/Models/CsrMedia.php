<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CsrMedia extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'csr_media';
    protected $dates = ['deleted_at'];

    public function csr() {
        return $this->belongsTo(Csr::class,'csr_id','id');
    }
}
