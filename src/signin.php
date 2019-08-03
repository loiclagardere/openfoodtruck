<?php require_once('includes/functions.php');?>

<?php

if(!empty($_POST)):

    $errors = [];

endif;
?>


<?php require_once('template/header.php'); ?>

<h1>Se connecter</h1>

<div class="form-container wrapper"> 
    <form action="" method="POST">
        <div class="form-log">
            <label for="username">Pseudo ou courriel</label>
            <input id="username" type="text" name="username" />
        </div>

        <div class="form-log">
            <label for="password">Mot de passe</label>
            <input id="password" type="password" name="password" />
        </div>

        <button type="submit">Se connecter</button>
    </form>
</div>

<?php require_once('template/footer.php'); ?>