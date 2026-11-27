<?php

namespace App\Enum;

enum Priorite: string
{
    case BASSE = 'basse';
    case MOYENNE = 'moyenne';
    case HAUTE = 'haute';

    public function label(): string
    {
        return match($this) {
            self::BASSE => 'Basse',
            self::MOYENNE => 'Moyenne',
            self::HAUTE => 'Haute',
        };
    }
}