<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:190'],
            'tel' => ['required', 'string', 'max:30'],
            'poste' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:3000'],
            'documents' => ['required', 'array', 'min:1'],
            'documents.*' => ['file', 'mimes:pdf,doc,docx', 'max:5120'],
            'rgpd_consent' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'documents.required' => __('Merci de joindre au moins un fichier (CV et/ou lettre de motivation).'),
            'documents.*.mimes' => __('Chaque fichier doit être au format PDF, DOC ou DOCX.'),
            'documents.*.max' => __('Chaque fichier ne doit pas dépasser 5 Mo.'),
            'rgpd_consent.accepted' => __("Vous devez accepter l'utilisation de vos données pour envoyer votre candidature."),
        ];
    }
}
