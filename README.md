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
    GET /users/is_not_activated
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
