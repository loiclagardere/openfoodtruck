<?php
session_start();
session_destroy();
session_start();

$_SESSION['flash'][]= [
    'label' => "Vous êtes déconnecté",
    'status' => "succes"
];
header('Location: signin.php');
