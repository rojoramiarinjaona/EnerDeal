```mermaid
classDiagram
    class User {
        +String nom
        +String prenom
        +String email
        +String mdp
        +String image
        +String lieu_de_residence
    }
    class Demande {
        +String Status
    }


    note for Categorie "Solaire, hydraulique, eolienne"
    class Categorie {
        +String nom
        +String slug
    }
    
    class Energie {
        +String titre
        +double stock_kwh
        +String slug
        +String localisation
    }
    
    class Formule {
        +String ref
        +String intitule
        +String qtite_kwh
        + String duree
        + double taxite
        + String details_contrat
        + String conditions_resilation
        + String modalite_livraison
    }
    
    class Facture {
        +double montant
        +Date date_paiement
        +String statut_paiement
    }

    note for DeclarationIncident "Grave ou pas grave"
    class DeclarationIncident {
        +String titre
        +String details
        +int niveau
    }
    
    class Contrat {
        +Date date_debut
        +String statut
    }
    
    namespace I {
    class Role {
        +String nom
    }
    
    class Permission {
        +String nom
    }
    
    }
    
    User "1" -- "*" Facture : possede
    User "1" -- "*" DeclarationIncident : declare
    User "1" -- "*" Role : a
    User "1" -- "*" Demande : fait
    Demande "1" -- "*" Formule : contient
    Role "1" -- "*" Permission : accorde
    Categorie "1" -- "*" Energie : contient
    Energie "1" -- "*" Formule : propose
    Formule "1" -- "*" Facture : genere
    Formule "1" -- "*" Contrat : necessite


