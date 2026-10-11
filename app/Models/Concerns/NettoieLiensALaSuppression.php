<?php

namespace App\Models\Concerns;

use App\Models\SiteVisitForm;
use App\Models\Visite;

/**
 * À la suppression d'un dossier (pré-inscription, demande entreprise, candidature, message) :
 *  - les rendez-vous du Journal des visites liés à ce dossier sont conservés, mais détachés du dossier ;
 *  - le lien entre le dossier et la visite du site qui l'a envoyé est effacé
 *    (la visite elle-même reste dans les statistiques, de façon anonyme).
 */
trait NettoieLiensALaSuppression
{
    public static function bootNettoieLiensALaSuppression(): void
    {
        static::deleting(function ($dossier) {
            Visite::where('visitable_type', $dossier->getMorphClass())
                ->where('visitable_id', $dossier->getKey())
                ->update(['visitable_type' => null, 'visitable_id' => null]);

            SiteVisitForm::where('formulaire_type', $dossier->getMorphClass())
                ->where('formulaire_id', $dossier->getKey())
                ->delete();
        });
    }
}
