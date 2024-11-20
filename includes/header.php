<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title><?php echo $title ?? 'Gestion des Tâches'; ?></title>
</head>
<body>
    <header class="bg-primary text-white py-3 mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0">Bienvenue, <?php echo htmlspecialchars($_SESSION['prenom']); ?> <?php echo htmlspecialchars($_SESSION['nom']); ?> !</h1>
            <nav>
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="dashboard.php">Tableau de bord</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="add_task.php">Ajouter une tâche</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="logout.php">Déconnexion</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
