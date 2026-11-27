<?php
namespace App\Enum;

enum Statut: string
{
    case A_FAIRE = "a_faire";
    case EN_COURS = "en_cours";
    case TERMINEE = "terminee";

    public function label(): string
    {
        return match($this) {
            self::A_FAIRE => 'À faire',
            self::EN_COURS => 'En cours',
            self::TERMINEE => 'Terminée',
        };
    }
}