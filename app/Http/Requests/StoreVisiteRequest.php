<?php

namespace App\Http\Requests;

use App\Models\Visite;
use Illuminate\Foundation\Http\FormRequest;

class StoreVisiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'visit_date' => ['required', 'date'],
            'visit_time' => ['required', 'date_format:H:i'],
            'visitor_name' => ['required', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:190'],
            'type' => ['required', 'in:'.implode(',', array_keys(Visite::TYPES))],
            'dossier' => ['nullable', 'regex:/^(pre_registration|corporate_request|job_application):\d+$/'],
            'status' => ['nullable', 'in:'.implode(',', Visite::STATUTS)],
            'internal_note' => ['nullable', 'string', 'max:3000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'visit_date' => 'la date',
            'visit_time' => "l'heure",
            'visitor_name' => 'le nom du visiteur',
            'type' => 'le type de rendez-vous',
            'dossier' => 'le dossier lié',
        ];
    }
}
