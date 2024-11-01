<?php
include 'db.php';

$result = $conn->query("SELECT sm.produit_id, p.nom AS produit, sm.type_mouvement, sm.quantite, sm.date_mouvement 
                        FROM stock_movements sm 
                        JOIN produits p ON sm.produit_id = p.id");

$data = [];

while ($row = $result->fetch_assoc()) {
    $quantite = $row['type_mouvement'] === 'sortie' ? -$row['quantite'] : $row['quantite'];
    $data[] = [
        'produit' => $row['produit'],
        'quantite' => $quantite,
        'date_mouvement' => $row['date_mouvement']
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
?>
