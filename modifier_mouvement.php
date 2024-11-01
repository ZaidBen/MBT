<?php
include 'db.php';

$id = $_POST['id'];
$new_quantite = $_POST['quantite'];

$response = [];

$conn->begin_transaction();

try {
    // Récupérer les informations actuelles du mouvement
    $result = $conn->query("SELECT produit_id, type_mouvement, quantite FROM mouvements WHERE id = $id");
    if (!$result) {
        throw new Exception("Erreur lors de la récupération des informations du mouvement: " . $conn->error);
    }

    if ($result->num_rows == 0) {
        throw new Exception("Aucun mouvement trouvé avec l'ID: " . $id);
    }

    $row = $result->fetch_assoc();
    $produit_id = $row['produit_id'];
    $type_mouvement = $row['type_mouvement'];
    $ancienne_quantite = $row['quantite'];

    // Ajuster les quantités dans la table produits
    if ($type_mouvement == 'entree') {
        $conn->query("UPDATE produits SET quantite = quantite - $ancienne_quantite + $new_quantite WHERE id = $produit_id");
    } elseif ($type_mouvement == 'sortie') {
        $conn->query("UPDATE produits SET quantite = quantite + $ancienne_quantite - $new_quantite WHERE id = $produit_id");
    }

    // Mettre à jour le mouvement
    $sql = "UPDATE mouvements SET quantite='$new_quantite' WHERE id=$id";
    if (!$conn->query($sql)) {
        throw new Exception("Erreur lors de la mise à jour du mouvement: " . $conn->error);
    }

    // Mettre à jour la table stock_movements
    $sql_movement = "UPDATE stock_movements SET quantite='$new_quantite' WHERE id=$id";
    if (!$conn->query($sql_movement)) {
        throw new Exception("Erreur lors de la mise à jour du mouvement de stock: " . $conn->error);
    }

    $conn->commit();
    $response['status'] = 'success';
    $response['message'] = 'Mouvement de stock modifié avec succès';
} catch (Exception $e) {
    $conn->rollback();
    $response['status'] = 'error';
    $response['message'] = $e->getMessage();
    error_log($e->getMessage());
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
