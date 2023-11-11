<?php

namespace App\Services;
use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Rifa;

class RifaService
{
    public function countOrdersByStatus(Rifa $rifa, OrderStatus $orderStatus)
    {
        return $rifa->orders()->where('status', $orderStatus)
            ->count('id');
    }
}
