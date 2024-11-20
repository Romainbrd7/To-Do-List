<?php
session_start();
require_once('../config/db.php');
require_once('../includes/task_functions.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Vérifie si un ID de tâche est passé dans l'URL
if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id']; // Récupère l'ID de la tâche
    $user_id = $_SESSION['user_id']; // Récupère l'ID de l'utilisateur connecté

    // Appelle la fonction deleteTask() définie dans task_functions.php
    deleteTask($task_id, $user_id);

    // Redirige vers le tableau de bord après suppression
    header('Location: dashboard.php');
    exit();
} else {
    // Si l'ID de tâche n'est pas spécifié dans l'URL
    echo "Aucune tâche spécifiée pour la suppression.";
    exit();
}
