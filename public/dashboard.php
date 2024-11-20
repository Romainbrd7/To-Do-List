<?php
session_start();
require '../config/db.php'; // Assure-toi que le chemin est correct
require '../includes/task_functions.php'; // Assure-toi que le chemin est correct

// Inclure le fichier header
require '../includes/header.php'; // Assure-toi que le chemin est correct

// Récupérer l'ID de l'utilisateur connecté
$currentUserId = $_SESSION['user_id'] ?? null;

// Initialiser les variables
$user_tasks = [];
$shared_tasks = [];
$invitations = [];

// Vérifier si l'utilisateur est connecté
if ($currentUserId) {
    // Récupérer les tâches de l'utilisateur
    $user_tasks = getUserTasks($currentUserId);

    // Récupérer les tâches partagées avec l'utilisateur
    $shared_tasks = getSharedTasks($currentUserId);

    // Récupérer les invitations en attente
    $invitations = getPendingInvitations($currentUserId);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <link href="../css/styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-primary">Tableau de bord</h1>

        <!-- Section pour les invitations -->
        <h2 class="mt-4">Invitations à accepter ou refuser</h2>
        <?php if (count($invitations) > 0): ?>
            <ul class="list-group">
                <?php foreach ($invitations as $invitation): ?>
                    <li class="list-group-item">
                        <?php echo htmlspecialchars($invitation['sender_name']); ?> veut partager une tâche avec vous.
                        <a class="btn btn-success btn-sm" href="accept_invitation.php?id=<?php echo $invitation['id']; ?>">Accepter</a>
                        <a class="btn btn-danger btn-sm" href="decline_invitation.php?id=<?php echo $invitation['id']; ?>">Refuser</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Vous n'avez aucune invitation en attente.</p>
        <?php endif; ?>

        <!-- Liste des tâches créées par l'utilisateur -->
        <h2 class="mt-4">Mes tâches</h2>
        <?php if (count($user_tasks) > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($user_tasks as $task): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($task['description']); ?></td>
                        <td>
                            <?php
                                if ($task['statut_id'] == 1) echo 'À faire';
                                elseif ($task['statut_id'] == 2) echo 'En cours';
                                elseif ($task['statut_id'] == 3) echo 'Terminée';
                            ?>
                        </td>
                        <td>
                            <a class="btn btn-primary btn-sm" href="edit_task.php?task_id=<?php echo $task['id']; ?>">Modifier</a>
                            <a class="btn btn-danger btn-sm" href="delete_task.php?task_id=<?php echo $task['id']; ?>">Supprimer</a>
                            <a class="btn btn-warning btn-sm" href="share_task.php?task_id=<?php echo $task['id']; ?>">Partager</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Vous n'avez pas encore ajouté de tâches.</p>
        <?php endif; ?>

        <!-- Liste des tâches partagées avec l'utilisateur -->
        <h2 class="mt-4">Tâches partagées avec moi</h2>
        <?php 
        $visible_shared_tasks = array_filter($shared_tasks, function($task) {
            return isset($task['isVisible']) && $task['isVisible'] == true;
        });
        ?>
        <?php if (count($visible_shared_tasks) > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Statut</th>
                        <th>Permission</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($visible_shared_tasks as $shared_task): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($shared_task['description']); ?></td>
                        <td>
                            <?php
                                if ($shared_task['statut_id'] == 1) echo 'À faire';
                                elseif ($shared_task['statut_id'] == 2) echo 'En cours';
                                elseif ($shared_task['statut_id'] == 3) echo 'Terminée';
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($shared_task['description_permission']); ?></td>
                        <td>
                            <?php if ($shared_task['id_permission'] == 2 || $shared_task['id_permission'] == 3): ?>
                                <a class="btn btn-primary btn-sm" href="edit_task.php?task_id=<?php echo $shared_task['id']; ?>">Modifier</a>
                            <?php endif; ?>
                            <?php if ($shared_task['id_permission'] == 3): ?>
                                <a class="btn btn-warning btn-sm" href="share_task.php?task_id=<?php echo $shared_task['id']; ?>">Partager à nouveau</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune tâche ne vous a été partagée.</p>
        <?php endif; ?>

        <!-- Section pour les tâches masquées -->
        <button id="show-hidden-tasks" class="btn btn-secondary mt-4" onclick="toggleHiddenTasks()">Afficher/Masquer les tâches masquées</button>
        <div id="hidden-tasks-section" class="mt-3 d-none">
            <h3>Tâches Partagées Masquées</h3>
            <ul class="list-group">
                <?php foreach ($hiddenTasks as $task): ?>
                    <li class="list-group-item">
                        <?php echo htmlspecialchars($task['description']); ?>
                        <a class="btn btn-danger btn-sm" href="deleteHiddenTask.php?task_id=<?php echo htmlspecialchars($task['id']); ?>">Supprimer</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        function toggleHiddenTasks() {
            var hiddenTasksSection = document.getElementById('hidden-tasks-section');
            hiddenTasksSection.classList.toggle('d-none');
        }
    </script>
</body>
</html>
