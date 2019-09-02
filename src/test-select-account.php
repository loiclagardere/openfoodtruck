<?php
// require_once('includes/db.php');
// require_once('includes/functions.php');
// require_once('includes/queries.php');
require_once('includes/bootstrap.php');

$coockingDiets = selectDatabase($db, 'coocking_diets') ? selectDatabase($db, 'coocking_diets') : "";
$coockingTypes = selectDatabase($db, 'coocking_types') ? selectDatabase($db, 'coocking_types') : "";
$coockingOrigins = selectDatabase($db, 'coocking_origins') ? selectDatabase($db, 'coocking_origins') : "";

// simmulate authentification /////////////////////////////////
$_SESSION['auth'] = [
    'id' => 204
];
$userId = $_SESSION['auth']['id'];

$user = getAll($db, $userId);
// debugP($user[0], '$user');
// debugP($user[0]->coocking_diet_name, '$user');
// die();

// debugV($_POST['coocking_diet']);////////////////////////////

// Check form send
if (!empty($_POST)) :

    $errors = [];
    // Check company_name field
    if (empty($_POST['company_name'])) :
        $errors['company_name'] = "Ce champs est obligatoire.";
    endif;

    // Check situation fiels
    if (empty($_POST['company_situation'])) :
        $errors['company_situation'] = "Ce champs est obligatoire.";
    endif;

    // Check errors
    // if (empty($errors)) :
    //     echo "pas d'erreur";
    //     $name = trim($_POST['company_name']);
    //     $situation = $_POST['company_situation'];
    //     $label = $_POST['company_label'];
    //     $street = $_POST['company_street'];
    //     $postcode = $_POST['company_postcode'];
    //     $city = $_POST['company_city'];
    //     $latitude = $_POST['company_latitude'];
    //     $longitude = $_POST['company_longitude'];

    //     $data = [
    //         'user_id' => $userId,
    //         'company_name' => $name,
    //         'company_situation' => $situation,
    //         'company_label' => $label,
    //         'company_street' => $street,
    //         'company_postcode' => $postcode,
    //         'company_city' => $city,
    //         'company_latitude' => $latitude,
    //         'company_longitude' => $longitude
    //     ];

    //     $sql = "UPDATE users
    //             SET company_name = :company_name,
    //                 company_situation = :company_situation,
    //                 company_label = :company_label,
    //                 company_street = :company_street,
    //                 company_postcode = :company_postcode,
    //                 company_city = :company_city,
    //                 company_latitude = :company_latitude,
    //                 company_longitude = :company_longitude
    //             WHERE user_id = :user_id";

    //     $request = $db->prepare($sql);
    //     $request->execute($data);
    // endif;

    if (!empty($_POST['coocking_diet'])) :
        $sql = "DELETE FROM users_coocking_diets
                WHERE id_users = " . $userId;
        $request = $db->prepare($sql);
        $request->execute();
        foreach ($_POST['coocking_diet'] as $value) :
            $data = ['id_users' => $userId, 'id_coocking_diets' => $value];
            $sql = "INSERT INTO users_coocking_diets (id_users, id_coocking_diets)
                    VALUES (:id_users, :id_coocking_diets)";
            $request = $db->prepare($sql);
            $request->execute($data);
        endforeach;
    endif;

endif;



?>

<?php require_once('template/header.php'); ?>

<section>
<h2>Ajouter un établissement</h2>
    <div class="form-container">
        <form action="" method="post">
            <div class="form-group">
                <label for="company-name" name="companyNameGroup">* Nom de l'établissement</label>
                <div class="contain-input">
                    <input id="company-name" type="text" name="company_name" required value="<?php echo isset($user->company_name) ? $user['company_name'] : ""  ?>">
                </div>
                <?= !empty($errors['name']) ? '<div class="error-field">' . $errors['name'] . '</div>' : '' ?>
            </div>

            <div class="form-group" name="situationGroup">
                <label for="company-situation">* Adresse </label>
                <select id="company-situation" name="company_situation" required></select>
                <?= !empty($errors['situation']) ? '<div class="error-field">' . $errors['situation'] . '</div>' : '' ?>
            </div>
            <input id="company-label" type="hidden" name="company_label" />
            <input id="company-street" type="hidden" name="company_street" />
            <input id="company-postcode" type="hidden" name="company_postcode" />
            <input id="company-city" type="hidden" name="company_city" />
            <input id="company-latitude" type="hidden" name="company_latitude" />
            <input id="company-longitude" type="hidden" name="company_longitude" />

            <div class="form-group" name="coockingDietsGroup">
                <label for="coocking-diet"> Types de régimes alimentaires </label>
                <select id="coocking-diet" name="coocking_diet[]" multiple="multiple">
                    <?php foreach ($coockingDiets as $value) : ?>
                    <option <?php
                                foreach ($user as $diet) :
                                    echo (isset($diet->coocking_diet_name) && $value->coocking_diet_name === $diet->coocking_diet_name) ? ' selected' : '';
                                endforeach;
                                ?> value=" <?= $value->diet_id ?> "><?= $value->coocking_diet_name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" name="coockingTypesGroup">
                <label for="coocking-type"> Types des plats </label>
                <select id="coocking-type" name="coocking_type[0,1]" multiple="multiple">
                    <?php foreach ($coockingTypes as $value) : ?>
                    <option <?php
                                foreach ($user as $type) :
                                    // debugP($type);
                                    // die();
                                    echo (isset($type->coocking_type_name) && $value->coocking_type_name === $type->coocking_type_name) ? ' selected' : '';
                                endforeach;
                                ?> value=" <?= $value->type_id ?> "><?= $value->coocking_type_name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" name="coockingOriginsGroup">
                <label for="coocking-origin"> Origine des plats </label>
                <select id="coocking-origin" name="coocking_origin[]" multiple="multiple">
                    <?php foreach ($coockingOrigins as $value) : ?>
                    <option  <?php
                                foreach ($user as $origin) :
                                    echo (isset($origin->coocking_origin_name) && $value->coocking_origin_name === $origin->coocking_origin_name) ? ' selected' : '';
                                endforeach;
                                ?> value=" <?= $value->origin_id ?> "><?= $value->coocking_origin_name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" name="companyForm">Valider</button>
        </form>
    </div>
    <div>
        <p>Guidés par l'envie de répondre au mieux à vos attentes, n'hésitez pas à nous contacter à cette adresse : serviceclient@openfoodtruck.fr pour nous faire
            part de vos remarques, souhaits ou suggestions.</p>
    </div>
</section>

<?php require_once('template/footer.php'); ?>