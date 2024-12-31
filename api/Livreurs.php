<?php
class Livreurs
{
    // Attributs privés
    private $conn;
    private $id_livreur;
    private $id_users;
    private $first_name;
    private $last_name;
    private $sexe;
    private $email;
    private $telephone;
    private $livreurs_password;
    private $rue;
    private $ville;
    private $code_postal;
    private $pays;
    private $statut_livreur;
    private $last_connexion;
    private $notification_option;
    private $is_activated;
    private $is_connected;
    private $vehicule_type;
    private $vehicule_immatriculation;

    // Constructeur
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Getters et Setters
    // Getters et Setters
    public function getIdLivreur()
    {
        return $this->id_livreur;
    }

    public function setIdLivreur($id_livreur)
    {
        $this->id_livreur = $id_livreur;
    }

    public function getIdUsers()
    {
        return $this->id_users;
    }

    public function setIdUsers($id_users)
    {
        $this->id_users = $id_users;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
    }

    public function getSexe()
    {
        return $this->sexe;
    }

    public function setSexe($sexe)
    {
        $this->sexe = $sexe;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getTelephone()
    {
        return $this->telephone;
    }

    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }

    public function getLivreursPassword()
    {
        return $this->livreurs_password;
    }

    public function setLivreursPassword($livreurs_password)
    {
        $this->livreurs_password = $livreurs_password;
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

    public function getStatutLivreur()
    {
        return $this->statut_livreur;
    }

    public function setStatutLivreur($statut_livreur)
    {
        $this->statut_livreur = $statut_livreur;
    }

    public function getLastConnexion()
    {
        return $this->last_connexion;
    }

    public function setLastConnexion($last_connexion)
    {
        $this->last_connexion = $last_connexion;
    }

    public function getNotificationOption()
    {
        return $this->notification_option;
    }

    public function setNotificationOption($notification_option)
    {
        $this->notification_option = $notification_option;
    }

    public function getIsActivated()
    {
        return $this->is_activated;
    }

    public function setIsActivated($is_activated)
    {
        $this->is_activated = $is_activated;
    }

    public function getIsConnected()
    {
        return $this->is_connected;
    }

    public function setIsConnected($is_connected)
    {
        $this->is_connected = $is_connected;
    }

    public function getVehiculeType()
    {
        return $this->vehicule_type;
    }

    public function setVehiculeType($vehicule_type)
    {
        $this->vehicule_type = $vehicule_type;
    }

    public function getVehiculeImmatriculation()
    {
        return $this->vehicule_immatriculation;
    }

    public function setVehiculeImmatriculation($vehicule_immatriculation)
    {
        $this->vehicule_immatriculation = $vehicule_immatriculation;
    }


    // Méthode pour créer un livreur
    public function create()
    {
        // Vérification de l'unicité du numéro de téléphone
        $checkQuery = "SELECT id_livreur FROM livreurs WHERE telephone = :telephone";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(':telephone', $this->telephone);
        $checkStmt->execute();

        if (
            $checkStmt->rowCount() > 0
        ) {
            // Si le numéro existe déjà
            return json_encode(['status' => 'error', 'message' => 'Le numéro de téléphone existe déjà']);
        }

        // Génération du mot de passe par défaut
        $firstLetter = strtoupper(substr($this->first_name, 0, 1)); // Première lettre en majuscule
        $last8Digits = substr($this->telephone, -8); // 8 derniers chiffres du numéro de téléphone
        $this->livreurs_password = $firstLetter . '@' . $last8Digits;

        // Hachage du mot de passe
        $this->livreurs_password = password_hash($this->livreurs_password, PASSWORD_BCRYPT);

        // Requête d'insertion
        $query = "INSERT INTO livreurs (
                id_users, first_name, last_name, sexe, email, telephone, livreurs_password,
                rue, ville, code_postal, pays, vehicule_type, vehicule_immatriculation
              ) VALUES (
                :id_users, :first_name, :last_name, :sexe, :email, :telephone, :livreurs_password,
                :rue, :ville, :code_postal, :pays, :vehicule_type, :vehicule_immatriculation
              )";
        $stmt = $this->conn->prepare($query);

        // Liaison des paramètres
        $stmt->bindParam(':id_users', $this->id_users);
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':sexe', $this->sexe);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':telephone', $this->telephone);
        $stmt->bindParam(':livreurs_password', $this->livreurs_password);
        $stmt->bindParam(':rue', $this->rue);
        $stmt->bindParam(':ville', $this->ville);
        $stmt->bindParam(':code_postal', $this->code_postal);
        $stmt->bindParam(':pays', $this->pays);
        $stmt->bindParam(':vehicule_type', $this->vehicule_type);
        $stmt->bindParam(':vehicule_immatriculation', $this->vehicule_immatriculation);

        // Exécution de la requête
        if ($stmt->execute()) {
            return json_encode(['status' => 'success', 'message' => 'Livreur créé avec succès']);
        }

        return json_encode(['status' => 'error', 'message' => 'Échec de la création du livreur']);
    }


    // Méthode pour lire un seul livreur
    public function readOne()
    {
        $query = "SELECT * FROM livreurs WHERE id_livreur = :id_livreur";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_livreur', $this->id_livreur);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            foreach ($row as $key => $value) {
                $this->$key = $value;
            }

            return json_encode(['status' => 'success', 'data' => $row]);
        }

        return json_encode(['status' => 'error', 'message' => 'Livreur non trouvé']);
    }

    // Méthode pour lire tous les livreurs
    public function readAll($val = 1)
    {
        $query = "SELECT * FROM livreurs WHERE is_activated = :is_activated";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':is_activated', $val, PDO::PARAM_INT);
        $stmt->execute();

        $livreurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($livreurs)) {
            return json_encode(['status' => 'success', 'data' => $livreurs]);
        }

        return json_encode(['status' => 'error', 'message' => 'Aucun livreur trouvé']);
    }


    // Méthode pour mettre à jour un livreur
    public function update()
    {
        $query = "UPDATE livreurs SET
                    first_name = :first_name,
                    last_name = :last_name,
                    sexe = :sexe,
                    email = :email,
                    telephone = :telephone,
                    rue = :rue,
                    ville = :ville,
                    code_postal = :code_postal,
                    pays = :pays,
                    statut_livreur = :statut_livreur,
                    notification_option = :notification_option,
                    is_activated = :is_activated,
                    is_connected = :is_connected,
                    vehicule_type = :vehicule_type,
                    vehicule_immatriculation = :vehicule_immatriculation
                  WHERE id_livreur = :id_livreur";
        $stmt = $this->conn->prepare($query);

        // Liaison des paramètres
        $stmt->bindParam(':id_livreur', $this->id_livreur);
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':sexe', $this->sexe);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':telephone', $this->telephone);
        $stmt->bindParam(':rue', $this->rue);
        $stmt->bindParam(':ville', $this->ville);
        $stmt->bindParam(':code_postal', $this->code_postal);
        $stmt->bindParam(':pays', $this->pays);
        $stmt->bindParam(':statut_livreur', $this->statut_livreur);
        $stmt->bindParam(':notification_option', $this->notification_option);
        $stmt->bindParam(':is_activated', $this->is_activated);
        $stmt->bindParam(':is_connected', $this->is_connected);
        $stmt->bindParam(':vehicule_type', $this->vehicule_type);
        $stmt->bindParam(':vehicule_immatriculation', $this->vehicule_immatriculation);

        if ($stmt->execute()) {
            return json_encode(['status' => 'success', 'message' => 'Livreur mis à jour avec succès']);
        }

        return json_encode(['status' => 'error', 'message' => 'Échec de la mise à jour du livreur']);
    }

    // Méthode pour supprimer un livreur
    public function delete()
    {
        // Vérification préalable si l'ID du livreur est défini
        if (empty($this->id_livreur)) {
            return json_encode([
                'status' => 'error',
                'message' => 'ID du livreur non fourni ou invalide'
            ]);
        }

        try {
            // Préparer la requête
            $query = "DELETE FROM livreurs WHERE id_livreur = :id_livreur";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_livreur', $this->id_livreur, PDO::PARAM_INT);

            // Exécuter la requête
            $stmt->execute();

            // Vérifier si un livreur a été supprimé
            if ($stmt->rowCount() > 0) {
                return json_encode([
                    'status' => 'success',
                    'message' => 'Livreur supprimé avec succès'
                ]);
            }

            // Si aucun livreur n'a été supprimé
            return json_encode([
                'status' => 'error',
                'message' => 'Aucun livreur trouvé avec cet ID'
            ]);
        } catch (Exception $e) {
            // Gérer les erreurs
            return json_encode([
                'status' => 'error',
                'message' => 'Échec de la suppression : ' . $e->getMessage()
            ]);
        }
    }
}
