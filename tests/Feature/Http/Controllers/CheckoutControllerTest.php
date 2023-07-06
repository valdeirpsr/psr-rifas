<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Order;
use App\Models\Rifa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CheckoutControllerTest extends TestCase
{
    private Rifa $rifa;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rifa = Rifa::factory(1)->create([
            'status' => Rifa::STATUS_PUBLISHED
        ])->first();
    }

    /**
     * A página deve retornar Status ode 200 quando o pedido estiver reservado
     */
    public function test_a_pagina_deve_retornar_ok_com_pedido_reservado(): void
    {
        $order = Order::factory()->create([
            'status' => Order::STATUS_RESERVED,
            'rifa_id' => $this->rifa->id
        ]);

        $response = $this->get("/checkout/{$order->id}");

        $response->assertStatus(200);
    }

    public function test_redirecionar_para_home_caso_pedido_nao_seja_encontrado(): void
    {
        $response = $this->get("/checkout/invalid");

        $response->assertLocation('/');
    }
}
