<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Winner extends Model
{
    use HasFactory;

    protected $fillable = [
        'drawn_number',
        'testimonial',
        'video',
        'position',
        'rifa_id',
        'order_id',
    ];

    public function video(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Storage::url($value) : $value
        );
    }

    public function rifa(): HasOne
    {
        return $this->hasOne(Rifa::class, 'id', 'rifa_id');
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
