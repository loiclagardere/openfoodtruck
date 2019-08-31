<?php
// Check the state of thze session
// avoid errors if session is already started
if (session_status() == PHP_SESSION_NONE) : // same as if (session_status() !== PHP_SESSION_ACTIVE)
    session_start();
endif;
require_once('includes/functions.php')
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Open Food Truck - Trouvez votre food trucks partout en France</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/css/select2.min.css" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" href="assets/css/select2.css" />

</head>

<body>

    <header>
        <div id="topbar">
            <div class="logo-header">
                <span>Open Food Truck</span>
            </div>
            <nav class="menu-header">
                <ul class="menu-list">
                    <li><a href="index.php" title="Retour vers l'accueil.">Home</a></li>
                    <li><a href="#" title="Pésentation.">Présentation</a></li>
                    <?php if (isset($_SESSION['auth'])) : ?>
                    <li><a href="account.php" title="Pour accerder à votre compte">Mon compte</a></li>
                    <li><a href="signout.php" title="Pour se déconnecter">Se déconnecter</a></li>
                    <?php else : ?>
                    <li><a href="signin.php" title="Pour se connecter">Se connecter</a></li>
                    <li><a href="signup.php" title="Pour s'inscrire">S'inscrire</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
        <div id="banner-top" class=ban1>
        </div>
    </header>

    <div id="container" class="wrapper">