<?php

namespace App\Observers;

use App\Models\Rifa;
use App\Models\Winner as WinnerModel;

class Winner
{
    /**
     * Handle the Winner "created" event.
     */
    public function created(WinnerModel $winner): void
    {
        $winner->rifa->update(['status' => Rifa::STATUS_FINISHED]);
    }
}
