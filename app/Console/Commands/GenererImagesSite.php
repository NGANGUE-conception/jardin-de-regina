<?php

namespace App\Console\Commands;

use App\Support\Etablissement;
use GdImage;
use Illuminate\Console\Command;
use RuntimeException;

/**
 * Génère toutes les images techniques du site à partir de public/images/logo.png :
 *  - favicon.ico (16, 32, 48 px), favicon-96x96.png, apple-touch-icon.png,
 *    icônes 192 / 512 px et icône "maskable" pour le manifeste ;
 *  - l'image d'aperçu de partage (1200 × 630) utilisée par WhatsApp, Facebook, LinkedIn... ;
 *  - une version WebP allégée de chaque photo de public/images.
 *
 * À relancer quand un nouveau logo (ou de nouvelles photos) est fourni :
 *     php artisan site:images
 */
class GenererImagesSite extends Command
{
    protected $signature = 'site:images';

    protected $description = 'Génère le favicon, les icônes, l\'image de partage et les versions WebP des photos';

    /** Couleurs de la charte graphique. */
    private const CREME = [255, 249, 242];

    private const MENTHE = [227, 242, 241];

    private const PECHE = [253, 232, 225];

    private const SOLEIL = [255, 216, 116];

    private const CIEL = [169, 216, 234];

    private const TEAL_FONCE = [18, 65, 77];

    private const TEAL = [33, 123, 121];

    /** La silhouette de la petite fille commence à droite du texte du logo (voir logo.png). */
    private const DEBUT_SILHOUETTE_X = 476;

    /** Les pieds de la fillette s'arrêtent à cette ligne ; en dessous commence la colline du logo, coupée par le cadrage. */
    private const BAS_SILHOUETTE_Y = 301;

    /** Côté le plus long des photos WebP : les photos s'affichent sur 600 px maximum, 1 200 px restent nets sur les écrans haute définition. */
    private const TAILLE_MAX_PHOTO = 1200;

    /** Qualité WebP des photos (75 : différence invisible à l'œil, fichiers nettement plus légers). */
    private const QUALITE_PHOTO = 75;

    /** Hauteur du logo WebP : 3 fois sa plus grande hauteur d'affichage (64 px), pour rester net sur tous les écrans. */
    private const HAUTEUR_LOGO_WEBP = 192;

    public function handle(): int
    {
        $logo = $this->chargerPng(public_path('images/logo.png'));

        $this->genererIcones($logo);
        $this->genererImagePartage($logo);
        $this->genererPhotosWebp();
        $this->genererLogoWebp($logo);

        $this->newLine();
        $this->info('Images du site générées.');

        return self::SUCCESS;
    }

    private function genererIcones(GdImage $logo): void
    {
        $this->line('<comment>Icônes</comment> (silhouette du logo)');

        $silhouette = $this->silhouetteAvecColline($logo);

        // Favicon (onglets, résultats Google) : pastille arrondie aux couleurs du site, silhouette en blanc.
        // Dessinée en grand puis réduite, pour des contours lisses même en 16 px.
        $pastille = $this->pastille($silhouette, 512);
        $pngs = [];
        foreach ([16, 32, 48] as $taille) {
            $pngs[$taille] = $this->enPng($this->reduire($pastille, $taille));
        }
        file_put_contents(public_path('favicon.ico'), $this->construireIco($pngs));
        $this->ligneFichier('favicon.ico (16, 32, 48 px)');

        $this->enregistrerPng($this->reduire($pastille, 96), public_path('favicon-96x96.png'));

        // Icônes d'écran d'accueil (iPhone, Android) : fond crème, comme le site.
        $this->enregistrerPng($this->carre($silhouette, 180, 0.12, self::CREME), public_path('apple-touch-icon.png'));
        $this->enregistrerPng($this->carre($silhouette, 192, 0.12, self::CREME), public_path('icon-192.png'));
        $this->enregistrerPng($this->carre($silhouette, 512, 0.12, self::CREME), public_path('icon-512.png'));

        // "Maskable" : Android peut découper l'icône en rond, la silhouette doit rester dans la zone centrale.
        $this->enregistrerPng($this->carre($silhouette, 512, 0.22, self::CREME), public_path('icon-maskable-512.png'));
    }

