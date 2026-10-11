<?php

namespace App\Models;

use App\Models\Concerns\NettoieLiensALaSuppression;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory, NettoieLiensALaSuppression;

    protected $fillable = [
        'message_number',
        'fullname',
        'email',
        'phone',
        'subject',
        'message',
        'status',
    ];

    public const STATUTS = ['non_lu', 'lu', 'repondu'];

    public static function genererNumero(): string
    {
        $dernier = static::where('message_number', 'like', '#JR-MSG-%')
            ->orderByDesc('id')
            ->first();

        $prochain = 1;
        if ($dernier) {
            $prochain = (int) substr($dernier->message_number, -4) + 1;
        }

        return sprintf('#JR-MSG-%04d', $prochain);
    }

    public function toAdminArray(): array
    {
        return [
            'id' => $this->message_number,
            'dbId' => $this->id,
            'nom' => $this->fullname,
            'email' => $this->email,
            'tel' => $this->phone,
            'objet' => $this->subject,
            'message' => $this->message,
            'statut' => $this->status,
            'dateLabel' => ucfirst($this->created_at->diffForHumans()),
        ];
    }
}
