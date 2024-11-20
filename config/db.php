<?php
$host = 'mysql';  // Nom du service MySQL dans Docker
$db = 'php_app';      // Nom de la base de données dans Docker
$user = 'phpuser';    // Utilisateur MySQL défini dans docker-compose.yml
$password = 'phppassword';  // Mot de passe MySQL défini dans docker-compose.yml

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
