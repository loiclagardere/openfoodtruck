<?php
require_once('includes/bootstrap.php');
// require_once('includes/functions.php');
authentificated();




// Check submit form : reset-password-form
if (isset($_POST['reset-password-form'])) : // test
    $errors = [];
    // Check content field content password and passwordConfirm
    if (!empty($_POST['password']) && !empty($_POST['passwordConfirm'])) :

        // Compare the field content password and passwordConfirm
        if ($_POST['password'] === $_POST['passwordConfirm']) :

            $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $userId = $_SESSION['auth']->id;
            $data = [
                'password' => $passwordHash,
                'user_id'  => $userId
            ];

            // Request to update user
            $sql = "UPDATE users
                    SET password = :password
                    WHERE user_id = :user_id";
            $request = $db->prepare($sql);
            $request->execute($data);

            $_SESSION['flash']['password'] = [
                'message' => 'Votre mot de passe a bien été mis à jour.',
                'status' => 'success'
            ];
        else :
            $errors['password'] = "Les mots de passe saisis ne sont pas identiques.";
        endif;
    else :
        $errors['password'] = "Ce champs est obligatoire.";
        $errors['passwordConfirm'] = "Ce champs est obligatoire";
    endif;
endif;

?>


<?php require_once('template/header.php'); ?>

<section>
    <h1> Votre compte utilisateur </h1>

    <p> Bienvenue <?= $_SESSION['auth']->username; ?> </p>

    <h2>Modifier votre mot de passe</h2>

    <div class="notice">
        <p>Les champs marqués d'un astérisque (*) sont obligatoires</p>
    </div>
    <?= flash() ?>
    <div id="reset-password-form" class="form-container">
        <form id="reset-form" action="" method="post">
            <div class="form-group" name="passwordGroup">
                <label for="password">* Saisissez un mot de passe</label>
                <input id="password" type="password" name="password" required />
                <?= !empty($errors['password']) ? '<div class="error-field">' . $errors['password'] . '</div>' : '' ?>

            </div>
            <div class="form-group" name="passwordConfirmgroup">
                <label for="password-confirm">* Confirmez le mot de passe</label>
                <input id="password-confirm" type="password" name="passwordConfirm" required />
                <?= !empty($errors['passwordConfirm']) ? '<div class="error-field">' . $errors['passwordConfirm'] . '</div>' : '' ?>
            </div>
            <button type="submit" name="resetPasswordForm">Changer le mot de passe</button>
        </form>
    </div>
</section>

<section>
    <h2>Ajouter un établissement</h2>

    <div class="form-container">
        <form id="company-form" action="" method="post" autocomplete="on">
            <div class="form-group">
                <label for="company-name">* Nom de l'établissement</label>
                <input id="company-name" type="text" name="name" />
                <?= !empty($errors['company_name']) ? '<div class="error-field">' . $errors['company_name'] . '</div>' : '' ?>
            </div>
            <div class="form-group" name="addressGroup">
                <label for="address">* Adresse </label>
                <select id="address" name="company_address"></select>
            </div>
            <input id="street" type="hidden" name="company_street" />
            <input id="postcode" type="hidden" name="company_postcode" />
            <input id="city" type="hidden" name="company_city" />
            <input id="latitude" type="hidden" name="company_latitude" />
            <input id="longitude" type="hidden" name="company_longitude" />
            <div>
                <select id="coocking-diets" class="form-group select" name="couleur">
                    <option value="none" selected>Aucun</option>
                    <option value="vegetarian">végétarien</option>
                    <option value="vegan">végétalien</option>
                    <option value="salt-free">Sans sel</option>
                    <option value="gluten-free">Sans gluten</option>
                    <option value="lactose-free">Sans lactose</option>
                    <option value="locavore">locavore</option>
                </select>
            </div>

            <button type="submit" name="companyForm">Ajouter l'établissement</button>
        </form>
    </div>

</section>



<?php require_once('template/footer.php'); ?>