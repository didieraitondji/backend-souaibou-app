<?php
class Livraison
{
    private $id_livraison;
    private $id_commande;
    private $id_users;
    private $id_livreur;
    private $rue;
    private $ville;
    private $code_postal;
    private $pays;
    private $statut_livraison;
    private $created_at;
    private $updated_at;
    private $date_livraison_estimee;
    private $date_livraison_effective;
    private $moyen_transport;
    private $commentaires;

    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Getters et Setters
    public function getIdLivraison()
    {
        return $this->id_livraison;
    }

    public function setIdLivraison($id_livraison)
    {
        $this->id_livraison = $id_livraison;
    }

    public function getIdCommande()
    {
        return $this->id_commande;
    }

    public function setIdCommande($id_commande)
    {
        $this->id_commande = $id_commande;
    }

    public function getIdUsers()
    {
        return $this->id_users;
    }

    public function setIdUsers($id_users)
    {
        $this->id_users = $id_users;
    }

    public function getIdLivreur()
    {
        return $this->id_livreur;
    }

    public function setIdLivreur($id_livreur)
    {
        $this->id_livreur = $id_livreur;
    }

    public function getRue()
    {
        return $this->rue;
    }

    public function setRue($rue)
    {
        $this->rue = $rue;
    }

    public function getVille()
    {
        return $this->ville;
    }

    public function setVille($ville)
    {
        $this->ville = $ville;
    }

    public function getCodePostal()
    {
        return $this->code_postal;
    }

    public function setCodePostal($code_postal)
    {
        $this->code_postal = $code_postal;
    }

    public function getPays()
    {
        return $this->pays;
    }

    public function setPays($pays)
    {
        $this->pays = $pays;
    }

    public function getStatutLivraison()
    {
        return $this->statut_livraison;
    }

    public function setStatutLivraison($statut_livraison)
    {
        $this->statut_livraison = $statut_livraison;
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

    public function getDateLivraisonEstimee()
    {
        return $this->date_livraison_estimee;
    }

    public function setDateLivraisonEstimee($date_livraison_estimee)
    {
        $this->date_livraison_estimee = $date_livraison_estimee;
    }

    public function getDateLivraisonEffective()
    {
        return $this->date_livraison_effective;
    }

    public function setDateLivraisonEffective($date_livraison_effective)
    {
        $this->date_livraison_effective = $date_livraison_effective;
    }

    public function getMoyenTransport()
    {
        return $this->moyen_transport;
    }

    public function setMoyenTransport($moyen_transport)
    {
        $this->moyen_transport = $moyen_transport;
    }

    public function getCommentaires()
    {
        return $this->commentaires;
    }

    public function setCommentaires($commentaires)
    {
        $this->commentaires = $commentaires;
    }

    // Méthode pour créer une livraison
    public function create()
    {
        $query = "INSERT INTO livraisons (id_commande, id_users, id_livreur, rue, ville, code_postal, pays, statut_livraison, date_livraison_estimee, moyen_transport, commentaires)
                  VALUES (:id_commande, :id_users, :id_livreur, :rue, :ville, :code_postal, :pays, :statut_livraison, :date_livraison_estimee, :moyen_transport, :commentaires)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id_commande', $this->id_commande);
        $stmt->bindParam(':id_users', $this->id_users);
        $stmt->bindParam(':id_livreur', $this->id_livreur);
        $stmt->bindParam(':rue', $this->rue);
        $stmt->bindParam(':ville', $this->ville);
        $stmt->bindParam(':code_postal', $this->code_postal);
        $stmt->bindParam(':pays', $this->pays);
        $stmt->bindParam(':statut_livraison', $this->statut_livraison);
        $stmt->bindParam(':date_livraison_estimee', $this->date_livraison_estimee);
        $stmt->bindParam(':moyen_transport', $this->moyen_transport);
        $stmt->bindParam(':commentaires', $this->commentaires);

        if ($stmt->execute()) {
            return json_encode(['status' => 'success', 'message' => 'Livraison créée avec succès']);
        }

        return json_encode(['status' => 'error', 'message' => 'Erreur lors de la création de la livraison']);
    }

    // Méthode pour lire une livraison par ID
    public function readOne()
    {
        $query = "SELECT * FROM livraisons WHERE id_livraison = :id_livraison";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_livraison', $this->id_livraison);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id_commande = $row['id_commande'];
            $this->id_users = $row['id_users'];
            $this->id_livreur = $row['id_livreur'];
            $this->rue = $row['rue'];
            $this->ville = $row['ville'];
            $this->code_postal = $row['code_postal'];
            $this->pays = $row['pays'];
            $this->statut_livraison = $row['statut_livraison'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            $this->date_livraison_estimee = $row['date_livraison_estimee'];
            $this->date_livraison_effective = $row['date_livraison_effective'];
            $this->moyen_transport = $row['moyen_transport'];
            $this->commentaires = $row['commentaires'];

            return json_encode($row);
        }

        return json_encode(['status' => 'error', 'message' => 'Livraison non trouvée']);
    }

    // Méthode pour lire toutes les livraisons
    public function readAll()
    {
        $query = "SELECT * FROM livraisons";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $livraisons = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $livraisons[] = $row;
            }

            return json_encode($livraisons);
        }

        return json_encode(['status' => 'error', 'message' => 'Aucune livraison trouvée']);
    }

    // Méthode pour mettre à jour une livraison
    public function update()
    {
        $query = "UPDATE livraisons SET 
                  id_commande = :id_commande,
                  id_users = :id_users,
                  id_livreur = :id_livreur,
                  rue = :rue,
                  ville = :ville,
                  code_postal = :code_postal,
                  pays = :pays,
                  statut_livraison = :statut_livraison,
                  date_livraison_estimee = :date_livraison_estimee,
                  date_livraison_effective = :date_livraison_effective,
                  moyen_transport = :moyen_transport,
                  commentaires = :commentaires
                  WHERE id_livraison = :id_livraison";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id_commande', $this->id_commande);
        $stmt->bindParam(':id_users', $this->id_users);
        $stmt->bindParam(':id_livreur', $this->id_livreur);
        $stmt->bindParam(':rue', $this->rue);
        $stmt->bindParam(':ville', $this->ville);
        $stmt->bindParam(':code_postal', $this->code_postal);
        $stmt->bindParam(':pays', $this->pays);
        $stmt->bindParam(':statut_livraison', $this->statut_livraison);
        $stmt->bindParam(':date_livraison_estimee', $this->date_livraison_estimee);
        $stmt->bindParam(':date_livraison_effective', $this->date_livraison_effective);
        $stmt->bindParam(':moyen_transport', $this->moyen_transport);
        $stmt->bindParam(':commentaires', $this->commentaires);
        $stmt->bindParam(':id_livraison', $this->id_livraison);

        if ($stmt->execute()) {
            return json_encode(['status' => 'success', 'message' => 'Livraison mise à jour avec succès']);
        }

        return json_encode(['status' => 'error', 'message' => 'Erreur lors de la mise à jour de la livraison']);
    }

    // Méthode pour supprimer une livraison
    public function delete()
    {
        $query = "DELETE FROM livraisons WHERE id_livraison = :id_livraison";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_livraison', $this->id_livraison);

        if ($stmt->execute()) {
            return json_encode(['status' => 'success', 'message' => 'Livraison supprimée avec succès']);
        }

        return json_encode(['status' => 'error', 'message' => 'Erreur lors de la suppression de la livraison']);
    }
}
