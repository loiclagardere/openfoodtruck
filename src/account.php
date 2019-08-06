<?php
require_once('includes/db.php');
require_once('includes/functions.php');
authentificated();

// Check submit form
if (!empty($_POST)) :

    // Check contnent field password and passwordConfirm
    if (!empty($_POST['password']) && !empty($_POST['passwordConfirm'])) :

        // Compare the values entered 
        if ($_POST['password'] === $_POST['passwordConfirm']) :

            $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $userId = $_SESSION['auth']->id;
            $data = [
                'password' => $passwordHash,
                'id'       => $userId
            ];

            // Request to update user
            $sql = "UPDATE users
                    SET password = :password
                    WHERE id = :id";
            $request = $db->prepare($sql);
            $request->execute($data);

            $_SESSION['flash'][] = [
                'message' => 'Votre mot de passe a bien été mis à jour.',
                'status' => 'success'
            ];
        else :
            $_SESSION['flash'][] = [
                'message' => 'Les mots de passe saisis ne sont pas identiques.',
                'status' => 'error'
            ];
        endif;
    else :
        $_SESSION['flash'][] = [
            'message' => 'Veuillez remplir les champs obligatoires.',
            'status' => 'error'
        ];
    endif;
endif;
?>


<?php require_once('template/header.php'); ?>

<h1> Votre compte utilisateur </h1>

<p> Bienvenue <?= $_SESSION['auth']->username; ?> </p>


<div>
    <p>Modifier votre mot de passe</p>

    <div class="notice">
        <p>Les champs marqués d'un astérisque (*) sont obligatoires</p>
    </div>
    <form action="" method="post">
        <div class="form-log">
            <label for="password">Saisissez un mot de passe *</label>
            <input id="password" type="password" name="password" />
        </div>
        <div class="form-log">
            <label for="password-confirm">Confirmez le mot de passe *</label>
            <input id="password-confirm" type="password" name="passwordConfirm" />
        </div>
        <button type="submit">Changer le mot de passe</button>
    </form>
</div>

<?php require_once('template/footer.php'); ?>