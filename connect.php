<?php
$host = "localhost"; 
$user = "root"; 
$pass = ""; 
$dbname = "ecommerce"; // Assure-toi que la base de données 'pfe' existe !

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Échec de la connexion : " . $e->getMessage());
}
?>
