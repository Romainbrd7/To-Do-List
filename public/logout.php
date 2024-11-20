<?php
session_start();

function logout() {
    session_unset();    // Efface toutes les variables de session
    session_destroy();  // Supprime la session actuelle
    return 'index.php'; // Retourne la page de redirection pour les tests
}

// Appel de la fonction logout et redirection uniquement si le fichier est exécuté directement
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    $redirect = logout();
    header("Location: $redirect");
    exit();
}
