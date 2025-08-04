<?php

namespace Tests\Feature;

use App\Models\Rifa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class RifaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_rifa_dentro_do_prazo_nao_pode_aparecer_como_expirada(): void
    {
        Rifa::factory(1)->create([
            'status' => Rifa::STATUS_DRAFT,
            'expired_at' => now()->addDay(1),
        ]);

        Rifa::factory(1)->create([
            'status' => Rifa::STATUS_FINISHED,
            'expired_at' => now()->subMinutes(120),
        ]);

        $response = $this->get(route('rifas.finalizadas'));

        $response->assertInertia(fn (Assert $page) => $page->component('Rifa/PsrList')
            ->count('values', 1)
        );
    }

    /**
     * Somente rifas com data de publicação superior à definida em published_at
     * devem ser retornadas na página inicial
     *
     * @return void
     */
    public function test_valida_exibicacao_de_rifas_nao_publicadas()
    {
        Rifa::factory(1)->create([
            'status' => Rifa::STATUS_PUBLISHED,
            'published_at' => now()->subDay(),
        ]);

        Rifa::factory(1)->create([
            'status' => Rifa::STATUS_PUBLISHED,
            'published_at' => now()->addDay(),
        ]);

        $this->get(route('home'))
            ->assertInertia(fn (Assert $page) => $page->component('Home/PsrList')
                ->count('values', 1)
            );
    }
}
