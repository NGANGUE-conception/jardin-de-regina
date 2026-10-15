<?php

namespace App\Support;

use Illuminate\Support\Number;

/**
 * Tarif de la crèche et exemple de reste à charge pour les familles (page Tarifs).
 *
 * Tarification PAJE : le même tarif horaire pour toutes les familles ; c'est l'aide de la CAF (CMG)
 * qui varie selon les revenus. Les montants viennent de config/creche.php.
 */
class Tarification
{
    public function __construct(private Etablissement $etablissement) {}

    public function tarifHoraire(): float
    {
        return (float) config('creche.tarif_horaire');
    }

    /** Ex. "10 €" (en anglais : "€10") */
    public function tarifHoraireLisible(): string
    {
        return self::euros($this->tarifHoraire(), decimales: fmod($this->tarifHoraire(), 1.0) == 0.0 ? 0 : 2);
    }

    /** Heures d'accueil par mois à temps plein : 5 jours × amplitude d'ouverture × 52 semaines / 12 mois. */
    public function heuresMensuellesTempsPlein(): float
    {
        return round($this->heuresBrutesTempsPlein(), 2);
    }

    public function coutMensuelTempsPlein(): float
    {
        return round($this->heuresBrutesTempsPlein() * $this->tarifHoraire(), 2);
    }

    /** Durée d'ouverture d'une journée, en heures (ex. 10 pour 8h30 – 18h30). */
    public function heuresParJour(): float
    {
        return ($this->minutes($this->etablissement->fermetureMachine()) - $this->minutes($this->etablissement->ouvertureMachine())) / 60;
    }

    private function heuresBrutesTempsPlein(): float
    {
        return $this->heuresParJour() * 5 * 52 / 12;
    }

    /**
     * Exemple de reste à charge mensuel à temps plein, pour chaque tranche de revenus du CMG.
     *
     * @return array<int, array{revenus_min: int|null, revenus_max: int|null, cmg: float, apres_cmg: float, credit_impot: float, reste: float}>
     */
    public function exemplesTempsPlein(): array
    {
        $cout = $this->coutMensuelTempsPlein();
        $plafondCmg = $cout * 0.85; // la CAF ne prend jamais en charge plus de 85 % du coût
        $credit = config('creche.credit_impot');
        $exemples = [];
        $revenusMin = null;

        foreach (config('creche.cmg') as $tranche) {
            $cmg = min((float) $tranche['montant'], $plafondCmg);
            $apresCmg = $cout - $cmg;
            $creditMensuel = min($apresCmg * 12, (float) $credit['plafond_depenses']) * (float) $credit['taux'] / 12;

            $exemples[] = [
                'revenus_min' => $revenusMin,
                'revenus_max' => $tranche['revenus_jusqu_a'],
                'cmg' => round($cmg, 2),
                'apres_cmg' => round($apresCmg, 2),
                'credit_impot' => round($creditMensuel, 2),
                'reste' => round($apresCmg - $creditMensuel, 2),
            ];
            $revenusMin = $tranche['revenus_jusqu_a'];
        }

        return $exemples;
    }

    /** Montant en euros dans la langue du site : "1 029 €" en français, "€1,029" en anglais. */
    public static function euros(float $montant, int $decimales = 0): string
    {
        return Number::currency($montant, in: 'EUR', locale: app()->getLocale() === 'en' ? 'en_GB' : 'fr_FR', precision: $decimales);
    }

    private function minutes(string $heure): int
    {
        [$h, $m] = array_pad(explode(':', $heure), 2, '0');

        return (int) $h * 60 + (int) $m;
    }
}
