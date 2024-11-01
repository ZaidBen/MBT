<?php
include '../db.php';

header('Content-Type: application/json');

// Capture et affiche les valeurs reçues pour le débogage
$id = isset($_GET['id']) ? $_GET['id'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';

if (empty($id) || empty($type)) {
    echo json_encode(['error' => 'Invalid ID or type']);
    exit;
}

$response = [];

if ($type == 'Facture') {
    $sql = "SELECT f.id, f.numero, f.client_id, f.date, f.total_ht, f.total_tva, f.total_ttc, c.nom AS client_nom, c.adresse, c.contact, c.ice,
            sp.montant_du, sp.montant_paye, sp.montant_restant 
            FROM factures f 
            JOIN clients c ON f.client_id = c.id 
            JOIN status_paiement sp ON sp.numero = f.numero AND sp.type_document = 'facture'
            WHERE f.id='$id'";
} else if ($type == 'Bon de Livraison') {
    $sql = "SELECT b.id, b.numero, b.client_id, b.date, b.total_ht, b.total_tva, b.total_ttc, c.nom AS client_nom, c.adresse, c.contact, c.ice,
            sp.montant_du, sp.montant_paye, sp.montant_restant 
            FROM bons_de_livraison b 
            JOIN clients c ON b.client_id = c.id 
            JOIN status_paiement sp ON sp.numero = b.numero AND sp.type_document = 'bon_de_livraison'
            WHERE b.id='$id'";
} else if ($type == 'Devis') {
    $sql = "SELECT d.id, d.numero, d.client_id, d.date, d.total_ht, d.total_tva, d.total_ttc, c.nom AS client_nom, c.adresse, c.contact, c.ice 
            FROM devis d 
            JOIN clients c ON d.client_id = c.id 
            WHERE d.id='$id'";
} else {
    echo json_encode(['error' => 'Invalid document type']);
    exit;
}

$result = $conn->query($sql);

if ($result === false) {
    echo json_encode(['error' => 'SQL Error: ' . $conn->error]);
    exit;
}

if ($result->num_rows > 0) {
    $response = $result->fetch_assoc();
    $response['type'] = $type;

    if ($type == 'Facture') {
        $sqlProduits = "SELECT d.id, d.produit_nom AS nom, d.quantite, d.prix_ht 
                        FROM facture_details d 
                        WHERE d.facture_id='$id'";
    } else if ($type == 'Bon de Livraison') {
        $sqlProduits = "SELECT d.id, d.produit_nom AS nom, d.quantite, d.prix_ht, ROUND(d.prix_ht * 1.20, 2) AS prix_ttc 
                        FROM bon_livraison_details d 
                        WHERE d.bon_livraison_id='$id'";
    } else if ($type == 'Devis') {
        $sqlProduits = "SELECT d.id, d.produit_nom AS nom, d.quantite, d.prix_ht, ROUND(d.prix_ht * 1.20, 2) AS prix_ttc 
                        FROM devis_details d 
                        WHERE d.devis_id='$id'";
    }

    $resultProduits = $conn->query($sqlProduits);

    if ($resultProduits === false) {
        echo json_encode(['error' => 'SQL Error (Products): ' . $conn->error]);
        exit;
    }

    $produits = [];
    while ($row = $resultProduits->fetch_assoc()) {
        $produits[] = $row;
    }

    $response['produits'] = $produits;
} else {
    echo json_encode(['error' => 'Document not found']);
    exit;
}

echo json_encode($response);

$conn->close();
?>
