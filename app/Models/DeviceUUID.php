<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceUUID extends Model
{
    // use HasFactory;
    use SoftDeletes;

    protected $table = 'device_uuid';

    protected $fillable = ['*'];
}
