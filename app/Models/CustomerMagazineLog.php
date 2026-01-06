<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerMagazineLog extends Model
{
    protected $table = 'customer_magazine_log';
    protected $primaryKey = 'logid';
    public $timestamps = false;

    protected $fillable = [
        'magazine_id',
        'customer_id',
        'date_time',
    ];

    protected $casts = [
        'date_time' => 'datetime',
    ];
}
