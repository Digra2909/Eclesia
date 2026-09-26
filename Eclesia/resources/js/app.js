document.addEventListener('DOMContentLoaded', function () {
    // ===== Filtre des cartes (unités) : nom + code fidèle + statut =====
    const filtreNom = document.getElementById('filtre-unites-nom');
    const filtreCode = document.getElementById('filtre-unites-code');
    const filtreStatut = document.getElementById('filtre-unites-statut');
    const cartesUnites = document.querySelectorAll('#unites-cards [data-filtre-unit]');

    function codeFideleCorrespond(code, terme) {
        if (!terme) return true;

        const numero = (code ?? '').toUpperCase().replace('COMP-CNTRL-', '');

        return numero.startsWith(terme) || (code ?? '').toLowerCase().includes(terme);
    }

    function appliquerFiltresUnites() {
        const terme = (filtreNom?.value ?? '').trim().toLowerCase();
        const code = (filtreCode?.value ?? '').trim();
        const statut = filtreStatut?.value ?? '';
        cartesUnites.forEach((carte) => {
            const nom = (carte.dataset.nom ?? '').toLowerCase();
            const statutCarte = carte.dataset.statut ?? '';
            const visible =
                (!terme || nom.includes(terme)) &&
                codeFideleCorrespond(carte.dataset.code, code) &&
                (!statut || statutCarte === statut);
            carte.style.display = visible ? '' : 'none';
        });
    }

    filtreNom?.addEventListener('input', appliquerFiltresUnites);
    filtreCode?.addEventListener('input', appliquerFiltresUnites);
    filtreStatut?.addEventListener('change', appliquerFiltresUnites);

    // ===== Filtres des tableaux (cours, séances) =====
    document.querySelectorAll('[data-table-filter]').forEach((input) => {
        const tableSelector = input.dataset.tableFilter;
        const colonnes = (input.dataset.cols ?? '').split(',').map((c) => c.trim()).filter(Boolean);

        input.addEventListener('input', function () {
            const terme = this.value.trim().toLowerCase();
            document.querySelectorAll(`${tableSelector} tbody tr`).forEach((ligne) => {
                let colonnesTexte;
                if (colonnes.length > 0) {
                    colonnesTexte = colonnes.map((i) => ligne.children[i]?.textContent ?? '').join(' ').toLowerCase();
                } else {
                    colonnesTexte = ligne.textContent.toLowerCase();
                }
                ligne.style.display = !terme || colonnesTexte.includes(terme) ? '' : 'none';
            });
        });
    });

    // ===== Filtres des cartes programmes (intitulé + type + statut) =====
    ['#filtre-prog-intitule', '#filtre-prog-type', '#filtre-prog-statut'].forEach((sel) => {
        const champ = document.querySelector(sel);
        const evenement = sel.includes('intitule') ? 'input' : 'change';
        champ?.addEventListener(evenement, () => {
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

    // ===== Modal de confirmation de suppression (remplace les alertes JS) =====
    document.addEventListener('click', function (e) {
        const bouton = e.target.closest('[data-confirm-delete]');
        if (!bouton) return;

        const formulaire = document.getElementById('form-confirm-delete');
        const libelle = document.getElementById('element-supprimer');

        if (formulaire) {
            formulaire.action = bouton.dataset.confirmDelete;
        }
        if (libelle) {
            libelle.textContent = bouton.dataset.confirmLabel ?? 'cet élément';
        }
    });
});