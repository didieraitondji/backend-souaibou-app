<?php
class Commandes
{
    // Attributs privés
    private $conn;
    private $id_commande;
    private $id_users;
    private $total_commande;
    private $statut_commande;
    private $moyen_paiement;
    private $est_a_livrer;
    private $livraison_creer;
    private $rue_livraison;
    private $ville_livraison;
    private $code_postal_livraison;
    private $pays_livraison;
    private $date_commande;
    private $commentaires;
    private $produits = [];

    // Constructeur
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Getters et setters

    public function getProduits(): array
    {
        return $this->produits;
    }

    // Setter pour $produits (remplace tout le tableau)
    public function setProduits(array $produits): void
    {
        $this->produits = $produits;
    }


    public function getIdCommande()
    {
        return $this->id_commande;
    }
    public function setIdCommande($id_commande)
    {
        $this->id_commande = $id_commande;
    }

    public function getIdUsers()
    {
        return $this->id_users;
    }
    public function setIdUsers($id_users)
    {
        $this->id_users = $id_users;
    }

    public function getTotalCommande()
    {
        return $this->total_commande;
    }
    public function setTotalCommande($total_commande)
    {
        $this->total_commande = $total_commande;
    }

    public function getStatutCommande()
    {
        return $this->statut_commande;
    }
    public function setStatutCommande($statut_commande)
    {
        $this->statut_commande = $statut_commande;
    }

    public function getMoyenPaiement()
    {
        return $this->moyen_paiement;
    }
    public function setMoyenPaiement($moyen_paiement)
    {
        $this->moyen_paiement = $moyen_paiement;
    }

    public function getEstALivrer()
    {
        return $this->est_a_livrer;
    }
    public function setEstALivrer($est_a_livrer)
    {
        $this->est_a_livrer = $est_a_livrer;
    }

    public function getLivraisonCreer()
    {
        return $this->livraison_creer;
    }
    public function setLivraisonCreer($livraison_creer)
    {
        $this->livraison_creer = $livraison_creer;
    }

    public function getRueLivraison()
    {
        return $this->rue_livraison;
    }
    public function setRueLivraison($rue_livraison)
    {
        $this->rue_livraison = $rue_livraison;
    }

    public function getVilleLivraison()
    {
        return $this->ville_livraison;
    }
    public function setVilleLivraison($ville_livraison)
    {
        $this->ville_livraison = $ville_livraison;
    }

    public function getCodePostalLivraison()
    {
        return $this->code_postal_livraison;
    }
    public function setCodePostalLivraison($code_postal_livraison)
    {
        $this->code_postal_livraison = $code_postal_livraison;
    }

    public function getPaysLivraison()
    {
        return $this->pays_livraison;
    }
    public function setPaysLivraison($pays_livraison)
    {
        $this->pays_livraison = $pays_livraison;
    }

    public function getDateCommande()
    {
        return $this->date_commande;
    }
    public function setDateCommande($date_commande)
    {
        $this->date_commande = $date_commande;
    }

    public function getCommentaires()
    {
        return $this->commentaires;
    }
    public function setCommentaires($commentaires)
    {
        $this->commentaires = $commentaires;
    }

    // Méthode create
    public function create()
    {
        try {
            // Démarrer une transaction
            $this->conn->beginTransaction();

            // Insertion dans la table commandes
            $query = "INSERT INTO commandes (id_users, total_commande, statut_commande, moyen_paiement, est_a_livrer, livraison_creer, rue_livraison, ville_livraison, code_postal_livraison, pays_livraison, commentaires)
                      VALUES (:id_users, :total_commande, :statut_commande, :moyen_paiement, :est_a_livrer, :livraison_creer, :rue_livraison, :ville_livraison, :code_postal_livraison, :pays_livraison, :commentaires)";
            $stmt = $this->conn->prepare($query);

            // Liaison des paramètres
            $stmt->bindParam(':id_users', $this->id_users);
            $stmt->bindParam(':total_commande', $this->total_commande);
            $stmt->bindParam(':statut_commande', $this->statut_commande);
            $stmt->bindParam(':moyen_paiement', $this->moyen_paiement);
            $stmt->bindParam(':est_a_livrer', $this->est_a_livrer);
            $stmt->bindParam(':livraison_creer', $this->livraison_creer);
            $stmt->bindParam(':rue_livraison', $this->rue_livraison);
            $stmt->bindParam(':ville_livraison', $this->ville_livraison);
            $stmt->bindParam(':code_postal_livraison', $this->code_postal_livraison);
            $stmt->bindParam(':pays_livraison', $this->pays_livraison);
            $stmt->bindParam(':commentaires', $this->commentaires);

            $stmt->execute();

            // Récupérer l'ID de la commande créée
            $this->id_commande = $this->conn->lastInsertId();

            // Insertion des produits dans produits_commandes
            $queryProduit = "INSERT INTO produits_commandes (id_commande, id_produit, quantite, prix_unitaire, total_produit)
                             VALUES (:id_commande, :id_produit, :quantite, :prix_unitaire, :total_produit)";
            $stmtProduit = $this->conn->prepare($queryProduit);

            foreach ($this->produits as $produit) {
                $total_produit = $produit['quantite'] * $produit['prix_unitaire'];
                $stmtProduit->bindParam(':id_commande', $this->id_commande);
                $stmtProduit->bindParam(':id_produit', $produit['id_produit']);
                $stmtProduit->bindParam(':quantite', $produit['quantite']);
                $stmtProduit->bindParam(':prix_unitaire', $produit['prix_unitaire']);
                $stmtProduit->bindParam(':total_produit', $total_produit);
                $stmtProduit->execute();
            }

            // Valider la transaction
            $this->conn->commit();

            return json_encode(['status' => 'success', 'message' => 'Commande et produits ajoutés avec succès']);
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            $this->conn->rollBack();
            return json_encode(['status' => 'error', 'message' => 'Échec de la création : ' . $e->getMessage()]);
        }
    }


