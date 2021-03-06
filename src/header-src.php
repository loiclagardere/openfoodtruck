<?php
// Check the state of thze session
// avoid errors if session is already started
if (session_status() == PHP_SESSION_NONE) : // same as if (session_status() !== PHP_SESSION_ACTIVE)
    session_start();
endif;
require_once('includes/functions.php');

$url = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"];
$urlIndex = "http://localhost/php/initiation/openfoodtruck-php/openfoodtruck/src/index.php";
$urlSearch = "http://localhost/php/initiation/openfoodtruck-php/openfoodtruck/src/search.php";

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Open Food Truck - Trouvez votre food trucks partout en France</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/css/select2.min.css" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" href="assets/css/select2.css" />
    <link rel="stylesheet" href="assets/css/leaflet.css" />

</head>

<body>

    <header>
        <div class="logo-header">
            OpenFoodTruck
        </div>
        <input type="checkbox" id="chk">
        <label for="chk" class="show-menu-btn">
            <span>Menu</span>
            <i class="fas fa-hamburger"></i>
        </label>

        <ul class="menu">
            <li>
                <a href="index.php" title="Pésentation.">Présentation</a>
            </li>
            <li>
                <a href="search.php" title="Retour vers l'accueil.">Rechercher</a>
            </li>
            <?php if (isset($_SESSION['auth'])) : ?>
                <li>
                    <a href="account.php" title="Pour accerder à votre compte">Mon compte</a>
                </li>
                <li>
                    <a href="signout.php" title="Pour se déconnecter">Se déconnecter</a>
                </li>
            <?php else : ?>
                <li>
                    <a href="home-connexion.php" title="Pour se connecte ou créer un compte.">Espace pro</a>
                </li>
            <?php endif; ?>
            <li>
                <label for="chk" class="hide-menu-btn">
                    <span>Ferner</span>
                    <i class="fas fa-times"></i>
                </label>
            </li>
        </ul>
    </header>





    <!-- <header>
        <div id="topbar" <?php echo ($url == $urlIndex) ? 'class="posit-abs"' : ""; ?>>
            <div class="logo-header">
                <span>Open Food Truck</span>
            </div>
            <nav class="menu-header">
                <ul class="menu-list">
                    <li><a href="index.php" title="Pésentation.">Présentation</a></li>
                    <li><a href="search.php" title="Retour vers l'accueil.">Rechercher</a></li>
                    <?php if (isset($_SESSION['auth'])) : ?>
                        <li><a href="account.php" title="Pour accerder à votre compte">Mon compte</a></li>
                        <li><a href="signout.php" title="Pour se déconnecter">Se déconnecter</a></li>
                    <?php else : ?>
                        <li><a href="home-connexion.php" title="Pour se connecte ou créer un compte.">Espace pro</a></li>
                    <?php endif; ?>
                    <li>
                        <label for="chk" class="hide-menu-btn">
                            <span>Fermer</span>
                            <i class="fas fa-times"></i>
                        </label>
                    </li>
                </ul>
            </nav>
            <div class="content-hide-btn">
                <input type="checkbox" id="chk">
                <label for="chk" class="show-menu-btn">
                    <span>Menu</span>
                    <i class="fas fa-hamburger"></i>
                </label>
            </div>
        </div>
        <?php if ($url == $urlIndex) : ?>
            <div class="banner-header">
                <div class="text-banner">
                    <h1>Trouvez votre food truck partout en France!</h1>
                </div>
            </div>
        <?php endif; ?>
    </header> -->


    <div id="container" <?php echo ($url == $urlIndex) ? "" : "class='wrapper'"; ?>>