<?php

include_once("./config/database.php");
$database = new Database();

$jsonErrorData = json_encode([
    "status" => "Erreur",
    "message" => "La demande n'est pas valide, vérifiez l'url",
    "code" => 0
]);

switch ($url[0]) {
    case 'users':
        $users = new Users($database->getConnection());
        if (!empty($url[1])) {
            switch ($url[1]) {
                case 'is_activated':
                    echo $users->readAll(1);
                    break;
                case 'is_not_activated':
                    echo $users->readAll(0);
                    break;
                default:
                    echo $jsonErrorData;
                    break;
            }
        } else {
            echo $users->readAll();
        }
        break;
    case 'user':
        if (empty($url[1])) {
            echo $jsonErrorData;
        } else {
            $users = new Users($database->getConnection());
            $users->setIdUsers($url[1]);
            echo $users->readOne();
        }
        break;
    case 'produits':
        if (!empty($url[1])) {
            echo $jsonErrorData;
        } else {
            $produits = new Produits($database->getConnection());
            echo $produits->readAll();
        }
        break;
    case 'produit':
        if (empty($url[1])) {
            echo $jsonErrorData;
        } else {
            $produit = new Produits($database->getConnection());
            $produit->setIdProduit($url[1]);
            echo $produit->readOne();
        }
        break;
    case 'commandes':
        if (!empty($url[1])) {
            echo $jsonErrorData;
        } else {
            $commandes = new Commandes($database->getConnection());
            echo $commandes->readAll();
        }
        break;
    case 'commande':
        if (empty($url[1])) {
            echo $jsonErrorData;
        } else {
            $commande = new Commandes($database->getConnection());
            $commande->setIdCommande($url[1]);
            echo $commande->readOne();
        }
        break;
    case 'livreurs':
        $livreurs = new Livreurs($database->getConnection());
        if (!empty($url[1])) {
            switch ($url[1]) {
                case 'is_activated':
                    echo $livreurs->readAll(1);
                    break;
                case 'is_not_activated':
                    echo $livreurs->readAll(0);
                    break;
                default:
                    echo $jsonErrorData;
                    break;
            }
        } else {
            echo $livreurs->readAll();
        }
        break;
    case 'livreur':
        if (empty($url[1])) {
            echo $jsonErrorData;
        } else {
            $livreur = new Livreurs($database->getConnection());
            $livreur->setIdLivreur($url[1]);
            echo $livreur->readOne();
        }
        break;
    case 'categories':
        if (!empty($url[1])) {
            echo $jsonErrorData;
        } else {
            $categorie = new Categories($database->getConnection());
            echo $categorie->readAll();
        }
        break;
    case 'categorie':
        if (empty($url[1])) {
            echo $jsonErrorData;
        } else {
            $categorie = new Categories($database->getConnection());
            $categorie->setIdCategorie($url[1]);
            echo $categorie->readOne();
        }
        break;
    case 'livraisons':
        if (!empty($url[1])) {
            echo $jsonErrorData;
        } else {
            $livraisons = new Livraison($database->getConnection());
            echo $livraisons->readAll();
        }
        break;
    case 'livraison':
        if (empty($url[1])) {
            echo $jsonErrorData;
        } else {
            $livraison = new Livraison($database->getConnection());
            $livraison->setIdLivraison($url[1]);
            echo $livraison->readOne();
        }
        break;
    default:
        echo $jsonErrorData;
        break;
}
