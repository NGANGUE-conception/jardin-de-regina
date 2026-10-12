{{--
    Bouton « Nouvelle pré-inscription manuelle » + tiroir de saisie (tableau de bord et page Pré-inscriptions).
    Sert à enregistrer une demande reçue par téléphone, sur place ou par email.
    En cas d'erreur de saisie, la page se recharge avec le tiroir rouvert et les valeurs conservées.
--}}
@php
    use App\Models\PreRegistration;

    $erreurs = $errors->getBag('preInscriptionManuelle');
    $champ = 'w-full h-11 px-4 bg-cream rounded-2xl font-body text-sm text-primary-deep focus:outline-none focus:ring-2 focus:ring-primary';
    $etiquette = 'font-body text-xs font-bold text-primary-deep/80 mb-1 block';
    $joursSemaine = ['lundi' => 'Lun', 'mardi' => 'Mar', 'mercredi' => 'Mer', 'jeudi' => 'Jeu', 'vendredi' => 'Ven'];
    $joursCoches = $erreurs->any() ? (array) old('care_days', []) : array_keys($joursSemaine);
    $etatInitial = [
        'ouvert' => $erreurs->any(),
        'careType' => (string) old('care_type', 'regulier'),
        'email' => (string) old('p1_email'),
    ];
@endphp

