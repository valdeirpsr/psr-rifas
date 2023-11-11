<?php

namespace App\Services;
use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Rifa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RifaService
{
    public function countOrdersByStatus(Rifa $rifa, OrderStatus $orderStatus)
    {
        return $rifa->orders()->where('status', $orderStatus)
            ->count('id');
    }

    public function getRanking(Rifa $rifa): Collection
    {
        return Order::query()->select([
            'customer_fullname',
            'customer_telephone',
            DB::raw('SUM(JSON_LENGTH(numbers_reserved)) AS total')
        ])
        ->where('status', OrderStatus::PAID)
        ->where('rifa_id', $rifa->id)
        ->groupBy('customer_telephone')
        ->orderBy('total', 'desc')
        ->get();
    }
}
