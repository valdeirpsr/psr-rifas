<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum WinnerPosition: int implements HasLabel
{
    case ONE = 1;
    case TWO = 2;
    case THREE = 3;
    case FOUR = 4;
    case FIVE = 5;
    case SIX = 6;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ONE => '1º Prêmio',
            self::TWO => '2º Prêmio',
            self::THREE => '3º Prêmio',
            self::FOUR => '4º Prêmio',
            self::FIVE => '5º Prêmio',
            self::SIX => '6º Prêmio',
        };
    }
}
