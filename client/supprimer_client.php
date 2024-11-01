<?php
include '../db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM clients WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Client supprimé avec succès";
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
    header("Location: clients.php");
}
?>
