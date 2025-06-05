<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ppat extends Model
{
    // use HasFactory;
    use SoftDeletes;

    protected $table = 'ppat';

    protected $fillable = ['*'];

    public function customer()
    {
        return $this->hasMany(PpatCustomer::class);
    }

    public function customers()
    {
        return $this->hasManyThrough(
            Customer::class,
            PpatCustomer::class,
            'ppat_id',
            'id',
            'id',
            'customer_id',
        );
    }

    public function document()
    {
        return $this->hasMany(PpatDocument::class,'ppat_id','id');
    }
}
