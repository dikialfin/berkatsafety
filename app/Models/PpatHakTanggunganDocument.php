<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PpatHakTanggunganDocument extends Model
{
    // use HasFactory;
    use SoftDeletes;

    protected $table = 'ppat_hak_tanggungan_document';

    protected $fillable = ['*'];
}
