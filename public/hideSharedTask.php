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
    $task_id = $_GET['task_id'];       // Récupère l'ID de la tâche
    $user_id = $_SESSION['user_id'];    // Récupère l'ID de l'utilisateur connecté

    // Appelle la fonction hideSharedTask() définie dans task_functions.php
    if (hideSharedTask($user_id, $task_id, $pdo)) {
        // Redirige vers le tableau de bord après le masquage
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Erreur lors du masquage de la tâche.";
        exit();
    }
} else {
    // Si l'ID de tâche n'est pas spécifié dans l'URL
    echo "Aucune tâche spécifiée pour le masquage.";
    exit();
}
