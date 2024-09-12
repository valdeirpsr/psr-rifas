<?php

namespace Tests\Feature;

use App\Models\Rifa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class RifaControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
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
}
