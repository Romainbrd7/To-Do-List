<?php
session_start();
require_once '../config/db.php';
require_once '../includes/task_functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];
    $user_id = $_SESSION['user_id'];

    if (deleteSharedTask($user_id, $task_id, $pdo)) {
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Erreur lors de la suppression de la tâche.";
    }
} else {
    echo "Aucune tâche spécifiée pour la suppression.";
}
