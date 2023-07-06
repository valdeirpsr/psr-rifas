<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CheckoutController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $result = Order::with([
            'rifa' => fn ($query) => $query->select('id', 'title', 'price'),
        ])
        ->where('id', $id)
        ->first();

        if ($result === null) {
            return redirect('/');
        }

        $rifa = $result->rifa;

        $order = $result->makeHidden('rifa');
        $order->transaction_amount = $rifa->price * count($order->numbers_reserved);
        $order->expire_at = Carbon::parse($order->expire_at);

        return inertia('Checkout/PsrShow', [
            'order' => $order,
            'rifa' => $rifa
        ]);
    }
}