    private function genererImagePartage(GdImage $logo): void
    {
        $this->line('<comment>Image de partage</comment> (1200 × 630)');

        $l = 1200;
        $h = 630;
        $image = imagecreatetruecolor($l, $h);
        imagealphablending($image, true);
        imagefill($image, 0, 0, $this->couleur($image, self::CREME));

        // Formes décoratives douces, comme sur le site.
        imagefilledellipse($image, 1110, 40, 560, 560, $this->couleur($image, self::MENTHE));
        imagefilledellipse($image, 70, 620, 460, 460, $this->couleur($image, self::PECHE));
        imagefilledellipse($image, 175, 120, 34, 34, $this->couleur($image, self::SOLEIL));
        imagefilledellipse($image, 1135, 585, 26, 26, $this->couleur($image, self::CIEL));
        imagefilledellipse($image, 1088, 612, 14, 14, $this->couleur($image, self::SOLEIL));

        // Logo complet, centré.
        $logoRecadre = $this->recadrer($logo, $this->boiteVisible($logo, 0));
        $largeurLogo = 700; // le logo d'origine fait 510 px de large : au-delà, il deviendrait flou
        $hauteurLogo = (int) round(imagesy($logoRecadre) * $largeurLogo / imagesx($logoRecadre));
        imagecopyresampled($image, $logoRecadre, (int) (($l - $largeurLogo) / 2), 80, 0, 0, $largeurLogo, $hauteurLogo, imagesx($logoRecadre), imagesy($logoRecadre));

        // Accroche (si une police TrueType est disponible sur la machine).
        $gras = $this->trouverPolice(['segoeuib.ttf', 'arialbd.ttf', 'DejaVuSans-Bold.ttf']);
        $normal = $this->trouverPolice(['segoeui.ttf', 'arial.ttf', 'DejaVuSans.ttf']);
        $yTexte = 80 + $hauteurLogo + 70;

        if ($gras) {
            $this->texteCentre($image, config('creche.slogan'), $gras, 38, $yTexte, self::TEAL_FONCE);
        }
        if ($normal) {
            $this->texteCentre($image, '10 semaines à 3 ans et plus  ·  '.app(Etablissement::class)->horaires().'  ·  Pré-inscription en ligne', $normal, 25, $yTexte + 50, self::TEAL);
        }
        if (! $gras || ! $normal) {
            $this->warn('  Aucune police TrueType trouvée : image générée sans texte d\'accroche.');
        }

        imagejpeg($image, public_path(config('creche.image_partage')), 88);
        $this->ligneFichier(config('creche.image_partage'));
    }

    private function genererPhotosWebp(): void
    {
        $this->line('<comment>Photos WebP</comment>');

        foreach (glob(public_path('images/*.jpg')) as $chemin) {
            if (realpath($chemin) === realpath(public_path(config('creche.image_partage')))) {
                continue;
            }

            $photo = imagecreatefromjpeg($chemin);
            $l = imagesx($photo);
            $h = imagesy($photo);
            $ratio = min(1, self::TAILLE_MAX_PHOTO / max($l, $h));

            if ($ratio < 1) {
                $redimensionnee = imagecreatetruecolor((int) round($l * $ratio), (int) round($h * $ratio));
                imagecopyresampled($redimensionnee, $photo, 0, 0, 0, 0, imagesx($redimensionnee), imagesy($redimensionnee), $l, $h);
                $photo = $redimensionnee;
            }

            $destination = preg_replace('/\.jpg$/', '.webp', $chemin);
            imagewebp($photo, $destination, self::QUALITE_PHOTO);

            $this->ligneFichier(sprintf(
                'images/%s  %d Ko → %d Ko%s',
                basename($destination),
                round(filesize($chemin) / 1024),
                round(filesize($destination) / 1024),
                $ratio < 1 ? sprintf(' (redimensionnée en %d × %d)', imagesx($photo), imagesy($photo)) : '',
            ));
        }
    }

