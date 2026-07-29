<?php
$hote     = 'localhost';
$bdd      = 'LeBonCoin';
$user     = 'root';
$password = 'root';     // mot de passe par défaut MAMP

try {
    $pdo = new PDO("mysql:host=$hote;port=8889;dbname=$bdd;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>