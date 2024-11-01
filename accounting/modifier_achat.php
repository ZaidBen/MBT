<?php
include '../db.php';

$id = $_POST['id'];
$produit = $_POST['produit'];
$prix_achat = $_POST['prix_achat'];
$fournisseur = $_POST['fournisseur'];
$quantite = $_POST['quantite'];
$date = $_POST['date'];

$sql = "UPDATE achat SET produit='$produit', prix_achat='$prix_achat', fournisseur='$fournisseur', quantite='$quantite', date='$date' WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    header("Location: accounting.php");
} else {
    echo "Erreur: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
