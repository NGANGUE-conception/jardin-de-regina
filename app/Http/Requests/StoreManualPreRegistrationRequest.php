<?php

namespace App\Http\Requests;

use App\Models\PreRegistration;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Pré-inscription saisie par la direction dans l'espace admin (demande reçue par téléphone, sur place...).
 * Plus souple que le formulaire public : email du parent facultatif, pas de second parent ni d'adresse obligatoires.
 */
class StoreManualPreRegistrationRequest extends FormRequest
{
    /**
     * Sac d'erreurs dédié : permet de rouvrir automatiquement le tiroir de saisie
     * sans confondre ses erreurs avec celles d'autres formulaires de la page.
     */
    protected $errorBag = 'preInscriptionManuelle';

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'source' => ['required', Rule::in(array_keys(PreRegistration::SOURCES))],

            'child_last_name' => ['required', 'string', 'max:100'],
            'child_first_name' => ['required', 'string', 'max:100'],
            'child_gender' => ['required', 'in:Fille,Garçon,Autre / Non précisé'],
            'child_birthdate' => ['required', 'date'],
            'entry_date' => ['required', 'date'],
            'care_type' => ['required', 'in:regulier,occasionnel,urgence'],
            'care_days' => ['required_unless:care_type,urgence', 'nullable', 'array'],
            'care_days.*' => ['string', 'in:lundi,mardi,mercredi,jeudi,vendredi'],
            'special_needs_details' => ['nullable', 'string', 'max:2000'],

            'p1_civilite' => ['nullable', 'in:Madame,Monsieur,Autre'],
            'p1_nom' => ['required', 'string', 'max:100'],
            'p1_prenom' => ['required', 'string', 'max:100'],
            'p1_tel' => ['required', 'string', 'max:30'],
            'p1_email' => ['nullable', 'email', 'max:190'],
            'p1_cp' => ['nullable', 'string', 'max:10'],
            'p1_ville' => ['nullable', 'string', 'max:100'],

            'internal_note' => ['nullable', 'string', 'max:3000'],
            'envoyer_confirmation' => ['nullable', 'boolean'],
            'famille_informee' => ['accepted'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'famille_informee.accepted' => 'Merci de confirmer que la famille a été informée de l\'enregistrement de ses données.',
            'care_days.required_unless' => 'Merci de choisir au moins un jour de présence (sauf accueil d\'urgence).',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'source' => 'l\'origine de la demande',
            'child_gender' => 'le sexe de l\'enfant',
            'p1_civilite' => 'la civilité',
            'internal_note' => 'la note interne',
        ];
    }
}
