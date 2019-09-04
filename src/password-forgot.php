<?php
session_start();
require_once('includes/bootstrap.php');


// Check submit form
if (!empty($_POST) && empty($_POST['lastname'])) :


    // Check content field and email format
    if (!empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) :
        $data = ['email' => $_POST['email']];
        // Request to select user
        $sql = "SELECT *
                FROM users
                WHERE email = :email
                AND token_confirmed_at IS NOT NULL"; // IS NOT NULL : account must have been confirmed
        $request = $db->prepare($sql);
        $request->execute($data);
        $user = $request->fetch();


        // Check user
        if ($user) :
            $userId = $user->user_id;
            $tokenReset = stringRandom(60);
            $data = [
                'token_reset' => $tokenReset,
                'user_id' => $userId
            ];
            // Request to update user : 
            $sql = "UPDATE users
                    SET token_reset = :token_reset, token_reseted_at = NOW()
                    WHERE user_id = :user_id";
            $request = $db->prepare($sql);
            $request->execute($data);
            // Generate email contains confirmation link
            $confirmationLink = "http://localhost/php/initiation/openfoodtruck-php/openfoodtruck/src/password-reset.php?user_id=$userId&token_reset=$tokenReset";
            $subject = "Validez votre nouveau mot de passe.";
            $body = passwordForgotMail($_POST['email'], $confirmationLink);
            $sendMailResult = sendMail($_POST['email'], $subject, $body);


            if ($sendMailResult = true) :
                $_SESSION['flash'][] = [
                    'message' => "<p>Un courriel vous a été envoyé à l'adresse " . $_POST['email'] . ". </p>" . "<p>Veuillez cliquer sur le lien pour valider votre nouveau mot de passe.</p>",
                    'status' => 'succes'
                ];
                header('Location: signin.php');
                die();
            else :
                $_SESSION['flash'][] = [
                    'message' => "<p>Une erreur est survenue sur le serveur</p><p>Veuillez renouveler votre inscription.</p>",
                    'status' => 'error'
                ];
            endif;


        else :
            $_SESSION['flash'][] = [
                'message' => "Ce couriel ne correspond à aucun compte.",
                'status' => "error"
            ];
        endif;
    endif;
endif;

?>


<?php require_once('header.php'); ?>

<section class="section-form">
    <h2>Mot de passe oublié</h2>
    <div class="notice">
        <p>Les champs marqués d'un astérisque (*) sont obligatoires</p>
    </div>
    <?= flash() ?>
    <p>Veuillez entrer l'adresse courriel utilisée pour vous connecter à votre compte Open Food Truck.</p>
    <p>Un message avec un lien va vous étre envoyé à cette adresse afin que vous puissiez reinitialiser votre mot de passe.</p>

    <div class="form-container">
        <form action="" method="post">

            <div id="lastname" class="form-group" name="lastnameGroup">
                <label for="lastname">* Nom de famille </label>
                <input id="lastname" type="text" name="lastname" />
            </div>

            <div class="form-group" name="emailGroup">
                <label for="email">* Couriel</label>
                <div class="contain-input">
                    <input id="email" type="emal" name="email" value="<?= valueField('username'); ?>" />
                </div>
            </div>

            <button type="submit">Recevoir le courriel</button>
            
        </form>
    </div>
</section>

<?php require_once('footer.php'); ?>