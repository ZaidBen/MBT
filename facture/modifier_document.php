<?php
include '../db.php';

$id = $_POST['idDocument'];
$type = $_POST['typeDocument'];
$numero = $_POST['numero'];
$client_id = $_POST['client_id'];
$date = $_POST['date'];

if ($type == 'Facture') {
    $total_ht = $_POST['total_ht'];
    $total_tva = $_POST['total_tva'];
    $total_ttc = $_POST['total_ttc'];
    $montant_paye = $_POST['montant_paye'];
    $montant_restant = $total_ttc - $montant_paye;

    $sql = "UPDATE factures SET numero='$numero', client_id='$client_id', date='$date', total_ht='$total_ht', total_tva='$total_tva', total_ttc='$total_ttc' WHERE id=$id";
    $conn->query($sql);

    $sql_status = "UPDATE status_paiement SET montant_du='$total_ttc', montant_paye='$montant_paye', montant_restant='$montant_restant' WHERE numero='$numero' AND type_document='facture'";
    $conn->query($sql_status);

} else if ($type == 'Bon de Livraison') {
    $total_ht = $_POST['total_ht_bl'];
    $total_tva = $_POST['total_tva_bl'];
    $total_ttc = $_POST['total_ttc_bl'];
    $montant_paye = $_POST['montant_paye_bl'];
    $montant_restant = $total_ttc - $montant_paye;

    $sql = "UPDATE bons_de_livraison SET numero='$numero', client_id='$client_id', date='$date', total_ht='$total_ht', total_tva='$total_tva', total_ttc='$total_ttc' WHERE id=$id";
    $conn->query($sql);

    $sql_status = "UPDATE status_paiement SET montant_du='$total_ttc', montant_paye='$montant_paye', montant_restant='$montant_restant' WHERE numero='$numero' AND type_document='bon_de_livraison'";
    $conn->query($sql_status);

} else if ($type == 'Devis') {
    $total_ht = $_POST['total_ht_devis'];
    $total_tva = $_POST['total_tva_devis'];
    $total_ttc = $_POST['total_ttc_devis'];

    $sql = "UPDATE devis SET numero='$numero', client_id='$client_id', date='$date', total_ht='$total_ht', total_tva='$total_tva', total_ttc='$total_ttc' WHERE id=$id";
    $conn->query($sql);
}

$produit_noms = $_POST['produit_nom'];
$quantites = $_POST['quantite'];
$prix_ttcs = $_POST['prix_ttc'];

// Mettre à jour les quantités des produits avant la modification
if ($type == 'Facture') {
    $details_result = $conn->query("SELECT produit_nom, quantite FROM facture_details WHERE facture_id=$id");
    while ($detail = $details_result->fetch_assoc()) {
        $produit_nom = $detail['produit_nom'];
        $quantite = $detail['quantite'];
        $conn->query("UPDATE produits SET quantite = quantite + $quantite WHERE nom = '$produit_nom'");
    }
    $conn->query("DELETE FROM facture_details WHERE facture_id=$id");

    foreach ($produit_noms as $index => $produit_nom) {
        $quantite = $quantites[$index];
        $prix_ht = $prix_ttcs[$index] / 1.20;
        $sql_detail = "INSERT INTO facture_details (facture_id, produit_nom, quantite, prix_ht) VALUES ('$id', '$produit_nom', '$quantite', '$prix_ht')";
        $conn->query($sql_detail);

        // Mettre à jour les nouvelles quantités des produits
        $conn->query("UPDATE produits SET quantite = quantite - $quantite WHERE nom = '$produit_nom'");
    }
} else if ($type == 'Bon de Livraison') {
    $details_result = $conn->query("SELECT produit_id, quantite FROM bon_livraison_details WHERE bon_livraison_id=$id");
    while ($detail = $details_result->fetch_assoc()) {
        $produit_id = $detail['produit_id'];
        $quantite = $detail['quantite'];
        $conn->query("UPDATE produits SET quantite = quantite + $quantite WHERE id = '$produit_id'");
    }
    $conn->query("DELETE FROM bon_livraison_details WHERE bon_livraison_id=$id");

    foreach ($produit_noms as $index => $produit_nom) {
        $quantite = $quantites[$index];
        $prix_ht = $prix_ttcs[$index] / 1.20;

        $sql_produit_id = "SELECT id FROM produits WHERE nom='$produit_nom'";
        $result_produit_id = $conn->query($sql_produit_id);
        if ($result_produit_id->num_rows > 0) {
            $row_produit_id = $result_produit_id->fetch_assoc();
            $produit_id = $row_produit_id['id'];
            $sql_detail = "INSERT INTO bon_livraison_details (bon_livraison_id, produit_id, produit_nom, quantite, prix_ht) VALUES ('$id', '$produit_id', '$produit_nom', '$quantite', '$prix_ht')";
            $conn->query($sql_detail);

            // Mettre à jour les nouvelles quantités des produits
            $conn->query("UPDATE produits SET quantite = quantite - $quantite WHERE id = '$produit_id'");
        }
    }
} else if ($type == 'Devis') {
    $details_result = $conn->query("SELECT produit_id, quantite FROM devis_details WHERE devis_id=$id");
    while ($detail = $details_result->fetch_assoc()) {
        $produit_id = $detail['produit_id'];
        $quantite = $detail['quantite'];
        $conn->query("UPDATE produits SET quantite = quantite + $quantite WHERE id = '$produit_id'");
    }
    $conn->query("DELETE FROM devis_details WHERE devis_id=$id");

    foreach ($produit_noms as $index => $produit_nom) {
        $quantite = $quantites[$index];
        $prix_ht = $prix_ttcs[$index] / 1.20;

        $sql_produit_id = "SELECT id FROM produits WHERE nom='$produit_nom'";
        $result_produit_id = $conn->query($sql_produit_id);
        if ($result_produit_id->num_rows > 0) {
            $row_produit_id = $result_produit_id->fetch_assoc();
            $produit_id = $row_produit_id['id'];
            $sql_detail = "INSERT INTO devis_details (devis_id, produit_nom, quantite, prix_ht) VALUES ('$id', '$produit_nom', '$quantite', '$prix_ht')";
            $conn->query($sql_detail);

            // Mettre à jour les nouvelles quantités des produits
            $conn->query("UPDATE produits SET quantite = quantite - $quantite WHERE id = '$produit_id'");
        }
    }
}

header("Location: gestion_documents.php");
$conn->close();
?>
