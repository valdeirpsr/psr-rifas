<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasLabel
{
    case ARCHIVED = 'archived';
    case EXPIRED = 'expired';
    case PAID = 'paid';
    case RESERVED = 'reserved';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ARCHIVED => 'Arquivado',
            self::EXPIRED => 'Expirado',
            self::PAID => 'Pago',
            self::RESERVED => 'Reservado',
            default => 'Arquivado'
        };
    }
}
