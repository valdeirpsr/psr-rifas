<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Order;
use App\Models\Rifa;
use App\Services\MercadoPago;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    /**
     * Ao fazer um POST para a rota de pagamento, o sistema deverá criar um pagamento com a resposta
     * do sistema de pagamento
     */
    public function test_salvar_dados_de_pagamento_na_tabela_payment_ao_fazer_requisicao(): void
    {
        $rifa = Rifa::factory(1)->hasOrders(1)->create()->first();
        $order = Order::where('rifa_id', $rifa->id)->first();

        $resultFake = [
            'id' => fake()->randomNumber(),
            'ticket_url' => 'https://example.com',
            'payment_method_id' => 'pix',
            'date_of_expiration' => '2023-07-06T15:48:10.178-04:00',
            'transaction_amount' => 1.00,
            'qr_code' => 'qrcode',
        ];

        $this->mock(MercadoPago::class, function ($mock) use ($resultFake) {
            $mock->shouldReceive('generatePix')->once()
                ->andReturn((object)$resultFake);
        });

        $response = $this->post("/payments", [
            'orderId' => $order->id
        ]);

        $this->assertDatabaseHas('payments', [
            'id' => $resultFake['id']
        ]);

        $response->assertLocation("payment/{$resultFake['id']}");
    }
}
