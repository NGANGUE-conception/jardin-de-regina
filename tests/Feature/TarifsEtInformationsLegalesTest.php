<?php

namespace Tests\Feature;

use App\Support\Tarification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tarif horaire unique et exemple de reste à charge (page Tarifs), date d'ouverture, identité légale.
 */
class TarifsEtInformationsLegalesTest extends TestCase
{
    use RefreshDatabase;

    public function test_exemple_de_reste_a_charge_a_temps_plein(): void
    {
        $tarification = app(Tarification::class);

        // 8h30 – 18h30, 5 jours par semaine : 10 h × 5 × 52 / 12 ≈ 216,67 h par mois, à 10 €/h.
        $this->assertSame(10.0, $tarification->heuresParJour());
        $this->assertSame(2166.67, $tarification->coutMensuelTempsPlein());

        $exemples = $tarification->exemplesTempsPlein();
        $this->assertCount(3, $exemples);
        $this->assertSame([null, 24333], [$exemples[0]['revenus_min'], $exemples[0]['revenus_max']]);
        $this->assertSame(992.12, $exemples[0]['cmg']);
        // Crédit d'impôt : 50 % de 3 500 € par an au maximum, soit 145,83 € par mois.
        $this->assertSame(145.83, $exemples[0]['credit_impot']);
        $this->assertSame(1028.72, $exemples[0]['reste']);
        $this->assertSame([54075, null], [$exemples[2]['revenus_min'], $exemples[2]['revenus_max']]);
        $this->assertSame(1302.43, $exemples[2]['reste']);
    }

    public function test_l_aide_de_la_caf_ne_depasse_jamais_85_pourcent_du_cout(): void
    {
        config(['creche.tarif_horaire' => 4.0]);

        $tarification = app(Tarification::class);
        $plafond = round($tarification->coutMensuelTempsPlein() * 0.85, 2);

        foreach ($tarification->exemplesTempsPlein() as $exemple) {
            $this->assertLessThanOrEqual($plafond, $exemple['cmg']);
        }
    }

    public function test_la_page_tarifs_affiche_le_tarif_unique_et_l_exemple(): void
    {
        // Montants au format de la langue (espaces insécables en français : « 1 029 € »).
        $this->get('/tarifs')
            ->assertOk()
            ->assertSee('Tarif horaire unique')
            ->assertSee(Tarification::euros(10).'</span>', false)
            ->assertSee('≈ '.Tarification::euros(1028.72), false)
            ->assertDontSee('[xx,xx €]', false);
        $this->assertSame('1 029 €', str_replace(["\u{202F}", "\u{00A0}"], ' ', Tarification::euros(1028.72)));

        $this->get('/en/tarifs')
            ->assertOk()
            ->assertSee('€10')
            ->assertSee('≈ €1,029', false);
    }

    public function test_la_date_d_ouverture_s_affiche_seulement_avant_l_ouverture(): void
    {
        $this->travelTo('2026-10-15 10:00');
        $this->get('/tarifs')->assertSee('Ouverture le 30 octobre 2026');

        $this->travelTo('2026-11-02 10:00');
        $this->get('/tarifs')->assertDontSee('Ouverture le')->assertSee('12 places · Alfortville');
    }

    public function test_les_mentions_legales_affichent_l_identite_de_la_societe(): void
    {
        $this->get('/mentions-legales')
            ->assertOk()
            ->assertSee('LE JARDIN DE REGINA (SAS)')
            ->assertSee('104 174 685 / 104 174 685 00016')
            ->assertSee('TEF GROUP INVEST (SAS), représentée par', false)
            ->assertSee('Adresse de l&#039;établissement', false)
            ->assertDontSee('Direction pédagogique')
            ->assertDontSee('[Numéro SIRET à compléter]');
    }
}
