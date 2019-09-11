<?php
require_once('includes/bootstrap.php');
authentificated();

?>
<?php require_once('header.php'); ?>


    <h2> Votre compte utilisateur </h2>
    <span class="username"> Bienvenue <?= $_SESSION['auth']->username; ?>.</span>


<div class="zeropix">

      <div class="connexion-contain">

        <section class="section-connexion fifty">
            <h3>Modifier votre mot de passe</h3>
                <div class="text-connexion-notice">
                    <p>Pour des raisons de sécurités, n'hésitez pas à modifier réguliérement votre mot de passe.</p>
                    <p><span classs="border-link"><a href="account-password-reset.php">Modifier mon mot de passe</a></span></p>
                </div>
        </section>

        <section class="section-connexion fifty">
            <h3>Ajouter / modifier votre établissement</h3>
            <div class="connexion-notice">
                <div class="text-connexion-notice">
                    <p>Pour gérer les informations relatives à votre établissement.</p>
                    <p>Notez que ces informations sont utiles pour un meilleur référencement à la fois sur notre site et sur les différents moteurs de recherches.</p>
                    <p><span classs="border-link"><a href="account-company.php">Gérer ma fiche établissement</a></span></p>
                </div>
            </div>
        </section>

    </div>
</div>


<?php require_once('footer.php'); ?>