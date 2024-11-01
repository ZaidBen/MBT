<?php
include 'db.php';

$nom = $_POST['nom'];
$quantite = $_POST['quantite'];

$response = array();
$errors = array();

for ($i = 0; $i < count($nom); $i++) {
    $current_nom = $nom[$i];
    $current_quantite = $quantite[$i];

    // Vérifier si le produit existe déjà
    $check_sql = "SELECT * FROM produits WHERE nom = '$current_nom'";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        $errors[] = 'Le produit ' . $current_nom . ' existe déjà.';
    } else {
        // Insérer le nouveau produit
        $sql = "INSERT INTO produits (nom, quantite) VALUES ('$current_nom', $current_quantite)";
        if ($conn->query($sql) !== TRUE) {
            $errors[] = 'Erreur lors de l\'ajout de ' . $current_nom . ': ' . $conn->error;
        }
    }
}

if (count($errors) > 0) {
    $response['status'] = 'error';
    $response['message'] = implode("<br>", $errors);
} else {
    $response['status'] = 'success';
    $response['message'] = 'Produits ajoutés avec succès';
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
