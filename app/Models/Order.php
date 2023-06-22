<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'rifa_id',
        'customer_fullname',
        'customer_email',
        'customer_telephone',
        'numbers_reserved',
        'status',
    ];

    protected $casts = [
        'numbers_reserved' => 'array'
    ];

    public function rifa(): HasOne
    {
        return $this->hasOne(Rifa::class, 'id');
    }
}
