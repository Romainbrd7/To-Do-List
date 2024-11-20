<?php
session_start();
require_once('../config/db.php');

// Vérifie si l'utilisateur est connecté, sinon redirige vers la page de connexion
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $description = $_POST['description'];
    $user_id = $_SESSION['user_id'];
    $statut_id = 1; // Par défaut, statut de tâche "à faire"

    $stmt = $pdo->prepare('INSERT INTO tasks (user_id, description, statut_id) VALUES (?, ?, ?)');
    $stmt->execute([$user_id, $description, $statut_id]);

    header('Location: dashboard.php');
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
    <title>Ajouter une tâche</title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-primary text-center mb-4">Ajouter une tâche</h1>
        <form method="POST" class="bg-light p-4 rounded shadow">
            <div class="mb-3">
                <label for="description" class="form-label">Description de la tâche :</label>
                <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success">Ajouter</button>
                <a href="dashboard.php" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
