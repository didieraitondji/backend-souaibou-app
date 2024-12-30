<?php

class LivreursAuth
{
    private $db;

    // Constructeur pour initialiser la connexion à la base de données
    public function __construct($db)
    {
        $this->db = $db;
    }

    // Méthode pour connecter un livreur
    public function login($telephone, $password)
    {
        // Recherche le livreur par son téléphone
        $query = "SELECT * FROM livreurs WHERE telephone = :telephone AND is_activated = 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $livreur = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $livreur['livreurs_password'])) {
                $this->updateConnexionStatus($livreur['id_livreur'], true);
                return json_encode([
                    'status' => 'success',
                    'message' => 'Connexion réussie',
                    'data' => $livreur
                ]);
            } else {
                return json_encode([
                    'status' => 'error',
                    'message' => 'Mot de passe incorrect'
                ]);
            }
        } else {
            return json_encode([
                'status' => 'error',
                'message' => 'Téléphone incorrect ou livreur désactivé'
            ]);
        }
    }

    // Met à jour la dernière connexion du livreur
    private function updateLastConnexion($livreurId)
    {
        $query = "UPDATE livreurs SET last_connexion = :last_connexion WHERE id_livreur = :id_livreur";
        $stmt = $this->db->prepare($query);

        // Stocker la date dans une variable avant de la lier
        $currentDateTime = date('Y-m-d H:i:s');
        $stmt->bindParam(':last_connexion', $currentDateTime);
        $stmt->bindParam(':id_livreur', $livreurId);

        $stmt->execute();
    }

    // Met à jour le statut de connexion du livreur
    public function updateConnexionStatus($livreurId, $val = false)
    {
        $query = "UPDATE livreurs SET is_connected = :is_connected WHERE id_livreur = :id_livreur";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':is_connected', $val);
        $stmt->bindParam(':id_livreur', $livreurId);
        $stmt->execute();
    }

    // Vérifie si un livreur est connecté
    public function isLoggedIn($livreurId)
    {
        $query = "SELECT is_connected FROM livreurs WHERE id_livreur = :livreur_id AND is_activated = 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':livreur_id', $livreurId);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $livreur = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($livreur['is_connected'] == 1) {
                return json_encode([
                    'status' => 'success',
                    'message' => 'Livreur connecté',
                    'data' => [
                        'livreur_id' => $livreurId,
                        'is_connected' => true
                    ]
                ]);
            } else {
                return json_encode([
                    'status' => 'error',
                    'message' => 'Livreur non connecté dans la base de données',
                    'data' => [
                        'livreur_id' => $livreurId,
                        'is_connected' => false
                    ]
                ]);
            }
        } else {
            // Le livreur n'existe pas ou est désactivé
            return json_encode([
                'status' => 'error',
                'message' => 'Livreur non trouvé ou désactivé',
                'data' => [
                    'livreur_id' => $livreurId,
                    'is_connected' => false
                ]
            ]);
        }
    }

    // Méthode pour déconnecter un livreur
    public function logout($livreurId)
    {
        $this->updateLastConnexion($livreurId);
        $this->updateConnexionStatus($livreurId);
        return json_encode([
            'status' => 'success',
            'message' => 'Livreur déconnecté avec succès !'
        ]);
    }
}
