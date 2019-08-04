<?php
session_start();
require_once('includes/functions.php');
?>

<?php
if (!isset($_SESSION['auth'])) :
    $_SESSION['flash'][] = [
        'message' => "Veuiilez vous identifier pour avoir accés à votre compte.",
        'status' => 'error'
    ];
    header('Location: signin.php');
    die();
endif;
?>

<?php require_once('template/header.php'); ?>

<h1> Votre compte utilisateur </h1>

<p>  Bienvenue <?= $_SESSION['auth']->username; ?> </p>


<div>
    <p>Pour changer votre mot de passe</p>
  <form action="" method="posst">
    <div class="form-log">
        <label for="password">Saisssez un mot de passe</label>
        <input id="password" type="password" name="password" />
    </div>
    <div class="form-log">
        <label for="password-confirm">Confirmez ce mot de passe</label>
        <input id="password-confirm" type="password" name="password_confirm" />
    </div>
    <button type="submit">Valider</button>
</form>  
</div>



<?php require_once('template/footer.php'); ?>