<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $ice = $_POST['ice'];
    $contact = $_POST['contact'];
    $adresse = $_POST['adresse'];

    $sql = "INSERT INTO clients (nom, ice, contact, adresse) VALUES ('$nom', '$ice', '$contact', '$adresse')";

    if ($conn->query($sql) === TRUE) {
        echo "Client ajouté avec succès";
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
    header("Location: clients.php");
}
?>
