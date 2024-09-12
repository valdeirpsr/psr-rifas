<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Rifa;
use Carbon\Carbon;
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
        $rifasModel = Rifa::latest()
            ->where('status', Rifa::STATUS_FINISHED)
            ->orWhere('expired_at', '<', now())
            ->limit(20);

        $rifas = $rifasModel->get()
            ->setHidden([
                'created_at',
                'description',
                'updated_at',
            ])
            ->map(function ($rifa) {
                $rifa->thumbnail = Storage::url($rifa->thumbnail);

                return $rifa;
            });

        return Inertia::render('Rifa/PsrList', [
            'values' => $rifas,
            'title' => '//Rifas Finalizadas',
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
            'winners' => $winners,
        ]);
    }

    /**
     * Display the orders
     */
    public function showOrders(Rifa $rifa, string $telephone)
    {
        $orders = Order::with([
            'payment' => fn (HasOne $query) => $query->select(['ticket_url', 'order_id', 'id']),
        ])
            ->where('rifa_id', $rifa->id)
            ->where('customer_telephone', $telephone)
            ->where(function ($query) {
                $query->where('status', Order::STATUS_PAID)
                    ->orWhere(function ($query) {
                        $query->where('status', Order::STATUS_RESERVED)
                            ->where('expire_at', '>', now());
                    });
            })
            ->get()
            ->map(function (Order $order) {
                $order->expire_at = Carbon::parse($order->expire_at);
                $order->created_at = now();

                return $order;
            })
            ->all();

        return new OrderResource($orders);
    }
}
