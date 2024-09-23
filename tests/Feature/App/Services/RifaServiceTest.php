<?php

namespace Tests\Feature\App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Rifa;
use App\Models\Winner;
use App\Services\RifaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

class RifaServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Valida o retorno da contagem de pedidos filtrados pelo status de pagamento
     */
    public function test_valida_retorno_contagem_pedidos_filtrados_por_status(): void
    {
        $rifa = Rifa::factory()->createQuietly();
        Order::factory()->createOneQuietly([
            'rifa_id' => $rifa->id,
            'numbers_reserved' => range(1, 10),
            'status' => OrderStatus::PAID,
        ]);

        $service = new RifaService;
        $result = $service->countOrdersByStatus($rifa, OrderStatus::PAID);

        assertEquals(10, $result);
    }

    /**
     * Valida o ranking de comprados gerados e retornados
     */
    public function test_valida_ranking_compradores_retornados(): void
    {
        $rifa = Rifa::factory()->createQuietly();

        $topRanking = Order::factory()->createOneQuietly([
            'rifa_id' => $rifa->id,
            'numbers_reserved' => range(1, 10),
            'status' => OrderStatus::PAID,
        ]);

        Order::factory()->createOneQuietly([
            'rifa_id' => $rifa->id,
            'numbers_reserved' => [11, 12],
            'status' => OrderStatus::PAID,
        ]);

        $service = new RifaService;
        $result = $service->getRanking($rifa);

        assertEquals(
            $topRanking->customer_fullname,
            $result->first()->customer_fullname
        );
    }

    /**
     * Valida a lista de vencedores de um leilão
     */
    public function test_valida_lista_vencedores_retornados(): void
    {
        $winner = Winner::factory()->createOneQuietly();
        $rifa = $winner->rifa()->first();

        $service = new RifaService;
        $result = $service->winners($rifa);

        assertEquals(
            $winner->drawn_number,
            $result->first()->drawn_number
        );
    }
}
