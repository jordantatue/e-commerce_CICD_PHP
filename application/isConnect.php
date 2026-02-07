<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['LOGGED_USER'])) {
    $_SESSION['LOGIN_ERROR_MESSAGE'] = 'Veuillez vous connecter pour accéder à cette page.';
    header('Location: /login/login.php');
    exit();
}

