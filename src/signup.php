<?php
session_start();
require_once('includes/db.php');
// require_once('vendor/autoload.php');
require_once('includes/functions.php');
require_once('includes/mail.php');

// Check submit form
if (!empty($_POST)) :
    // unset($_SESSION['msg']);
    $errors = []; // to stock messages error

    // Check SIRET number
    $urlApi = "https://entreprise.data.gouv.fr/api/sirene/v1/siret/"; // url API Sirene
    $siret = $_POST['siret']; // n° siret 45188444900021
    $requestApi = $urlApi . $siret;

    // curl session
    $curlSession = curl_init();
    curl_setopt($curlSession, CURLOPT_URL, $requestApi);
    curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($curlSession, CURLOPT_SSL_VERIFYPEER, true);  // certicat ssl
    // curl_setopt($curlSession, CURLOPT_SSL_VERIFYHOST, 2); // 
    curl_setopt($curlSession, CURLOPT_HTTPHEADER, array(
        'Accept: */*',
        // 'Accept-Encoding: gzip, deflate', // renvoie null
        'Content-Type: application/json; charset=utf-8',
        'Host: entreprise.data.gouv.fr',
    ));
    $resultApi = json_decode(curl_exec($curlSession));
    curl_close($curlSession);

    if (!isset($resultApi->etablissement)) :
        $errors['siret'] = "Le numéro de Siret n'est pas valide.";
    endif;

    // Check username field content and the content format
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
    if (empty($errors) && isset($resultApi->etablissement)) :

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
        $emailMessage = "<p>Afin de valider votre compte, cliquez ";
        $emailMessage .= "<a href=\"http://localhost/php/initiation/openfoodtruck-php/openfoodtruck/src/signup-confirm.php?id=$userId&token=$token\"> ici </a>";
        $emailMessage .= " ou copier le lien suivant dans la barre d'adresse de votre navigateur puis liquer sur \"enter\" :<br>";
        $emailMessage .= "http://localhost/php/initiation/openfoodtruck-php/openfoodtruck/src/signup-confirm.php?id=$userId&token=$token";

        // $emailMessage = wordwrap($emailMessage, 70, "\n", true); // hyphenation test
        sendMail('loic.lagardere@free.fr', $emailSubject, $emailMessage);

die('<br>test envoie mail');
        $_SESSION['flash'][] = [
            'message' => "<p>Un courriel vous a été envoyé à l'adresse " . $_POST['email'] . ". </p>" . "<p>Veuillez cliquer sur le lien pour valider votre compte.</p>",
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
    <p><strong>Cette accés est reservé aux professionnels souhaitant faire apparaitre leur etablisssement sur le site.</strong></p>
    <div class="notice">
        <p>Les champs marqués d'un astérisque (*) sont obligatoires</p>
    </div>
    <?= flash() ?>
    <div class="form-container">
        <form action="" method="post">
            <div class="form-group" name="usernameGroup">
                <label for="username">* Pseudo <span class="text-info">(Seulement des lettres, chiffres et le tiret du bas)</span></label>
                <input id="username" type="text" name="username" value="<?= valueField('username'); ?>" required />
                <?= !empty($errors['username']) ? '<div class="error-field">' . $errors['username'] . '</div>' : '' ?>
            </div>
            <div class="form-group" name="emailGroup">
                <label for="email">* Courriel</label>
                <input id="email" type="text" name="email" value="<?= valueField('email'); ?>" required />
                <?= !empty($errors['email']) ? '<div class="error-field">' . $errors['email'] . '</div>' : '' ?>
            </div>
            <div class="form-group" name="siretGroup">
                <label for="siret">* n° SIRET</label>
                <input id="siret" type="text" name="siret" maxlength="14" value="<?= valueField('siret'); ?>" required />
                <?= !empty($errors['siret']) ? '<div class="error-field">' . $errors['siret'] . '</div>' : '' ?>
            </div>
            <div class="form-group" name="passwordGroup">
                <label for="password">* Mot de passe <span class="text-info">(Minimum 8 caractéres)</label>
                <input id="password" type="password" name="password" required />
                <?= !empty($errors['password']) ? '<div class="error-field">' . $errors['password'] . '</div>' : '' ?>
            </div>
            <div class="form-group" name="passwordConfirmGroup">
                <label for="password-confirm">* Confirmez votre mot de passe</label>
                <input id="password-confirm" type="password" name="passwordConfirm" required />
            </div>
            <button type="submit" name="signupForm">S'inscrire</button>
        </form>
    </div>
</section>

<?php require_once('template/footer.php'); ?>