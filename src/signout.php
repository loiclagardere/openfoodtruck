<?php
session_start();
unset($_SESSION['auth']);

$_SESSION['flash'][]= [
    'message' => "Vous êtes déconnecté.",
    'status' => "succes"
];
header('Location: signin.php');
