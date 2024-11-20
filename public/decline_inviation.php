<?php
session_start();
require '../config/db.php';
require '../includes/task_functions.php';

$invitationId = $_GET['id']; // ID de l'invitation récupéré depuis l'URL

// Appel de la fonction pour refuser l'invitation
if (declineInvitation($invitationId)) {
    // Redirection après refus
    header('Location: dashboard.php');
    exit();
} else {
    echo "Erreur lors du refus de l'invitation.";
}
?>
