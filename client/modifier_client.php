<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['idClient'];
    $nom = $_POST['nom'];
    $ice = $_POST['ice'];
    $contact = $_POST['contact'];
    $adresse = $_POST['adresse'];

    $sql = "UPDATE clients SET nom='$nom', ice='$ice', contact='$contact', adresse='$adresse' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Client mis à jour avec succès";
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
    header("Location: clients.php");
}
?>
