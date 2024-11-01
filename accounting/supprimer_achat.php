<?php
include '../db.php';

$id = $_GET['id'];

$sql = "DELETE FROM achat WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    header("Location: accounting.php");
} else {
    echo "Erreur: " . $conn->error;
}

$conn->close();
?>
