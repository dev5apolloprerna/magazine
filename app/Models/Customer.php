<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Contracts\Auth\CanResetPassword;

class Customer extends Authenticatable implements JWTSubject
{

    use Notifiable, CanResetPasswordTrait;


    protected $table = 'customer_master';
    protected $primaryKey = 'customer_id';

    protected $fillable = [
        'customer_name',
        'customer_mobile',
        'customer_email',
        'password',
        'login_count',
        'magazine_count',
        'iStatus'
    ];

    protected $hidden = ['password'];


    public $timestamps = true;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
     public function getEmailForPasswordReset()
    {
        return $this->customer_email; // ✅ change this to your column name
    }

   public function loginLogs()
    {
        return $this->hasMany(CustomerLoginLog::class, 'customer_id', 'customer_id');
    }

    public function lastLogin()
    {
        return $this->hasOne(CustomerLoginLog::class, 'customer_id', 'customer_id')
            ->latestOfMany('login_date_time');
    }

    public function magazineLogs()
    {
        return $this->hasMany(CustomerMagazineLog::class, 'customer_id', 'customer_id');
    }



}

    