<?php
include 'db.php';

$id = $_POST['id'];

$response = [];

$conn->begin_transaction();

try {
    // Récupérer les informations du mouvement avant de le supprimer
    $result = $conn->query("SELECT produit_id, type_mouvement, quantite, date_mouvement FROM mouvements WHERE id = $id");
    if (!$result) {
        throw new Exception("Erreur lors de la récupération des informations du mouvement: " . $conn->error);
    }

    if ($result->num_rows == 0) {
        throw new Exception("Aucun mouvement trouvé avec l'ID: " . $id);
    }

    $row = $result->fetch_assoc();
    $produit_id = $row['produit_id'];
    $type_mouvement = $row['type_mouvement'];
    $quantite = $row['quantite'];
    $date_mouvement = $row['date_mouvement'];

    // Ajoutez des messages de débogage pour vérifier les valeurs des variables
    error_log("Produit ID : " . $produit_id);
    error_log("Type de mouvement : " . $type_mouvement);
    error_log("Quantité : " . $quantite);
    error_log("Date de mouvement : " . $date_mouvement);

    // Supprimer le mouvement
    $sql = "DELETE FROM mouvements WHERE id = $id";
    if (!$conn->query($sql)) {
        throw new Exception("Erreur lors de la suppression du mouvement: " . $conn->error);
    }

    // Mettre à jour la quantité du produit
    if ($type_mouvement == 'entree') {
        $update_product_sql = "UPDATE produits SET quantite = quantite - $quantite WHERE id = $produit_id";
    } elseif ($type_mouvement == 'sortie') {
        $update_product_sql = "UPDATE produits SET quantite = quantite + $quantite WHERE id = $produit_id";
    }

    if (!$conn->query($update_product_sql)) {
        throw new Exception("Erreur lors de la mise à jour de la quantité du produit: " . $conn->error);
    }

    // Supprimer de la table stock_movements
    $sql_movement = "DELETE FROM stock_movements WHERE produit_id = $produit_id AND type_mouvement = '$type_mouvement' AND quantite = $quantite AND date_mouvement = '$date_mouvement'";
    if (!$conn->query($sql_movement)) {
        throw new Exception("Erreur lors de la suppression du mouvement de stock dans stock_movements: " . $conn->error);
    }

    $conn->commit();
    $response['status'] = 'success';
    $response['message'] = 'Mouvement de stock supprimé avec succès';
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
