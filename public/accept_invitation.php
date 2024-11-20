<?php
session_start();
require '../config/db.php';
require '../includes/task_functions.php';

$invitationId = $_GET['id']; // ID de l'invitation récupéré depuis l'URL

// Appel de la fonction pour accepter l'invitation
if (acceptInvitation($invitationId)) {
    // Redirection après acceptation
    header('Location: dashboard.php');
    exit();
} else {
    echo "Erreur lors de l'acceptation de l'invitation.";
}
?>
