<?php
include '../db.php';

$date = $_POST['date'];
$produits = $_POST['produit'];
$prix_achats = $_POST['prix_achat'];
$fournisseurs = $_POST['fournisseur'];
$quantites = $_POST['quantite'];

foreach ($produits as $index => $produit) {
    $prix_achat = $prix_achats[$index];
    $fournisseur = $fournisseurs[$index];
    $quantite = $quantites[$index];

    $sql = "INSERT INTO achat (produit, prix_achat, fournisseur, quantite, date) VALUES ('$produit', '$prix_achat', '$fournisseur', '$quantite', '$date')";

    if ($conn->query($sql) !== TRUE) {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
header("Location: accounting.php");
?>
