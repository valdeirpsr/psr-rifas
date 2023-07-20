<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Rifa;
use App\Models\Slideshow;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class RifasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function list()
    {
        $rifasModel = Rifa::where('status', Rifa::STATUS_FINISHED)
            ->orWhere('expired_at', '>=', now())
            ->latest()
            ->limit(20);

        $rifas = $rifasModel->get()
            ->setHidden([
                'created_at',
                'description',
                'updated_at'
            ])
            ->map(function($rifa) {
                $rifa->thumbnail = Storage::url($rifa->thumbnail);
                return $rifa;
            });

        return Inertia::render('Rifa/PsrList', [
            'values' => $rifas,
            'title' => '//Rifas Finalizadas'
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

        $rifa->thumbnail = Storage::url($rifa->thumbnail);

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
