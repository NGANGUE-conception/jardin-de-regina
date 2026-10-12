/*
 * JavaScript du site : Alpine.js (menus, carrousels, onglets, formulaires en étapes...)
 * avec ses deux extensions utilisées par les pages :
 *  - collapse  : blocs qui se déplient en douceur (x-collapse) ;
 *  - intersect : animations déclenchées au défilement (x-intersect).
 *
 * Les petites fonctions propres à certaines pages (ex. journalVisites, preInscription) sont définies
 * dans des balises <script> de ces pages, avant le démarrage d'Alpine.
 */
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import intersect from '@alpinejs/intersect';

Alpine.plugin(collapse);
Alpine.plugin(intersect);

window.Alpine = Alpine;
Alpine.start();
