<?php

namespace App\Enum;

enum MenuStatus: string
{
    case BROUILLON = 'brouillon';
    case PUBLIE = 'publie';
    case ARCHIVE = 'archive';

    public function label(): string
    {
        return match ($this) {
            self::BROUILLON => 'brouillon',
            self::PUBLIE => 'publié',
            self::ARCHIVE => 'archivé',
        };
    }
}
