<?php

class TypeProduit
{
    // Attributs privés
    private $conn;
    private $id_type;
    private $nom_type;
    private $t_description;
    private $created_at;
    private $updated_at;

    // Constructeur
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Getters et Setters
    public function getIdType()
    {
        return $this->id_type;
    }

    public function setIdType($id_type)
    {
        $this->id_type = $id_type;
    }

    public function getNomType()
    {
        return $this->nom_type;
    }

    public function setNomType($nom_type)
    {
        $this->nom_type = $nom_type;
    }

    public function getTDescription()
    {
        return $this->t_description;
    }

    public function setTDescription($t_description)
    {
        $this->t_description = $t_description;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    // Méthodes CRUD

    // Créer un type de produit
    public function create()
    {
        $query = "INSERT INTO type_produit (nom_type, t_description) VALUES (:nom_type, :t_description)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nom_type', $this->nom_type);
        $stmt->bindParam(':t_description', $this->t_description);

        if ($stmt->execute()) {
            return json_encode(['status' => 'success', 'message' => 'Type de produit créé avec succès']);
        }
        return json_encode(['status' => 'error', 'message' => 'Échec de la création du type de produit']);
    }

    // Lire un type de produit par ID
    public function readOne()
    {
        $query = "SELECT * FROM type_produit WHERE id_type = :id_type";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_type', $this->id_type);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->setNomType($row['nom_type']);
            $this->setTDescription($row['t_description']);
            return json_encode(['status' => 'success', 'data' => $row]);
        }
        return json_encode(['status' => 'error', 'message' => 'Type de produit introuvable']);
    }

    // Lire tous les types de produits
    public function readAll()
    {
        $query = "SELECT * FROM type_produit ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $types = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return json_encode(['status' => 'success', 'data' => $types]);
    }

    // Mettre à jour un type de produit
    public function update()
    {
        $query = "UPDATE type_produit SET nom_type = :nom_type, t_description = :t_description WHERE id_type = :id_type";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nom_type', $this->nom_type);
        $stmt->bindParam(':t_description', $this->t_description);
        $stmt->bindParam(':id_type', $this->id_type);

        if ($stmt->execute()) {
            return json_encode(['status' => 'success', 'message' => 'Type de produit mis à jour avec succès']);
        }
        return json_encode(['status' => 'error', 'message' => 'Échec de la mise à jour du type de produit']);
    }

    // Supprimer un type de produit
    public function delete()
    {
        $query = "DELETE FROM type_produit WHERE id_type = :id_type";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_type', $this->id_type, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return json_encode(['status' => 'success', 'message' => 'Type de produit supprimé avec succès']);
        }
        return json_encode(['status' => 'error', 'message' => 'Échec de la suppression du type de produit']);
    }
}
