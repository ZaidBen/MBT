<?php
include '../db.php';

$nom = $_POST['nom'];
$ville = $_POST['ville'];
$contact = $_POST['contact'];

$sql = "INSERT INTO fournisseur (nom, ville, contact) VALUES ('$nom', '$ville', '$contact')";

if ($conn->query($sql) === TRUE) {
    header("Location: accounting.php");
} else {
    echo "Erreur: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
