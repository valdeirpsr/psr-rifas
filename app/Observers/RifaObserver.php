<?php

namespace App\Observers;

use App\Models\Rifa;
use Illuminate\Support\Str;

class RifaObserver
{
    public function creating(Rifa $rifa): void
    {
        do {
            $rifa->slug = Str::slug($rifa->title).'-'.Str::random(8);
        } while (Rifa::where('slug', $rifa->slug)->count() !== 0);
    }
}
