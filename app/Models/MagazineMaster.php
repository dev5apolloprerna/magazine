<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MagazineMaster extends Model
{
    protected $table = 'magazine_master';

    // created_at / updated_at exist, so keep timestamps enabled
    public $timestamps = true;

    protected $fillable = [
        'title',
        'image',
        'pdf',
        'month',
        'year',
        'publish_date',
        'iStatus',
        'isDelete',
    ];

    protected $casts = [
        'year' => 'integer',
        'iStatus' => 'integer',
        'isDelete' => 'integer',
    ];
}
