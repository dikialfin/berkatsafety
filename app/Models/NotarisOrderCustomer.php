<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotarisOrderCustomer extends Model
{
    // use HasFactory;
    use SoftDeletes;

    protected $table = 'notaris_order_customer';

    protected $fillable = ['*'];

    public function permission()
    {
        return $this->hasMany(SubscriptionPermission::class,'subscription_id','id');
    }
}
