{{--
    Gabarit des pages d'erreur (404, 419, 429, 500, 503...).
    Volontairement autonome : styles intégrés, aucune requête en base, aucun fichier compilé par Vite.
    Ainsi, la page s'affiche même si la base de données ou les fichiers du site sont en panne.
--}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title>@yield('titre') — Le Jardin de Régina</title>
    <style>
        :root {
            --primary: #217B79;
            --primary-deep: #12414D;
            --coral: #D9451F;
            --cream: #FFF9F2;
            --mint: #E3F2F1;
            --peach: #FDE8E1;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            background: var(--cream);
            color: var(--primary-deep);
            font-family: 'Nunito', system-ui, -apple-system, 'Segoe UI', sans-serif;
            position: relative;
            overflow-x: hidden;
        }
        .bulle { position: fixed; border-radius: 50%; filter: blur(60px); z-index: 0; pointer-events: none; }
        .bulle-1 { width: 380px; height: 380px; background: var(--mint); top: -120px; right: -80px; }
        .bulle-2 { width: 300px; height: 300px; background: var(--peach); bottom: -100px; left: -80px; }
        .carte {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 560px;
            background: #fff;
            border-radius: 32px;
            padding: 40px 32px;
            text-align: center;
            box-shadow: 0 20px 50px rgba(18, 65, 77, 0.08);
        }
        .logo { height: 56px; width: auto; margin: 0 auto 24px; display: block; }
        .code {
            display: inline-block;
            font-family: 'Chewy', 'Nunito', sans-serif;
            font-size: 15px;
            letter-spacing: 0.08em;
            color: var(--primary);
            background: var(--mint);
            padding: 6px 14px;
            border-radius: 999px;
            margin-bottom: 16px;
        }
        h1 {
            font-family: 'Chewy', 'Nunito', sans-serif;
            font-weight: 400;
            font-size: clamp(28px, 6vw, 38px);
            line-height: 1.15;
            margin: 0 0 14px;
        }
        p { font-size: 16px; line-height: 1.6; color: rgba(18, 65, 77, 0.75); margin: 0 0 12px; }
        .actions { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-top: 28px; }
        .bouton {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            height: 48px;
            padding: 0 22px;
            border-radius: 999px;
            font-family: inherit;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: 0;
            transition: transform 0.15s ease, opacity 0.15s ease;
        }
        .bouton:hover { transform: translateY(-2px); opacity: 0.92; }
        .bouton:focus-visible { outline: 3px solid var(--primary); outline-offset: 3px; }
        .principal { background: var(--coral); color: #fff; box-shadow: 0 8px 20px rgba(217, 69, 31, 0.25); }
        .secondaire { background: var(--mint); color: var(--primary-deep); }
        .contact {
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid var(--mint);
            font-size: 14px;
            color: rgba(18, 65, 77, 0.6);
        }
        .contact a { color: var(--primary); font-weight: 700; text-decoration: none; }
        .contact a:hover { text-decoration: underline; }
        @media (max-width: 480px) {
            .carte { padding: 32px 20px; border-radius: 24px; }
            .bouton { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>
    <div class="bulle bulle-1"></div>
    <div class="bulle bulle-2"></div>

    <main class="carte">
        <a href="{{ url('/') }}" aria-label="Retour à l'accueil du Jardin de Régina">
            <img src="{{ asset('images/logo.png') }}" alt="Le Jardin de Régina" class="logo">
        </a>

        <span class="code">@yield('code')</span>
        <h1>@yield('titre')</h1>

        @yield('message')

        <div class="actions">
            @yield('actions')
        </div>

        <div class="contact">
            Besoin d'aide ? Appelez-nous au <a href="tel:0767646184">07 67 64 61 84</a>
            ou écrivez à <a href="mailto:jardinderegina94@gmail.com">jardinderegina94@gmail.com</a>.
        </div>
    </main>
</body>
</html>
