<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PpatCustomer extends Model
{
    // use HasFactory;
    use SoftDeletes;

    protected $table = 'ppat_customer';

    protected $fillable = ['*'];

    public function user()
    {
        return $this->belongsTo(Customer::class,'customer_id','id');
    }
}
