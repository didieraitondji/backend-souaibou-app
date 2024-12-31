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

switch ($url[0]) {
    case 'user':
        if (empty($url[1])) {
            echo $jsonErrorData;
        } else {
            $user = new Users($database->getConnection());

            // Mise à jour des informations de l'utilisateur
            $user->setFirstName($data["first_name"]);
            $user->setLastName($data["last_name"]);
            $user->setSexe($data["sexe"]);
            $user->setTelephone($data["telephone"]);
            $user->setEmail($data["email"]);
            $user->setRue($data["rue"]);
            $user->setVille($data["ville"]);
            $user->setCodePostal($data["code_postal"]);
            $user->setPays($data["pays"]);
            $user->setNotificationOption($data["notification_option"]);
            $user->setPicture($data["picture"]);
            $user->setUserType($data["user_type"]);
            $user->setIsActivated($data["is_activated"]);
            $user->setIdUsers($url[1]);

            // Exécuter la mise à jour et renvoyer le résultat
            echo $user->update();
        }
        break;


    case 'produit':
        if (empty($url[1])) {
            echo $jsonErrorData;
        } else {
            $produit = new Produits($database->getConnection());

            $produit->setIdProduit($url[1]);
            $produit->setNomProduit($data["nom_produit"]);
            $produit->setPDescription($data["p_description"]);
            $produit->setIdCategorie($data["id_categorie"]);
            $produit->setPrix($data["prix"]);
            $produit->setQuantiteStock($data["quantite_stock"]);
            $produit->setStatutProduit($data["statut_produit"]);
            $produit->setEstEnPromotion($data["est_en_promotion"]);
            $produit->setPrixPromotionnel($data["prix_promotionnel"]);
            $produit->setDateDebutPromotion($data["date_debut_promotion"]);
            $produit->setDateFinPromotion($data["date_fin_promotion"]);
            $produit->setPImage($data["p_image"]);


            echo $produit->update();
        }
        break;
    case 'commande':
        if (empty($url[1])) {
            echo $jsonErrorData;
        } else {
            $commande = new Commandes($database->getConnection());

            $commande->setIdCommande($url[1]);
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

            echo $commande->update();
        }
        break;
    case 'livreur':
        if (empty($url[1])) {
            echo $jsonErrorData;
        } else {

            $livreur = new Livreurs($database->getConnection());


            $livreur->setIdLivreur($url[1]);
            $livreur->setFirstName($data["first_name"]);
            $livreur->setLastName($data["last_name"]);
            $livreur->setEmail($data["email"]);
            $livreur->setTelephone($data["telephone"]);
            $livreur->setSexe($data["sexe"]);
            $livreur->setRue($data["rue"]);
            $livreur->setVille($data["ville"]);
            $livreur->setCodePostal($data["code_postal"]);
            $livreur->setPays($data["pays"]);
            $livreur->setStatutLivreur($data["statut_livreur"]);
            $livreur->setNotificationOption($data["notification_option"]);
            $livreur->setIsActivated($data["is_activated"]);
            $livreur->setIsConnected($data["is_connected"]);
            $livreur->setVehiculeType($data["vehicule_type"]);
            $livreur->setVehiculeImmatriculation($data["vehicule_immatriculation"]);


            echo $livreur->update();
        }
        break;
    case 'livraison':
        if (empty($url[1])) {
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
            $livraison->setDateLivraisonEffective($data["date_livraison_effective"]);
            $livraison->setMoyenTransport($data["moyen_transport"]);
            $livraison->setCommentaires($data["commentaires"]);
            $livraison->setIdLivraison($url[1]);

            echo $livraison->update();
        }
        break;
    case 'categorie':
        if (empty($url[1])) {
            echo $jsonErrorData;
        } else {
            $categorie = new Categories($database->getConnection());

            $categorie->setIdCategorie($url[1]);
            $categorie->setNomCategorie($data["nom_categorie"]);
            $categorie->setCDescription($data["c_description"]);
            $categorie->setCImage($data["c_image"]);
            $categorie->setStatutCategorie($data['statut_categorie']);

            echo $categorie->update();
        }
        break;

    default:
        echo $jsonErrorData;
        break;
}
