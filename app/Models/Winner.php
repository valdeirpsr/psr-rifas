<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    protected $casts = [
        'video' => 'array'
    ];

    public function rifa(): HasOne
    {
        return $this->hasOne(Rifa::class, 'id', 'rifa_id');
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }
}
