<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['idProduit'];
    $nom = $_POST['nom'];
    $quantite = $_POST['quantite'];

    $sql = "UPDATE produits SET nom='$nom', quantite=$quantite WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        // Redirection vers la page de stock avec un message de succès
        header('Location: index.php?message=Produit modifié avec succès');
        exit;
    } else {
        // Redirection vers la page de index avec un message d'erreur
        header('Location: index.php?error=Erreur lors de la modification du produit');
        exit;
    }
} else {
    // Redirection vers la page de index avec un message d'erreur
    header('Location: index.php?error=Méthode de requête non valide');
    exit;
}

$conn->close();
?>
