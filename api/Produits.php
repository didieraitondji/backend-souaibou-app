<?php

class Produits
{
    // Attributs privés
    private $id_produit;
    private $id_users;
    private $nom_produit;
    private $p_description;
    private $id_categorie;
    private $prix;
    private $quantite_stock;
    private $statut_produit;
    private $est_en_promotion;
    private $prix_promotionnel;
    private $date_debut_promotion;
    private $date_fin_promotion;
    private $p_image;
    private $created_at;
    private $updated_at;

    private $conn; // Connexion à la base de données

    // Constructeur
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Getters et Setters
    public function getIdProduit()
    {
        return $this->id_produit;
    }
    public function setIdProduit($id_produit)
    {
        $this->id_produit = $id_produit;
    }

    public function getIdUsers()
    {
        return $this->id_users;
    }
    public function setIdUsers($id_users)
    {
        $this->id_users = $id_users;
    }

    public function getNomProduit()
    {
        return $this->nom_produit;
    }
    public function setNomProduit($nom_produit)
    {
        $this->nom_produit = $nom_produit;
    }

    public function getDescription()
    {
        return $this->p_description;
    }
    public function setPDescription($p_description)
    {
        $this->p_description = $p_description;
    }

    public function getIdCategorie()
    {
        return $this->id_categorie;
    }
    public function setIdCategorie($id_categorie)
    {
        $this->id_categorie = $id_categorie;
    }

    public function getPrix()
    {
        return $this->prix;
    }
    public function setPrix($prix)
    {
        $this->prix = $prix;
    }

    public function getQuantiteStock()
    {
        return $this->quantite_stock;
    }
    public function setQuantiteStock($quantite_stock)
    {
        $this->quantite_stock = $quantite_stock;
    }

    public function getStatutProduit()
    {
        return $this->statut_produit;
    }
    public function setStatutProduit($statut_produit)
    {
        $this->statut_produit = $statut_produit;
    }

    public function getEstEnPromotion()
    {
        return $this->est_en_promotion;
    }
    public function setEstEnPromotion($est_en_promotion)
    {
        $this->est_en_promotion = $est_en_promotion;
    }

    public function getPrixPromotionnel()
    {
        return $this->prix_promotionnel;
    }
    public function setPrixPromotionnel($prix_promotionnel)
    {
        $this->prix_promotionnel = $prix_promotionnel;
    }

    public function getDateDebutPromotion()
    {
        return $this->date_debut_promotion;
    }
    public function setDateDebutPromotion($date_debut_promotion)
    {
        $this->date_debut_promotion = $date_debut_promotion;
    }

    public function getDateFinPromotion()
    {
        return $this->date_fin_promotion;
    }
    public function setDateFinPromotion($date_fin_promotion)
    {
        $this->date_fin_promotion = $date_fin_promotion;
    }

    public function getPImage()
    {
        return $this->p_image;
    }
    public function setPImage($p_image)
    {
        $this->p_image = $p_image;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    // Méthodes CRUD

    // CREATE
    public function create()
    {
        // Vérifier si le nom du produit existe déjà, insensible à la casse
        $checkQuery = "SELECT COUNT(*) as count FROM produits WHERE LOWER(nom_produit) = LOWER(:nom_produit)";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(':nom_produit', $this->nom_produit);
        $checkStmt->execute();
        $result = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] > 0) {
            return json_encode([
                'status' => 'error',
                'message' => 'Le nom du produit existe déjà. Veuillez choisir un autre nom.'
            ]);
        }

