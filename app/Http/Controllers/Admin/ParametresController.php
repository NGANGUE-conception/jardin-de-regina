<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * Paramètres de l'espace admin.
 *
 * Changer l'email de connexion ou le mot de passe exige d'avoir vérifié son identité
 * (en retapant son mot de passe) dans les 10 dernières minutes. La vérification est
 * effacée après chaque changement réussi.
 */
class ParametresController extends Controller
{
    private const SESSION_VERIFICATION = 'parametres.identite_verifiee_a';

    private const DUREE_VERIFICATION_SECONDES = 10 * 60;

    public function index(Request $request)
    {
        $verifieeA = (int) $request->session()->get(self::SESSION_VERIFICATION, 0);
        $expireA = $verifieeA + self::DUREE_VERIFICATION_SECONDES;

        return view('pages.admin.parametres', [
            'identiteVerifiee' => $this->identiteVerifiee($request),
            'verificationExpireDans' => max(0, $expireA - now()->getTimestamp()),
        ]);
    }

    public function verifierIdentite(Request $request)
    {
        $request->validateWithBag('identite', [
            'verification_identite' => ['required', 'current_password'],
        ], [
            'verification_identite.required' => 'Merci de taper votre mot de passe actuel.',
            'verification_identite.current_password' => 'Mot de passe incorrect. Votre identité n\'a pas pu être vérifiée.',
        ]);

        $request->session()->put(self::SESSION_VERIFICATION, now()->getTimestamp());

        return redirect()
            ->route('admin.parametres', ['onglet' => 'compte'])
            ->with('success', 'Identité vérifiée : vous pouvez modifier votre email ou votre mot de passe pendant 10 minutes.');
    }

    public function verrouiller(Request $request)
    {
        $request->session()->forget(self::SESSION_VERIFICATION);

        return redirect()->route('admin.parametres', ['onglet' => 'compte']);
    }

    public function updateEmail(Request $request)
    {
        if (! $this->identiteVerifiee($request)) {
            return $this->verificationRequise();
        }

        $user = $request->user();
        $request->merge(['email' => strtolower(trim((string) $request->input('email')))]);

        $validated = $request->validateWithBag('emailConnexion', [
            'email' => [
                'required',
                'email',
                'max:190',
                Rule::notIn([$user->email]),
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ], [
            'email.required' => 'Merci de saisir la nouvelle adresse email.',
            'email.email' => "Cette adresse email n'est pas valide.",
            'email.max' => 'Cette adresse email est trop longue.',
            'email.not_in' => "C'est déjà votre adresse de connexion actuelle.",
            'email.unique' => 'Cette adresse est déjà utilisée par un autre compte.',
        ]);

        $user->forceFill(['email' => $validated['email']])->save();

        $request->session()->forget(self::SESSION_VERIFICATION);

        return redirect()
            ->route('admin.parametres', ['onglet' => 'compte'])
            ->with('success', 'Adresse de connexion modifiée : utilisez désormais '.$validated['email'].' pour vous connecter.');
    }

    public function updatePassword(Request $request)
    {
        if (! $this->identiteVerifiee($request)) {
            return $this->verificationRequise();
        }

        $user = $request->user();

        $validated = $request->validateWithBag('motDePasse', [
            'password' => [
                'required',
                'confirmed',
                Password::min(12)->mixedCase()->numbers()->symbols(),
                function (string $attribut, mixed $valeur, Closure $echec) use ($user) {
                    if (is_string($valeur) && Hash::check($valeur, $user->password)) {
                        $echec("Le nouveau mot de passe doit être différent de l'actuel.");
                    }
                },
            ],
        ], [
            'password.required' => 'Merci de saisir un nouveau mot de passe.',
            'password.confirmed' => 'La confirmation ne correspond pas au nouveau mot de passe.',
            'password.min' => 'Le nouveau mot de passe doit contenir au moins 12 caractères.',
            'password.mixed' => 'Le nouveau mot de passe doit contenir au moins une majuscule et une minuscule.',
            'password.numbers' => 'Le nouveau mot de passe doit contenir au moins un chiffre.',
            'password.symbols' => 'Le nouveau mot de passe doit contenir au moins un symbole (ex. ! ? @ # %).',
        ]);

        // Le modèle User chiffre automatiquement le mot de passe (cast "hashed").
        // Le nouveau jeton "se souvenir de moi" invalide les connexions mémorisées sur les autres appareils.
        $user->forceFill([
            'password' => $validated['password'],
            'remember_token' => Str::random(60),
        ])->save();

        // Déconnecte les autres appareils (sessions stockées en base), en gardant la session actuelle.
        if (config('session.driver') === 'database') {
            DB::table(config('session.table', 'sessions'))
                ->where('user_id', $user->getAuthIdentifier())
                ->where('id', '!=', $request->session()->getId())
                ->delete();
        }

        $request->session()->forget(self::SESSION_VERIFICATION);
        $request->session()->regenerate();

        return redirect()
            ->route('admin.parametres', ['onglet' => 'compte'])
            ->with('success', 'Mot de passe modifié. Les autres appareils connectés ont été déconnectés.');
    }

    private function identiteVerifiee(Request $request): bool
    {
        $verifieeA = (int) $request->session()->get(self::SESSION_VERIFICATION, 0);

        return $verifieeA > 0 && now()->getTimestamp() - $verifieeA < self::DUREE_VERIFICATION_SECONDES;
    }

    private function verificationRequise()
    {
        return redirect()
            ->route('admin.parametres', ['onglet' => 'compte'])
            ->withErrors(['verification_identite' => 'Pour des raisons de sécurité, vérifiez d\'abord votre identité (ou à nouveau, après 10 minutes).'], 'identite');
    }
}
