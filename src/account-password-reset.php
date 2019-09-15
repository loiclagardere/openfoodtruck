<?php
require_once('includes/bootstrap.php');
authentificated();

// debugP($_SESSION['auth']);
// die();
$userId = $_SESSION['auth']->user_id;
$userData = getAll($db, $userId);

//debugP($userData); ////////////////////////////////////////////////////////////////////


// Password reset form
// Check submit form : reset-password-form
if (!empty($_POST) && isset($_POST['resetPasswordForm'])) : // test
    $errors = [];
    // Check content field content password and passwordConfirm
    if (!empty($_POST['password']) && !empty($_POST['passwordConfirm'])) :

        // Compare the field content password and passwordConfirm
        if ($_POST['password'] === $_POST['passwordConfirm']) :

            $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $data = [
                'password' => $passwordHash,
                'user_id'  => $userId
            ];

            // Request to update user
            $sql = "UPDATE users
                    SET password = :password
                    WHERE user_id = :user_id";
            $request = $db->prepare($sql);
            $request->execute($data);

            $_SESSION['flash'][] = [
                'message' => 'Votre mot de passe a bien été mis à jour.',
                'status' => 'success'
            ];
        else :
            $errors['password'] = "Les mots de passe saisis ne sont pas identiques.";
        endif;
    else :
        $errors['password'] = "Ce champs est obligatoire.";
        $errors['passwordConfirm'] = "Ce champs est obligatoire";
    endif;
endif;


?>
<?php require_once('header.php'); ?>

<section class="section-form">
    <h2> Votre compte utilisateur </h2>
    <span class="username"> Bienvenue <?= $_SESSION['auth']->username; ?>.</span>

    <h3>Modifier votre mot de passe</h3>

    <div class="notice">
        <p>Les champs marqués d'un astérisque (*) sont obligatoires</p>
    </div>
    <?= flash() ?>
    <div class="form-container">
        <form action="" method="post">
            <div class="form-group" name="passwordGroup">
                <label for="password">* Saisissez un mot de passe</label>
                <div class="contain-input">
                    <input id="password" type="password" name="password" required />
                </div>
                <?= !empty($errors['password']) ? '<div class="error-field">' . $errors['password'] . '</div>' : '' ?>
            </div>

            <div class="form-group" name="passwordConfirmGroup">
                <label for="password-confirm">* Confirmez votre mot de passe</label>
                <div class="contain-input">
                    <input id="password-confirm" type="password" name="passwordConfirm" required />
                </div>
                <?= !empty($errors['passwordConfirm']) ? '<div class="error-field">' . $errors['passwordConfirm'] . '</div>' : '' ?>
            </div>
            <button id="resetPasswordForm" type="submit" name="resetPasswordForm">Changer le mot de passe</button>
        </form>
    </div>
</section>


<?php require_once('footer.php'); ?>