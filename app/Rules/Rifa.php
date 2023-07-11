<?php

namespace App\Rules;

use App\Models\Rifa as ModelsRifa;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Rifa implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $rifa = ModelsRifa::availables()
            ->select('id')
            ->where('id', $value);

        if ($rifa->count() === 0) {
            $fail('Rifa not found');
        }
    }
}
