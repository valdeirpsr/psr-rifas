<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Rifa;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class MercadoPago
{
    private const API_URL = 'https://api.mercadopago.com/v1/payments';

    private string $accessToken = '';

    public function __construct()
    {
        $this->accessToken = env('MERCADOPAGO_ACCESS_TOKEN');
    }

    public function generatePix(Order $order, Rifa $rifa)
    {
        $notificationUrl = route('payment.update');

        /**
         * Não funciona caso o endereço seja localhost
         */
        if (env('APP_DEBUG') === true) {
            $notificationUrl = str_replace('localhost', 'laravel.test', $notificationUrl);
        }

        $customerNameParts = collect(explode(' ', $order->customer_fullname));

        $response = Http::acceptJson()
            ->withToken($this->accessToken)
            ->contentType('application/json')
            ->post(self::API_URL, [
                'transaction_amount' => floatval(number_format(count($order->numbers_reserved) * $rifa->price, 2, '.', '')),
                'description' => "Rifa \"{$rifa->title}\"",
                'payment_method_id' => 'pix',
                'notification_url' => $notificationUrl,
                'date_of_expiration' => Carbon::parse($order->expire_at)->format('Y-m-d\TH:i:s.vP'),
                'payer' => [
                    'email' => $order->customer_email,
                    'first_name' => $customerNameParts->first(),
                    'last_name' => $customerNameParts->last(),
                ],
            ]);

        if ($response->failed()) {
            return $response->throw();
        }

        return (object) [
            'id' => $response->json('id'),
            'ticket_url' => $response->json('point_of_interaction.transaction_data.ticket_url'),
            'payment_method_id' => $response->json('payment_method_id'),
            'date_of_expiration' => $response->json('date_of_expiration'),
            'transaction_amount' => $response->json('transaction_amount'),
            'qr_code' => $response->json('point_of_interaction.transaction_data.qr_code'),
        ];
    }

    /**
     * Busca as informações de um pedido através da API
     */
    public function getPayment(int $paymentId): object
    {
        $response = Http::withToken($this->accessToken)
            ->get(self::API_URL."/{$paymentId}");

        if ($response->failed()) {
            return $response->throw();
        }

        return $response->object();
    }
}
