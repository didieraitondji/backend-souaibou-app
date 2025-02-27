# Documentation de l'API

## Base URL

Chemin de dévellopement Local

```json
    http://souaibou-api.net/
```

## Endpoints GET

### 1. Utilisateurs

#### Récupérer tous les utilisateurs

```json
    http://souaibou-api.net/users
```

**Réponse :**

```json
    [
        {
            "id": 1,
            "nom": "John Doe",
            "email": "john@example.com",
            "is_activated": 1
        }
    ]
```

#### Récupérer les utilisateurs activés

```json
    http://souaibou-api.net/users/is_activated
```

#### Récupérer les utilisateurs non activés

```json
    http://souaibou-api.net/users/is_not_activated
```

#### Récupérer un utilisateur spécifique

```json
    http://souaibou-api.net/user/{id}
```

### 2. Produits

#### Récupérer tous les produits

```json
    http://souaibou-api.net/produits
```

#### Récupérer un produit spécifique

```json
    http://souaibou-api.net/produit/{id}
```

### 3. Commandes

#### Récupérer toutes les commandes

```json
    http://souaibou-api.net/commandes
```

#### Récupérer une commande spécifique

```json
    http://souaibou-api.net/commande/{id}
```

### 4. Livreurs

#### Récupérer tous les livreurs

```json
    http://souaibou-api.net/livreurs
```

#### Récupérer les livreurs activés

```json
    http://souaibou-api.net/livreurs/is_activated
```

#### Récupérer les livreurs non activés

```json
    http://souaibou-api.net/livreurs/is_not_activated
```

#### Récupérer un livreur spécifique

```json
    http://souaibou-api.net/livreur/{id}
```

### 5. Catégories

#### Récupérer toutes les catégories

```json
    http://souaibou-api.net/categories
```

#### Récupérer une catégorie spécifique

```json
    http://souaibou-api.net/categorie/{id}
```

### 6. Livraisons

#### Récupérer toutes les livraisons

```json
    http://souaibou-api.net/livraisons
```

#### Récupérer une livraison spécifique

```json
    http://souaibou-api.net/livraison/{id}
```

---

### Gestion des erreurs

En cas d'erreur ou de route invalide, l'API retourne une réponse JSON comme ceci :

```json
    {
        "status": "Erreur",
        "message": "La demande n'est pas valide, vérifiez l'url",
        "code": 0
    }
```

---

## Endpoints POST

### 1. Utilisateur (`/user`)

#### 1.1 Inscription

**Endpoint:** `http://souaibou-api.net/user`

**Description:** Crée un nouvel utilisateur.

**Paramètres:**

- `first_name` (string, requis) - Prénom de l'utilisateur
- `last_name` (string, requis) - Nom de famille
- `sexe` (string, requis) - Sexe
- `telephone` (string, requis) - Numéro de téléphone
- `rue` (string, requis) - Adresse
- `password` (string, requis) - Mot de passe
- `email` (string, requis) - Adresse email
- `ville` (string, requis) - Ville
- `pays` (string, requis) - Pays
- `code_postal` (string, requis) - Code postal
- `notification_option` (boolean, optionnel) - Option de notification

**Réponse:**

```json
    {
        "status": "success",
        "message": "Utilisateur créé avec succès"
    }
```

#### 1.2 Connexion

**Endpoint:** `http://souaibou-api.net/user/login`

**Description:** Connecte un utilisateur.

**Paramètres:**

- `telephone` (string, requis) - Numéro de téléphone
- `password` (string, requis) - Mot de passe

**Réponse:**

```json
    {
        "status": "success",
        "token": "JWT_TOKEN"
    }
```

---

### 2. Produit (`/produit`)

#### 2.1 Ajout d'un produit

**Endpoint:** `http://souaibou-api.net/produit`

**Description:** Ajoute un nouveau produit à la base de données.

**Paramètres:**

- `id_users` (int, requis) - ID de l'utilisateur
- `nom_produit` (string, requis) - Nom du produit
- `p_description` (string, requis) - Description du produit
- `id_categorie` (int, requis) - ID de la catégorie
- `prix` (float, requis) - Prix du produit
- `quantite_stock` (int, requis) - Quantité en stock
- `est_en_promotion` (boolean, optionnel) - Indique si le produit est en promotion
- `prix_promotionnel` (float, optionnel) - Prix promotionnel
- `date_debut_promotion` (string, optionnel) - Date de début de la promotion
- `date_fin_promotion` (string, optionnel) - Date de fin de la promotion
- `p_image` (string, optionnel) - URL de l'image du produit

**Réponse:**

```json
    {
        "status": "success",
        "message": "Produit ajouté avec succès"
    }
```

---

### 3. Commande (`/commande`)

#### 3.1 Création d'une commande

**Endpoint:** `http://souaibou-api.net/commande`

**Description:** Crée une nouvelle commande.

**Paramètres:**

- `id_users` (int, requis) - ID de l'utilisateur
- `total_commande` (float, requis) - Montant total
- `statut_commande` (string, requis) - Statut de la commande
- `moyen_paiement` (string, requis) - Moyen de paiement
- `est_a_livrer` (boolean, requis) - Indique si la commande doit être livrée
- `livraison_creer` (boolean, optionnel) - Indique si une livraison a été créée
- `rue_livraison` (string, optionnel) - Adresse de livraison
- `ville_livraison` (string, optionnel) - Ville de livraison
- `code_postal_livraison` (string, optionnel) - Code postal
- `pays_livraison` (string, optionnel) - Pays de livraison
- `commentaires` (string, optionnel) - Commentaires sur la commande
- `produits` (array, requis) - Liste des produits commandés

**Réponse:**

```json
    {
        "status": "success",
        "message": "Commande créée avec succès"
    }
```

---

