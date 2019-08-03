<?php
session_start();
require_once('includes/db.php');
require_once('includes/functions.php');

// Check data posted
if (!empty($_POST)) :

    $errors = []; // to stock messages error

    // Check pseudo
    if (empty($_POST['username']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])) :
        $errors['username'] = "Le format du pseudo n'est pas valide.";
    else :
        $data = ['username' => $_POST['username']];
        $sql = "SELECT id FROM users WHERE username = :username";
        $request = $db->prepare($sql);
        $request->execute($data);
        $user = $request->fetch();

        if ($user) :
            $errors['username'] = "Ce pseudo est dejà pris.";
        endif;
    endif;

    // Check email
    if (empty($_POST['email']) || !emailFilterVar($_POST['email'])) :
        $errors['email'] = "Le courriel n'est pas valide.";
    else :
        $data = ['email' => $_POST['email']];
        $sql = "SELECT id FROM users WHERE email = :email";
        $request = $db->prepare($sql);
        $request->execute($data);
        $user = $request->fetch();

        if ($user) :
            $errors['email'] = "Ce courriel est dejà utilisé pour un autre compte.";
        endif;
    endif;

    // Check pasword and pasword confirmation
    if (empty($_POST['password']) || $_POST['password'] != $_POST['password_confirm']) :
        $errors['password'] = "Le mot de passe n'est pas valide.";
    endif;

    // Check errors
    if (empty($errors)) :
        //Request database
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // password hash
        $token = stringRandom(60); // generate a confirmation token
        $data = ['username' => $_POST['username'], 'email' => $_POST['email'], 'password' => $password, 'token_confirm' => $token];

        $sql = "INSERT INTO users (username, email, password, token_confirm) VALUES (:username, :email, :password, :token_confirm)";
        $request = $db->prepare($sql);
        $request->execute($data);
        $user_id = $db->lastInsertId();
        // confirmation mail
        $emailSubject = "Open Food Truck - Confirmation de votre courriel";
        $emailMessage = "Afin de valider votre compte, veuillez cliquer sur le lien suivant :\n\n";
        $emailMessage .= "http://localhost/php/initiation/openfoodtruck-php/openfoodtruck/src/";
        $emailMessage .= "confirm.php?id=$user_id&token=$token";
        // $emailMessage = wordwrap($emailMessage, 70, "\n", true); // hyphenation test
        mail($_POST['email'], $emailSubject, $emailMessage);
        $_SESSION['flash'][] = [
            'label' => "Un courriel vous a été envoyé à l'adresse " . $_POST['email'] . ". " . "Veuiilez cliquer sur le lien pour valider votre compte.",
            'status' => 'info'
        ];
        header('Location: signin.php');
        die();
    endif;

endif;
?>
<?php require_once('template/header.php'); ?>

<h1>S'inscrire</h1>

<?php if (!empty($errors)) : ?>
    <div class="message error">
        <p>Les informations saisies dans le formulaire ne sont pas correctes :</p>
        <ul>
            <?php foreach ($errors as $error) : ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<div class="form-container wrapper">
    <form action="" method="POST">
        <div class="form-log">
            <label for="username">Pseudo</label>
            <input id="username" type="text" name="username" />
        </div>
        <div class="form-log">
            <label for="email">Courriel</label>
            <input id="email" type="tewt" name="email" />
        </div>
        <div class="form-log">
            <label for="password">Mot de passe</label>
            <input id="password" type="password" name="password" />
        </div>
        <div class="form-log">
            <label for="password-confirm">Confirmez votre mot de passe</label>
            <input id="password-confirm" type="password" name="password_confirm" />
        </div>
        <button type="submit">M'inscrire</button>
    </form>
</div>

<?php require_once('template/footer.php'); ?>