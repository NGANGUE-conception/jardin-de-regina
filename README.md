# Le Jardin de Régina — site de la micro-crèche

Site public et espace de gestion de la micro-crèche écoresponsable **Le Jardin de Régina**,
4 bis rue des Écoles, 94140 Alfortville (réseau TEF Group Invest).

- **Site public** : présentation, projet pédagogique, fonctionnement, tarifs, berceaux entreprise,
  recrutement, contact, pré-inscription en ligne, pages légales, plan du site.
- **Espace admin** (`/admin`) : pré-inscriptions (dont la saisie manuelle des demandes reçues par
  téléphone), demandes des entreprises, candidatures, messages, journal des rendez-vous de visite,
  statistiques de visite du site, exports Excel, paramètres de l'établissement et du compte.

## Technologies

| Rôle | Outil |
|---|---|
| Serveur | PHP 8.5, Laravel 13 |
| Pages | Blade, Alpine.js (menus, carrousels, formulaires en étapes), Tailwind CSS 4 |
| Compilation des fichiers CSS/JS | Vite (`npm run build`) |
| Base de données | MySQL (XAMPP en local) |
| Polices | Chewy et Nunito, hébergées sur le site (aucun appel à Google Fonts) |
| Exports Excel | maatwebsite/excel |
| Tests | PHPUnit (base dédiée `jardin_de_regina_test`) |

## Installation sur un poste de développement (Windows + XAMPP)

Prérequis : XAMPP (PHP 8.5 avec les extensions `gd` et `fileinfo` activées dans `php.ini`, MySQL),
Composer, Node.js.

```powershell
git clone https://github.com/NGANGUE-conception/jardin-de-regina.git
cd jardin-de-regina
composer install
npm install
copy .env.example .env
php artisan key:generate
```

1. Dans phpMyAdmin, créer les bases `jardin_de_regina` (le site) et `jardin_de_regina_test` (les tests),
   en `utf8mb4_unicode_ci`.
2. Créer les tables puis le compte de la direction :
   ```powershell
   php artisan migrate
   php artisan admin:creer
   ```
3. Lancer le site (deux terminaux) :
   ```powershell
   php artisan serve      # le site : http://localhost:8000
   npm run dev            # recharge automatiquement le CSS/JS pendant qu'on modifie le code
   ```
   Sans `npm run dev`, lancer une fois `npm run build`.

En local, les emails ne partent pas : ils sont écrits dans `storage/logs/laravel.log` (`MAIL_MAILER=log`).

> **Tester sur un téléphone** (même Wi-Fi) : arrêter `npm run dev`, lancer `npm run build`, puis
> `php artisan serve --host=0.0.0.0 --port=8000` et ouvrir `http://<adresse IPv4 du PC>:8000`
> (adresse donnée par `ipconfig`). Avec `npm run dev` actif, le téléphone n'a pas la mise en forme.

## Commandes utiles

| Commande | Effet |
|---|---|
| `php artisan test` | Lance tous les tests automatiques (base `jardin_de_regina_test`, jamais la vraie base) |
| `php artisan admin:creer` | Crée un compte pour l'espace admin (mot de passe demandé de façon masquée) |
| `php artisan site:images` | Régénère favicon, icônes, image de partage (`images/partage.jpg`), logo WebP et photos WebP à partir de `public/images/` — à relancer après un changement de logo ou de photo |
| `php artisan visites:purger` | Supprime les statistiques de visite de plus de 25 mois (lancée automatiquement chaque nuit à 3 h, voir « Tâche planifiée ») |
| `npm run build` | Compile le CSS et le JavaScript dans `public/build` |
| `vendor/bin/pint` | Met le code PHP en forme selon les conventions du projet |

## Organisation du code (repères)

