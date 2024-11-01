<?php
include '../db.php';

$numero = $_POST['numero'];
$client_id = $_POST['client_id'];
$date = $_POST['date'];
$produit_ids = $_POST['produit_id'];
$quantites = $_POST['quantite'];
$prix_ttcs = $_POST['prix_ttc'];
$prix_hts = $_POST['prix_ht'];
$montant_paye = $_POST['montant_paye'];
$montant_du = $_POST['montant_du'];
$montant_restant = $montant_du - $montant_paye;

$total_ht = 0;
$total_tva = 0;
$total_ttc = 0;

// Calculer les totaux
foreach ($quantites as $index => $quantite) {
    $total_ht += $quantite * $prix_hts[$index];
}

$total_tva = round($total_ht * 0.20, 0);
$total_ttc = round($total_ht + $total_tva, 0);

// Insérer le bon de livraison
$sql = "INSERT INTO bons_de_livraison (numero, client_id, date, total_ht, total_tva, total_ttc) VALUES ('$numero', '$client_id', '$date', '$total_ht', '$total_tva', '$total_ttc')";
if ($conn->query($sql) === TRUE) {
    $bon_livraison_id = $conn->insert_id;

    // Insérer les détails du bon de livraison
    foreach ($produit_ids as $index => $produit_id) {
        $quantite = $quantites[$index];
        $prix_ht = $prix_hts[$index];

        // Récupérer le nom du produit
        $sql_produit = "SELECT nom FROM produits WHERE id='$produit_id'";
        $result_produit = $conn->query($sql_produit);
        if ($result_produit->num_rows > 0) {
            $row_produit = $result_produit->fetch_assoc();
            $produit_nom = $row_produit['nom'];

            $sql_detail = "INSERT INTO bon_livraison_details (bon_livraison_id, produit_id, produit_nom, quantite, prix_ht) VALUES ('$bon_livraison_id', '$produit_id', '$produit_nom', '$quantite', '$prix_ht')";
            
            if ($conn->query($sql_detail) !== TRUE) {
                echo "Erreur lors de l'insertion des détails: " . $conn->error;
            } else {
                // Mettre à jour la quantité du produit dans la table produits
                $sql_update_produit = "UPDATE produits SET quantite = quantite - $quantite WHERE id = '$produit_id'";
                if ($conn->query($sql_update_produit) !== TRUE) {
                    echo "Erreur lors de la mise à jour des quantités: " . $conn->error;
                } else {
                    // Ajouter le mouvement de stock de type sortie
                    $sql_movement = "INSERT INTO stock_movements (produit_id, type_mouvement, quantite, date_mouvement) VALUES ('$produit_id', 'sortie', '$quantite', '$date')";
                    if ($conn->query($sql_movement) !== TRUE) {
                        echo "Erreur lors de l'ajout du mouvement de stock: " . $conn->error;
                    }
                }
            }
        } else {
            echo "Produit non trouvé pour ID: " . $produit_id;
        }
    }

    // Insérer les informations de paiement dans la table status_paiement
    $sql_paiement = "INSERT INTO status_paiement (numero, type_document, montant_paye, montant_du, montant_restant) VALUES ('$numero', 'bon_de_livraison', '$montant_paye', '$montant_du', '$montant_restant')";
    if ($conn->query($sql_paiement) !== TRUE) {
        echo "Erreur lors de l'insertion du statut de paiement: " . $conn->error;
    }

    header("Location: gestion_documents.php");
} else {
    echo "Erreur: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
