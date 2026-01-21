<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $table = 'subscription_master';
    protected $primaryKey = 'subscription_id';

    protected $fillable = [
        'customer_id', 'plan_id', 'start_date', 'end_date',
        'amount', 'iStatus', 'isDelete', 'created_at', 'updated_at'
    ];

    public $timestamps = false;

      public function Plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'plan_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
    

}