<div x-data="{ ...@js($etatInitial), fermer() { this.ouvert = false } }" @keydown.escape.window="fermer()">
    <button type="button" @click="ouvert = true" class="h-[48px] px-6 rounded-full bg-coral text-white font-body font-bold text-sm shadow-lg hover:-translate-y-0.5 hover:opacity-90 transition-all flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14"/></svg>
        Nouvelle pré-inscription manuelle
    </button>

    <template x-teleport="body">
        <div>
            <div x-show="ouvert" x-cloak @click="fermer()" class="fixed inset-0 bg-primary-deep/40 backdrop-blur-sm z-50" x-transition.opacity></div>

            <aside x-show="ouvert" x-cloak role="dialog" aria-modal="true" aria-labelledby="titre-pre-inscription-manuelle"
                   x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                   class="fixed top-0 right-0 h-full w-full sm:w-[480px] bg-white shadow-2xl z-50 overflow-y-auto flex flex-col">
                <div class="p-6 pb-4 border-b border-mint bg-cream/60 sticky top-0 backdrop-blur-md z-10 flex items-start justify-between gap-4">
                    <div>
                        <h2 id="titre-pre-inscription-manuelle" class="font-display text-xl text-primary-deep">Nouvelle pré-inscription</h2>
                        <p class="font-body text-xs text-primary-deep/75 mt-1">Pour une demande reçue par téléphone, sur place ou par email. Un numéro de dossier est créé automatiquement.</p>
                    </div>
                    <button type="button" @click="fermer()" title="Fermer" class="w-8 h-8 shrink-0 rounded-full flex items-center justify-center text-primary-deep/75 hover:bg-mint/40 hover:text-primary-deep transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.pre-inscriptions.store') }}" class="p-6 flex-1 flex flex-col gap-6" novalidate>
                    @csrf

                    @if ($erreurs->any())
                        <div class="bg-coral/10 text-coral rounded-2xl p-4 font-body text-sm" role="alert">
                            <p class="font-bold mb-1">Merci de corriger :</p>
                            <ul class="list-disc pl-5">
                                @foreach ($erreurs->all() as $message)
                                    <li>{{ $message }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Origine --}}
                    <div>
                        <label for="pim-source" class="{{ $etiquette }}">Demande reçue par</label>
                        <select id="pim-source" name="source" class="{{ $champ }}">
                            @foreach (PreRegistration::SOURCES as $valeur => $libelle)
                                @continue($valeur === 'site')
                                <option value="{{ $valeur }}" @selected(old('source', 'telephone') === $valeur)>{{ $libelle }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Enfant --}}
                    <fieldset class="flex flex-col gap-4">
                        <legend class="font-body text-xs uppercase font-bold tracking-wider text-primary mb-3">Enfant</legend>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="pim-child-first" class="{{ $etiquette }}">Prénom *</label>
                                <input id="pim-child-first" name="child_first_name" value="{{ old('child_first_name') }}" required maxlength="100" class="{{ $champ }}" @if ($erreurs->has('child_first_name')) aria-invalid="true" @endif>
                            </div>
                            <div>
                                <label for="pim-child-last" class="{{ $etiquette }}">Nom *</label>
                                <input id="pim-child-last" name="child_last_name" value="{{ old('child_last_name') }}" required maxlength="100" class="{{ $champ }}" @if ($erreurs->has('child_last_name')) aria-invalid="true" @endif>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="pim-gender" class="{{ $etiquette }}">Sexe *</label>
                                <select id="pim-gender" name="child_gender" required class="{{ $champ }}" @if ($erreurs->has('child_gender')) aria-invalid="true" @endif>
                                    <option value="" @selected(! old('child_gender'))>Sélectionner</option>
                                    @foreach (['Fille', 'Garçon', 'Autre / Non précisé'] as $genre)
                                        <option @selected(old('child_gender') === $genre)>{{ $genre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="pim-birthdate" class="{{ $etiquette }}">Naissance (ou prévue) *</label>
                                <input id="pim-birthdate" name="child_birthdate" type="date" value="{{ old('child_birthdate') }}" required class="{{ $champ }}" @if ($erreurs->has('child_birthdate')) aria-invalid="true" @endif>
                            </div>
                        </div>
                    </fieldset>

                    {{-- Accueil --}}
                    <fieldset class="flex flex-col gap-4">
                        <legend class="font-body text-xs uppercase font-bold tracking-wider text-primary mb-3">Accueil souhaité</legend>
                        <div>
                            <label for="pim-entry" class="{{ $etiquette }}">Date d'entrée souhaitée *</label>
                            <input id="pim-entry" name="entry_date" type="date" value="{{ old('entry_date') }}" required class="{{ $champ }}" @if ($erreurs->has('entry_date')) aria-invalid="true" @endif>
                        </div>
                        <div>
                            <span class="{{ $etiquette }}" id="pim-type-label">Type d'accueil *</span>
                            <div class="grid grid-cols-3 gap-2" role="radiogroup" aria-labelledby="pim-type-label">
                                @foreach (['regulier' => 'Régulier', 'occasionnel' => 'Occasionnel', 'urgence' => 'Urgence'] as $valeur => $libelle)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="care_type" value="{{ $valeur }}" x-model="careType" class="peer sr-only">
                                        <span class="h-10 rounded-full bg-cream font-body text-sm font-bold text-primary-deep flex items-center justify-center peer-checked:bg-primary peer-checked:text-white peer-focus-visible:ring-2 peer-focus-visible:ring-primary transition-colors">{{ $libelle }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div x-show="careType !== 'urgence'">
                            <span class="{{ $etiquette }}" id="pim-jours-label">Jours de présence *</span>
                            <div class="flex flex-wrap gap-2" role="group" aria-labelledby="pim-jours-label">
                                @foreach ($joursSemaine as $valeur => $libelle)
                                    <label class="cursor-pointer">
                                        <input type="checkbox" name="care_days[]" value="{{ $valeur }}" @checked(in_array($valeur, $joursCoches, true)) class="peer sr-only">
                                        <span class="w-14 h-10 rounded-full bg-cream font-body text-sm font-bold text-primary-deep flex items-center justify-center peer-checked:bg-primary peer-checked:text-white peer-focus-visible:ring-2 peer-focus-visible:ring-primary transition-colors">{{ $libelle }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label for="pim-special" class="{{ $etiquette }}">Allergies, PAI ou besoins spécifiques</label>
                            <textarea id="pim-special" name="special_needs_details" rows="2" maxlength="2000" placeholder="Laisser vide si rien de particulier" class="w-full px-4 py-3 bg-cream rounded-2xl font-body text-sm text-primary-deep placeholder:text-primary-deep/40 focus:outline-none focus:ring-2 focus:ring-primary">{{ old('special_needs_details') }}</textarea>
                        </div>
                    </fieldset>

                    {{-- Parent --}}
                    <fieldset class="flex flex-col gap-4">
                        <legend class="font-body text-xs uppercase font-bold tracking-wider text-primary mb-3">Parent référent</legend>
                        <div>
                            <label for="pim-civilite" class="{{ $etiquette }}">Civilité</label>
                            <select id="pim-civilite" name="p1_civilite" class="{{ $champ }}">
                                @foreach (['Madame', 'Monsieur', 'Autre'] as $civilite)
                                    <option @selected(old('p1_civilite', 'Madame') === $civilite)>{{ $civilite }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="pim-p1-prenom" class="{{ $etiquette }}">Prénom *</label>
                                <input id="pim-p1-prenom" name="p1_prenom" value="{{ old('p1_prenom') }}" required maxlength="100" class="{{ $champ }}" @if ($erreurs->has('p1_prenom')) aria-invalid="true" @endif>
                            </div>
                            <div>
                                <label for="pim-p1-nom" class="{{ $etiquette }}">Nom *</label>
                                <input id="pim-p1-nom" name="p1_nom" value="{{ old('p1_nom') }}" required maxlength="100" class="{{ $champ }}" @if ($erreurs->has('p1_nom')) aria-invalid="true" @endif>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="pim-p1-tel" class="{{ $etiquette }}">Téléphone *</label>
                                <input id="pim-p1-tel" name="p1_tel" type="tel" value="{{ old('p1_tel') }}" required maxlength="30" class="{{ $champ }}" @if ($erreurs->has('p1_tel')) aria-invalid="true" @endif>
                            </div>
                            <div>
                                <label for="pim-p1-email" class="{{ $etiquette }}">Email (facultatif)</label>
                                <input id="pim-p1-email" name="p1_email" type="email" x-model="email" maxlength="190" class="{{ $champ }}" @if ($erreurs->has('p1_email')) aria-invalid="true" @endif>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label for="pim-p1-cp" class="{{ $etiquette }}">Code postal</label>
                                <input id="pim-p1-cp" name="p1_cp" value="{{ old('p1_cp') }}" maxlength="10" inputmode="numeric" class="{{ $champ }}">
                            </div>
                            <div class="col-span-2">
                                <label for="pim-p1-ville" class="{{ $etiquette }}">Ville</label>
                                <input id="pim-p1-ville" name="p1_ville" value="{{ old('p1_ville') }}" maxlength="100" class="{{ $champ }}">
                            </div>
                        </div>
                    </fieldset>

                    <div>
                        <label for="pim-note" class="{{ $etiquette }}">Note interne (visible uniquement dans l'admin)</label>
                        <textarea id="pim-note" name="internal_note" rows="3" maxlength="3000" placeholder="Ex. : appel du 12/10, rappeler en fin de journée" class="w-full px-4 py-3 bg-cream rounded-2xl font-body text-sm text-primary-deep placeholder:text-primary-deep/40 focus:outline-none focus:ring-2 focus:ring-primary">{{ old('internal_note') }}</textarea>
                    </div>

                    <div class="flex flex-col gap-3 bg-cream rounded-2xl p-4">
                        <label class="flex items-start gap-3 font-body text-sm text-primary-deep" :class="(email || '').trim() === '' ? 'opacity-60' : 'cursor-pointer'">
                            <input type="checkbox" name="envoyer_confirmation" value="1" :disabled="(email || '').trim() === ''" @checked(old('envoyer_confirmation')) class="mt-0.5 w-4 h-4 accent-primary">
                            <span>Envoyer l'email de confirmation à la famille <span class="block text-xs text-primary-deep/75" x-show="(email || '').trim() === ''">Renseignez un email pour activer cette option.</span></span>
                        </label>
                        <label class="flex items-start gap-3 font-body text-sm text-primary-deep cursor-pointer">
                            <input type="checkbox" name="famille_informee" value="1" required @checked(old('famille_informee')) class="mt-0.5 w-4 h-4 accent-primary" @if ($erreurs->has('famille_informee')) aria-invalid="true" @endif>
                            <span>La famille est informée que ses informations sont enregistrées pour traiter sa demande (politique de confidentialité). *</span>
                        </label>
                    </div>

                    <div class="flex items-center gap-3 sticky bottom-0 bg-white pt-3 pb-1 border-t border-mint">
                        <button type="button" @click="fermer()" class="h-11 px-5 rounded-full bg-cream text-primary-deep font-body text-sm font-bold hover:bg-mint/40 transition-colors">Annuler</button>
                        <button type="submit" class="flex-1 h-11 px-4 rounded-full bg-primary text-white font-body text-sm font-bold shadow-md hover:opacity-90 transition-all">Créer le dossier</button>
                    </div>
                </form>
            </aside>
        </div>
    </template>
</div>
