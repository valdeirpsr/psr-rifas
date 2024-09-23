<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Rifa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RifaService
{
    /**
     * Conta o número de pedidos de uma rifa de acordo com a situação do pedido
     *
     * @return int
     */
    public function countOrdersByStatus(Rifa $rifa, OrderStatus $orderStatus)
    {
        return (int) $rifa->orders()
            ->where('status', $orderStatus)
            ->sum(DB::raw('JSON_LENGTH(numbers_reserved)'));
    }

    /**
     * Captura o ranking de compradores
     */
    public function getRanking(Rifa $rifa): Collection
    {
        return Order::query()->select([
            'customer_fullname',
            'customer_telephone',
            DB::raw('SUM(JSON_LENGTH(numbers_reserved)) AS numbers_reserved_total'),
        ])
            ->where('status', OrderStatus::PAID)
            ->where('rifa_id', $rifa->id)
            ->groupBy('customer_telephone')
            ->orderBy('numbers_reserved_total', 'desc')
            ->get();
    }

    /**
     * Captura os vencedores da rifa
     *
     * @return Rifa[]
     */
    public function winners(Rifa $rifa)
    {
        return $rifa->winners()->with('order')->get();
    }

    /**
     * Verifica se uma rifa há vencedores
     *
     * @return bool
     */
    public function hasWinner(Rifa $rifa)
    {
        return (bool) $rifa->winners()->count();
    }
}
