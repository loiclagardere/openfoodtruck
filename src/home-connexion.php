<?php
require_once('includes/bootstrap.php');
?>

<?php require_once('header.php'); ?>

<div class="zeropix">
    
    <h2>Espace pro</h2>
    <div>
        <p><strong>Cette section est reservée aux professionnels souhaitant faire apparaître leur établissement sur ce site.</strong></p>
    </div>
    
    <?= flash() ?>
    <div class="connexion-contain">

        <section class="section-connexion fifty">
            <h3>Connexion</h3>
                <div class="text-connexion-notice">
                    <p>Pour vous connecter à votre espace membre.</p>
                    <p>Si vous avez oublié vos identifiants, cliquez <a href="password-forgot.php">ici</a>.</p>
                    <p><span classs="border-link"><a href="signin.php">Je souhaite me connecter.</a></span></p>
                </div>
        </section>

        <section class="section-connexion fifty">
            <h3>Inscription</h3>
            <div class="connexion-notice">
                <div class="text-connexion-notice">
                    <p>Pour vous inscrire et créer un compte</p>
                    <p>Notez que votre numéro de Sieret vous sera demandé lors de l'inscription.</p>
                    <p><span classs="border-link"><a href="signup.php">Je souhaite créer un compte.</a></span></p>
                </div>
            </div>
        </section>

    </div>
</div>

<?php require_once('footer.php'); ?>