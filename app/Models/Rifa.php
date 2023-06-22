<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
