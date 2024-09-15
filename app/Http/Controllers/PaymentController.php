<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentStatusResource;
use App\Models\Order;
use App\Models\Payment;
use App\Services\MercadoPago as ServicesMercadoPago;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\QueryException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PaymentController extends Controller
{
    private const STATUS_APPROVED = 'approved';

    public function __construct(
        private ServicesMercadoPago $paymentGateway
    ) {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $order = Order::with([
            'rifa' => fn (BelongsTo $query) => $query->select('id', 'title', 'price', 'slug'),
            'payment' => fn (HasOne $query) => $query->select('id', 'order_id'),
        ])
            ->where('id', $request->input('orderId', 0))
            ->first();

        if (! $order) {
            return back();
        }

        if ($order->payment) {
            return redirect()->route('payment.show', ['payment' => $order->payment->id]);
        }

        if (now() > Carbon::parse($order->expire_at)) {
            return redirect()->route('rifas.show', ['rifa' => $order->rifa]);
        }

        try {
            $response = $this->paymentGateway->generatePix($order, $order->rifa);

            $payment = new Payment();
            $payment->id = $response->id;
            $payment->ticket_url = $response->ticket_url;
            $payment->payment_code = $response->payment_method_id;
            $payment->date_of_expiration = Carbon::parse($response->date_of_expiration)->timezone(config('app.timezone'));
            $payment->transaction_amount = $response->transaction_amount;
            $payment->qr_code = $response->qr_code;
            $payment->order_id = $order->id;
            $payment->save();

            return Inertia::location(route('payment.show', ['payment' => $response->id]));
        } catch (RequestException $e) {
            Log::emergency($e->response->body());

            return back()->withErrors(['warning' => true]);
        } catch (QueryException $e) {
            return Inertia::location(route('payment.show', ['payment' => $response->id]));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $qrcode = QrCode::encoding('UTF-8')->format('png')->size(300)->generate($payment->qr_code);
        $payment->qr_code_img = base64_encode($qrcode);
        $payment->date_of_expiration = Carbon::parse($payment->date_of_expiration)->timezone('America/Sao_Paulo');

        return inertia('Payment/PsrShow', [
            'payment' => $payment->only([
                'id',
                'qr_code',
                'qr_code_img',
                'ticket_url',
                'transaction_amount',
                'date_of_expiration',
                'date_approved',
            ]),
        ]);
    }

    /**
     * Recebe notificação via WebHook, consulta o pagamento e atualiza os dados
     * se necessário
     */
    public function update(Request $request): Response
    {
        if ($request->input('action') !== 'payment.updated') {
            return response(null, 204);
        }

        $paymentId = $request->input('data.id');

        $paymentInfo = $this->paymentGateway->getPayment($paymentId);

        if ($paymentInfo->status === self::STATUS_APPROVED) {
            $payment = Payment::where('id', $paymentId)->first();

            $payment->update([
                'date_approved' => Carbon::parse($paymentInfo->date_approved),
            ]);

            Order::where('id', $payment->order_id)
                ->update(['status' => Order::STATUS_PAID]);
        }

        return response('');
    }

    /**
     * Realiza uma conexão SSE com o cliente e envia informações periódicas sobre
     * um determinado pedido.
     */
    public function check(Payment $payment)
    {
        return new PaymentStatusResource($payment);
    }
}
