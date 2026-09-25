
pour toutes ces choses qui suivent il est strictement interdit de créer de la logique limite toi au front, tu as juste le droit des créer des routes et des vues, inventées les données en haut des blades sur des variables pour raison  d'affichage si besoin  

le logiciel aura plusieurs modules

le module actuelles est appelé formation, y aura d'autres modules et j'aimerai que tu organises les vues par application , par là j"entends tout ce qui est propre à l'application formation doit être dans un même dossier. exemple son sidebar, mais pas les boutons , app.blade,... et le dashboard actuel est exclusive à l'application formation donc chaque application aura son dahboard

les items qu'il y a actuellement possèdent des sous items qui seront accessibles au survols, au click ils donneront lieux à des modals,  et cela se présente comme suite :

NOuvelles unités (
    -ajouter 
    -Consulter : affiche les informations des nouvelles unites en format cards ,avec boutons en icone "supprimer" et "editer" ouvrant chacunes à des modals associés, fitre sur nom ou statut de 
      )

Cours (
    -ajouter 
    -Consulter : affiche les informations des associéss en format cards ,avec boutons en icone "supprimer" et "editer" ouvrant chacunes à des modals associés, fitre sur attributs stratégique par rapport à la table
      )

Programme (
    -ajouter 
    -Consulter : affiche les informations des associéss en format cards ,avec boutons en icone "supprimer", "affecter chargé de formation", " et "editer" ouvrant chacunes à des modals associés, fitre sur attributs stratégique par rapport à la table
        Seance (
            -ajouter 
            -Consulter : affiche les informations des associéss en format ligne ,avec bouton en icone "supprimer", "affecter formateur, , "assigner cours")
    , "valider programme
      )
      le modal seance doit être accessible depuis le modal programme

Presence :-modal de scannage de code QR pour enregistrer la préesence 
            statistiques des présenvces globales (par rapport a chaque unités) , filtre par date, et/ou par nom

Evaluation :-modal de cotation par NU bouclant sur la liste des NU (à la validation livewire de 
lacotation un nouvel NU vient) sous deux notes : oral, ecrit fonctionnalité bouton enregistrement cottation disponibe à une date  
            
Editions :-certificat -> modeal editions en lot par programme de formation en cliquant sur un bouton 
            -Rapports ->modal mensuels
            cette sectons est à venir ne l'implemente pas
