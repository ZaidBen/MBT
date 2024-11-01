<?php
include 'db.php';

header('Content-Type: application/json');

$sql = "SELECT nom, quantite FROM produits";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

$conn->close();
?>
