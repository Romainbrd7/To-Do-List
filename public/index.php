<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="../css/styles.css" rel="stylesheet">
    <style>
        /* Animation des boutons */
        .btn {
            transition: transform 0.2s ease, background-color 0.2s ease, color 0.2s ease;
        }

        .btn-primary:hover {
            transform: scale(1.1); /* Agrandit légèrement le bouton */
            background-color: #004085; /* Change la couleur de fond */
        }

        .btn-outline-primary:hover {
            transform: scale(1.1); /* Agrandit légèrement le bouton */
            background-color: #004085; /* Change la couleur de fond */
            color: #fff; /* Change la couleur du texte */
        }
    </style>
</head>
<body>
    <div class="container text-center mt-5">
        <h1 class="text-primary mb-4">Bienvenue sur votre gestionnaire de tâches</h1>
        <p class="lead">Gérez vos tâches en toute simplicité et efficacité.</p>
        <div class="d-flex justify-content-center gap-3 mt-4">
            <a href="login.php" class="btn btn-primary btn-lg">Connexion</a>
            <a href="register.php" class="btn btn-outline-primary btn-lg">Inscription</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
C:\Users\romai\To-Do-List\includes\header.php