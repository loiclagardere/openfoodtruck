<?php
session_start();
require_once('includes/bootstrap.php');

// Check submit form
if (!empty($_POST) && empty($_POST['lastname'])) :

    $errors = []; // to stock messages error


    // Check username field content and the content format
    if (usernamePregMatch($_POST['username'])  && !empty($_POST['username'])) :
        $data = ['username' => $_POST['username']];
        $sql = "SELECT user_id
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
        $sql = "SELECT user_id
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


    // Check Siret number
    if (!empty($_POST['siret']) && siretPregMatch($_POST['siret'])) :
        $urlApi = "https://entreprise.data.gouv.fr/api/sirene/v1/siret/"; // url API Sirene
        $siret = $_POST['siret']; // n° siret 45188444900021
        $requestApi = $urlApi . $siret;

        // curl session
        $curlSession = curl_init();
        curl_setopt($curlSession, CURLOPT_URL, $requestApi); // url submit
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);  // TRUE pour retourner le transfert en tant que chaîne de caractères de la valeur retournée par curl_exec() au lieu de l'afficher directement.
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

        debugP($resultApi);

        if (isset($resultApi->etablissement)) :
            $data = ['siret' => $_POST['siret']];
            $sql = "SELECT user_id
                    FROM users
                    WHERE siret = :siret";
            $request = $db->prepare($sql);
            $request->execute($data);
            $user = $request->fetch();

            debugP($user, 'result user');
            if ($user) :
                $errors['siret'] = "Ce numéro de Siret est déjà enregistré.";
            endif;


        else :
            $errors['siret'] = "Le numéro de Siret n'est pas valide.";
        endif;


    endif;


    // Check password and password confirmation
    if (empty($_POST['password']) || !passwordPregMatch($_POST['password']) || $_POST['password'] != $_POST['passwordConfirm']) :
        $errors['password'] = "Les mots de passe ne sont pas valides.";
    endif;

    // debugP($errors);
    // Check errors
    if (empty($errors)) :
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $token = stringRandom(60); // generate a confirmation token
        $data = ['username' => $_POST['username'], 'email' => $_POST['email'], 'siret' => $_POST['siret'], 'password' => $password, 'token_confirm' => $token];

        // Request to insert user
        $sql = "INSERT INTO users (username, email, siret, password, token_confirm)
                VALUES (:username, :email, :siret, :password, :token_confirm)";
        $request = $db->prepare($sql);
        $request->execute($data);
        $userId = $db->lastInsertId();

        // Generate email contains confirmation link
        $confirmationLink = "http://localhost/php/initiation/openfoodtruck-php/openfoodtruck/src/signup-confirm.php?user_id=$userId&token=$token";
        $subject = "Validez votre compte sur Openfoodtruck";
        $body = signupMail($_POST['username'], $_POST['email'], $confirmationLink);

        $sendMailResult = sendMail($_POST['email'], $subject, $body);


        if ($sendMailResult = true) :
            $_SESSION['flash'][] = [
                'message' => "<p>Un courriel vous a été envoyé à l'adresse " . $_POST['email'] . ". </p>" . "<p>Veuillez cliquer sur le lien pour valider votre compte.</p>",
                'status' => 'success'
            ];
            header('Location: signin.php');
            die();


        else :
            $_SESSION['flash'][] = [
                'message' => "<p>Une erreur est survenue sur le serveur</p><p>Veuillez renouveler votre inscription.</p>",
                'status' => 'error'
            ];
        endif;


    endif;


endif;
?>

<?php require_once('header.php'); ?>

<section class="section-form">
    <h2>Inscription</h2>
    <div class="notice">
        <p>Les champs marqués d'un astérisque (*) sont obligatoires</p>
    </div>
    <?= flash() ?>
    <div class="form-container">
        <form action="" method="post">

            <div id="lastname" class="form-group" name="lastnameGroup">
                <label for="lastname">* Nom de famille </label>
                <input id="lastname" type="text" name="lastname" />
            </div>

            <div class="form-group" name="usernameGroup">
                <label for="username">* Pseudo <span class="info-field">(Seulement des lettres, chiffres et le tiret du bas)</span></label>
                <div class="contain-input">
                    <input id="username" type="text" name="username" value="<?= valueField('username'); ?>" required />
                </div>
                <?= !empty($errors['username']) ? '<div class="error-field">' . $errors['username'] . '</div>' : '' ?>
            </div>

            <div class="form-group" name="emailGroup">
                <label for="email">* Courriel</label>
                <div class="contain-input">
                    <input id="email" type="text" name="email" value="<?= valueField('email'); ?>" required />
                </div>
                <?= !empty($errors['email']) ? '<div class="error-field">' . $errors['email'] . '</div>' : '' ?>
            </div>

            <div class="form-group" name="siretGroup">
                <label for="siret">* n° SIRET</label>
                <div class="contain-input">
                    <input id="siret" type="text" name="siret" maxlength="14" value="<?= valueField('siret'); ?>" required />
                </div>
                <?= !empty($errors['siret']) ? '<div class="error-field">' . $errors['siret'] . '</div>' : '' ?>
            </div>

            <div class="form-group" name="passwordGroup">
                <label for="password">* Mot de passe <span class="info-field">(Minimum 8 caractéres)</label>
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
            </div>

            <button id="signupForm" type="submit" name="signupForm">S'inscrire</button>

        </form>
    </div>
</section>

<?php require_once('footer.php'); ?>