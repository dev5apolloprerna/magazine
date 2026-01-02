<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table = 'plan_master';
    protected $primaryKey = 'plan_id';

    protected $fillable = [
        'plan_name',
        'plan_amount',
        'days',
        'iStatus'
    ];

    public $timestamps = true;
}
