<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePreRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'child_last_name' => ['required', 'string', 'max:100'],
            'child_first_name' => ['required', 'string', 'max:100'],
            'child_gender' => ['required', 'string', 'max:50'],
            'child_birthdate' => ['required', 'date'],
            'entry_date' => ['required', 'date'],
            'care_type' => ['required', 'in:regulier,occasionnel,urgence'],
            'care_days' => ['nullable', 'array'],
            'care_days.*' => ['string', 'in:lundi,mardi,mercredi,jeudi,vendredi'],
            'special_needs' => ['nullable', 'boolean'],
            'special_needs_details' => ['nullable', 'string', 'max:2000'],

            'p1_civilite' => ['nullable', 'string', 'max:30'],
            'p1_nom' => ['required', 'string', 'max:100'],
            'p1_prenom' => ['required', 'string', 'max:100'],
            'p1_email' => ['required', 'email', 'max:190'],
            'p1_tel' => ['required', 'string', 'max:30'],
            'p1_fixe' => ['nullable', 'string', 'max:30'],
            'p1_profession' => ['nullable', 'string', 'max:150'],
            'p1_employeur' => ['nullable', 'string', 'max:150'],
            'p1_cp' => ['nullable', 'string', 'max:10'],
            'p1_ville' => ['nullable', 'string', 'max:100'],
            'p1_adresse' => ['nullable', 'string', 'max:255'],
            'single_parent' => ['nullable', 'boolean'],

            'p2_civilite' => ['nullable', 'string', 'max:30'],
            'p2_nom' => ['nullable', 'string', 'max:100'],
            'p2_prenom' => ['nullable', 'string', 'max:100'],
            'p2_email' => ['nullable', 'email', 'max:190'],
            'p2_tel' => ['nullable', 'string', 'max:30'],
            'p2_profession' => ['nullable', 'string', 'max:150'],
            'p2_employeur' => ['nullable', 'string', 'max:150'],

            'message' => ['nullable', 'string', 'max:3000'],
            'newsletter_opt_in' => ['nullable', 'boolean'],
            'rgpd_consent' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'rgpd_consent.accepted' => 'Vous devez accepter le traitement de vos données pour envoyer votre demande.',
        ];
    }

    public function attributes(): array
    {
        return [
            'child_last_name' => "le nom de l'enfant",
            'child_first_name' => "le prénom de l'enfant",
            'child_gender' => "le sexe de l'enfant",
            'child_birthdate' => 'la date de naissance',
            'entry_date' => "la date d'entrée souhaitée",
            'p1_nom' => 'votre nom',
            'p1_prenom' => 'votre prénom',
            'p1_email' => 'votre adresse email',
            'p1_tel' => 'votre téléphone',
        ];
    }
}
