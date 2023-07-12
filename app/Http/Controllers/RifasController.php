<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Rifa;
use App\Models\Slideshow;
use App\Models\Winner;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Inertia\Inertia;

class RifasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rifas = Rifa::availables()
            ->latest()
            ->limit(20)
            ->get()
            ->setHidden([
                'created_at',
                'description',
                'updated_at'
            ]);

        $slideshows = Slideshow::orderBy('order')->get();

        return Inertia::render('Rifa/PsrList', [
            'values' => $rifas,
            'slideshows' => $slideshows
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Rifa $rifa)
    {
        $winners = $rifa->through('orders')
            ->has('winners')
            ->get(['orders.customer_fullname', 'winners.position', 'video']);

        return Inertia::render('Rifa/PsrShow', [
            'rifa' => $rifa,
            'ranking' => $rifa->ranking(),
            'winners' => $winners
        ]);
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
