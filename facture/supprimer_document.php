<?php
include '../db.php';

$id = $_GET['id'];
$type = $_GET['type'];

if ($type == 'Facture') {
    $sql = "DELETE FROM factures WHERE id='$id'";
    $sql_delete_details = "DELETE FROM facture_details WHERE facture_id='$id'";
} elseif ($type == 'Bon de Livraison') {
    $sql = "DELETE FROM bons_de_livraison WHERE id='$id'";
    $sql_delete_details = "DELETE FROM bon_livraison_details WHERE bon_livraison_id='$id'";
} elseif ($type == 'Devis') {
    $sql = "DELETE FROM devis WHERE id='$id'";
    $sql_delete_details = "DELETE FROM devis_details WHERE devis_id='$id'";
} else {
    echo "Type de document non valide.";
    exit;
}

if ($conn->query($sql) === TRUE) {
    $conn->query($sql_delete_details);
    header('Location: gestion_documents.php');
} else {
    echo "Erreur : " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
