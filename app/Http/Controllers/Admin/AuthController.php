<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        // Déjà connecté : on l'indique clairement au lieu d'ouvrir le tableau de bord sans explication.
        if (Auth::check()) {
            return redirect()
                ->route('admin')
                ->with('success', 'Vous êtes déjà connecté en tant que '.Auth::user()->email.'. Pour changer de compte, déconnectez-vous en bas du menu.');
        }

        return view('pages.admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Une nouvelle tentative de connexion ferme d'abord la session déjà ouverte (ex. page de connexion
        // restée dans l'historique du navigateur) : un mauvais mot de passe ne doit jamais aboutir au tableau de bord.
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin'));
        }

        return back()
            ->withErrors(['email' => 'Identifiants incorrects. Vérifiez votre email et votre mot de passe.'])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
