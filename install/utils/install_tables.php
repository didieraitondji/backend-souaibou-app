<?php

include_once("../config/database.php");
$database = new Database();

// logique de création des tables avec try_catch
try {

    // connexion avec la base de données avec pdo
    $pdo = $database->getConnection();

    // requêtes de création des tables
    $queries = [
        "CREATE TABLE IF NOT EXISTS users (
            id_users INT AUTO_INCREMENT PRIMARY KEY,
            first_name VARCHAR(100) NOT NULL,
            last_name VARCHAR(100) NOT NULL,
            sexe VARCHAR(5) NOT NULL,
            email VARCHAR(150) DEFAULT NULL,
            telephone VARCHAR(20) NOT NULL,
            users_password VARCHAR(255) NOT NULL,
            rue VARCHAR(255),
            ville VARCHAR(100),
            code_postal VARCHAR(20),
            pays VARCHAR(100),
            last_connexion DATETIME DEFAULT NULL,
            notification_option ENUM('sms', 'email', 'none') DEFAULT 'sms',
            picture VARCHAR(255) DEFAULT NULL,
            user_type ENUM('admin', 'user') DEFAULT 'user',
            is_activated BOOLEAN DEFAULT TRUE,
            is_connected BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL
        )",
        "CREATE TABLE IF NOT EXISTS livreurs (
            id_livreur INT AUTO_INCREMENT PRIMARY KEY,
            id_users INT NOT NULL,
            first_name VARCHAR(100) NOT NULL,
            last_name VARCHAR(100) NOT NULL,
            sexe VARCHAR(5) NOT NULL,
            email VARCHAR(255) DEFAULT NULL,
            telephone VARCHAR(20) NOT NULL,
            livreurs_password VARCHAR(255) NOT NULL,
            rue VARCHAR(255),
            ville VARCHAR(100),
            code_postal VARCHAR(20),
            pays VARCHAR(100),
            statut_livreur ENUM('Disponible', 'Occupé') DEFAULT 'Disponible',
            last_connexion DATETIME DEFAULT NULL,
            notification_option ENUM('sms', 'email', 'none') DEFAULT 'sms',
            is_activated BOOLEAN DEFAULT TRUE,
            is_connected BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            vehicule_type VARCHAR(50),
            vehicule_immatriculation VARCHAR(50),
            FOREIGN KEY (id_users) REFERENCES users(id_users) ON DELETE CASCADE
        )",
        "CREATE TABLE IF NOT EXISTS categories (
            id_categorie INT AUTO_INCREMENT PRIMARY KEY,
            id_users INT NOT NULL,
            nom_categorie VARCHAR(255) NOT NULL,
            c_description TEXT,
            c_image VARCHAR(2083),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            statut_categorie ENUM('Active', 'Inactive') DEFAULT 'Active',
            FOREIGN KEY (id_users) REFERENCES users(id_users) ON DELETE CASCADE
        )",
        "CREATE TABLE IF NOT EXISTS produits (
            id_produit INT AUTO_INCREMENT PRIMARY KEY,
            id_users INT DEFAULT NULL,
            nom_produit VARCHAR(255) NOT NULL,
            p_description TEXT,
            id_categorie INT NOT NULL,
            prix DECIMAL(10, 2) NOT NULL,
            quantite_stock INT NOT NULL,
            statut_produit ENUM('Disponible', 'Indisponible', 'En rupture') DEFAULT 'Disponible', 
            est_en_promotion BOOLEAN DEFAULT FALSE,
            prix_promotionnel DECIMAL(10, 2) DEFAULT NULL,
            date_debut_promotion DATETIME DEFAULT NULL,
            date_fin_promotion DATETIME DEFAULT NULL,
            p_image VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (id_categorie) REFERENCES categories(id_categorie) ON DELETE CASCADE,
            FOREIGN KEY (id_users) REFERENCES users(id_users) ON DELETE SET NULL
        )",
        "CREATE TABLE commandes (
            id_commande INT AUTO_INCREMENT PRIMARY KEY,
            id_users INT NOT NULL,
            total_commande DECIMAL(10, 2) NOT NULL,
            statut_commande ENUM('En attente', 'Payée', 'Annulée', 'Livrée') DEFAULT 'En attente',
            moyen_paiement ENUM('Carte bancaire', 'Espèces', 'PayPal', 'Mobile Money') NOT NULL,
            est_a_livrer BOOLEAN DEFAULT TRUE,
            livraison_creer BOOLEAN DEFAULT FALSE,
            rue_livraison VARCHAR(255) NOT NULL,
            ville_livraison VARCHAR(100) NOT NULL,
            code_postal_livraison VARCHAR(20) NOT NULL,
            pays_livraison VARCHAR(100) NOT NULL,
            date_commande DATETIME DEFAULT CURRENT_TIMESTAMP,
            commentaires TEXT DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (id_users) REFERENCES users(id_users)
        )",
        "CREATE TABLE produits_commandes (
            id_commande INT NOT NULL,
            id_produit INT NOT NULL,
            quantite INT NOT NULL,
            prix_unitaire DECIMAL(10, 2) NOT NULL,
            total_produit DECIMAL(10, 2) NOT NULL,
            PRIMARY KEY (id_commande, id_produit),
            FOREIGN KEY (id_commande) REFERENCES commandes(id_commande),
            FOREIGN KEY (id_produit) REFERENCES produits(id_produit)
        )",
        "CREATE TABLE IF NOT EXISTS livraisons (
            id_livraison INT AUTO_INCREMENT PRIMARY KEY,
            id_commande INT NOT NULL,
            id_users INT DEFAULT NULL,
            id_livreur INT DEFAULT NULL,
            rue VARCHAR(255) NOT NULL, 
            ville VARCHAR(100) NOT NULL, 
            code_postal VARCHAR(20) NOT NULL,
            pays VARCHAR(100) NOT NULL, 
            statut_livraison ENUM('En attente', 'En cours', 'Livrée', 'Annulée') DEFAULT 'En attente', 
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            date_livraison_estimee DATETIME, 
            date_livraison_effective DATETIME DEFAULT NULL, 
            moyen_transport VARCHAR(50),
            commentaires TEXT, 
            FOREIGN KEY (id_commande) REFERENCES commandes(id_commande) ON DELETE CASCADE,
            FOREIGN KEY (id_livreur) REFERENCES livreurs(id_livreur) ON DELETE SET NULL,
            FOREIGN KEY (id_users) REFERENCES users(id_users) ON DELETE SET NULL
        )"
    ];

    // exécusion des requêtes pour créer chaque tables et les contraintes associées.
    foreach ($queries as $query) {
        $stmt = $pdo->prepare($query);
        $stmt->execute();
    }

    // quand tout ce passe bien jusque ici on affiche le message suivant sur la page.
    echo "Tables created successfully !<br />";

    // création du compte pour le super-admin de la plateforme.
    $super_admin = "INSERT INTO users (
    first_name, last_name, sexe, email, telephone, users_password, rue, ville, code_postal, pays, 
    last_connexion, notification_option, picture, user_type, is_activated, created_at, updated_at
    ) 
    VALUES (
        :first_name, :last_name, :sexe, :email, :telephone, :users_password, :rue, :ville, :code_postal, :pays, 
        NOW(), :notification_option, :picture, :user_type, :is_activated, NOW(), NOW()
    )";

    // Préparer la requête
    $stmt = $pdo->prepare($super_admin);

    // Lier les valeurs
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':sexe', $sexe);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telephone', $telephone);
    $stmt->bindParam(':users_password', $users_password);
    $stmt->bindParam(':rue', $rue);
    $stmt->bindParam(':ville', $ville);
    $stmt->bindParam(':code_postal', $code_postal);
    $stmt->bindParam(':pays', $pays);
    $stmt->bindParam(':notification_option', $notification_option);
    $stmt->bindParam(':picture', $picture);
    $stmt->bindParam(':user_type', $user_type);
    $stmt->bindParam(':is_activated', $is_activated);

    // Définir les valeurs
    $first_name = 'Aitondji';
    $last_name = 'Didier';
    $sexe = "M";
    $email = 'superadmin@example.com';
    $telephone = '+2290163116556';
    $users_password = password_hash('Tose@1998', PASSWORD_BCRYPT);
    $rue = 'Carrefour Houèto';
    $ville = 'Abomey-Calavi';
    $code_postal = '12345';
    $pays = 'Bénin';
    $notification_option = 'email';
    $picture = "";
    $user_type = 'admin';
    $is_activated = TRUE;

    // Exécuter la requête
    $stmt->execute();
    echo "<br />super-admin created successfully !";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$pdo = null;
