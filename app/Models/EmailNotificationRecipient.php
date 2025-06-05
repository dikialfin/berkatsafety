<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailNotificationRecipient extends Model
{
    // use HasFactory;
    use SoftDeletes;

    protected $table = 'email_notification_recipient';

    protected $fillable = ['*'];

    public function tier()
    {
        return $this->belongsTo(MembershipTier::class,'membership_tier_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
