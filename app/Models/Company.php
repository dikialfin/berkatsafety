<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    // use HasFactory;
    use SoftDeletes;

    protected $table = 'company';

    protected $fillable = ['*'];

    public function admin()
    {
        return $this->hasOne(User::class,'company_id','id')->where('role_id',2);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class,'subscription_id','id');
    }

    public function website()
    {
        return $this->hasOne(CompanyWebsite::class,'company_id','id');
    }

    public function saas_setting()
    {
        return $this->hasOne(CompanySaasSetting::class,'company_id','id');
    }
}
