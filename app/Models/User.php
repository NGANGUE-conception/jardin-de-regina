<?php

namespace App\Models;

use App\Mail\ReinitialisationMotDePasse;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Support\EnvoiEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    /**
     * « Mot de passe oublié » : envoie le lien de réinitialisation avec l'email aux couleurs de la crèche
     * (au lieu de l'email anglais par défaut de Laravel).
     */
    public function sendPasswordResetNotification(#[\SensitiveParameter] $token): void
    {
        $lien = route('admin.password.reset', ['token' => $token, 'email' => $this->email]);

        EnvoiEmail::envoyer($this->email, new ReinitialisationMotDePasse(
            lien: $lien,
            nom: $this->name ?: 'Direction',
            dureeMinutes: (int) config('auth.passwords.users.expire'),
        ));
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
