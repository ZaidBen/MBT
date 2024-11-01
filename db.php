<?php
$servername = "localhost";
$username = "root";
$password = "E7DnO9eoP7Clc9Zw";
$dbname = "gestion_stock";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}
?>
