<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerLoginLog extends Model
{
    protected $table = 'customer_login_log';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['customer_id', 'login_date_time'];

    protected $casts = [
        'login_date_time' => 'datetime',
    ];
}