        // Insérer le produit
        $query = "INSERT INTO produits (id_users, nom_produit, p_description, id_categorie, prix, quantite_stock, est_en_promotion, prix_promotionnel, date_debut_promotion, date_fin_promotion, p_image)
              VALUES (:id_users, :nom_produit, :p_description, :id_categorie, :prix, :quantite_stock, :est_en_promotion, :prix_promotionnel, :date_debut_promotion, :date_fin_promotion, :p_image)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id_users', $this->id_users);
        $stmt->bindParam(':nom_produit', $this->nom_produit);
        $stmt->bindParam(':p_description', $this->p_description);
        $stmt->bindParam(':id_categorie', $this->id_categorie);
        $stmt->bindParam(':prix', $this->prix);
        $stmt->bindParam(':quantite_stock', $this->quantite_stock);
        $stmt->bindParam(':est_en_promotion', $this->est_en_promotion);
        $stmt->bindParam(':prix_promotionnel', $this->prix_promotionnel);
        $stmt->bindParam(':date_debut_promotion', $this->date_debut_promotion);
        $stmt->bindParam(':date_fin_promotion', $this->date_fin_promotion);
        $stmt->bindParam(':p_image', $this->p_image);

        if ($stmt->execute()) {
            return json_encode(['status' => 'success', 'message' => 'Produit créé avec succès']);
        }

        return json_encode(['status' => 'error', 'message' => 'Échec de la création du produit']);
    }


    // READ ONE
    public function readOne()
    {
        $query = "SELECT * FROM produits WHERE id_produit = :id_produit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_produit', $this->id_produit);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        }

        return json_encode(['status' => 'error', 'message' => 'Produit introuvable']);
    }

    // READ ALL
    public function readAll()
    {
        $query = "SELECT * FROM produits";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    // UPDATE
    public function update()
    {
        $query = "UPDATE produits 
                  SET nom_produit = :nom_produit, 
                      p_description = :p_description,
                      id_categorie = :id_categorie,
                      prix = :prix,
                      quantite_stock = :quantite_stock,
                      statut_produit = :statut_produit,
                      est_en_promotion = :est_en_promotion,
                      prix_promotionnel = :prix_promotionnel,
                      date_debut_promotion = :date_debut_promotion,
                      date_fin_promotion = :date_fin_promotion,
                      p_image = :p_image
                  WHERE id_produit = :id_produit";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id_produit', $this->id_produit);
        $stmt->bindParam(':nom_produit', $this->nom_produit);
        $stmt->bindParam(':p_description', $this->p_description);
        $stmt->bindParam(':id_categorie', $this->id_categorie);
        $stmt->bindParam(':prix', $this->prix);
        $stmt->bindParam(':quantite_stock', $this->quantite_stock);
        $stmt->bindParam(':statut_produit', $this->statut_produit);
        $stmt->bindParam(':est_en_promotion', $this->est_en_promotion);
        $stmt->bindParam(':prix_promotionnel', $this->prix_promotionnel);
        $stmt->bindParam(':date_debut_promotion', $this->date_debut_promotion);
        $stmt->bindParam(':date_fin_promotion', $this->date_fin_promotion);
        $stmt->bindParam(':p_image', $this->p_image);

        if ($stmt->execute()) {
            return json_encode(['status' => 'success', 'message' => 'Produit mis à jour avec succès']);
        }

        return json_encode(['status' => 'error', 'message' => 'Échec de la mise à jour du produit']);
    }

    // DELETE
    public function delete()
    {
        // Vérification si l'ID du produit est fourni et valide
        if (empty($this->id_produit)) {
            return json_encode([
                'status' => 'error',
                'message' => 'ID produit non fourni ou invalide'
            ]);
        }

        try {
            // Préparation de la requête de suppression
            $query = "DELETE FROM produits WHERE id_produit = :id_produit";
            $stmt = $this->conn->prepare($query);

            // Liaison du paramètre avec une vérification du type
            $stmt->bindParam(':id_produit', $this->id_produit, PDO::PARAM_INT);

            // Exécution de la requête
            $stmt->execute();

            // Vérification si une ligne a été supprimée
            if ($stmt->rowCount() > 0) {
                return json_encode([
                    'status' => 'success',
                    'message' => 'Produit supprimé avec succès'
                ]);
            }

            return json_encode([
                'status' => 'error',
                'message' => 'Aucun produit trouvé avec cet ID'
            ]);
        } catch (PDOException $e) {
            // Gestion des erreurs en cas d'exception
            return json_encode([
                'status' => 'error',
                'message' => 'Erreur lors de la suppression du produit : ' . $e->getMessage()
            ]);
        }
    }
}
