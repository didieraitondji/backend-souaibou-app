<?php

class Categories
{
    // Attributs privés
    private $conn;
    private $id_categorie;
    private $id_users;
    private $nom_categorie;
    private $c_description;
    private $c_image;
    private $created_at;
    private $statut_categorie;

    // Constructeur
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Getter et Setter pour id_categorie
    public function getIdCategorie()
    {
        return $this->id_categorie;
    }

    public function setIdCategorie($id_categorie)
    {
        $this->id_categorie = $id_categorie;
    }

    // Getter et Setter pour id_users
    public function getIdUsers()
    {
        return $this->id_users;
    }

    public function setIdUsers($id_users)
    {
        $this->id_users = $id_users;
    }

    // Getter et Setter pour nom_categorie
    public function getNomCategorie()
    {
        return $this->nom_categorie;
    }

    public function setNomCategorie($nom_categorie)
    {
        $this->nom_categorie = $nom_categorie;
    }

    // Getter et Setter pour c_description
    public function getCDescription()
    {
        return $this->c_description;
    }

    public function setCDescription($c_description)
    {
        $this->c_description = $c_description;
    }

    // Getter et Setter pour c_image
    public function getCImage()
    {
        return $this->c_image;
    }

    public function setCImage($c_image)
    {
        $this->c_image = $c_image;
    }

    // Getter et Setter pour created_at
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    // Getter et Setter pour statut_categorie
    public function getStatutCategorie()
    {
        return $this->statut_categorie;
    }

    public function setStatutCategorie($statut_categorie)
    {
        $this->statut_categorie = $statut_categorie;
    }

    // Méthodes CRUD

    // Créer une nouvelle catégorie
    public function create()
    {
        // Vérifier si le nom de la catégorie existe déjà, indépendamment de la casse
        $queryCheck = "SELECT id_categorie FROM categories 
                   WHERE LOWER(nom_categorie) = LOWER(:nom_categorie)";
        $stmtCheck = $this->conn->prepare($queryCheck);
        $stmtCheck->bindParam(':nom_categorie', $this->nom_categorie);
        $stmtCheck->execute();

        if (
            $stmtCheck->rowCount() > 0
        ) {
            // Le nom de la catégorie existe déjà
            return json_encode([
                'status' => 'error',
                'message' => 'Une catégorie avec ce nom existe déjà, indépendamment de la casse.'
            ]);
        }

        // Insérer une nouvelle catégorie si le nom n'existe pas
        $query = "INSERT INTO categories (id_users, nom_categorie, c_description, c_image)
              VALUES (:id_users, :nom_categorie, :c_description, :c_image)";
        $stmt = $this->conn->prepare($query);

        // Liaison des paramètres
        $stmt->bindParam(':id_users', $this->id_users);
        $stmt->bindParam(':nom_categorie', $this->nom_categorie);
        $stmt->bindParam(':c_description', $this->c_description);
        $stmt->bindParam(':c_image', $this->c_image);

        if ($stmt->execute()) {
            return json_encode([
                'status' => 'success',
                'message' => 'Catégorie créée avec succès'
            ]);
        }

        return json_encode([
            'status' => 'error',
            'message' => 'Échec de la création de la catégorie'
        ]);
    }


    // Lire une catégorie par ID
    public function readOne()
    {
        $query = "SELECT * FROM categories WHERE id_categorie = :id_categorie";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_categorie', $this->id_categorie);

        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Remplir les propriétés via setters
            $this->setIdUsers($row['id_users']);
            $this->setNomCategorie($row['nom_categorie']);
            $this->setCDescription($row['c_description']);
            $this->setCImage($row['c_image']);
            $this->setCreatedAt($row['created_at']);
            $this->setStatutCategorie($row['statut_categorie']);

            return json_encode(['status' => 'success', 'data' => $row]);
        }

        return json_encode(['status' => 'error', 'message' => 'Catégorie introuvable']);
    }

    // Lire toutes les catégories
    public function readAll()
    {
        $query = "SELECT * FROM categories ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return json_encode([
            'status' => 'success',
            'data' => $categories
        ]);
    }

    // Mettre à jour une catégorie
    public function update()
    {
        $query = "UPDATE categories
                  SET nom_categorie = :nom_categorie,
                      c_description = :c_description,
                      c_image = :c_image,
                      statut_categorie = :statut_categorie
                  WHERE id_categorie = :id_categorie";
        $stmt = $this->conn->prepare($query);

        // Liaison des paramètres
        $stmt->bindParam(':nom_categorie', $this->nom_categorie);
        $stmt->bindParam(':c_description', $this->c_description);
        $stmt->bindParam(':c_image', $this->c_image);
        $stmt->bindParam(':statut_categorie', $this->statut_categorie);
        $stmt->bindParam(':id_categorie', $this->id_categorie);

        if ($stmt->execute()) {
            return json_encode(['status' => 'success', 'message' => 'Catégorie mise à jour avec succès']);
        }

        return json_encode(['status' => 'error', 'message' => 'Échec de la mise à jour de la catégorie']);
    }

    // Supprimer une catégorie
    public function delete()
    {
        $query = "DELETE FROM categories WHERE id_categorie = :id_categorie";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_categorie', $this->id_categorie);

        if ($stmt->execute()) {
            return json_encode(['status' => 'success', 'message' => 'Catégorie supprimée avec succès']);
        }

        return json_encode(['status' => 'error', 'message' => 'Échec de la suppression de la catégorie']);
    }
}