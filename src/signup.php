<?php
session_start();
require_once('includes/db.php');
require_once('includes/functions.php');

// Check submit form
if (!empty($_POST)) :

    debugP($_SESSION, 'debug $session'); 
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
            'message' => "Un courriel vous a été envoyé à l'adresse " . $_POST['email'] . ". " . "Veuillez cliquer sur le lien pour valider votre compte.",
            'status' => 'succes'
        ];
        header('Location: signin.php');
        die();
    endif;
    $_SESSION['flash'][] = [
        'message' => "Les informations ne sont pas valides.",
        'status' => 'error'
    ];
endif;
?>

<?php require_once('template/header.php'); ?>

<section>
  
    
    <h1>S'inscrire</h1>
    
    <div class="notice">
        <p>Les champs marqués d'un astérisque (*) sont obligatoires</p>
    </div>
    <?= flash() ?>
    <div class="form-container">
        <form action="" method="post">
            <div class="form-log">
                <label for="username">* Pseudo <span class="text-info">(Seulement des lettres, chiffres et le tiret du bas)</span></label>
                <input id="username" type="text" name="username" value="<?= valueField('username'); ?>" />
                <?= !empty($errors['username']) ? '<div class="error-field">' . $errors['username'] . '</div>' : '' ?>
            </div>
            <div class="form-log">
                <label for="email">* Courriel</label>
                <input id="email" type="tewt" name="email" value="<?= valueField('email'); ?>" />
                <?= !empty($errors['email']) ? '<div class="error-field">' . $errors['email'] . '</div>' : '' ?>
            </div>
            <div class="form-log">
                <label for="password">* Mot de passe <span class="text-info">(Seulement des lettres, chiffres et le tiret du bas)</label>
                <input id="password" type="password" name="password" />
                <?= !empty($errors['password']) ? '<div class="error-field">' . $errors['password'] . '</div>' : '' ?>
            </div>
            <div class="form-log">
                <label for="password-confirm">* Confirmez votre mot de passe</label>
                <input id="password-confirm" type="password" name="passwordConfirm" />
            </div>
            <button type="submit">S'inscrire</button>
        </form>
    </div>
</section>

<?php require_once('template/footer.php'); ?>