    // Méthode readOne
    public function readOne()
    {
        try {
            // Vérifier si l'ID de commande est valide
            if (!isset($this->id_commande) || empty($this->id_commande)) {
                return json_encode(['status' => 'error', 'message' => 'ID de commande invalide']);
            }

            // Requête pour récupérer la commande
            $queryCommande = "SELECT * FROM commandes WHERE id_commande = :id_commande";
            $stmtCommande = $this->conn->prepare($queryCommande);
            $stmtCommande->bindParam(':id_commande', $this->id_commande);
            $stmtCommande->execute();
            $commande = $stmtCommande->fetch(PDO::FETCH_ASSOC);

            // Vérifier si la commande existe
            if (!$commande) {
                return json_encode(['status' => 'error', 'message' => 'La commande n\'existe pas !']);
            }

            // Requête pour récupérer les produits associés
            $queryProduits = "SELECT * FROM produits_commandes pc JOIN produits p ON pc.id_produit = p.id_produit WHERE pc.id_commande = :id_commande";

            $stmtProduits = $this->conn->prepare($queryProduits);
            $stmtProduits->bindParam(':id_commande', $this->id_commande);
            $stmtProduits->execute();
            $produits = $stmtProduits->fetchAll(PDO::FETCH_ASSOC);

            // Combiner la commande et ses produits
            $commande['produits'] = $produits;

            // Retourner la réponse JSON
            return json_encode(
                ['status' => 'success', 'message' => 'Commande trouvée avec succès', 'data' => $commande, 'produit' => $produits],
                JSON_UNESCAPED_UNICODE
            );
        } catch (Exception $e) {
            // Gestion des exceptions
            return json_encode(['status' => 'error', 'message' => 'Une erreur est survenue : ' . $e->getMessage()]);
        }
    }



    // Méthode readAll
    public function readAll()
    {
        try {
            // Requête pour récupérer toutes les commandes
            $queryCommandes = "SELECT * FROM commandes";
            $stmtCommandes = $this->conn->prepare($queryCommandes);
            $stmtCommandes->execute();
            $commandes = $stmtCommandes->fetchAll(PDO::FETCH_ASSOC);

            // Vérifier si des commandes existent
            if (!$commandes || empty($commandes)) {
                return json_encode(['status' => 'error', 'message' => 'Aucune commande trouvée.']);
            }

            // Ajouter les produits associés à chaque commande
            foreach ($commandes as &$commande) {
                $queryProduits = "SELECT * FROM produits_commandes pc JOIN produits p ON pc.id_produit = p.id_produit
                WHERE pc.id_commande = :id_commande";

                $stmtProduits = $this->conn->prepare($queryProduits);
                $stmtProduits->bindParam(':id_commande', $commande['id_commande']);
                $stmtProduits->execute();
                $commande['produits'] = $stmtProduits->fetchAll(PDO::FETCH_ASSOC);
            }

            // Retourner la réponse JSON
            return json_encode(
                ['status' => 'success', 'message' => 'Commandes récupérées avec succès.', 'data' => $commandes],
                JSON_UNESCAPED_UNICODE
            );
        } catch (Exception $e) {
            // Gestion des exceptions
            return json_encode(['status' => 'error', 'message' => 'Une erreur est survenue : ' . $e->getMessage()]);
        }
    }


