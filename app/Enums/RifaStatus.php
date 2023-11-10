<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum RifaStatus: string implements HasLabel
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';
    case FINISHED = 'finished';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DRAFT => 'Rascunho',
            self::PUBLISHED => 'Publicada',
            self::ARCHIVED => 'Arquivada',
            self::FINISHED => 'Finalizada',
            default => 'Rascunho'
        };
    }
}
