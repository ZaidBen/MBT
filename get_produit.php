<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $result = $conn->query("SELECT * FROM produits WHERE id = $id");

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(array('error' => 'Produit non trouvé'));
    }
} else {
    echo json_encode(array('error' => 'ID non spécifié'));
}

$conn->close();
?>