<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotarisOrder extends Model
{
    // use HasFactory;
    use SoftDeletes;

    protected $table = 'notaris_order';

    protected $fillable = ['*'];

    public function customer()
    {
        return $this->hasManyThrough(
            Customer::class,
            NotarisOrderCustomer::class,
            'notaris_order_id',
            'id',
            'id',
            'customer_id',
        );
    }

    public function document()
    {
        return $this->hasMany(NotarisDocument::class,'notaris_order_id','id');
    }
}
