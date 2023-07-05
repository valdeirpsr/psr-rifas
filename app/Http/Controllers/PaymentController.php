<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\MercadoPago as ServicesMercadoPago;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\QueryException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function __construct(
        private ServicesMercadoPago $paymentGateway
    ) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $order = Order::with([
            'rifa' => fn (HasOne $query) => $query->select('id', 'title', 'price'),
        ])
        ->where('id', $request->input('orderId', 0))
        ->first();

        try {
            $response = $this->paymentGateway->generatePix($order, $order->rifa);

            $payment = new Payment();
            $payment->id = $response->id;
            $payment->ticket_url = $response->ticket_url;
            $payment->payment_code = $response->payment_method_id;
            $payment->date_of_expiration = Carbon::parse($response->date_of_expiration);
            $payment->transaction_amount = $response->transaction_amount;
            $payment->qr_code = $response->qr_code;
            $payment->order_id = $order->id;
            $payment->save();

            return Inertia::location(route('payment.show', ['id' => $response->id]));
        } catch (RequestException $e) {
            if ($e->response->status() === 401) {
                Log::emergency($e->response->body());
            }

            return abort(500);
        } catch (QueryException $e) {
            return Inertia::location(route('payment.show', ['id' => $response->id]));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }
}
