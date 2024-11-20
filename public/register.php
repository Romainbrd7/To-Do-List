<?php
require_once('../config/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare('INSERT INTO users (nom, prenom, email, mot_de_passe) VALUES (?, ?, ?, ?)');
    $stmt->execute([$nom, $prenom, $email, $mot_de_passe]);

    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Inscription</title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Inscription</h1>
        <form method="POST" class="bg-light p-4 rounded shadow-sm mx-auto" style="max-width: 400px;">
            <!-- Champ pour le nom -->
            <div class="mb-3">
                <label for="nom" class="form-label">Nom :</label>
                <input type="text" name="nom" id="nom" class="form-control" required>
            </div>

            <!-- Champ pour le prénom -->
            <div class="mb-3">
                <label for="prenom" class="form-label">Prénom :</label>
                <input type="text" name="prenom" id="prenom" class="form-control" required>
            </div>

            <!-- Champ pour l'email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email :</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>

            <!-- Champ pour le mot de passe -->
            <div class="mb-3">
                <label for="mot_de_passe" class="form-label">Mot de passe :</label>
                <input type="password" name="mot_de_passe" id="mot_de_passe" class="form-control" required>
            </div>

            <!-- Boutons d'action -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg w-100">S'inscrire</button>
            </div>
            <div class="text-center mt-3">
                <a href="login.php" class="text-secondary">Déjà inscrit ? Connectez-vous</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
