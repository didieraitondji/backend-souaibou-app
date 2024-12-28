<?php

class Users
{
    // Propriétés de la classe (correspondent aux colonnes de la table users)
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

    // Objet PDO pour la connexion à la base de données
    private $conn;

    // Constructeur pour initialiser la connexion à la base de données
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Méthode pour créer un utilisateur
    public function create()
    {
        // SQL pour insérer un nouvel utilisateur
        $query = "INSERT INTO users (first_name, last_name, sexe, email, telephone, users_password, rue, ville, code_postal, pays, notification_option, picture)
        VALUES (:first_name, :last_name, :sexe, :email, :telephone, :users_password, :rue, :ville, :code_postal, :pays, :notification_option, :picture)";

        // Préparation de la requête
        $stmt = $this->conn->prepare($query);

        // Liaison des paramètres
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':sexe', $this->sexe);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':telephone', $this->telephone);
        $stmt->bindParam(':users_password', $this->users_password);
        $stmt->bindParam(':rue', $this->rue);
        $stmt->bindParam(':ville', $this->ville);
        $stmt->bindParam(':code_postal', $this->code_postal);
        $stmt->bindParam(':pays', $this->pays);
        $stmt->bindParam(':notification_option', $this->notification_option);
        $stmt->bindParam(':picture', $this->picture);

        // Exécution de la requête et retour de l'état d'exécution
        if ($stmt->execute()) {
            return json_encode([
                'status' => 'sucess',
                'message' => 'Compte créer avec succès ! '
            ]);
        }
        return false;
    }

    // Méthode pour récupérer un utilisateur par son ID
    public function readOne()
    {
        // Requête pour récupérer un utilisateur par son ID
        $query = "SELECT * FROM users WHERE id_users = :id_users";

        // Préparation de la requête
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_users', $this->id_users);
        $stmt->execute();

        // Si un utilisateur est trouvé
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Remplir les propriétés de l'objet avec les valeurs récupérées
            $user_data = [
                'id_users' => $row['id_users'],
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'sexe' => $row['sexe'],
                'email' => $row['email'],
                'telephone' => $row['telephone'],
                'rue' => $row['rue'],
                'ville' => $row['ville'],
                'code_postal' => $row['code_postal'],
                'pays' => $row['pays'],
                'last_connexion' => $row['last_connexion'],
                'notification_option' => $row['notification_option'],
                'picture' => $row['picture'],
                'user_type' => $row['user_type'],
                'is_activated' => $row['is_activated'],
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at'],
                'deleted_at' => $row['deleted_at']
            ];

            // Retourner les données de l'utilisateur sous format JSON
            return json_encode([
                'status' => 'success',
                'message' => 'Utilisateur trouvé',
                'data' => $user_data
            ]);
        } else {
            // Si aucun utilisateur n'est trouvé, retourner un message d'erreur
            return json_encode([
                'status' => 'error',
                'message' => 'Utilisateur non trouvé',
                'data' => []
            ]);
        }
    }

    public function readAll($val = 1)
    {
        // Requête pour récupérer tous les utilisateurs
        $query = "SELECT * FROM users WHERE is_activated = :is_activated";

        // Préparation de la requête
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':is_activated', $val);
        $stmt->execute();

        // Si des utilisateurs sont trouvés
        if ($stmt->rowCount() > 0) {
            $users_data = [];

            // Récupérer chaque utilisateur et remplir un tableau
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $user_data = [
                    'id_users' => $row['id_users'],
                    'first_name' => $row['first_name'],
                    'last_name' => $row['last_name'],
                    'sexe' => $row['sexe'],
                    'email' => $row['email'],
                    'telephone' => $row['telephone'],
                    'rue' => $row['rue'],
                    'ville' => $row['ville'],
                    'code_postal' => $row['code_postal'],
                    'pays' => $row['pays'],
                    'last_connexion' => $row['last_connexion'],
                    'notification_option' => $row['notification_option'],
                    'picture' => $row['picture'],
                    'user_type' => $row['user_type'],
                    'is_activated' => $row['is_activated'],
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['updated_at'],
                    'deleted_at' => $row['deleted_at']
                ];

                // Ajouter l'utilisateur au tableau des utilisateurs
                $users_data[] = $user_data;
            }

            // Retourner les données sous format JSON
            return json_encode([
                'status' => 'success',
                'message' => 'Utilisateurs récupérés avec succès',
                'data' => $users_data
            ]);
        } else {
            // Si aucun utilisateur n'est trouvé, retourner un message d'erreur
            return json_encode([
                'status' => 'error',
                'message' => 'Aucun utilisateur trouvé',
                'data' => []
            ]);
        }
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
                  users_password = :users_password,
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
        $stmt->bindParam(':users_password', $this->users_password);
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

    // Getters et setters pour les propriétés (si besoin)
    // Getters et Setters

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
