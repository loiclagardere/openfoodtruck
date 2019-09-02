<?php
session_start(); // use to connect user
require_once('includes/bootstrap.php');

// check data in URL
if (isset($_GET['user_id']) && isset($_GET['token_reset'])) :
    $userId = $_GET['user_id'];
    $tokenReset = $_GET['token_reset'];
    $data = [
        'user_id' => $userId,
        'token_reset' => $tokenReset
    ];

    // Request to select user
    $sql = "SELECT * 
            FROM users
            WHERE user_id = :user_id
            AND token_reset = :token_reset
            AND token_reseted_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)"; // prevoir crontab
    $request = $db->prepare($sql);
    $request->execute($data);
    $user = $request->fetch();

    // check user
    if ($user) :

        // check form
        if (!empty($_POST)) :

            // check password
            if (!empty($_POST['password']) && $_POST['password'] === $_POST['passwordConfirm']) :

                $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $data = [
                    'user_id' => $userId,
                    'token_reset' => $tokenReset,
                    'password' => $passwordHash
                ];
                $sql = "UPDATE users
                        SET password = :password, token_reset = NULL, token_reset_at = NULL
                        WHERE user_id = :user_id";

            else :
                $_SESSION['falsh'][] = [
                    'message' => 'Les informations saisies ne sont pas correctes.',
                    'status' => 'error'
                ];

            endif;
        endif;
        $_SESSION['flash'][] = [
            'message' => "Le lien utilisé n'est plus valide",
            'status' => 'error'
        ];
    endif;
else :
    header('Location: signin.php');
    die();
endif;
?>

<?php require_once('template/header.php'); ?>

<h2> Réinitialiser votre mot de passe </h2>

<section class="section-form">
    <div class="notice">
        <p>Les champs marqués d'un astérisque (*) sont obligatoires</p>
    </div>
    <div class="form-container">
        <form action="" method="post">
            <div class="form-group" name="passwordGroup">
                <label for="password">Saisissez un mot de passe *</label>
                <div class="contain-input">
                    <input id="password" type="password" name="password" required />
                </div>
            </div>
            <div class="form-group" name="passwordConfirmGroup">
                <label for="password-confirm">Confirmez le mot de passe *</label>
                <div class="contain-input">
                    <input id="password-confirm" type="password" name="passwordConfirm" required />
                </div>
            </div>
            <button type="submit">Valider le mot de passe</button>
        </form>
    </div>
</section>
<?php require_once('template/footer.php'); ?>