<?php

namespace App\Support;

use App\Models\ContactMessage;
use App\Models\CorporateRequest;
use App\Models\JobApplication;
use App\Models\PreRegistration;

/**
 * Nombre d'éléments à traiter dans l'espace admin (badges du menu et cloche de la barre du haut).
 * Calculé une seule fois par page, même si plusieurs composants l'utilisent.
 */
class CompteursAdmin
{
    private static ?array $compteurs = null;

    /**
     * @return array{pre-inscriptions: int, entreprises: int, candidatures: int, messages: int}
     */
    public static function aTraiter(): array
    {
        return self::$compteurs ??= [
            'pre-inscriptions' => PreRegistration::where('status', 'nouvelle')->count(),
            'entreprises' => CorporateRequest::where('status', 'nouvelle')->count(),
            'candidatures' => JobApplication::where('status', 'nouvelle')->count(),
            'messages' => ContactMessage::where('status', 'non_lu')->count(),
        ];
    }

    public static function total(): int
    {
        return array_sum(self::aTraiter());
    }
}
