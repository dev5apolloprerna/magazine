<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerMagazineLog extends Model
{
    protected $table = 'customer_magazine_log';
    protected $primaryKey = 'logid';
    public $timestamps = false; // set true only if you have created_at/updated_at

    protected $fillable = [
        'magazine_id',
        'customer_id',
        'clicked_count',
    ];
}
