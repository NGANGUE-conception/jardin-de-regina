<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCorporateRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:150'],
            'sector' => ['nullable', 'string', 'max:150'],
            'employees_count' => ['nullable', 'string', 'max:100'],
            'berceaux_count' => ['nullable', 'string', 'max:100'],
            'contact_name' => ['required', 'string', 'max:150'],
            'contact_role' => ['nullable', 'string', 'max:150'],
            'contact_email' => ['required', 'email', 'max:190'],
            'contact_tel' => ['required', 'string', 'max:30'],
            'message' => ['nullable', 'string', 'max:3000'],
            'rgpd_consent' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'rgpd_consent.accepted' => __("Vous devez accepter l'utilisation de vos données pour envoyer votre demande."),
        ];
    }
}
