<?php require_once('includes/db.php'); ?>
<?php require_once('includes/functions.php'); ?>
<?php session_start(); ?>
<?php

// Check submit form
if (!empty($_POST)) :

    $errors = []; // to stock messages error

    // Check field content
    if (!empty($_POST['username']) && !empty($_POST['password'])) :

        $data = ['username' => $_POST['username']];

        // Request to select user
        $sql = "SELECT *
                FROM users
                WHERE (username = :username
                OR email = :username)
                AND token_confirmed_at IS NOT NULL"; // IS NOT NULL : account must have been confirmed
        $request = $db->prepare($sql);
        $request->execute($data);
        $user = $request->fetch();

        // Check user and password
        if ($user && password_verify($_POST['password'],  $user->password)) :

            $_SESSION['auth'] = $user; // connect user
            $_SESSION['flash'][] = [
                'message' => 'Vous êtes connecté.',
                'status' => 'success'
            ];
            header('Location: account.php');
            die();
        else :
            $_SESSION['flash'][] = [
                'message' => "Les informations saisies sont incorrectes.",
                'status' => "error"
            ];
        endif;
    else :
        $_SESSION['flash'][] = [
            'message' => "Veuillez renseigner tous les champs.",
            'status' => "error"
        ];
    endif;
endif;
?>

<?php require_once('template/header.php'); ?>
<section>
    <h1>Se connecter</h1>
    <div class="notice">
        <p>Les champs marqués d'un astérisque (*) sont obligatoires</p>
    </div>
    <?= flash() ?>
    <div class="form-container">
        <div class="form-container">
            <form action="" method="post">
                <div class="form-log">
                    <label for="username">Pseudo ou courriel *</label>
                    <input id="username" type="text" name="username" value="<?= valueField('username'); ?>" />
                    <?= !empty($errors['username']) ? '<div class="error-field">' . $errors['username'] . '</div>' : '' ?>
                </div>
                <div class="form-log">
                    <label for="password">* Mot de passe<span class="text-link"><a href="password-forgot.php">( Mot de passe oublié )</a></span>
                    </label>
                    <input id="password" type="text" name="password" />
                </div>
                <button type="submit">Se connecter</button>
            </form>
        </div>
    </div>
</section>


<?php require_once('template/footer.php'); ?>