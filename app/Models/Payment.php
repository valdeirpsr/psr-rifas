<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'ticket_url',
        'payment_code',
        'date_of_expiration',
        'transaction_amount',
        'qr_code',
        'date_approved',
        'order_id',
    ];

    protected $casts = [
        'transaction_amount' => 'float'
    ];

    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }
}
