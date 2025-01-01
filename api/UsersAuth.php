<?php

class UsersAuth
{
    private $db;

    // Constructor pour initialiser la connexion à la base de données
    public function __construct($db)
    {
        $this->db = $db;
    }

    // Méthode pour connecter un utilisateur
    public function login($telephone, $password)
    {
        // Recherche l'utilisateur par son telephone
        $query = "SELECT * FROM users WHERE telephone = :telephone AND is_activated = 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $user['users_password'])) {
                $this->updateConnexionStatus($user['id_users'], true);
                $token = new Auth();
                return json_encode([
                    'status' => 'success',
                    'message' => 'Connexion réussie',
                    'token' => $token->generateToken($user['id_users']),
                    'data' => $user
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
                'message' => 'Email incorrect ou utilisateur désactivé'
            ]);
        }
    }

    private function updateLastConnexion($userId)
    {
        $query = "UPDATE users SET last_connexion = :last_connexion WHERE id_users = :id_users";
        $stmt = $this->db->prepare($query);

        // Stocker la date dans une variable avant de la lier
        $currentDateTime = date('Y-m-d H:i:s');
        $stmt->bindParam(':last_connexion', $currentDateTime);
        $stmt->bindParam(':id_users', $userId);

        $stmt->execute();
    }


    public function updateConnexionStatus($userId, $val = FALSE)
    {
        $query = "UPDATE users SET is_connected = :is_connected WHERE id_users = :id_users";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':is_connected', $val);
        $stmt->bindParam(':id_users', $userId);
        $stmt->execute();
    }

    // Méthode pour vérifier si l'utilisateur est connecté
    public function isLoggedIn($userId)
    {
        $query = "SELECT is_connected FROM users WHERE id_users = :user_id AND is_activated = 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user['is_connected'] == 1) {
                return json_encode([
                    'status' => 'success',
                    'message' => 'Utilisateur connecté',
                    'data' => [
                        'user_id' => $userId,
                        'is_connected' => true
                    ]
                ]);
            } else {
                return json_encode([
                    'status' => 'error',
                    'message' => 'Utilisateur non connecté dans la base de données',
                    'data' => [
                        'user_id' => $userId,
                        'is_connected' => false
                    ]
                ]);
            }
        } else {
            // L'utilisateur n'existe pas ou est désactivé
            return json_encode([
                'status' => 'error',
                'message' => 'Utilisateur non trouvé ou désactivé',
                'data' => [
                    'user_id' => $userId,
                    'is_connected' => false
                ]
            ]);
        }
    }

    // Méthode pour déconnecter un utilisateur
    public function logout($userId)
    {
        $this->updateLastConnexion($userId);
        $this->updateConnexionStatus($userId);
        return json_encode([
            'status' => 'sucess',
            'message' => 'Utilisateur déconnecté avec succès !'
        ]);
    }
}
