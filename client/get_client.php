<?php
include '../db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $result = $conn->query("SELECT * FROM clients WHERE id=$id");

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode([]);
    }

    $conn->close();
}
?>
