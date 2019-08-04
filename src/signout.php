<?php
session_start();
unset($_SESSION['auth']);

$_SESSION['flash'][]= [
    'label' => "Vous êtes déconnecté.",
    'status' => "succes"
];
header('Location: signin.php');
