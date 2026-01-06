<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RazorpayOrder extends Model
{
    protected $table = 'razorpay_orders';

    protected $fillable = [
        'customer_id',
        'plan_id',
        'order_id',
        'receipt',
        'amount',
        'currency',
        'payment_id',
        'signature',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];
}
