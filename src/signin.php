<?php session_start();
require_once('includes/bootstrap.php');

// Check submit form
if (!empty($_POST) && empty($_POST['lastname'])) :

    $errors = []; // to stock messages error


    // Check field content username and password
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

<?php require_once('header.php'); ?>
<section class="section-form">
    <h2>Connexion</h2>
    <div class="notice">
        <p>Les champs marqués d'un astérisque (*) sont obligatoires</p>
    </div>
    <?= flash() ?>
    <div class="form-container">
        <form action="" method="post">

            <div id="lastname-id" class="form-group" name="lastnameGroup">
                <label for="lastname">* Nom de famille </label>
                <div class="contain-input">
                    <input id="lastname" type="text" name="lastname" />
                </div>
            </div>

            <div class="form-group" name="usernameEmailGroup">
                <label for="username">* Pseudo ou courriel</label>
                <div class="contain-input">
                    <input id="username-email" type="text" name="username" value="<?= valueField('username'); ?>" required />
                </div>
                <?= !empty($errors['username']) ? '<div class="error-field">' . $errors['username'] . '</div>' : '' ?>
            </div>

            <div class="form-group" name="passwordGroup">
                <label for="password">* Mot de passe<span class="text-link"><a href="password-forgot.php">( Mot de passe oublié )</a></span></label>
                <div class="contain-input">
                    <input id="password" type="password" name="password" required />
                </div>
            </div>

            <button type="submit" name="signinForm">Se connecter</button>

        </form>
    </div>
</section>


<?php require_once('footer.php'); ?>