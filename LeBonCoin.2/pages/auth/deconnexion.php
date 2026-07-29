<?php
/*
 * FICHIER : pages/auth/deconnection.php
 * RÔLE    : Détruire la session et rediriger vers l'accueil
 */

session_start();
session_destroy();   // Supprime toutes les variables de session

header('Location: ../../index.php');
exit;
?>