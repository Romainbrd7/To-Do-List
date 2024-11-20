<?php
session_start();
require_once('../config/db.php');
require_once('../includes/task_functions.php');

// Inclure le fichier header
require '../includes/header.php'; // Assure-toi que le chemin est correct


// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Vérifie si un ID de tâche est passé dans l'URL
if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];
    $user_id = $_SESSION['user_id'];

    // Récupérer la tâche créée par l'utilisateur ou partagée avec lui
    $stmt = $pdo->prepare('
        SELECT tasks.*, shared_tasks.id_permission 
        FROM tasks 
        LEFT JOIN shared_tasks ON tasks.id = shared_tasks.task_id AND shared_tasks.user_id = ?
        WHERE tasks.id = ? AND (tasks.user_id = ? OR shared_tasks.user_id = ?)
    ');
    $stmt->execute([$user_id, $task_id, $user_id, $user_id]);
    $task = $stmt->fetch();

    // Si la tâche est introuvable ou si l'utilisateur n'a pas les droits de modification
    if (!$task || ($task['user_id'] != $user_id && $task['id_permission'] < 2)) {
        echo "<div class='container mt-5 alert alert-danger'>Vous n'avez pas la permission de modifier cette tâche ou la tâche est introuvable.</div>";
        exit();
    }

    // Si une modification est soumise
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $description = $_POST['description'];
        $statut_id = $_POST['statut_id'];

        // Met à jour la tâche
        $stmt = $pdo->prepare('UPDATE tasks SET description = ?, statut_id = ? WHERE id = ?');
        $stmt->execute([$description, $statut_id, $task_id]);

        header('Location: dashboard.php');
        exit();
    }
} else {
    echo "<div class='container mt-5 alert alert-danger'>Aucun identifiant de tâche fourni.</div>";
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
    <title>Modifier une tâche</title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Modifier une tâche</h1>
        <form method="POST" class="bg-light p-4 rounded shadow">
            <!-- Champ pour la description -->
            <div class="mb-3">
                <label for="description" class="form-label">Description :</label>
                <textarea name="description" id="description" class="form-control" rows="4" required><?php echo htmlspecialchars($task['description']); ?></textarea>
            </div>

            <!-- Champ pour le statut -->
            <div class="mb-3">
                <label for="statut_id" class="form-label">Statut :</label>
                <select name="statut_id" id="statut_id" class="form-select" required>
                    <option value="1" <?php echo $task['statut_id'] == 1 ? 'selected' : ''; ?>>À faire</option>
                    <option value="2" <?php echo $task['statut_id'] == 2 ? 'selected' : ''; ?>>En cours</option>
                    <option value="3" <?php echo $task['statut_id'] == 3 ? 'selected' : ''; ?>>Terminée</option>
                </select>
            </div>

            <!-- Boutons d'action -->
            <div class="text-center">
                <button type="submit" class="btn btn-success">Modifier</button>
                <a href="dashboard.php" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
