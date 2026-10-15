<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fullname' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:190'],
            'phone' => ['required', 'string', 'max:30'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:3000'],
            'rgpd_consent' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'rgpd_consent.accepted' => __("Vous devez accepter l'utilisation de vos données pour envoyer votre message."),
        ];
    }
}
