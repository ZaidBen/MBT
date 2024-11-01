<?php
include 'db.php';

// Récupérer les données du formulaire
$produit_ids = $_POST['produit_nom'];
$type_mouvements = $_POST['type_mouvement'];
$quantites = $_POST['quantite'];

$response = ['status' => 'success', 'message' => 'Mouvement de stock enregistré avec succès'];

// Vérifiez que toutes les listes ont la même longueur
if (count($produit_ids) !== count($type_mouvements) || count($produit_ids) !== count($quantites)) {
    $response['status'] = 'error';
    $response['message'] = 'Données incohérentes';
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Boucle pour chaque produit et effectuer les opérations d'insertion et de mise à jour
foreach ($produit_ids as $index => $produit_id) {
    $type_mouvement = $type_mouvements[$index];
    $quantite = $quantites[$index];

    // Insérer le mouvement de stock
    $sql = "INSERT INTO mouvements (produit_id, type_mouvement, quantite) VALUES ('$produit_id', '$type_mouvement', '$quantite')";
    if ($conn->query($sql) === TRUE) {
        // Mettre à jour la quantité du produit
        if ($type_mouvement == 'entree') {
            $update_sql = "UPDATE produits SET quantite = quantite + $quantite WHERE id = $produit_id";
        } elseif ($type_mouvement == 'sortie') {
            $update_sql = "UPDATE produits SET quantite = quantite - $quantite WHERE id = $produit_id";
        }

        if ($conn->query($update_sql) === TRUE) {
            // Insérer dans la table stock_movements
            $sql_movement = "INSERT INTO stock_movements (produit_id, type_mouvement, quantite) VALUES ('$produit_id', '$type_mouvement', '$quantite')";
            if ($conn->query($sql_movement) !== TRUE) {
                $response['status'] = 'error';
                $response['message'] = 'Erreur lors de l\'insertion dans stock_movements: ' . $conn->error;
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Erreur de mise à jour: ' . $conn->error;
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Erreur d\'insertion: ' . $conn->error;
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}

$conn->close();
header('Content-Type: application/json');
echo json_encode($response);
?>