    /**
     * Logo affiché sur les pages : WebP, recadré au plus juste et réduit à sa taille d'affichage
     * (le fichier logo.png d'origine, 670 px de large, reste utilisé pour Google et les emails).
     */
    private function genererLogoWebp(GdImage $logo): void
    {
        $this->line('<comment>Logo WebP</comment>');

        $recadre = $this->recadrer($logo, $this->boiteVisible($logo, 0));
        $hauteur = self::HAUTEUR_LOGO_WEBP;
        $largeur = (int) round(imagesx($recadre) * $hauteur / imagesy($recadre));
        $reduit = $this->toileTransparente($largeur, $hauteur);
        imagecopyresampled($reduit, $recadre, 0, 0, 0, 0, $largeur, $hauteur, imagesx($recadre), imagesy($recadre));
        imagesavealpha($reduit, true);

        imagewebp($reduit, public_path('images/logo.webp'), 90);
        $this->ligneFichier(sprintf('images/logo.webp  %d × %d, %d Ko (logo.png : %d Ko)', $largeur, $hauteur, round(filesize(public_path('images/logo.webp')) / 1024), round(filesize(public_path('images/logo.png')) / 1024)));
    }

    /* ---------------------------------------------------------------- Outils */

    private function chargerPng(string $chemin): GdImage
    {
        if (! is_file($chemin)) {
            throw new RuntimeException("Fichier introuvable : $chemin");
        }

        $image = imagecreatefrompng($chemin);
        imagesavealpha($image, true);

        return $image;
    }

    /**
     * Rectangle contenant les pixels réellement visibles (colorés et non transparents) à partir de la colonne $debutX.
     *
     * @return array{0: int, 1: int, 2: int, 3: int} [x, y, largeur, hauteur]
     */
    private function boiteVisible(GdImage $image, int $debutX, ?int $finY = null): array
    {
        $minX = $minY = PHP_INT_MAX;
        $maxX = $maxY = -1;

        for ($x = $debutX; $x < imagesx($image); $x++) {
            for ($y = 0; $y <= ($finY ?? imagesy($image) - 1); $y++) {
                $c = imagecolorsforindex($image, imagecolorat($image, $x, $y));
                $visible = $c['alpha'] < 100 && ($c['red'] < 225 || $c['green'] < 225 || $c['blue'] < 225);
                if ($visible) {
                    $minX = min($minX, $x);
                    $maxX = max($maxX, $x);
                    $minY = min($minY, $y);
                    $maxY = max($maxY, $y);
                }
            }
        }

        if ($maxX < 0) {
            throw new RuntimeException('Aucun pixel visible trouvé dans le logo.');
        }

        return [$minX, $minY, $maxX - $minX + 1, $maxY - $minY + 1];
    }

    private function recadrer(GdImage $image, array $boite): GdImage
    {
        [$x, $y, $l, $h] = $boite;
        $resultat = $this->toileTransparente($l, $h);
        imagecopy($resultat, $image, 0, 0, $x, $y, $l, $h);

        return $resultat;
    }

    /**
     * Place l'image au centre d'un carré, avec une marge (en proportion du côté) et un fond optionnel.
     */
    private function carre(GdImage $source, int $cote, float $marge, ?array $fond): GdImage
    {
        $carre = $this->toileTransparente($cote, $cote);

        if ($fond) {
            imagefill($carre, 0, 0, $this->couleur($carre, $fond));
        }

        $place = $cote * (1 - 2 * $marge);
        $echelle = $place / max(imagesx($source), imagesy($source));
        $l = max(1, (int) round(imagesx($source) * $echelle));
        $h = max(1, (int) round(imagesy($source) * $echelle));

        imagealphablending($carre, true);
        imagecopyresampled($carre, $source, (int) (($cote - $l) / 2), (int) (($cote - $h) / 2), 0, 0, $l, $h, imagesx($source), imagesy($source));
        imagesavealpha($carre, true);

        return $carre;
    }

