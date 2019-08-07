<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
};
require_once('includes/functions.php');
?>

<?php


if (!empty($_POST)) :
    $_SESSION['msg']="message msseage";
    
endif;

// debugP($_SESSION['msg']);
// debugP($_SESSION);
debugP($_POST);

?>
<?php if(isset($_POST['signin-form']) || isset($_POST['signup-form'])) {
    echo 'ok <br>';
        foreach($_POST as $key => $msg) { 
        
        echo 'clé passé en post: ' . $key . ', and message passé en post: ' . $msg . '<br>';
    }

    echo Key($_POST);
    } else {
        echo 'ko <br>';
    };
    // foreach($_SESSION as $msg) { 
        
    //     echo 'nom du bouton passé en post ' . $msg;
    // }

    // unset($_SESSION);
// }
        
        ?>

<div class="form-container wrapper">
    <form action="" method="post">
        <div class="form-log">
            <label for="username">Pseudo</label>
            <input id="username" type="text" name="username" />
        </div>
        <div class="form-log">
            <label for="password">Mot de passe</label>
            <input id="password" type="password" name="password" />
        </div>
        <button name='signin-form' type="submit">M'inscrire</button>
    </form>
</div>
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
        <button name='signup-form' type="submit">M'inscrire</button>
    </form>
</div>