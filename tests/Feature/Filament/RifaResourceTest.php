<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\RifaResource;
use App\Models\Rifa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RifaResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_a_rifa_deve_ser_criada_com_dados_basicos(): void
    {
        $rifa = Rifa::factory()->make();

        $form = [
            'title' => $rifa->title,
            'thumbnail' => [$rifa->thumbnail],
            'price' => $rifa->price,
            'total_numbers_available' => $rifa->total_numbers_available,
            'description' => $rifa->description,
            'ranking_buyer' => $rifa->ranking_buyer,
        ];

        Livewire::test(RifaResource\Pages\CreateRifa::class)
            ->fillForm($form)
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('rifas', [
            ...$form,
            /* Valores padrão */
            'buy_max' => 300,
            'buy_min' => 1,
            'status' => 'draft',
        ]);
    }

    /**
     * São valores obrigatórios:
     *  - title
     *  - thumbnail
     *  - price
     */
    public function test_a_rifa_nao_deve_ser_criada_sem_valores_obrigatorios(): void
    {
        $rifa = Rifa::factory()->make();

        Livewire::test(RifaResource\Pages\CreateRifa::class)
            ->fillForm([])
            ->call('create')
            ->assertHasFormErrors([
                'title' => 'required',
                'thumbnail' => 'required',
                'price' => 'required',
            ]);
    }
}
