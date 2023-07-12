<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function customerTelephone(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => preg_replace('/\D/', '', $value),
            set: fn (string $value) => preg_replace('/\D/', '', $value),
        );
    }

    public function rifa(): HasOne
    {
        return $this->hasOne(Rifa::class, 'id', 'rifa_id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function winners(): HasMany
    {
        return $this->hasMany(Winner::class);
    }
}

