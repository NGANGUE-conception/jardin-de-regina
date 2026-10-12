<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\MotDePasse;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

/**
 * « Mot de passe oublié » de l'espace admin :
 *  1. l'administrateur indique son email et reçoit un lien (valable 60 minutes, utilisable une seule fois) ;
 *  2. le lien ouvre une page où il choisit un nouveau mot de passe, puis il est connecté.
 *
 * Par sécurité, la réponse est la même que l'email existe ou non : impossible de deviner l'adresse de l'admin.
 */
class MotDePasseOublieController extends Controller
{
    private const MESSAGE_ENVOI = 'Si cette adresse correspond à un compte administrateur, un email contenant un lien de réinitialisation vient d\'être envoyé. Pensez à vérifier vos courriers indésirables.';

    private const MESSAGE_LIEN_INVALIDE = 'Ce lien de réinitialisation n\'est plus valable : il a expiré ou a déjà été utilisé. Faites une nouvelle demande ci-dessous.';

    public function demande()
    {
        return view('pages.admin.mot-de-passe-oublie', [
            'dureeMinutes' => (int) config('auth.passwords.users.expire'),
        ]);
    }

    public function envoyerLien(Request $request)
    {
        $request->merge(['email' => strtolower(trim((string) $request->input('email')))]);
        $request->validate(['email' => ['required', 'email', 'max:190']]);

        // Le résultat (compte inconnu, demande trop rapprochée...) n'est volontairement pas communiqué.
        Password::sendResetLink($request->only('email'));

        return back()->with('statut', self::MESSAGE_ENVOI);
    }

    public function formulaire(Request $request, string $token)
    {
        $email = strtolower((string) $request->query('email'));
        $utilisateur = User::where('email', $email)->first();

        // Lien expiré, déjà utilisé ou trafiqué : on le dit tout de suite, plutôt qu'après la saisie du mot de passe.
        if (! $utilisateur || ! Password::tokenExists($utilisateur, $token)) {
            return redirect()
                ->route('admin.password.request')
                ->withErrors(['email' => self::MESSAGE_LIEN_INVALIDE]);
        }

        return view('pages.admin.reinitialiser-mot-de-passe', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    public function reinitialiser(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => MotDePasse::regles(User::where('email', $request->input('email'))->first()),
        ], MotDePasse::messages());

        $statut = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $utilisateur, string $nouveau) {
                // Toutes les sessions sont fermées (y compris un appareil oublié ouvert) ; on reconnecte ensuite celui-ci.
                MotDePasse::changer($utilisateur, $nouveau);
                event(new PasswordReset($utilisateur));
            }
        );

        if ($statut !== Password::PASSWORD_RESET) {
            return redirect()
                ->route('admin.password.request')
                ->withErrors(['email' => self::MESSAGE_LIEN_INVALIDE]);
        }

        Auth::login(User::where('email', $request->input('email'))->firstOrFail());
        $request->session()->regenerate();

        return redirect()
            ->route('admin')
            ->with('success', 'Votre nouveau mot de passe est enregistré. Les autres appareils ont été déconnectés.');
    }
}
