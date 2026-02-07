<?php
session_start(); // Démarrez la session si ce n'est pas déjà fait

require_once(__DIR__ . '/../variables/functions.php');

// detruit toutes les données de session
session_unset();
// detruit la session elle mme 
session_destroy();

// Rediriger l'utilisateur vers la page d'accueil
redirectToUrl('/login/login.php');
