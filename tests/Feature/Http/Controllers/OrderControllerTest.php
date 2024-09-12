<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Order;
use App\Models\Rifa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Cria um pedido e reserva os números aleatórios de acordo com a quantidade
     */
    public function test_create_order(): void
    {
        $rifa = Rifa::factory()->create([
            'status' => 'published',
        ]);

        $telephone = $this->faker()->numerify('(##) # ####-####');

        $response = $this->post('/orders', [
            'fullname' => sprintf('%s %s', $this->faker()->firstName(), $this->faker()->lastName()),
            'email' => $this->faker()->safeEmail(),
            'telephone' => $telephone,
            'confirmTelephone' => $telephone,
            'terms' => true,
            'quantity' => $rifa->buy_min,
            'rifa' => $rifa->id,
        ]);

        $orderId = $this->getOrderId($response);

        $response->assertFound();
        $response->assertLocation(route('orders.show', ['id' => $orderId]));

        $this->assertDatabaseCount('orders', 1);
    }

    public function test_numeros_aleatorios_devem_ser_unicos(): void
    {
        $rifa = $this->generateRifa();
        $this->generateDefaultOrder($rifa);

        $response = $this->post('/orders', $this->generateBodyRequest($rifa, ['quantity' => 5]));
        $orderId = $this->getOrderId($response);

        $orderCreated = Order::find($orderId);

        $this->assertEqualsCanonicalizing(
            json_encode(['0000', '0002', '0003', '0005', '0007']),
            json_encode($orderCreated->numbers_reserved),
        );
    }

    public function test_se_nao_houver_quantidade_disponivel_gerar_um_erro(): void
    {
        $rifa = $this->generateRifa();
        $this->generateDefaultOrder($rifa);

        $response = $this->post('/orders', $this->generateBodyRequest($rifa, ['quantity' => 10]));

        $response->assertStatus(409);
    }

    public function test_gerar_erro_caso_a_rifa_tenha_expirado(): void
    {
        $rifa = $this->generateRifa([
            'expired_at' => now()->subMinutes(60),
        ]);

        $this->generateDefaultOrder($rifa);

        $response = $this->post('/orders', $this->generateBodyRequest($rifa, ['quantity' => 1]));

        $response->assertInvalid(['rifa']);
    }

    public function test_gerar_erro_caso_a_rifa_nao_esteja_publicada(): void
    {
        $rifa = $this->generateRifa([
            'status' => 'draft',
        ]);

        $this->generateDefaultOrder($rifa);

        $response = $this->post('/orders', $this->generateBodyRequest($rifa, ['quantity' => 1]));

        $response->assertInvalid(['rifa']);
    }

    public function test_gerar_erro_ao_criar_pedido_com_rifa_inexistente(): void
    {
        $rifa = $this->generateRifa();

        $this->generateDefaultOrder($rifa);

        $response = $this->post('/orders', $this->generateBodyRequest($rifa, ['rifa' => -1]));

        $response->assertInvalid(['rifa']);
    }

    public function test_ao_criar_pedido_o_telefone_deve_ser_salvo_apenas_com_digitos_numericos(): void
    {
        $rifa = $this->generateRifa();
        $this->generateDefaultOrder($rifa);

        $response = $this->post('/orders', $this->generateBodyRequest($rifa, [
            'telephone' => '(00) 0 0000-0000',
            'confirmTelephone' => '(00) 0 0000-0000',
        ]));
        $orderId = $this->getOrderId($response);

        $orderCreated = Order::find($orderId);

        $this->assertEquals('00000000000', $orderCreated->customer_telephone);
    }

    public function test_verifica_validacoes_da_requisicao_ao_tentar_reserver_numeros(): void
    {
        $rifa = $this->generateRifa(['buy_min' => 10]);

        $invalidFormBody = [
            'confirmTelephone' => '1234567',
            'email' => 'valdeir.dev',
            'fullname' => 'In va li do',
            'quantity' => 1,
            'rifa' => $rifa->id,
            'telephone' => '12345678',
            'terms' => false,
        ];

        $response = $this->post(route('orders.store'), $invalidFormBody);

        $response->assertInvalid([
            'confirmTelephone',
            'email',
            'fullname',
            'quantity',
            'telephone',
            'terms',
        ]);
    }

    public function test_o_usuario_nao_pode_comprar_mais_bilhetes_que_o_maximo_definido(): void
    {
        $rifa = $this->generateRifa();

        $invalidFormBody = $this->generateBodyRequest($rifa, [
            'quantity' => $rifa->buy_max + 1,
        ]);

        $response = $this->post(route('orders.store'), $invalidFormBody);

        $response->assertInvalid('quantity');
    }

    /**
     * A página deve retornar Status ode 200 quando o pedido estiver reservado
     */
    public function test_a_pagina_deve_retornar_ok_com_pedido_reservado(): void
    {
        $order = Order::factory()->for(Rifa::factory())->create();

        $response = $this->get(route('orders.show', ['id' => $order->id]));

        $response->assertStatus(200);
    }

    public function test_redirecionar_para_home_caso_pedido_nao_seja_encontrado(): void
    {
        $response = $this->get(route('orders.show', ['id' => 'invalid']));

        $response->assertLocation('/');
    }

    public function test_o_usuario_deve_ser_redirecionado_para_rifa_quando_o_pedido_sem_pagamento_estiver_expirado(): void
    {
        $order = Order::factory()->for(Rifa::factory())->create([
            'expire_at' => now()->subMinutes(60),
        ]);

        $response = $this->get(route('orders.show', ['id' => $order->id]));

        $response->assertLocation(route('rifas.show', ['rifa' => $order->rifa]));
    }

    private function getOrderId(\Illuminate\Testing\TestResponse $response)
    {
        $locationParts = explode('/', $response->headers->get('location'));

        return last($locationParts);
    }

    private function generateRifa(array $replace = []): Rifa
    {
        return Rifa::factory()->create(array_merge([
            'status' => 'published',
            'total_numbers_available' => 10,
            'buy_min' => 1,
        ], $replace));
    }

    private function generateDefaultOrder(Rifa $rifa): void
    {
        Order::factory()->create([
            'customer_fullname' => 'Valdeir Psr',
            'customer_email' => $this->faker()->safeEmail(),
            'customer_telephone' => '00000000000',
            'numbers_reserved' => ['1', '4', '6', '8', '9', '10'],
            'status' => 'paid',
            'expire_at' => null,
            'rifa_id' => $rifa->id,
        ]);
    }

    private function generateBodyRequest(Rifa $rifa, array $replace = []): array
    {
        $telephone = $this->faker()->numerify('(##) # ####-####');

        return array_merge([
            'fullname' => sprintf('%s %s', $this->faker()->firstName(), $this->faker()->lastName()),
            'email' => $this->faker()->safeEmail(),
            'telephone' => $telephone,
            'confirmTelephone' => $telephone,
            'terms' => true,
            'quantity' => $rifa->buy_min,
            'rifa' => $rifa->id,
        ], $replace);
    }
}
