<?php

include_once("./config/database.php");
$database = new Database();

// récupération des données
$input = file_get_contents("php://input");
$data = json_decode($input, true);

$jsonErrorData = json_encode(array(
    "status" => "error",
    "message" => "Identifiant invalide",
));

$token = new Auth();


switch ($url[0]) {
    case 'user':
        if (!empty($url[1])) {
            switch ($url[1]) {
                case 'login':
                    if (!empty($url[2])) {
                        echo $jsonErrorData;
                    } else {
                        $userAuth = new UsersAuth($database->getConnection());
                        echo $userAuth->login($data["telephone"], $data["password"]);
                    }
                    break;
                case 'logout':
                    if (!empty($url[2])) {
                        echo $jsonErrorData;
                    } else {
                        $userAuth = new UsersAuth($database->getConnection());
                        echo $userAuth->logout($data["user_id"]);
                    }
                    break;
                case 'is_login':
                    if (!empty($url[2])) {
                        echo $jsonErrorData;
                    } else {
                        $userAuth = new UsersAuth($database->getConnection());
                        echo $userAuth->isLoggedIn($data["user_id"]);
                    }
                    break;

                default:
                    echo $jsonErrorData;
                    break;
            }
        } else {
            $user = new Users($database->getConnection());
            $user->setFirstName($data["first_name"]);
            $user->setLastName($data["last_name"]);
            $user->setSexe($data["sexe"]);
            $user->setTelephone($data["telephone"]);
            $user->setRue($data["rue"]);
            $user->setUsersPassword($data["password"]);
            $user->setEmail($data["email"]);
            $user->setVille($data["ville"]);
            $user->setPays($data["pays"]);
            $user->setCodePostal($data["code_postal"]);
            $user->setNotificationOption($data["notification_option"]);

            // creation de l'utilisateur maintenant
            echo $user->create();
        }
        break;

    case 'produit':
        if (!empty($url[1])) {
            echo $jsonErrorData;
        } else {
            $produit = new Produits($database->getConnection());

            $produit->setIdUsers($data["id_users"]);
            $produit->setNomProduit($data["nom_produit"]);
            $produit->setPDescription($data["p_description"]);
            $produit->setIdCategorie($data["id_categorie"]);
            $produit->setPrix($data["prix"]);
            $produit->setQuantiteStock($data["quantite_stock"]);
            $produit->setEstEnPromotion($data["est_en_promotion"]);
            $produit->setPrixPromotionnel($data["prix_promotionnel"]);
            $produit->setDateDebutPromotion($data["date_debut_promotion"]);
            $produit->setDateFinPromotion($data["date_fin_promotion"]);
            $produit->setPImage($data["p_image"]);

            echo $produit->create();
        }
        break;
    case 'commande':
        if (!empty($url[1])) {
            echo $jsonErrorData;
        } else {
            $commande = new Commandes($database->getConnection());

            $commande->setIdUsers($data['id_users']);
            $commande->setTotalCommande($data['total_commande']);
            $commande->setStatutCommande($data['statut_commande']);
            $commande->setMoyenPaiement($data['moyen_paiement']);
            $commande->setEstALivrer($data['est_a_livrer']);
            $commande->setLivraisonCreer($data['livraison_creer']);
            $commande->setRueLivraison($data['rue_livraison']);
            $commande->setVilleLivraison($data['ville_livraison']);
            $commande->setCodePostalLivraison($data['code_postal_livraison']);
            $commande->setPaysLivraison($data['pays_livraison']);
            $commande->setCommentaires($data['commentaires']);
            $commande->setProduits($data['produits']);

            echo $commande->create();
        }
        break;
    case 'livreur':
        if (!empty($url[1])) {
            switch ($url[1]) {
                case 'login':
                    if (!empty($url[2])) {
                        echo $jsonErrorData;
                    } else {
                        $livreurAuth = new LivreursAuth($database->getConnection());
                        echo $livreurAuth->login($data["telephone"], $data["password"]);
                    }
                    break;
                case 'logout':
                    if (!empty($url[2])) {
                        echo $jsonErrorData;
                    } else {
                        $livreurAuth = new LivreursAuth($database->getConnection());
                        echo $livreurAuth->logout($data["livreur_id"]);
                    }
                    break;
                case 'is_login':
                    if (!empty($url[2])) {
                        echo $jsonErrorData;
                    } else {
                        $livreurAuth = new LivreursAuth($database->getConnection());
                        echo $livreurAuth->isLoggedIn($data["livreur_id"]);
                    }
                    break;

                default:
                    echo $jsonErrorData;
                    break;
            }
        } else {
            $livreur = new Livreurs($database->getConnection());
            $livreur->setIdUsers($data["id_users"]);
            $livreur->setFirstName($data["first_name"]);
            $livreur->setLastName($data["last_name"]);
            $livreur->setEmail($data["email"]);
            $livreur->setTelephone($data["telephone"]);
            $livreur->setSexe($data["sexe"]);
            $livreur->setRue($data["rue"]);
            $livreur->setVille($data["ville"]);
            $livreur->setCodePostal($data["code_postal"]);
            $livreur->setPays($data["pays"]);
            $livreur->setVehiculeType($data["vehicule_type"]);
            $livreur->setVehiculeImmatriculation($data["vehicule_immatriculation"]);

            echo $livreur->create();
        }
        break;
    case 'livraison':
        if (!empty($url[1])) {
            echo $jsonErrorData;
        } else {
            $livraison = new Livraison($database->getConnection());

            $livraison->setIdCommande($data["id_commande"]);
            $livraison->setIdUsers($data["id_users"]);
            $livraison->setIdLivreur($data["id_livreur"]);
            $livraison->setRue($data["rue"]);
            $livraison->setVille($data["ville"]);
            $livraison->setCodePostal($data["code_postal"]);
            $livraison->setPays($data["pays"]);
            $livraison->setStatutLivraison($data["statut_livraison"]);
            $livraison->setDateLivraisonEstimee($data["date_livraison_estimee"]);
            $livraison->setMoyenTransport($data["moyen_transport"]);
            $livraison->setCommentaires($data["commentaires"]);

            echo $livraison->create();
        }
        break;
    case 'categorie':
        if (!empty($url[1])) {
            echo $jsonErrorData;
        } else {
            $categorie = new Categories($database->getConnection());

            $categorie->setIdUsers($data["id_users"]);
            $categorie->setIdTypeProduit($data["id_type_produit"]);
            $categorie->setNomCategorie($data["nom_categorie"]);
            $categorie->setCDescription($data["c_description"]);
            $categorie->setCImage($data["c_image"]);
            $categorie->setStatutCategorie($data['statut_categorie']);

            echo $categorie->create();
        }
        break;
    case 'type_produit':
        if (!empty($url[1])) {
            echo $jsonErrorData;
        } else {
            $typeProduit = new TypeProduit($database->getConnection());

            $typeProduit->setTDescription($data['t_description']);
            $typeProduit->setNomType($data['nom_type']);

            echo $typeProduit->create();
        }
        break;

    default:
        echo $jsonErrorData;
        break;
}

/*
if ($data['token'] && $token->verifyToken($data['token'])) {
} else {
    echo json_encode(array(
        "status" => "error",
        "message" => "Token invalide !",
    ));
}*/
