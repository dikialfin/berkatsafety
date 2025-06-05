<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserLog extends Model
{
    // use HasFactory;

    protected $table = 'user_log';

    protected $fillable = ['*'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
