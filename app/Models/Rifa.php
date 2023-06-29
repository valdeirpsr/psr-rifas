<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Rifa extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'thumbnail',
        'price',
        'description',
        'slug',
        'total_numbers_available',
        'buy_max',
        'buy_min',
        'raffle',
        'status',
        'expired_at',
    ];

    public function thumbnail(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Storage::disk('s3')->url($value)
        );
    }

    public function price(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => floatval($value)
        );
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