    /**
     * La fillette du logo (sans le texte), posée sur une petite colline arrondie redessinée :
     * dans le logo, la colline s'étend sous le texte et serait coupée net par le cadrage.
     */
    private function silhouetteAvecColline(GdImage $logo): GdImage
    {
        [$x, $y, $l, $h] = $this->boiteVisible($logo, self::DEBUT_SILHOUETTE_X, self::BAS_SILHOUETTE_Y);
        $fille = $this->recadrer($logo, [$x, $y, $l, $h]);

        // Couleur teal du logo, prise dans la robe de la fillette.
        $c = imagecolorsforindex($logo, imagecolorat($logo, 540, 250));

        // Colline : demi-ellipse centrée sous les pieds, dessinée en 4x puis réduite pour des bords lisses.
        $largeurColline = (int) round($l * 1.25);
        $hauteurColline = (int) round($h * 0.16);
        $grande = $this->toileTransparente($largeurColline * 4, $hauteurColline * 4);
        imagealphablending($grande, true);
        imagefilledellipse($grande, $largeurColline * 2, $hauteurColline * 4, $largeurColline * 4, $hauteurColline * 8, imagecolorallocate($grande, $c['red'], $c['green'], $c['blue']));
        $colline = $this->toileTransparente($largeurColline, $hauteurColline);
        imagecopyresampled($colline, $grande, 0, 0, 0, 0, $largeurColline, $hauteurColline, $largeurColline * 4, $hauteurColline * 4);

        // Assemblage : la colline recouvre légèrement les pieds pour qu'ils y soient bien posés.
        $recouvrement = (int) round($hauteurColline * 0.25);
        $largeur = max($l, $largeurColline);
        $resultat = $this->toileTransparente($largeur, $h + $hauteurColline - $recouvrement);
        imagealphablending($resultat, true);
        $centrePieds = 544 - $x; // les jambes sont centrées vers x = 544 dans le logo
        $decalageFille = (int) round(($largeur - $l) / 2);
        imagecopy($resultat, $fille, $decalageFille, 0, 0, 0, $l, $h);
        imagecopy($resultat, $colline, max(0, min($largeur - $largeurColline, $decalageFille + $centrePieds - (int) ($largeurColline / 2))), $h - $recouvrement, 0, 0, $largeurColline, $hauteurColline);
        imagesavealpha($resultat, true);

        return $resultat;
    }

    /**
     * Carré aux coins arrondis, couleur teal du site, avec la silhouette en blanc (la fleur garde sa couleur corail).
     */
    private function pastille(GdImage $silhouette, int $cote): GdImage
    {
        $pastille = $this->toileTransparente($cote, $cote);
        imagealphablending($pastille, true);
        $teal = $this->couleur($pastille, self::TEAL);
        $rayon = (int) round($cote * 0.22);

        imagefilledrectangle($pastille, $rayon, 0, $cote - $rayon - 1, $cote - 1, $teal);
        imagefilledrectangle($pastille, 0, $rayon, $cote - 1, $cote - $rayon - 1, $teal);
        foreach ([[$rayon, $rayon], [$cote - $rayon - 1, $rayon], [$rayon, $cote - $rayon - 1], [$cote - $rayon - 1, $cote - $rayon - 1]] as [$cx, $cy]) {
            imagefilledellipse($pastille, $cx, $cy, 2 * $rayon, 2 * $rayon, $teal);
        }

        // Silhouette recolorée en blanc (les pixels teal du logo), en conservant la transparence et la fleur.
        $blanche = $this->toileTransparente(imagesx($silhouette), imagesy($silhouette));
        for ($x = 0; $x < imagesx($silhouette); $x++) {
            for ($y = 0; $y < imagesy($silhouette); $y++) {
                $c = imagecolorsforindex($silhouette, imagecolorat($silhouette, $x, $y));
                if ($c['alpha'] === 127) {
                    continue;
                }
                $estTeal = $c['green'] > $c['red'] + 20;
                $rgb = $estTeal ? [255, 255, 255] : [$c['red'], $c['green'], $c['blue']];
                imagesetpixel($blanche, $x, $y, imagecolorallocatealpha($blanche, $rgb[0], $rgb[1], $rgb[2], $c['alpha']));
            }
        }

        $motif = $this->carre($blanche, $cote, 0.13, null);
        imagecopy($pastille, $motif, 0, 0, 0, 0, $cote, $cote);
        imagesavealpha($pastille, true);

        return $pastille;
    }

