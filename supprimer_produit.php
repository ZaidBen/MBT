<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Requête pour supprimer le produit avec l'ID donné
    $sql = "DELETE FROM produits WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        // La suppression a réussi, rediriger vers la page de stock avec un message de confirmation
        header('Location: index.php?message=Produit supprimé avec succès');
        exit;
    } else {
        // Erreur lors de la suppression, rediriger vers la page de index avec un message d'erreur
        header('Location: index.php?error=Erreur lors de la suppression du produit');
        exit;
    }
} else {
    // L'ID du produit n'est pas spécifié, rediriger vers la page de index avec un message d'erreur
    header('Location: index.php?error=ID du produit non spécifié');
    exit;
}

$conn->close();
?>
