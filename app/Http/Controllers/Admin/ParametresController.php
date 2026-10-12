<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Etablissement;
use App\Support\MotDePasse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Throwable;

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

    /**
     * Coordonnées de l'établissement (téléphone, email, adresse, horaires, gestion), affichées sur tout le site.
     */
    public function updateEtablissement(Request $request, Etablissement $etablissement)
    {
        $request->merge([
            'email' => strtolower(trim((string) $request->input('email'))),
            'telephone' => trim((string) $request->input('telephone')),
            'code_postal' => preg_replace('/\s/', '', (string) $request->input('code_postal')),
        ]);

        $donnees = $request->validateWithBag('etablissement', [
            'telephone' => ['required', 'string', 'regex:/^(?:\+33\s?|0)[1-9](?:[\s.\-]?\d{2}){4}$/'],
            'email' => ['required', 'email', 'max:190'],
            'rue' => ['required', 'string', 'max:150'],
            'code_postal' => ['required', 'regex:/^\d{5}$/'],
            'ville' => ['required', 'string', 'max:100'],
            'ouverture' => ['required', 'date_format:H:i'],
            'fermeture' => ['required', 'date_format:H:i', 'after:ouverture'],
            'gestionnaire' => ['required', 'string', 'max:150'],
            'representant' => ['required', 'string', 'max:150'],
        ], [
            'telephone.regex' => 'Merci d\'indiquer un numéro de téléphone français valide (ex. 07 67 64 61 84).',
            'code_postal.regex' => 'Le code postal doit contenir 5 chiffres.',
            'fermeture.after' => 'L\'heure de fermeture doit être après l\'heure d\'ouverture.',
        ], [
            'telephone' => 'le téléphone',
            'email' => 'l\'email de contact',
            'rue' => 'le numéro et la rue',
            'code_postal' => 'le code postal',
            'ville' => 'la ville',
            'ouverture' => 'l\'heure d\'ouverture',
            'fermeture' => 'l\'heure de fermeture',
            'gestionnaire' => 'la société gestionnaire',
            'representant' => 'le représentant légal',
        ]);

        $donnees['telephone'] = self::formaterTelephone($donnees['telephone']);
        $donnees['rue'] = trim($donnees['rue']);
        $donnees['ville'] = trim($donnees['ville']);

        // Nouvelle adresse : on recalcule la position GPS (pour Google Maps) via la Base Adresse Nationale.
        $avertissement = null;
        $adresseModifiee = $donnees['rue'] !== $etablissement->rue()
            || $donnees['code_postal'] !== $etablissement->codePostal()
            || $donnees['ville'] !== $etablissement->ville();

        if ($adresseModifiee) {
            $position = $this->geolocaliser($donnees['rue'], $donnees['code_postal'], $donnees['ville']);

            if ($position) {
                [$donnees['latitude'], $donnees['longitude']] = $position;
            } else {
                $avertissement = 'L\'adresse a été enregistrée, mais sa position n\'a pas pu être retrouvée automatiquement : vérifiez l\'orthographe de la rue et de la ville. La position précédente reste utilisée pour Google Maps.';
            }
        }

        $etablissement->enregistrer($donnees);

        return redirect()
            ->route('admin.parametres')
            ->with('success', 'Coordonnées de l\'établissement mises à jour sur tout le site.')
            ->with('avertissement', $avertissement);
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
            'password' => MotDePasse::regles($user),
        ], MotDePasse::messages());

        // Garde cet appareil connecté, déconnecte les autres.
        MotDePasse::changer($user, $validated['password'], $request->session()->getId());

        $request->session()->forget(self::SESSION_VERIFICATION);
        $request->session()->regenerate();

        return redirect()
            ->route('admin.parametres', ['onglet' => 'compte'])
            ->with('success', 'Mot de passe modifié. Les autres appareils connectés ont été déconnectés.');
    }

    /** "0767646184", "+33 7 67 64 61 84"... → "07 67 64 61 84" */
    private static function formaterTelephone(string $telephone): string
    {
        $chiffres = preg_replace('/\D/', '', $telephone);
        if (str_starts_with($chiffres, '33')) {
            $chiffres = '0'.substr($chiffres, 2);
        }

        return trim(chunk_split($chiffres, 2, ' '));
    }

    /**
     * Position GPS d'une adresse française (Base Adresse Nationale, service public gratuit).
     *
     * @return array{0: float, 1: float}|null [latitude, longitude], ou null si l'adresse n'est pas trouvée avec certitude
     */
    private function geolocaliser(string $rue, string $codePostal, string $ville): ?array
    {
        try {
            $reponse = Http::timeout(5)->get('https://api-adresse.data.gouv.fr/search/', [
                'q' => "$rue $codePostal $ville",
                'postcode' => $codePostal,
                'limit' => 1,
            ]);
            $resultat = $reponse->json('features.0');

            if (! $reponse->successful() || ! $resultat || ($resultat['properties']['score'] ?? 0) < 0.5) {
                return null;
            }

            [$longitude, $latitude] = $resultat['geometry']['coordinates'];

            return [round((float) $latitude, 6), round((float) $longitude, 6)];
        } catch (Throwable $e) {
            report($e);

            return null;
        }
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