    private function reduire(GdImage $source, int $cote): GdImage
    {
        $resultat = $this->toileTransparente($cote, $cote);
        imagecopyresampled($resultat, $source, 0, 0, 0, 0, $cote, $cote, imagesx($source), imagesy($source));
        imagesavealpha($resultat, true);

        return $resultat;
    }

    private function toileTransparente(int $l, int $h): GdImage
    {
        $toile = imagecreatetruecolor($l, $h);
        imagealphablending($toile, false);
        imagefill($toile, 0, 0, imagecolorallocatealpha($toile, 255, 255, 255, 127));
        imagesavealpha($toile, true);

        return $toile;
    }

    private function couleur(GdImage $image, array $rgb): int
    {
        return imagecolorallocate($image, ...$rgb);
    }

    private function enPng(GdImage $image): string
    {
        ob_start();
        imagepng($image, null, 9);

        return (string) ob_get_clean();
    }

    private function enregistrerPng(GdImage $image, string $chemin): void
    {
        imagepng($image, $chemin, 9);
        $this->ligneFichier(basename($chemin).' ('.imagesx($image).' px)');
    }

    /**
     * Fichier .ico contenant plusieurs tailles au format PNG (accepté par tous les navigateurs actuels).
     *
     * @param  array<int, string>  $pngs  [taille => contenu PNG]
     */
    private function construireIco(array $pngs): string
    {
        $entete = pack('vvv', 0, 1, count($pngs));
        $repertoire = '';
        $donnees = '';
        $decalage = 6 + 16 * count($pngs);

        foreach ($pngs as $taille => $png) {
            $repertoire .= pack('CCCCvvVV', $taille >= 256 ? 0 : $taille, $taille >= 256 ? 0 : $taille, 0, 0, 1, 32, strlen($png), $decalage);
            $donnees .= $png;
            $decalage += strlen($png);
        }

        return $entete.$repertoire.$donnees;
    }

    private function trouverPolice(array $noms): ?string
    {
        $dossiers = ['C:/Windows/Fonts', '/usr/share/fonts/truetype/dejavu', '/usr/share/fonts/TTF', '/Library/Fonts'];

        foreach ($dossiers as $dossier) {
            foreach ($noms as $nom) {
                if (is_file("$dossier/$nom")) {
                    return "$dossier/$nom";
                }
            }
        }

        return null;
    }

    private function texteCentre(GdImage $image, string $texte, string $police, int $taille, int $y, array $rgb): void
    {
        $boite = imagettfbbox($taille, 0, $police, $texte);
        $largeur = $boite[2] - $boite[0];
        imagettftext($image, $taille, 0, (int) ((imagesx($image) - $largeur) / 2), $y, $this->couleur($image, $rgb), $police, $texte);
    }

    private function ligneFichier(string $texte): void
    {
        $this->line("  ✓ public/$texte");
    }
}
