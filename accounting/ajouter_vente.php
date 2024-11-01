<?php
include '../db.php';

$date = $_POST['date'];
$produits = $_POST['produit'];
$prix_ventes = $_POST['prix_vente'];

foreach ($produits as $index => $produit) {
    $prix_vente = $prix_ventes[$index];

    $sql = "INSERT INTO vente (produit, prix_vente, date) VALUES ('$produit', '$prix_vente', '$date')";

    if ($conn->query($sql) !== TRUE) {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
header("Location: accounting.php");
?>
