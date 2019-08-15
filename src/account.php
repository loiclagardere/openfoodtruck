<?php
require_once('includes/db.php');
require_once('includes/functions.php');
authentificated();

// Check submit form
// if (isset($_POST['reset-password-form']) && !empty($_POST)) :
if (isset($_POST['resetPasswordForm'])) : ///////TEST/////////
    $errors = [];
    // Check contnent field password and passwordConfirm
    if (!empty($_POST['password']) && !empty($_POST['passwordConfirm'])) :

        // Compare the values entered 
        if ($_POST['password'] === $_POST['passwordConfirm']) :

            $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $userId = $_SESSION['auth']->id;
            $data = [
                'password' => $passwordHash,
                'id'       => $userId
            ];

            // Request to update user
            $sql = "UPDATE users
                    SET password = :password
                    WHERE id = :id";
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
    // $_SESSION['flash']['password'] = [
    //     'message' => 'Les informations ne sont pas valides.',
    //     'status' => 'error'
    // ];
endif;

// add info of firm
if (isset($_POST['firmForm'])) :
    $errors = [];

    $userId = $_SESSION['auth']->id;
    $data = ['id_user' => $userId];

    $sql = "SELECT *
            FROM firms
            WHERE id_user = :id_user";
    $request = $db->prepare($sql);
    $request->execute($data);
    $firm = $request->fetch();

    // check Firm    
    if (!$firm) :
        echo 'insert';

        // Check the field content
        if (!empty($_POST['name'])) :
            $data = ['name' => $_POST['name'], 'id_user' => $userId];


            $sql = "INSERT INTO firms (name, id_user)
                    VALUES (:name, :id_user)";
            $request = $db->prepare($sql);
            $request->execute($data);
            $firmId = $db->lastInsertId();

            $_SESSION['flash'][] = [
                'message' => "Les informations sont bien enrgistrées.",
                'status' => "success"
            ];
        else :
            $_SESSION['flash'][] = [
                'message' => "Veuillez remplir les champs obligatoires.",
                'status' => "error"
            ];
            $errors['name'] = "Veuillez remplir ce champ.";
        endif;
    else :

        if (!empty($_POST['name'])) :
            echo 'update';
            // debugP($_SESSION['auth']->id); ///////////////////////////////

            $data = ['name' => $_POST['name'], 'id_user' => $userId];
            $sql = "UPDATE firms
                SET name = :name
                WHERE id_user = :id_user";
            $request = $db->prepare($sql);
            $request->execute($data);
            $_SESSION['flash'][] = [
                'message' => "Les modifications sont bien enrgistrées.",
                'status' => "success"
            ];
        else :
            $_SESSION['flash'][] = [
                'message' => "Veuillez remplir les champs obligatoires.",
                'status' => "error"
            ];
            $errors['name'] = "Veuillez remplir ce champ.";
        endif;


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
                <input id="password" type="password" name="password" />
                <?= !empty($errors['password']) ? '<div class="error-field">' . $errors['password'] . '</div>' : '' ?>

            </div>
            <div class="form-group" name="passwordConfirmgroup">
                <label for="password-confirm">* Confirmez le mot de passe</label>
                <input id="password-confirm" type="password" name="passwordConfirm" />
                <?= !empty($errors['passwordConfirm']) ? '<div class="error-field">' . $errors['passwordConfirm'] . '</div>' : '' ?>
            </div>
            <button type="submit" name="resetPasswordForm">Changer le mot de passe</button>
            <!-- <button id="reset-btn" type="submit" name="resetPasswordForm" class="btn-invalid" disabled = "disabled">Changer le mot de passe</button> -->
        </form>
    </div>
</section>

<section>
    <h2>Ajouter un établissement</h2>

    <div class="form-container">
        <form id="firm-form" action="" method="post"  autocomplete="on">
            <div class="form-group">
                <label for="firm-name">* Nom de l'établissement</label>
                <input id="firm-name" type="text" name="name" />
                <?= !empty($errors['name']) ? '<div class="error-field">' . $errors['name'] . '</div>' : '' ?>
            </div>

            <button type="submit" name="firmForm">Ajouter l'établissement</button>
        </form>
    </div>

</section>






<?php require_once('template/footer.php'); ?>