| Où | Quoi |
|---|---|
| `routes/web.php` | Toutes les adresses du site et de l'admin |
| `config/creche.php` | Coordonnées par défaut de l'établissement (modifiables ensuite dans Admin → Paramètres) |
| `app/Support/Etablissement.php` | Coordonnées en vigueur, disponibles dans toutes les vues sous `$etab` |
| `app/Support/Referencement.php`, `PagesPubliques.php` | Adresse canonique, données pour Google (JSON-LD), sitemap.xml, plan du site |
| `app/Support/SuiviVisites.php`, `StatistiquesVisites.php` | Mesure d'audience anonyme (exemptée de consentement CNIL) |
| `app/Support/EnvoiEmail.php` | Envoi des emails qui ne bloque jamais un formulaire si le service d'email est en panne |
| `app/Support/MotDePasse.php` | Règles du mot de passe admin |
| `app/Http/Middleware/` | Anti-spam des formulaires, suivi des visites, en-têtes de sécurité |
| `resources/views/pages/` | Pages publiques ; `pages/admin/` pour l'admin |
| `resources/views/components/` | Éléments réutilisés (en-tête, pied de page, champs d'erreur, tiroir de pré-inscription manuelle...) |
| `resources/views/errors/` | Pages d'erreur (404, 419, 429, 500, 503...) |
| `tests/Feature/` | Tests automatiques |

## Version anglaise du site

Les boutons **FR / EN** du menu changent la langue du site public (choix mémorisé un an dans le cookie
`jr_langue`). L'espace admin reste en français.

- Dans les pages, chaque texte est écrit en français dans `__('...')`, par exemple `{{ __('Nos tarifs') }}`.
- Sa traduction se trouve dans **`lang/en.json`** : `"Nos tarifs": "Our fees"`. Sans traduction, le texte reste en français.
- **Modifier un texte français = mettre à jour la ligne correspondante de `lang/en.json`** (le texte français sert de clé).
- Le test `TraductionAnglaiseTest` échoue si une phrase du site n'a pas de traduction : lancer `php artisan test` après chaque modification de texte.
- Les valeurs envoyées par les formulaires (objet du message, poste, civilité...) restent en français, pour que l'admin les lise toujours en français.
- Les pages légales en anglais indiquent que seule la version française fait foi.

## Mise en ligne (production)

### 1. Fichier `.env` du serveur
Partir de `.env.example` et changer au minimum :

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://www.<domaine-du-site>
LOG_STACK=daily
LOG_LEVEL=warning
SESSION_SECURE_COOKIE=true
DB_DATABASE=... / DB_USERNAME=... / DB_PASSWORD=...     # fournis par l'hébergeur
MAIL_MAILER=smtp
MAIL_SCHEME=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=... / MAIL_PASSWORD=...                   # identifiants SMTP Brevo
MAIL_FROM_ADDRESS=contact@<domaine-du-site>
MAIL_ADMIN_ADDRESS=<adresse qui reçoit les alertes>
```

Puis `php artisan key:generate` (une seule fois, à la première installation).
Hors production, `robots.txt` interdit l'indexation par Google : c'est voulu.

### 2. Installation des fichiers

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build          # ou compiler en local et envoyer le dossier public/build
php artisan migrate --force
php artisan admin:creer          # première installation uniquement
php artisan optimize
```

- La racine du site (document root) doit pointer sur le dossier **`public/`**, jamais sur la racine du projet.
- Les dossiers `storage/` et `bootstrap/cache/` doivent être accessibles en écriture.
- `public/.htaccess` active la compression et la mise en cache des fichiers (Apache).
- À chaque mise à jour : `git pull`, puis les commandes ci-dessus sauf `admin:creer`.

### 3. Tâche planifiée (cron)
Indispensable pour la purge automatique des statistiques (RGPD). Dans le panneau de l'hébergeur :

```
* * * * * cd /chemin/vers/jardin-de-regina && php artisan schedule:run >> /dev/null 2>&1
```

### 4. Emails (Brevo)
Valider le domaine dans Brevo et ajouter chez le registraire du domaine les enregistrements
**SPF**, **DKIM** et **DMARC** indiqués par Brevo, sinon les emails risquent d'arriver en spam.
Une panne d'envoi n'empêche jamais l'enregistrement d'une demande : elle est notée dans
`storage/logs/` (voir `app/Support/EnvoiEmail.php`).

### 5. Sauvegardes
Prévoir une sauvegarde automatique quotidienne de la base MySQL et du dossier `storage/app/private`
(CV des candidats), proposée par la plupart des hébergeurs.

### 6. Après la mise en ligne
- Mentions légales et politique de confidentialité : compléter l'hébergeur et ajouter Brevo (sous-traitant pour les emails).
- Déclarer le site dans Google Search Console (envoyer `https://<domaine>/sitemap.xml`).
- Créer la fiche Google Business Profile et la fiche monenfant.fr.

## Sécurité (déjà en place)

- Admin protégé par connexion, limitation des tentatives, déconnexion à la fermeture du navigateur.
- Changement d'email ou de mot de passe admin seulement après vérification d'identité (10 minutes).
- Formulaires publics : champ piège anti-robots, délai minimum de remplissage, 10 envois par heure maximum.
- CV stockés hors de la partie publique, téléchargeables uniquement depuis l'admin.
- En-têtes de sécurité (anti-clickjacking, Content-Security-Policy, HTTPS imposé en production).
- Mesure d'audience sans adresse IP, refus possible sur la page « Gestion des cookies ».
