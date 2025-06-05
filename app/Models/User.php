<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\VerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject //, MustVerifyEmail
{
    use Notifiable,
        SoftDeletes;

    protected $table = 'user';
    /**
     * Fillable input. The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'photo',
        'name',
        'username',
        'email',
        'password',
        'phone',

        'housetel',
        'address',
        'location',
        'postcode',
        'state',
        'city',

        'gender',
        'race',
        'nric',

        'verify_token',
        'reset_token',

        'is_verified',
        'status',

        'role_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verify_token',
        'forgot_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_verified' => 'boolean',
    ];


    /**
     * Get the profile photo URL attribute.
     *
     * @return string
     */
    public function getPhotoUrlAttribute()
    {
        return vsprintf(
            'https://www.gravatar.com/avatar/%s.jpg?s=200&d=%s',
            [
                md5(strtolower($this->email)),
                $this->name ? urlencode("https://ui-avatars.com/api/$this->name") : 'mp',
            ]
        );
    }

    /**
     * Get the oauth providers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function oauthProviders()
    {
        return $this->hasMany(OAuthProvider::class);
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    /**
     * Get JWT key indentifier
     *
     * @return int
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get JWT claim
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    // =============
    // ORM RELATION
    // =============

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id')->withTrashed();
    }

    public function membership()
    {
        return $this->hasMany(UserMembership::class, 'user_id');
    }

    public function hasVerified()
    {
        return $this->is_verified;
    }

    public function credits()
    {
        return $this->hasOne(UserCredit::class, 'user_id');
    }
}
