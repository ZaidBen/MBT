<?php
include '../db.php';

$nom = $_POST['nouveau_nom'];
$adresse = $_POST['nouveau_adresse'];
$contact = $_POST['nouveau_contact'];
$ice = $_POST['nouveau_ice'];

$sql = "INSERT INTO clients (nom, adresse, contact, ice) VALUES ('$nom', '$adresse', '$contact', '$ice')";
if ($conn->query($sql) === TRUE) {
    $client_id = $conn->insert_id;
    echo json_encode(['success' => true, 'client_id' => $client_id, 'client_nom' => $nom]);
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]);
}

$conn->close();
?>
