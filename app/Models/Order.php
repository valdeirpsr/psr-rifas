<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    /** @var string */
    public const STATUS_PAID = 'paid';

    /** @var string */
    public const STATUS_RESERVED = 'reserved';

    protected $fillable = [
        'rifa_id',
        'customer_fullname',
        'customer_email',
        'customer_telephone',
        'numbers_reserved',
        'status',
    ];

    protected $casts = [
        'numbers_reserved' => 'array',
        'total_numbers' => 'int'
    ];

    public function rifa(): HasOne
    {
        return $this->hasOne(Rifa::class, 'id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
