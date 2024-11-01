<?php
include '../db.php';

$numero = $_POST['numero'];
$client_id = $_POST['client_id'];
$date = $_POST['date'];
$produit_noms = $_POST['produit_nom'];
$quantites = $_POST['quantite'];
$prix_ttcs = $_POST['prix_ttc'];

$total_ht = 0;
$total_tva = 0;
$total_ttc = 0;

// Calculer les totaux
foreach ($quantites as $index => $quantite) {
    $prix_ttc = $prix_ttcs[$index];
    $prix_ht = $prix_ttc / 1.20;
    $total_ht += $quantite * $prix_ht;
}

$total_tva = $total_ht * 0.20;
$total_ttc = $total_ht + $total_tva;

// Insérer le devis
$sql = "INSERT INTO devis (numero, client_id, date, total_ht, total_tva, total_ttc) VALUES ('$numero', '$client_id', '$date', '$total_ht', '$total_tva', '$total_ttc')";
if ($conn->query($sql) === TRUE) {
    $devis_id = $conn->insert_id;

    // Insérer les détails du devis
    foreach ($produit_noms as $index => $produit_nom) {
        $quantite = $quantites[$index];
        $prix_ttc = $prix_ttcs[$index];
        $prix_ht = $prix_ttc / 1.20;
        $sql_detail = "INSERT INTO devis_details (devis_id, produit_nom, quantite, prix_ht) VALUES ('$devis_id', '$produit_nom', '$quantite', '$prix_ht')";
        $conn->query($sql_detail);
    }

    header("Location: gestion_documents.php");
} else {
    echo "Erreur: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
