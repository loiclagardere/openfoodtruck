<?php
session_start();
require_once('includes/db.php');
require_once('includes/functions.php');

// Check submit form
if (!empty($_POST)) :

    $errors = []; // to stock messages error

    // Check contnent field username and content format
    if (!empty($_POST['username']) && preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])) :
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

    // Check pasword and pasword confirmation
    if (empty($_POST['password']) || $_POST['password'] != $_POST['passwordConfirm']) :
        $errors['password'] = "Le mot de passe n'est pas valide.";
    endif;

    // Check errors
    if (empty($errors)) :

        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $token = stringRandom(60); // generate a confirmation token
        $data = ['username' => $_POST['username'], 'email' => $_POST['email'], 'password' => $password, 'token_confirm' => $token];
        
        // Request to insert user
        $sql = "INSERT INTO users (username, email,
                password, token_confirm)
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

<h1>S'inscrire</h1>
<div class="notice">
    <p>Les champs marqués d'un astérisque (*) sont obligatoires</p>
</div>
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
            <label for="username">Pseudo *</label>
            <input id="username" type="text" name="username" />
        </div>
        <div class="form-log">
            <label for="email">Courriel *</label>
            <input id="email" type="tewt" name="email" />
        </div>
        <div class="form-log">
            <label for="password">Mot de passe *</label>
            <input id="password" type="password" name="password" />
        </div>
        <div class="form-log">
            <label for="password-confirm">Confirmez votre mot de passe *</label>
            <input id="password-confirm" type="password" name="passwordConfirm" />
        </div>
        <button type="submit">S'inscrire</button>
    </form>
</div>

<?php require_once('template/footer.php'); ?>