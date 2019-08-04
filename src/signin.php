<?php require_once('includes/db.php'); ?>
<?php require_once('includes/functions.php'); ?>
<?php session_start(); ?>
<?php
if (!empty($_POST)) :
    $errors = [];
    if (!empty($_POST['username']) && !empty($_POST['password'])) :
        $data = ['username' => $_POST['username']];
        $sql = "SELECT * FROM users WHERE (username = :username OR email = :username) AND token_confirmed_at IS NOT NULL";
        $request = $db->prepare($sql);
        $request->execute($data);
        $user = $request->fetch();

        $passwordVerify = password_verify($_POST['password'],  $user->password);

        if ($user && $passwordVerify) :
            $_SESSION['auth'] = $user;
            $_SESSION['flash'][] = [
                'label' => 'Vous êtes connecté.',
                'status' => 'success'
            ];
            header('Location: account.php');
            die();
        else :
            $errors['field'] = "Les informations saisies sont incorrectes.";
        endif;

    else :
        $errors['field'] = "Veuillez renseigner tous les champs.";

    endif;

endif;
?>


<?php require_once('template/header.php'); ?>

<h1>Se connecter</h1>
<?php if (!empty($errors)) : ?>
    <div class="message error">
        <ul>
            <?php foreach ($errors as $error) : ?>
                <li> <?= $error ?></li>


            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>


<div class="form-container wrapper">
    <form action="" method="post">
        <div class="form-log">
            <label for="username">Pseudo ou courriel</label>
            <input id="username" type="text" name="username" />
        </div>

        <div class="form-log">
            <label for="password">Mot de passe</label>
            <input id="password" type="text" name="password" />
        </div>

        <button type="submit">Se connecter</button>
    </form>
</div>

<?php require_once('template/footer.php'); ?>