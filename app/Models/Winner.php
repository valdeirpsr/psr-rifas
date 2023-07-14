<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function rifa(): BelongsTo
    {
        return $this->belongsTo(Rifa::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
