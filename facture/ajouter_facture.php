<?php
include '../db.php';

$numero = $_POST['numero'];
$client_id = $_POST['client_id'];
$date = $_POST['date'];
$produit_noms = $_POST['produit_nom'];
$quantites = $_POST['quantite'];
$prix_hts = $_POST['prix_ht'];
$montant_du = $_POST['montant_du'];
$montant_paye = $_POST['montant_paye'];
$montant_restant = $_POST['montant_restant'];

$total_ht = 0;
$total_tva = 0;
$total_ttc = 0;

// Calculer les totaux
foreach ($quantites as $index => $quantite) {
    $total_ht += round($quantite * $prix_hts[$index], 2);
}

$total_tva = round($total_ht * 0.20, 0);
$total_ttc = round($total_ht + $total_tva, 0);

// Insérer la facture
$sql = "INSERT INTO factures (numero, client_id, date, total_ht, total_tva, total_ttc) VALUES ('$numero', '$client_id', '$date', '$total_ht', '$total_tva', '$total_ttc')";
if ($conn->query($sql) === TRUE) {
    $facture_id = $conn->insert_id;

    // Insérer les détails de la facture
    foreach ($produit_noms as $index => $produit_nom) {
        $quantite = $quantites[$index];
        $prix_ht = round($prix_hts[$index], 2);
        $sql_detail = "INSERT INTO facture_details (facture_id, produit_nom, quantite, prix_ht) VALUES ('$facture_id', '$produit_nom', '$quantite', '$prix_ht')";
        if ($conn->query($sql_detail) === TRUE) {
            // Mettre à jour la quantité du produit dans la table produits
            $sql_update_produit = "UPDATE produits SET quantite = quantite - $quantite WHERE nom = '$produit_nom'";
            if ($conn->query($sql_update_produit) !== TRUE) {
                echo "Erreur lors de la mise à jour des quantités: " . $conn->error;
            } else {
                // Ajouter le mouvement de stock de type sortie
                $sql_produit = "SELECT id FROM produits WHERE nom = '$produit_nom'";
                $result_produit = $conn->query($sql_produit);
                if ($result_produit->num_rows > 0) {
                    $row_produit = $result_produit->fetch_assoc();
                    $produit_id = $row_produit['id'];

                    $sql_movement = "INSERT INTO stock_movements (produit_id, type_mouvement, quantite, date_mouvement) VALUES ('$produit_id', 'sortie', '$quantite', '$date')";
                    if ($conn->query($sql_movement) !== TRUE) {
                        echo "Erreur lors de l'ajout du mouvement de stock: " . $conn->error;
                    }
                }
            }
        } else {
            echo "Erreur lors de l'insertion des détails: " . $conn->error;
        }
    }

    // Insérer dans la table status_paiement
    $sql_paiement = "INSERT INTO status_paiement (numero, type_document, montant_paye, montant_du, montant_restant) VALUES ('$numero', 'facture', '$montant_paye', '$montant_du', '$montant_restant')";
    if ($conn->query($sql_paiement) !== TRUE) {
        echo "Erreur lors de l'insertion du statut de paiement: " . $conn->error;
        error_log("Erreur lors de l'insertion du statut de paiement: " . $conn->error); // Log the error
        error_log("SQL: " . $sql_paiement); // Log the SQL query
    }

    header("Location: gestion_documents.php");
} else {
    echo "Erreur: " . $sql . "<br>" . $conn->error;
    error_log("Erreur: " . $sql . "<br>" . $conn->error); // Log the error
}

$conn->close();
?>