    // Méthode update
    public function update()
    {
        try {
            // Démarrer une transaction
            $this->conn->beginTransaction();

            // Mise à jour des détails de la commande
            $query = "UPDATE commandes
                  SET total_commande = :total_commande, statut_commande = :statut_commande, 
                      moyen_paiement = :moyen_paiement, est_a_livrer = :est_a_livrer, livraison_creer = :livraison_creer, rue_livraison = :rue_livraison, ville_livraison = :ville_livraison, 
                      code_postal_livraison = :code_postal_livraison, pays_livraison = :pays_livraison, 
                      commentaires = :commentaires
                  WHERE id_commande = :id_commande";

            $stmt = $this->conn->prepare($query);

            // Liaison des paramètres pour la commande
            $stmt->bindParam(':id_commande', $this->id_commande);
            $stmt->bindParam(':total_commande', $this->total_commande);
            $stmt->bindParam(':statut_commande', $this->statut_commande);
            $stmt->bindParam(':moyen_paiement', $this->moyen_paiement);
            $stmt->bindParam(':est_a_livrer', $this->est_a_livrer);
            $stmt->bindParam(':livraison_creer', $this->livraison_creer);
            $stmt->bindParam(':rue_livraison', $this->rue_livraison);
            $stmt->bindParam(':ville_livraison', $this->ville_livraison);
            $stmt->bindParam(':code_postal_livraison', $this->code_postal_livraison);
            $stmt->bindParam(':pays_livraison', $this->pays_livraison);
            $stmt->bindParam(':commentaires', $this->commentaires);

            $stmt->execute();

            // Suppression des produits existants pour cette commande
            $queryDeleteProduits = "DELETE FROM produits_commandes WHERE id_commande = :id_commande";
            $stmtDelete = $this->conn->prepare($queryDeleteProduits);
            $stmtDelete->bindParam(':id_commande', $this->id_commande);
            $stmtDelete->execute();

            // Réinsertion des produits mis à jour
            $queryProduit = "INSERT INTO produits_commandes (id_commande, id_produit, quantite, prix_unitaire, total_produit)
                         VALUES (:id_commande, :id_produit, :quantite, :prix_unitaire, :total_produit)";
            $stmtProduit = $this->conn->prepare($queryProduit);

            foreach ($this->produits as $produit) {
                $total_produit = $produit['quantite'] * $produit['prix_unitaire'];
                $stmtProduit->bindParam(':id_commande', $this->id_commande);
                $stmtProduit->bindParam(':id_produit', $produit['id_produit']);
                $stmtProduit->bindParam(':quantite', $produit['quantite']);
                $stmtProduit->bindParam(':prix_unitaire', $produit['prix_unitaire']);
                $stmtProduit->bindParam(':total_produit', $total_produit);
                $stmtProduit->execute();
            }

            // Valider la transaction
            $this->conn->commit();

            return json_encode(['status' => 'success', 'message' => 'Commande et produits mis à jour avec succès']);
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            $this->conn->rollBack();
            return json_encode(['status' => 'error', 'message' => 'Échec de la mise à jour : ' . $e->getMessage()]);
        }
    }


    // Méthode delete
    public function delete()
    {
        // Vérification préalable si l'ID de la commande est défini
        if (empty($this->id_commande)) {
            return json_encode([
                'status' => 'error',
                'message' => 'ID de commande non fourni ou invalide'
            ]);
        }

        try {
            // Démarrer une transaction
            $this->conn->beginTransaction();

            // Supprimer les entrées associées dans produits_commandes
            $queryProduitsCommandes = "DELETE FROM produits_commandes WHERE id_commande = :id_commande";
            $stmtProduitsCommandes = $this->conn->prepare($queryProduitsCommandes);
            $stmtProduitsCommandes->bindParam(':id_commande', $this->id_commande, PDO::PARAM_INT);
            $stmtProduitsCommandes->execute();

            // Vérifier si des produits étaient liés à la commande
            $produitsSupprimes = $stmtProduitsCommandes->rowCount();

            // Supprimer l'entrée dans commandes
            $queryCommandes = "DELETE FROM commandes WHERE id_commande = :id_commande";
            $stmtCommandes = $this->conn->prepare($queryCommandes);
            $stmtCommandes->bindParam(':id_commande', $this->id_commande, PDO::PARAM_INT);
            $stmtCommandes->execute();

            // Vérifier si la commande a été supprimée
            $commandeSupprimee = $stmtCommandes->rowCount();

            if ($commandeSupprimee > 0) {
                // Valider la transaction
                $this->conn->commit();
                return json_encode([
                    'status' => 'success',
                    'message' => "Commande supprimée avec succès, ainsi que $produitsSupprimes produit(s) associé(s)"
                ]);
            }

            // Si aucune commande n'a été trouvée
            $this->conn->rollBack();
            return json_encode([
                'status' => 'error',
                'message' => 'Aucune commande trouvée avec cet ID'
            ]);
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            $this->conn->rollBack();
            return json_encode([
                'status' => 'error',
                'message' => 'Échec de la suppression : ' . $e->getMessage()
            ]);
        }
    }
}
