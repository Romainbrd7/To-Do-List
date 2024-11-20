<?php
session_start();
require '../config/db.php'; // Assure-toi que le chemin est correct
require '../includes/task_functions.php'; // Assure-toi que le chemin est correct

// Vérifier si une tâche est sélectionnée pour le partage
if (isset($_GET['task_id'])) {
    $taskId = $_GET['task_id'];
} elseif (isset($_POST['task_id'])) {
    $taskId = $_POST['task_id'];
} else {
    // Si le task_id n'est pas défini, affiche une erreur ou redirige l'utilisateur
    die("<div class='container mt-5 alert alert-danger'>Aucune tâche sélectionnée pour le partage.</div>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receiverEmail = $_POST['email']; // Email du destinataire
    $permissionId = $_POST['permission']; // Permission sélectionnée
    $senderId = $_SESSION['user_id'];
    
    // Trouver l'utilisateur par email
    $receiver = findUserByEmail($receiverEmail);
    
    if ($receiver) {
        $receiverId = $receiver['id'];
        
        // Créer une invitation avec le statut 'pending'
        if (createInvitation($senderId, $receiverId, $taskId, $permissionId)) {
            $success_message = "Invitation envoyée à {$receiver['prenom']} {$receiver['nom']}.";
        } else {
            $error_message = "Erreur lors de l'envoi de l'invitation.";
        }
    } else {
        $error_message = "Utilisateur non trouvé.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Partager une tâche</title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Partager une tâche</h1>

        <!-- Affichage des messages -->
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success text-center">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger text-center">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="share_task.php?task_id=<?php echo $taskId; ?>" class="bg-light p-4 rounded shadow-sm mx-auto" style="max-width: 500px;">
            <!-- Champs cachés pour passer le task_id -->
            <input type="hidden" name="task_id" value="<?php echo $taskId; ?>">

            <!-- Email du destinataire -->
            <div class="mb-3">
                <label for="email" class="form-label">Email du destinataire :</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>

            <!-- Niveau de permission -->
            <div class="mb-3">
                <label for="permission" class="form-label">Niveau de permission :</label>
                <select name="permission" id="permission" class="form-select" required>
                    <option value="1">Lecture seule</option>
                    <option value="2">Lecture + Modification</option>
                    <option value="3">Lecture + Modification + Partage</option>
                </select>
            </div>

            <!-- Bouton d'envoi -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg w-100">Envoyer une invitation</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
