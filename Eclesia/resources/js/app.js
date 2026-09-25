document.addEventListener('DOMContentLoaded', function () {
    // ===== Remplissage automatique des modales (éditer / supprimer) =====
    // Un bouton portant [data-bs-modal-fill] transmet ses data-* vers les
    // champs nommés de la modale cible ([data-bs-target]).
    document.querySelectorAll('[data-bs-modal-fill]').forEach((button) => {
        button.addEventListener('click', function () {
            const targetId = this.dataset.bsTarget;
            if (!targetId) {
                return;
            }
            const modal = document.querySelector(targetId);
            if (!modal) {
                return;
            }

            if (this.dataset.action) {
                const form = modal.querySelector('form');
                if (form) {
                    form.action = this.dataset.action;
                }
                window._action = this.dataset.action;
            }

            Object.entries(this.dataset).forEach(([key, value]) => {
                if (key === 'bsModalFill' || key === 'bsTarget' || key === 'bsToggle' || key === 'action') {
                    return;
                }
                const field = modal.querySelector(`[name="${key}"], [data-field="${key}"]`);
                if (field) {
                    if (field.tagName === 'SELECT') {
                        field.value = value;
                    } else if (field.type === 'checkbox') {
                        field.checked = value === '1' || value === 'true';
                    } else {
                        if (field.hasAttribute('data-field')) {
                            field.textContent = value;
                        } else {
                            field.value = value;
                        }
                    }
                }
            });
        });
    });

    // ===== Soumission des confirmations de suppression =====
    document.querySelectorAll('[data-confirm-submit]').forEach((button) => {
        button.addEventListener('click', function () {
            if (!window._action) {
                return;
            }
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = window._action;
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            form.appendChild(csrf);
            form.appendChild(method);
            document.body.appendChild(form);
            form.submit();
        });
    });

    // ===== Filtre des cartes (nouvelles unités) =====
    const filtreNom = document.getElementById('filtre-unites-nom');
    const filtreStatut = document.getElementById('filtre-unites-statut');
    const cartesUnites = document.querySelectorAll('#unites-cards [data-filtre-unit]');

    function appliquerFiltresUnites() {
        const terme = (filtreNom?.value ?? '').trim().toLowerCase();
        const statut = filtreStatut?.value ?? '';
        cartesUnites.forEach((carte) => {
            const nom = (carte.dataset.nom ?? '').toLowerCase();
            const statutCarte = carte.dataset.statut ?? '';
            const visible =
                (!terme || nom.includes(terme)) && (!statut || statutCarte === statut);
            carte.style.display = visible ? '' : 'none';
        });
    }

    filtreNom?.addEventListener('input', appliquerFiltresUnites);
    filtreStatut?.addEventListener('change', appliquerFiltresUnites);

    // ===== Filtre mono-champ des tableaux (cours, programmes, séances) =====
    document.querySelectorAll('[data-table-filter]').forEach((input) => {
        const tableSelector = input.dataset.tableFilter;
        const colonnes = (input.dataset.cols ?? '').split(',').map((c) => c.trim()).filter(Boolean);

        input.addEventListener('input', function () {
            const terme = this.value.trim().toLowerCase();
            let compteur = 0;
            document.querySelectorAll(`${tableSelector} tbody tr`).forEach((ligne) => {
                let colonnesTexte;
                if (colonnes.length > 0) {
                    colonnesTexte = colonnes.map((i) => ligne.children[i]?.textContent ?? '').join(' ').toLowerCase();
                } else {
                    colonnesTexte = ligne.textContent.toLowerCase();
                }
                const visible = !terme || colonnesTexte.includes(terme);
                ligne.style.display = visible ? '' : 'none';
                if (visible) {
                    compteur++;
                }
            });
        });
    });

    // ===== Ajout de plusieurs séances (clonage de lignes) =====
    const conteneurSeances = document.getElementById('seances-conteneur');
    const modeleSeance = document.querySelector('[data-seance-row]');

    if (conteneurSeances && modeleSeance) {
        function mettreAJourNumeros() {
            document.querySelectorAll('[data-seance-row]').forEach((ligne, index) => {
                const num = ligne.querySelector('[data-seance-num]');
                if (num) {
                    num.textContent = `Séance ${index + 1}`;
                }
            });
        }

        document.getElementById('seances-add')?.addEventListener('click', function () {
            const clone = modeleSeance.cloneNode(true);
            clone.removeAttribute('data-seance-row');
            clone.classList.add('seance-row');
            clone.querySelectorAll('input').forEach((input) => (input.value = ''));
            conteneurSeances.appendChild(clone);
            const retirer = clone.querySelector('[data-seance-retirer]');
            if (retirer) {
                retirer.addEventListener('click', () => {
                    clone.remove();
                    mettreAJourNumeros();
                });
            }
            mettreAJourNumeros();
        });

        const premierRetirer = modeleSeance.querySelector('[data-seance-retirer]');
        if (premierRetirer) {
            premierRetirer.addEventListener('click', () => {
                modeleSeance.remove();
                mettreAJourNumeros();
            });
        }
    }

    // ===== Modales CRUD des paramètres (statuts, types, cours, postes) =====
    function gererFormCrud(button) {
        const formSel = button.dataset.crudForm;
        const form = document.querySelector(formSel);
        if (!form) {
            return;
        }
        // "Éditer" : on bascule le formulaire en mode mise à jour.
        if (button.hasAttribute('data-crud-edit')) {
            form.action = button.dataset.action;
            if (!form.querySelector('input[name="_method"]')) {
                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'PUT';
                form.appendChild(method);
            }
            Object.entries(JSON.parse(button.dataset.fields || '{}')).forEach(([nom, valeur]) => {
                const champ = form.querySelector(`[name="${nom}"]`);
                if (champ) {
                    champ.value = valeur;
                }
            });
        } else {
            // "Nouveau" : retour au mode création.
            form.action = form.dataset.actionStore;
            form.querySelector('input[name="_method"]')?.remove();
        }
    }

    document.querySelectorAll('[data-crud-form]').forEach((form) => {
        form.dataset.actionStore = form.action;
    });

    document.querySelectorAll('[data-crud-edit]').forEach((button) => {
        button.addEventListener('click', function () {
            gererFormCrud(this);
        });
    });
    document.querySelectorAll('[data-crud-reset]').forEach((button) => {
        button.addEventListener('click', function () {
            gererFormCrud(this);
        });
    });

    // ===== Bascule champs programme NU / Ord =====
    const progType = document.getElementById('prog-type');
    if (progType) {
        function basculerChampsProg() {
            const type = progType.value;
            const nu = document.getElementById('prog-fields-nu');
            const ord = document.getElementById('prog-fields-ord');
            if (nu) {
                nu.classList.toggle('d-none', type !== 'NU');
            }
            if (ord) {
                ord.classList.toggle('d-none', type !== 'Ord');
            }
        }
        progType.addEventListener('change', basculerChampsProg);
        basculerChampsProg();
    }

    // ===== Validation d'un programme : le commentaire grise "Valider" =====
    document.querySelectorAll('[data-validation-form] textarea').forEach((textArea) => {
        const validerBtn = textArea.closest('[data-validation-form]').querySelector('[data-validation]');
        if (!validerBtn) {
            return;
        }
        function verifierValidation() {
            validerBtn.disabled = textArea.value.trim().length > 0;
        }
        textArea.addEventListener('input', verifierValidation);
        verifierValidation();
    });

    // ===== Après création d'un programme : ouverture directe des séances =====
    const seancesPourProgramme = document.getElementById('dashboard-seances-programme');
    if (seancesPourProgramme && seancesPourProgramme.value) {
        const selectProgramme = document.getElementById('seances-programme');
        if (selectProgramme) {
            selectProgramme.value = seancesPourProgramme.value;
        }
        const modalSeances = document.getElementById('modal-seance-ajouter');
        if (modalSeances) {
            bootstrap.Modal.getOrCreateInstance(modalSeances).show();
        }
    }

    // ===== Filtre des statistiques de présence =====
    const filtreStatsPresence = document.getElementById('filtre-stats-presence');
    filtreStatsPresence?.addEventListener('input', function () {
        const terme = this.value.trim().toLowerCase();
        document.querySelectorAll('#presence-stats-cards [data-filtre-pres]').forEach((carte) => {
            carte.style.display = carte.textContent.toLowerCase().includes(terme) ? '' : 'none';
        });
    });

    // ===== Filtres des programmes (consulter + valider) =====
    ['#filtre-prog-intitule', '#filtre-prog-type', '#filtre-prog-statut'].forEach((sel) => {
        const champ = document.querySelector(sel);
        champ?.addEventListener(sel.includes('intitule') ? 'input' : 'change', () => {
            const terme = (document.getElementById('filtre-prog-intitule')?.value ?? '').trim().toLowerCase();
            const type = document.getElementById('filtre-prog-type')?.value ?? '';
            const statut = document.getElementById('filtre-prog-statut')?.value ?? '';
            document.querySelectorAll('#prog-cards [data-filtre-prog]').forEach((carte) => {
                const okTerme = !terme || (carte.dataset.intitule ?? '').toLowerCase().includes(terme);
                const okType = !type || carte.dataset.type === type;
                const okStatut = !statut || carte.dataset.statut === statut;
                carte.style.display = okTerme && okType && okStatut ? '' : 'none';
            });
        });
    });

    ['#filtre-validation-intitule', '#filtre-validation-type'].forEach((sel) => {
        const champ = document.querySelector(sel);
        champ?.addEventListener(sel.includes('intitule') ? 'input' : 'change', () => {
            const terme = (document.getElementById('filtre-validation-intitule')?.value ?? '').trim().toLowerCase();
            const type = document.getElementById('filtre-validation-type')?.value ?? '';
            document.querySelectorAll('#prog-validation-cards [data-validation-card]').forEach((carte) => {
                const okTerme = !terme || (carte.dataset.intitule ?? '').toLowerCase().includes(terme);
                const okType = !type || carte.dataset.type === type;
                carte.style.display = okTerme && okType ? '' : 'none';
            });
        });
    });

    // ===== "Afficher" dans la validation : déplie le formulaire de décision =====
    document.querySelectorAll('[data-afficher]').forEach((button) => {
        button.addEventListener('click', function () {
            const carte = this.closest('.card');
            const forme = carte?.querySelector('[data-validation-form]');
            if (forme) {
                this.classList.toggle('d-none', !forme.classList.contains('d-none'));
                forme.classList.toggle('d-none');
            }
        });
    });

    // ===== Enregistrement d'une présence (code_fidele en priorité) =====
    async function envoyerPresence(codeFidele, feedback) {
        const url = document.getElementById('form-presence-code')?.dataset.url;
        if (!codeFidele || !url) {
            return false;
        }
        feedback.classList.remove('d-none');
        try {
            const reponse = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                    'Accept': 'application/json',
                },
                body: new URLSearchParams({
                    code_fidele: codeFidele,
                    est_present: '1',
                }),
            });
            const data = await reponse.json();
            feedback.classList.toggle('alert-danger', !reponse.ok);
            feedback.classList.toggle('alert-success', reponse.ok);
            feedback.classList.add('alert', 'small');
            feedback.textContent = data.message ?? (reponse.ok ? 'Présence enregistrée.' : 'Erreur lors de l\'enregistrement.');
            return reponse.ok;
        } catch (e) {
            feedback.classList.add('alert', 'alert-danger', 'small');
            feedback.textContent = 'Erreur réseau. Réessayez.';
            return false;
        }
    }

    const formPresenceCode = document.getElementById('form-presence-code');
    if (formPresenceCode) {
        formPresenceCode.addEventListener('submit', async function (e) {
            e.preventDefault();
            const code = this.querySelector('[name="code_fidele"]').value.trim();
            const feedback = document.getElementById('presence-code-feedback');
            const ok = await envoyerPresence(code, feedback);
            if (ok) {
                this.querySelector('[name="code_fidele"]').value = '';
            }
        });
    }

    // ===== Scanner de QR code (présence) — voie secondaire =====
    const boutonDemarrer = document.getElementById('qr-start-btn');
    const boutonArreter = document.getElementById('qr-stop-btn');
    const boutonEnregistrer = document.getElementById('qr-enregistrer-btn');

    if (boutonDemarrer && boutonArreter) {
        let lecteur = null;
        let codeScanne = null;

        boutonDemarrer.addEventListener('click', function () {
            if (typeof Html5Qrcode === 'undefined') {
                alert('Le lecteur de QR code n\'a pas pu être chargé. Vérifiez votre connexion.');
                return;
            }
            lecteur = new Html5Qrcode('qr-reader');
            lecteur
                .start(
                    { facingMode: 'environment' },
                    { fps: 10, qrbox: { width: 220, height: 220 } },
                    (code) => {
                        boutonArreter.click();
                        codeScanne = code;
                        const recap = document.getElementById('qr-recap');
                        recap.classList.remove('d-none');
                        document.getElementById('qr-participant').textContent = `QR code scanné : ${code}`;
                        boutonEnregistrer.disabled = false;
                    },
                    () => {}
                )
                .catch(() => alert('Impossible d\'accéder à la caméra. Autorisez-la puis réessayez.'));
            boutonDemarrer.classList.add('d-none');
            boutonArreter.classList.remove('d-none');
        });

        boutonArreter.addEventListener('click', function () {
            lecteur?.stop().finally(() => {
                lecteur?.clear();
                lecteur = null;
                boutonArreter.classList.add('d-none');
                boutonDemarrer.classList.remove('d-none');
            });
        });

        boutonEnregistrer?.addEventListener('click', async function () {
            const feedback = document.getElementById('qr-enregistrer-feedback');
            boutonEnregistrer.disabled = true;
            const ok = await envoyerPresence(codeScanne, feedback);
            if (ok) {
                codeScanne = null;
                document.getElementById('qr-recap').classList.add('d-none');
            } else {
                boutonEnregistrer.disabled = false;
            }
        });
    }
});