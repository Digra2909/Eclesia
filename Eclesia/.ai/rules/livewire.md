---
paths:
  - 'app/Livewire/**'
---

# Livewire

## Code fidèle : format COMP-CNTRL-<n°>
Règle générale : un code fidèle est toujours stocké au format « COMP-CNTRL-<n°> » (colonne unique, max 15 car.). Le code est généré automatiquement à la création (app/Livewire/Unites.php) ; l'opérateur ne saisit JAMAIS le code à l'insertion. La saisie du numéro seul (normalisée par app/Livewire/Presences.php) ne sert qu'à la RECHERCHE (enregistrement d'une présence, filtre par code) : « COMP-CNTRL-3 », « 3 » ou un code scanné renvoient au même code canonique. Le code complet est toujours affiché dans l'UI.
