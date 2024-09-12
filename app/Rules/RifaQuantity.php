<?php

namespace App\Rules;

use App\Models\Rifa;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class RifaQuantity implements DataAwareRule, ValidationRule
{
    /**
     * Dados adicionais para validação
     *
     * @var array<string, mixed>
     */
    protected $data = [];

    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $rifa = Rifa::find($this->data['rifa']);

        if (! $rifa) {
            $fail('Rifa inválida');

            return;
        }

        if ($value < $rifa->buy_min) {
            $fail("O pedido deve ser de no mínimo {$rifa->buy_min} bilhetes");

            return;
        }

        if ($value > $rifa->buy_max) {
            $fail("O pedido deve ser de no máximo {$rifa->buy_max} bilhetes");

            return;
        }
    }
}
