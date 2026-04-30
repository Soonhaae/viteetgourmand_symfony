<?php

namespace App\Enum;

enum MenuStatus: string
{
    case BROUILLON = 'brouillon';
    case PUBLIE = 'publie';
    case ARCHIVE = 'archive';
}