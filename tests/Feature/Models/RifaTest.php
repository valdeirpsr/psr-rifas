<?php

namespace Tests\Feature\Models;

use App\Models\Rifa;
use Tests\TestCase;

class RifaTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_o_slug_da_rifa_nao_deve_ser_alterado(): void
    {
        $rifa = Rifa::factory()->create();
        $rifa->slug = 'changed';
        $rifa->save();

        $this->assertDatabaseMissing('rifas', [
            'slug' => 'changed',
        ]);
    }
}
