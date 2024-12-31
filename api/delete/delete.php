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
            $user->setIdUsers($url[1]);
            echo $user->delete();
        }
        break;


    case 'produit':
        if (empty($url[1])) {
            echo $jsonErrorData;
        } else {
            $produit = new Produits($database->getConnection());
            $produit->setIdProduit($url[1]);
            echo $produit->delete();
        }
        break;
    case 'commande':
        if (empty($url[1])) {
            echo $jsonErrorData;
        } else {
            $commande = new Commandes($database->getConnection());
            $commande->setIdCommande($url[1]);
            echo $commande->delete();
        }
        break;
    case 'livreur':
        if (empty($url[1])) {
            echo $jsonErrorData;
        } else {
            $livreur = new Livreurs($database->getConnection());
            $livreur->setIdLivreur($url[1]);
            echo $livreur->delete();
        }
        break;
    case 'livraison':
        if (empty($url[1])) {
            echo $jsonErrorData;
        } else {
            $livraison = new Livraison($database->getConnection());
            $livraison->setIdLivraison($url[1]);
            echo $livraison->delete();
        }
        break;
    case 'categorie':
        if (empty($url[1])) {
            echo $jsonErrorData;
        } else {
            $categorie = new Categories($database->getConnection());
            $categorie->setIdCategorie($url[1]);
            echo $categorie->delete();
        }
        break;

    default:
        echo $jsonErrorData;
        break;
}
