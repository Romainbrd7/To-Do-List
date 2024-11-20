<?php
require_once '../config/db.php';

function addTask($description, $user_id, $statut_id = 1) {
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO Tasks (user_id, description, statut_id) VALUES (?, ?, ?)');
    $stmt->execute([$user_id, $description, $statut_id]);
}

function getUserTasks($user_id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM tasks WHERE user_id = ?');
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

function hideSharedTask($user_id, $task_id, $pdo)
{
    // Prépare la requête pour mettre `isVisible` à false
    $stmt = $pdo->prepare("UPDATE shared_tasks SET isVisible = FALSE WHERE user_id = ? AND task_id = ?");
    return $stmt->execute([$user_id, $task_id]);
}


function getHiddenSharedTasks($user_id, $pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM tasks 
                           INNER JOIN shared_tasks ON tasks.id = shared_tasks.task_id
                           WHERE shared_tasks.user_id = ? AND shared_tasks.isVisible = FALSE");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}


function deleteSharedTask($user_id, $task_id, $pdo)
{
    $stmt = $pdo->prepare("DELETE FROM shared_tasks WHERE user_id = ? AND task_id = ? AND isVisible = FALSE");
    return $stmt->execute([$user_id, $task_id]);
}


function getSharedTasks($user_id) {
    global $pdo;
    $stmt = $pdo->prepare('
        SELECT tasks.*, shared_tasks.id_permission, permission.description_permission , shared_tasks.isVisible
        FROM tasks 
        JOIN shared_tasks ON tasks.id = shared_tasks.task_id
        JOIN permission ON shared_tasks.id_permission = permission.id_permission
        WHERE shared_tasks.user_id = ?
    ');
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour créer une invitation
function createInvitation($senderId, $receiverId, $taskId, $permissionId) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO invitations (sender_id, receiver_id, task_id, status, permission_id) VALUES (?, ?, ?, 'pending', ?)");
    return $stmt->execute([$senderId, $receiverId, $taskId, $permissionId]);
}



// Fonction pour récupérer les invitations en attente
function getPendingInvitations($receiverId) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT invitations.id, users.prenom AS sender_name 
                          FROM invitations 
                          JOIN users ON users.id = invitations.sender_id
                          WHERE invitations.receiver_id = ? AND invitations.status = 'pending'");
    $stmt->execute([$receiverId]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// task_functions.php
function loginUser($email, $mot_de_passe, $pdo)
{
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];
        return 'dashboard.php';
    } else {
        return 'Identifiants incorrects';
    }
}



function acceptInvitation($invitationId) {
    global $pdo;
    
    // Mettre à jour l'invitation pour qu'elle soit acceptée
    $stmt = $pdo->prepare("UPDATE invitations SET status = 'accepted' WHERE id = ?");
    $stmt->execute([$invitationId]);

    // Récupérer les informations de l'invitation pour partager la tâche
    $stmt = $pdo->prepare("SELECT task_id, receiver_id FROM invitations WHERE id = ?");
    $stmt->execute([$invitationId]);
    $invitation = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Ajouter la tâche partagée dans Shared_Tasks
    $stmt = $pdo->prepare("INSERT INTO shared_tasks (task_id, user_id, id_permission) VALUES (?, ?, 1)");
    return $stmt->execute([$invitation['task_id'], $invitation['receiver_id']]);
}


// Fonction pour trouver un utilisateur par email
function findUserByEmail($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, prenom, nom FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fonction pour refuser une invitation
function declineInvitation($invitationId) {
    global $pdo;
    
    // Mettre à jour l'invitation pour qu'elle soit refusée
    $stmt = $pdo->prepare("UPDATE invitations SET status = 'declined' WHERE id = ?");
    return $stmt->execute([$invitationId]);
}


// Fonction pour modifier une tâche
function updateTask($task_id, $description, $statut_id) {
    global $pdo;
    $stmt = $pdo->prepare('UPDATE tasks SET description = ?, statut_id = ? WHERE id = ?');
    $stmt->execute([$description, $statut_id, $task_id]);
}

// Fonction pour supprimer une tâche
function deleteTask($task_id, $user_id) {
    global $pdo;
    $stmt = $pdo->prepare('DELETE FROM tasks WHERE id = ? AND user_id = ?');
    $stmt->execute([$task_id, $user_id]);
}

// Fonction pour partager une tâche avec un autre utilisateur
function shareTask($task_id, $email, $permission_id) {
    global $pdo;
    
    // Rechercher l'utilisateur avec cet email
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user) {
        // Partager la tâche
        $stmt = $pdo->prepare('INSERT INTO shared_tasks (task_id, id_permission, user_id) VALUES (?, ?, ?)');
        $stmt->execute([$task_id, $permission_id, $user['id']]);
        return true;
    }
    return false;
}
?>
