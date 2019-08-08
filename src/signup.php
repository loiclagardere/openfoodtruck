<?php
session_start();
require_once('includes/db.php');
require_once('includes/functions.php');

// Check submit form
if (!empty($_POST)) :

    $errors = []; // to stock messages error

    // Check username field content the and content format
    if (!empty($_POST['username']) && usernamePregMatch($_POST['username'])) :
        $data = ['username' => $_POST['username']];
        $sql = "SELECT id
                FROM users
                WHERE username = :username";
        $request = $db->prepare($sql);
        $request->execute($data);
        $user = $request->fetch();

        if ($user) :
            $errors['username'] = "Ce pseudo est dejà pris.";
        endif;
    else :
        $errors['username'] = "Le format du pseudo n'est pas valide.";
    endif;

    // Check email and email format
    if (!empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) :
        $data = ['email' => $_POST['email']];
        $sql = "SELECT id
                FROM users
                WHERE email = :email";
        $request = $db->prepare($sql);
        $request->execute($data);
        $user = $request->fetch();

        if ($user) :
            $errors['email'] = "Ce courriel est utilisé pour un autre compte.";
        endif;
    else :
        $errors['email'] = "Le courriel n'est pas valide.";
    endif;

    // Check password and password confirmation
    if (empty($_POST['password']) || !passwordPregMatch($_POST['password']) || $_POST['password'] != $_POST['passwordConfirm']) :
        $errors['password'] = "Les mots de passe ne sont pas valides.";
    endif;

    // Check errors
    if (empty($errors)) :

        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $token = stringRandom(60); // generate a confirmation token
        $data = ['username' => $_POST['username'], 'email' => $_POST['email'], 'password' => $password, 'token_confirm' => $token];

        // Request to insert user
        $sql = "INSERT INTO users (username, email, password, token_confirm)
                VALUES (:username, :email, :password, :token_confirm)";
        $request = $db->prepare($sql);
        $request->execute($data);
        $userId = $db->lastInsertId();

        // Generate email contains confirmation link
        $emailSubject = "Open Food Truck - Confirmation de votre courriel";
        $emailMessage = "Afin de valider votre compte, veuillez cliquer sur le lien suivant \n";
        $emailMessage = "ou copiez le dans la barre d'adresse de votre navigateur puis liquer sur \"enter\" :\n\n";
        $emailMessage .= "http://localhost/php/initiation/openfoodtruck-php/openfoodtruck/src/";
        $emailMessage .= "signup-confirm.php?id=$userId&token=$token";
        $emailHeaders = array(
            'From' => 'webservice@openfoodtruck.fr',
            'Reply-To' => 'webservice@openfoodtruck.fr',
            'X-Mailer' => 'PHP/' . phpversion()
        );
        // $emailMessage = wordwrap($emailMessage, 70, "\n", true); // hyphenation test
        mail($_POST['email'], $emailSubject, $emailMessage, $emailHeaders);
        $_SESSION['flash'][] = [
            'message' => "Un courriel vous a été envoyé à l'adresse " . $_POST['email'] . ". " . "Veuiilez cliquer sur le lien pour valider votre compte.",
            'status' => 'info'
        ];
        header('Location: signin.php');
        die();
    endif;

endif;
?>

<?php require_once('template/header.php'); ?>
<section>
    <h1>S'inscrire</h1>
    <?= flash(); ?>
    <div class="notice">
        <p>Les champs marqués d'un astérisque (*) sont obligatoires</p>
        <p>Le pseudonyme doit contenir au moins 6 caractéres.</p>
        <p>Seuls les chiffres, les lettres majuscules et minuscules sont autorisées.</p>
        <p>Le mot de passe doit contenir au moins 8 caractéres.</p>
    </div>

    <div class="form-container wrapper">
        <form action="" method="post">
            <div class="form-log">
                <label for="username">Pseudo *</label>
                <input id="username" type="text" name="username" value="<?= valueField('username'); ?>" />
            </div>
            <?= !empty($errors) ? '<div class="error-field"><p>' . $errors['username'] . '</p>' : '' ?>
    </div>
    <div class="form-log">
        <label for="email">Courriel *</label>
        <input id="email" type="tewt" name="email" value="<?= valueField('email'); ?>" />
        <?= !empty($errors['email']) ? '<div class="error-field"><p>' . $errors['email'] . '</p>' : '' ?>
    </div>
    <div class="form-log">
        <label for="password">Mot de passe *</label>
        <input id="password" type="password" name="password" />
        <?= !empty($errors['password']) ? '<div class="error-field"><p>' . $errors['password'] . '</p>' : '' ?>
    </div>
    <div class="form-log">
        <label for="password-confirm">Confirmez votre mot de passe *</label>
        <input id="password-confirm" type="password" name="passwordConfirm" />
    </div>
    <button type="submit">S'inscrire</button>
    </form>
    </div>
</section>

<?php require_once('template/footer.php'); ?>