<?php
require_once('includes/db.php');
require_once('includes/functions.php');
authentificated();

//


// Check submit form
// if (isset($_POST['reset-password-form']) && !empty($_POST)) :
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
$_SESSION['flash']['password'] = [
    'message' => 'Les informations ne sont pas valides.',
    'status' => 'error'
];
endif;

// add info users about company
if (!empty($_POST['CompanyForm'])) :
    $errors = [];

    $userId = $_SESSION['auth']->id;
    $data = ['id_user' => $userId];

    $sql = "SELECT *
            FROM users
            WHERE id_user = :id_user";
    $request = $db->prepare($sql);
    $request->execute($data);
    $user = $request->fetch();


    // Check the field content
    if (empty($_POST['company_name'])) :
        $_SESSION['flash'][] = [
            'message' => "Veuillez remplir les champs obligatoires.",
            'status' => "error"
        ];
        $errors['name'] = "Veuillez remplir ce champ.";

    elseif (emtpy($user->company_name)) :
        $data = ['company_name' => $_POST['company_name'], 'id' => $userId];

        $sql = "INSERT INTO users (company_name, id)
                    VALUES (:company_name, :id)";
        $request = $db->prepare($sql);
        $request->execute($data);

        $_SESSION['flash'][] = [
            'message' => "Les informations sont bien enrgistrées.",
            'status' => "success"
        ];

    else :
    /////////// UPDATE INTO USERS  /////////
        echo 'faire insert into users';
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
            'message' => "Veuillez remplir les champs obligatoires.SVP",
            'status' => "error"
        ];
        $errors['name'] = "Veuillez remplir ce champ.";
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
            <div>
                <select id="coock-diet" class="form-group select" name="couleur">
                    <option value="vegetarian" selected>Aucun</option>
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