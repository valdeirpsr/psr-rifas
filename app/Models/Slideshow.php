<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Slideshow extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'image',
        'alt',
        'order',
    ];

    public function image(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Storage::url($value)
        );
    }
}
