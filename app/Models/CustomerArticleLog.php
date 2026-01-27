<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerArticleLog extends Model
{
    protected $table = 'customer_article_log';
    protected $primaryKey = 'logid';
    public $timestamps = false;

    protected $fillable = [
        'magazine_id',
        'article_id',
        'customer_id',
        'date_time',
    ];

    protected $casts = [
        'date_time' => 'datetime',
    ];
}
