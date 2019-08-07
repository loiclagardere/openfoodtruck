<?php session_start(); ?>
<?php require_once('includes/db.php'); ?>
<?php require_once('includes/functions.php'); ?>
<?php

// Check submit form
if (!empty($_POST)) :

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

            $userId = $user->id;
            $tokenReset = stringRandom(60);
            $data = [
                'token_reset' => $tokenReset,
                'id' => $userId
            ];

            // Request to update user : 
            $sql = "UPDATE users
                    SET token_reset = :token_reset, token_reseted_at = NOW()
                    WHERE id = :id";
            $request = $db->prepare($sql);
            $request->execute($data);

            // Generate email contains confirmation link
            $emailSubject = "Open Food Truck - Réinitialisation de votre mot de passe.";
            $emailMessage .= "Afin de valider votre nouveau mot de passe, veuillez cliquer sur le lien suivant \n";
            $emailMessage .= "ou copiez le dans la barre d'adresse de votre navigateur puis cliquez sur \"enter\" :\n\n";
            $emailMessage .= "http://localhost/php/initiation/openfoodtruck-php/openfoodtruck/src/";
            $emailMessage .= "password-reset.php?id=$userId&token_reset=$tokenReset";
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
        else :
            $_SESSION['flash'][] = [
                'message' => "Ce couriel ne correspond à aucun compte.",
                'status' => "error"
            ];
        endif;
    endif;
endif;

?>


<?php require_once('template/header.php'); ?>

<h1>Mot de passe oublié</h1>
<div class="notice">
    <p>Les champs marqués d'un astérisque (*) sont obligatoires</p>
</div>
<?= flash() ?>
<p>Veuillez entrer l'adresse couriel utilisée pour vous connecter à votre compte Open Food Truck.</p>
<p>Un message avec un lien va vous étre envoyé à cette adresse afin que vous puissiez reinitialiser votre mot de passe.</p>

<div class="form-container wrapper">
    <form action="" method="post">
        <div class="form-log">
            <label for="email">Couriel *</label>
            <input id="email" type="emal" name="email" value="<?= valueField('username'); ?>" />
        </div>

        <button type="submit">Envoyer couriel</button>
    </form>
</div>

<?php require_once('template/footer.php'); ?>