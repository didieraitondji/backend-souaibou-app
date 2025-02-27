# Documentation de l'API

## Base URL

Chemin de dévellopement Local

```json
    http://souaibou-api.net/
```

## Endpoints

### 1. Utilisateurs

#### Récupérer tous les utilisateurs

```json
    GET /users
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
    GET /users/is_activated
```

#### Récupérer les utilisateurs non activés

```json
    GET /users/is_not_activated
```

#### Récupérer un utilisateur spécifique

```json
    GET /user/{id}
```

### 2. Produits

#### Récupérer tous les produits

```json
    GET /produits
```

#### Récupérer un produit spécifique

```json
    GET /produit/{id}
```

### 3. Commandes

#### Récupérer toutes les commandes

```json
    GET /commandes
```

#### Récupérer une commande spécifique

```json
    GET /commande/{id}
```

### 4. Livreurs

#### Récupérer tous les livreurs

```json
    GET /livreurs
```

#### Récupérer les livreurs activés

```json
    GET /livreurs/is_activated
```

#### Récupérer les livreurs non activés

```json
    GET /livreurs/is_not_activated
```

#### Récupérer un livreur spécifique

```json
    GET /livreur/{id}
```

### 5. Catégories

#### Récupérer toutes les catégories

```json
    GET /categories
```

#### Récupérer une catégorie spécifique

```json
    GET /categorie/{id}
```

### 6. Livraisons

#### Récupérer toutes les livraisons

```json
    GET /livraisons
```

#### Récupérer une livraison spécifique

```json
    GET /livraison/{id}
```

---

## Gestion des erreurs

En cas d'erreur ou de route invalide, l'API retourne une réponse JSON comme ceci :

```json
    {
        "status": "Erreur",
        "message": "La demande n'est pas valide, vérifiez l'url",
        "code": 0
    }
```

---

---

📌 **Auteur** : AITONDJI Tolome Didier
📅 **Dernière mise à jour** : 27 Février 2025
