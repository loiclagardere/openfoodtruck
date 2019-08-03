<?php
session_start();
require_once('includes/functions.php');
?>

<?php require_once('template/header.php'); ?>

<h1> Votre compte utilisateur </h1>

<!-- <?php debugV($_SESSION); ?> -->
<p> Bienvenue <?= $_SESSION['auth']->username; ?>

<?php require_once('template/footer.php'); ?>