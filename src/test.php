<?php
require_once('includes/functions.php');



// test regex
// Un regex est toujours entourée de caractéres spéciaux utilisés comme délimiteur, ici /.
//
// l'expression doit contenir un minimum de 8 caractéres et doit être composée d'au moins un lettre en minuscule,
// une letre en majuscule, un chiffre et un caractére
$regex ="/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/";
$pregMatch = preg_match($regex, "Pala3!");

if ($pregMatch === 1):
    echo 'regex est contenu dans phrase';
    debugV($pregMatch);
else:
    echo 'regex n\'est pas contenu dans la phrase';
    debugV($pregMatch);
endif;
?>




<?php

//////////////////////  Back up signup.php   ///////////////////

session_start();
require_once('includes/db.php');
require_once('includes/functions.php');

// Check data posted
if (!empty($_POST)) :

    $errors = []; // to stock messages error

    // Check pseudo
    if (!empty($_POST['username']) && preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])) :
        $data = ['username' => $_POST['username']];
        $sql = "SELECT id FROM users WHERE username = :username";
        $request = $db->prepare($sql);
        $request->execute($data);
        $user = $request->fetch();

        if ($user) :
            $errors['username'] = "Ce pseudo est dejà pris.";
        endif;
        else :
            $errors['username'] = "Le format du pseudo n'est pas valide.";
    endif;

    // Check email
    if (!empty($_POST['email']) && emailFilterVar($_POST['email'])) :
        $data = ['email' => $_POST['email']];
        $sql = "SELECT id FROM users WHERE email = :email";
        $request = $db->prepare($sql);
        $request->execute($data);
        $user = $request->fetch();

        if ($user) :
            $errors['email'] = "Ce courriel n'est pas disponible.";
        endif;
    else :
        $errors['email'] = "Le courriel n'est pas valide.";
    endif;
    // if (empty($_POST['email']) || !emailFilterVar($_POST['email'])) :
    //     $errors['email'] = "Le courriel n'est pas valide.";
    // else :
    //     $data = ['email' => $_POST['email']];
    //     $sql = "SELECT id FROM users WHERE email = :email";
    //     $request = $db->prepare($sql);
    //     $request->execute($data);
    //     $user = $request->fetch();

    //     if ($user) :
    //         $errors['email'] = "Ce courriel est dejà utilisé pour un autre compte.";
    //     endif;
    // endif;

    // Check pasword and pasword confirmation
    if (empty($_POST['password']) || $_POST['password'] != $_POST['passwordConfirm']) :
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
        $userId = $db->lastInsertId();
        // confirmation mail
        $emailSubject = "Open Food Truck - Confirmation de votre courriel";
        $emailMessage = "Afin de valider votre compte, veuillez cliquer sur le lien suivant :\n\n";
        $emailMessage .= "http://localhost/php/initiation/openfoodtruck-php/openfoodtruck/src/";
        $emailMessage .= "confirm.php?id=$userId&token=$token";
        // $emailMessage = wordwrap($emailMessage, 70, "\n", true); // hyphenation test
        mail($_POST['email'], $emailSubject, $emailMessage);
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
    <form action="" method="post">
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
            <input id="password-confirm" type="password" name="passwordConfirm" />
        </div>
        <button type="submit">M'inscrire</button>
    </form>
</div>

<?php require_once('template/footer.php'); ?>




