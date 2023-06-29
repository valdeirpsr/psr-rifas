<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Rifa;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RifasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Display the orders
     */
    public function showOrders(Rifa $rifa, string $telephone)
    {
        $orders = Order::with([
            'payment' => fn (HasOne $query) => $query->select(['ticket_url', 'order_id'])
        ])
        ->where('rifa_id', $rifa->id)
        ->where('customer_telephone', $telephone)
        ->get();

        return new OrderResource($orders);
    }
}