### 4. Livreur (`/livreur`)

#### 4.1 Inscription d'un livreur

**Endpoint:** `http://souaibou-api.net/livreur`

**Description:** Ajoute un livreur au système.

**Paramètres:**

- `id_users` (int, requis) - ID de l'utilisateur
- `first_name` (string, requis) - Prénom
- `last_name` (string, requis) - Nom
- `email` (string, requis) - Email
- `telephone` (string, requis) - Numéro de téléphone
- `sexe` (string, requis) - Sexe
- `rue` (string, requis) - Adresse
- `ville` (string, requis) - Ville
- `code_postal` (string, requis) - Code postal
- `pays` (string, requis) - Pays
- `vehicule_type` (string, requis) - Type de véhicule
- `vehicule_immatriculation` (string, requis) - Immatriculation du véhicule

**Réponse:**

```json
    {
        "status": "success",
        "message": "Livreur ajouté avec succès"
    }
```

---

### 5. Livraison (`/livraison`)

#### 5.1 Création d'une livraison

**Endpoint:** `http://souaibou-api.net/livraison`

**Description:** Ajoute une nouvelle livraison.

**Paramètres:**

- `id_commande` (int, requis) - ID de la commande
- `id_users` (int, requis) - ID de l'utilisateur
- `id_livreur` (int, requis) - ID du livreur
- `rue` (string, requis) - Adresse de livraison
- `ville` (string, requis) - Ville
- `code_postal` (string, requis) - Code postal
- `pays` (string, requis) - Pays
- `statut_livraison` (string, requis) - Statut de la livraison
- `date_livraison_estimee` (string, optionnel) - Date estimée de livraison
- `moyen_transport` (string, optionnel) - Moyen de transport
- `commentaires` (string, optionnel) - Commentaires supplémentaires

**Réponse:**

```json
    {
        "status": "success",
        "message": "Livraison créée avec succès"
    }
```

---

### 6. Catégorie (`/categorie`)

#### 6.1 Création d'une catégorie

**Endpoint:** `http://souaibou-api.net/categorie`

**Description:** Ajoute une nouvelle catégorie de produits.

**Paramètres:**

- `id_users` (int, requis) - ID de l'utilisateur
- `nom_categorie` (string, requis) - Nom de la catégorie
- `c_description` (string, optionnel) - Description de la catégorie
- `c_image` (string, optionnel) - URL de l'image de la catégorie

**Réponse:**

```json
    {
        "status": "success",
        "message": "Catégorie ajoutée avec succès"
    }
```

---

📌 **Note:** Toutes les requêtes doivent être envoyées en JSON avec le bon format pour éviter les erreurs.

---

## Endpoints PUT

### Description

Cette API permet de mettre à jour les informations des utilisateurs, produits, commandes, livreurs, livraisons et catégories en fonction de l'URL fournie.

### Requête

L'API attend une requête HTTP contenant des données JSON envoyées via `php://input`. Les données sont ensuite décodées et utilisées pour mettre à jour les entrées correspondantes dans la base de données.

### Gestion des routes

L'API repose sur une gestion basique des routes basée sur la première partie de l'URL (`$url[0]`).

### Routes disponibles

#### `PUT /user/{id}`

- **Description** : Met à jour les informations d'un utilisateur.
- **Champs requis** :
  - `first_name`
  - `last_name`
  - `sexe`
  - `telephone`
  - `email`
  - `rue`
  - `ville`
  - `code_postal`
  - `pays`
  - `notification_option`
  - `picture`
  - `user_type`
  - `is_activated`

#### `PUT /produit/{id}`

- **Description** : Met à jour les informations d'un produit.
- **Champs requis** :
  - `nom_produit`
  - `p_description`
  - `id_categorie`
  - `prix`
  - `quantite_stock`
  - `statut_produit`
  - `est_en_promotion`
  - `prix_promotionnel`
  - `date_debut_promotion`
  - `date_fin_promotion`
  - `p_image`

#### `PUT /commande/{id}`

- **Description** : Met à jour une commande.
- **Champs requis** :
  - `total_commande`
  - `statut_commande`
  - `moyen_paiement`
  - `est_a_livrer`
  - `livraison_creer`
  - `rue_livraison`
  - `ville_livraison`
  - `code_postal_livraison`
  - `pays_livraison`
  - `commentaires`
  - `produits`

#### `PUT /livreur/{id}`

- **Description** : Met à jour un livreur.
- **Champs requis** :
  - `first_name`
  - `last_name`
  - `email`
  - `telephone`
  - `sexe`
  - `rue`
  - `ville`
  - `code_postal`
  - `pays`
  - `statut_livreur`
  - `notification_option`
  - `is_activated`
  - `is_connected`
  - `vehicule_type`
  - `vehicule_immatriculation`

#### `PUT /livraison/{id}`

- **Description** : Met à jour une livraison.
- **Champs requis** :
  - `id_commande`
  - `id_users`
  - `id_livreur`
  - `rue`
  - `ville`
  - `code_postal`
  - `pays`
  - `statut_livraison`
  - `date_livraison_estimee`
  - `date_livraison_effective`
  - `moyen_transport`
  - `commentaires`

#### `PUT /categorie/{id}`

- **Description** : Met à jour une catégorie.
- **Champs requis** :
  - `nom_categorie`
  - `c_description`
  - `c_image`
  - `statut_categorie`

### Gestion des erreurs de mise à jour

Si un identifiant est manquant ou invalide, l'API renvoie une réponse JSON :

```json
    {
        "status": "error",
        "message": "Identifiant invalide"
    }
```

---

## Endpoints DELETE

---

## Notes sur le concepteur/Développeur

📌 **Auteur** : AITONDJI Tolome Didier

📅 **Dernière mise à jour** : 27 Février 2025
