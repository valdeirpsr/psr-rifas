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
        $rifa = ModelsRifa::select('id')
            ->where('id', $value)
            ->where(function($query) {
                $query->where('expired_at', '>', now()->format('Y-m-d H:i'))
                    ->orWhereNull('expired_at');
            })
            ->where('status', ModelsRifa::STATUS_PUBLISHED);

        if ($rifa->count() === 0) {
            $fail('Rifa not found');
        }
    }
}
