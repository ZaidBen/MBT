<?php
include '../db.php';

$id = $_POST['id'];
$produit = $_POST['produit'];
$prix_vente = $_POST['prix_vente'];
$date = $_POST['date'];

$sql = "UPDATE vente SET produit='$produit', prix_vente='$prix_vente', date='$date' WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    header("Location: accounting.php");
} else {
    echo "Erreur: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
