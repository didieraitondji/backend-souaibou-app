<?php

class Users
{
    private $id_users;
    private $first_name;
    private $last_name;
    private $sexe;
    private $email;
    private $telephone;
    private $users_password;
    private $rue;
    private $ville;
    private $code_postal;
    private $pays;
    private $last_connexion;
    private $notification_option;
    private $picture;
    private $user_type;
    private $is_activated;
    private $created_at;
    private $updated_at;
    private $deleted_at;
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        $checkQuery = "SELECT COUNT(*) FROM users WHERE telephone = :telephone";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(':telephone', $this->telephone);
        $checkStmt->execute();

        if ($checkStmt->fetchColumn() > 0) {
            return $this->jsonResponse('error', 'Le numéro de téléphone existe déjà', []);
        }

        $query = "INSERT INTO users (first_name, last_name, sexe, email, telephone, users_password, rue, ville, code_postal, pays, picture)
        VALUES (:first_name, :last_name, :sexe, :email, :telephone, :users_password, :rue, :ville, :code_postal, :pays, :picture)";

        $stmt = $this->conn->prepare($query);

        $passhash = password_hash($this->users_password, PASSWORD_BCRYPT);

        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':sexe', $this->sexe);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':telephone', $this->telephone);
        $stmt->bindParam(':users_password', $passhash);
        $stmt->bindParam(':rue', $this->rue);
        $stmt->bindParam(':ville', $this->ville);
        $stmt->bindParam(':code_postal', $this->code_postal);
        $stmt->bindParam(':pays', $this->pays);
        $stmt->bindParam(':picture', $this->picture);

        if ($stmt->execute()) {
            return $this->jsonResponse('success', 'Compte créé avec succès !', []);
        }

        return $this->jsonResponse('error', 'Impossible de créer l\'utilisateur', []);
    }

    public function readOne()
    {
        $query = "SELECT * FROM users WHERE id_users = :id_users";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_users', $this->id_users);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            foreach ($row as $key => $value) {
                $this->$key = $value;
            }

            return json_encode(['status' => 'success', 'data' => $row]);
        }

        return json_encode(['status' => 'error', 'message' => 'Utilisateur non trouvé']);
    }


    public function readAll($val = 1)
    {
        $query = "SELECT * FROM users WHERE is_activated = :is_activated";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':is_activated', $val, PDO::PARAM_INT);
        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($users)) {
            return json_encode(['status' => 'success', 'data' => $users]);
        }

        return json_encode(['status' => 'error', 'message' => 'Aucun utilisateur trouvé']);
    }


    private function jsonResponse($status, $message, $data = [])
    {
        return json_encode([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    // Méthode pour mettre à jour les informations d'un utilisateur
    public function update()
    {
        $query = "UPDATE users
              SET first_name = :first_name,
                  last_name = :last_name,
                  sexe = :sexe,
                  email = :email,
                  telephone = :telephone,
                  rue = :rue,
                  ville = :ville,
                  code_postal = :code_postal,
                  pays = :pays,
                  notification_option = :notification_option,
                  picture = :picture,
                  user_type = :user_type,
                  is_activated = :is_activated
              WHERE id_users = :id_users";

        $stmt = $this->conn->prepare($query);

        // Lier les paramètres
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':sexe', $this->sexe);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':telephone', $this->telephone);
        $stmt->bindParam(':rue', $this->rue);
        $stmt->bindParam(':ville', $this->ville);
        $stmt->bindParam(':code_postal', $this->code_postal);
        $stmt->bindParam(':pays', $this->pays);
        $stmt->bindParam(':notification_option', $this->notification_option);
        $stmt->bindParam(':picture', $this->picture);
        $stmt->bindParam(':user_type', $this->user_type);
        $stmt->bindParam(':is_activated', $this->is_activated);
        $stmt->bindParam(':id_users', $this->id_users);

        // Exécuter la requête et vérifier si elle a réussi
        if ($stmt->execute()) {
            return json_encode([
                'status' => 'success',
                'message' => 'Utilisateur mis à jour avec succès',
                'data' => [
                    'id_users' => $this->id_users,
                    'first_name' => $this->first_name,
                    'last_name' => $this->last_name,
                    'sexe' => $this->sexe,
                    'email' => $this->email,
                    'telephone' => $this->telephone,
                    'rue' => $this->rue,
                    'ville' => $this->ville,
                    'code_postal' => $this->code_postal,
                    'pays' => $this->pays,
                    'notification_option' => $this->notification_option,
                    'picture' => $this->picture,
                    'user_type' => $this->user_type,
                    'is_activated' => $this->is_activated,
                    'created_at' => $this->created_at,
                    'updated_at' => date("Y-m-d H:i:s")
                ]
            ]);
        }

        // En cas d'échec de la requête
        return json_encode([
            'status' => 'error',
            'message' => 'Échec de la mise à jour de l\'utilisateur',
            'data' => []
        ]);
    }


    // Méthode pour supprimer un utilisateur
    public function delete()
    {
        $query = "DELETE FROM users WHERE id_users = :id_users";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_users', $this->id_users);

        if ($stmt->execute()) {
            return true;
        }
        return false;
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

    // Getter et Setter pour first_name
    public function getFirstName()
    {
        return $this->first_name;
    }

    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    // Getter et Setter pour last_name
    public function getLastName()
    {
        return $this->last_name;
    }

    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
    }

    // Getter et Setter pour sexe
    public function getSexe()
    {
        return $this->sexe;
    }

    public function setSexe($sexe)
    {
        $this->sexe = $sexe;
    }

    // Getter et Setter pour email
    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    // Getter et Setter pour telephone
    public function getTelephone()
    {
        return $this->telephone;
    }

    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }

    // Getter et Setter pour users_password
    public function getUsersPassword()
    {
        return $this->users_password;
    }

    public function setUsersPassword($users_password)
    {
        $this->users_password = $users_password;
    }

    // Getter et Setter pour rue
    public function getRue()
    {
        return $this->rue;
    }

    public function setRue($rue)
    {
        $this->rue = $rue;
    }

    // Getter et Setter pour ville
    public function getVille()
    {
        return $this->ville;
    }

    public function setVille($ville)
    {
        $this->ville = $ville;
    }

    // Getter et Setter pour code_postal
    public function getCodePostal()
    {
        return $this->code_postal;
    }

    public function setCodePostal($code_postal)
    {
        $this->code_postal = $code_postal;
    }

    // Getter et Setter pour pays
    public function getPays()
    {
        return $this->pays;
    }

    public function setPays($pays)
    {
        $this->pays = $pays;
    }

    // Getter et Setter pour last_connexion
    public function getLastConnexion()
    {
        return $this->last_connexion;
    }

    public function setLastConnexion($last_connexion)
    {
        $this->last_connexion = $last_connexion;
    }

    // Getter et Setter pour notification_option
    public function getNotificationOption()
    {
        return $this->notification_option;
    }

    public function setNotificationOption($notification_option)
    {
        $this->notification_option = $notification_option;
    }

    // Getter et Setter pour picture
    public function getPicture()
    {
        return $this->picture;
    }

    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    // Getter et Setter pour user_type
    public function getUserType()
    {
        return $this->user_type;
    }

    public function setUserType($user_type)
    {
        $this->user_type = $user_type;
    }

    // Getter et Setter pour is_activated
    public function getIsActivated()
    {
        return $this->is_activated;
    }

    public function setIsActivated($is_activated)
    {
        $this->is_activated = $is_activated;
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

    // Getter et Setter pour updated_at
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    // Getter et Setter pour deleted_at
    public function getDeletedAt()
    {
        return $this->deleted_at;
    }

    public function setDeletedAt($deleted_at)
    {
        $this->deleted_at = $deleted_at;
    }
}
