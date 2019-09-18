<?php
require_once('includes/bootstrap.php');
authentificated();

// debugP($_SESSION['auth'], 'auth');
$userId = $_SESSION['auth']->user_id;
$userData = getAll($db, $userId);

//debugP($userData); ////////////////////////////////////////////////////////////////////


// Company form
$coockingDiets = selectDatabase($db, 'coocking_diets') ? selectDatabase($db, 'coocking_diets') : "";
$coockingTypes = selectDatabase($db, 'coocking_types') ? selectDatabase($db, 'coocking_types') : "";
$coockingOrigins = selectDatabase($db, 'coocking_origins') ? selectDatabase($db, 'coocking_origins') : "";


// Check form send
if (!empty($_POST)  && isset($_POST['companyForm'])) :

    $errors = [];
    // Check company_name field
    if (empty($_POST['company_name'])) :
        $errors['company_name'] = "Ce champs est obligatoire.";
    endif;

    // Check situation fiels
    if (empty($_POST['company_situation']) && !$userData[0]->company_label) :
        $errors['company_situation'] = "Ce champs est obligatoire.";
    endif;


    // Check errors
    if (empty($errors)) :
        $name = trim($_POST['company_name']);
        $situation = (!empty($_POST['company_situation'])) ? $_POST['company_situation'] : $userData[0]->company_situation;
        $label = $_POST['company_label'];
        $street = $_POST['company_street'];
        $postcode = $_POST['company_postcode'];
        $city = $_POST['company_city'];
        $latitude = $_POST['company_latitude'];
        $longitude = $_POST['company_longitude'];

        // extract($_POST);
        //  $company_latitude

        if (empty($_POST['company_situation'])) :
            $data = ['company_name' => $name, 'user_id' => $userId];
            $sql = "UPDATE users SET company_name = :company_name WHERE user_id = :user_id";
        else :
            $data = [
                'user_id' => $userId,
                'company_name' => $name,
                'company_situation' => $situation,
                'company_label' => $label,
                'company_street' => $street,
                'company_postcode' => $postcode,
                'company_city' => $city,
                'company_latitude' => $latitude,
                'company_longitude' => $longitude
            ];

            $sql = "UPDATE users
                SET company_name = :company_name,
                    company_situation = :company_situation,
                    company_label = :company_label,
                    company_street = :company_street,
                    company_postcode = :company_postcode,
                    company_city = :company_city,
                    company_latitude = :company_latitude,
                    company_longitude = :company_longitude
                WHERE user_id = :user_id";
        endif;

        $request = $db->prepare($sql);
        $request->execute($data);
    endif;


    // Insert coocking diets
    if (!empty($_POST['coocking_diet'])) :
        $sql = "DELETE FROM users_coocking_diets
                WHERE id_users_ucd = " . $userId;
        $request = $db->prepare($sql);
        $request->execute();
        foreach ($_POST['coocking_diet'] as $value) :
            $data = ['id_users_ucd' => $userId, 'id_coocking_diets' => $value];
            $sql = "INSERT INTO users_coocking_diets (id_users_ucd, id_coocking_diets)
                    VALUES (:id_users_ucd, :id_coocking_diets)";
            $request = $db->prepare($sql);
            $request->execute($data);
        endforeach;
    endif;


    // Insert coocking types
    if (!empty($_POST['coocking_type'])) :
        $sql = "DELETE FROM users_coocking_types
                WHERE id_users_uct = " . $userId;
        $request = $db->prepare($sql);
        $request->execute();
        // debugP($_POST['coocking_type'], 'coocking_type');
        foreach ($_POST['coocking_type'] as $value) :
            $data = ['id_users_uct' => $userId, 'id_coocking_types' => $value];
            $sql = "INSERT INTO users_coocking_types (id_users_uct, id_coocking_types)
                    VALUES (:id_users_uct, :id_coocking_types)";
            $request = $db->prepare($sql);
            $request->execute($data);
        endforeach;
    endif;


    // Insert coocking origins
    if (!empty($_POST['coocking_origin'])) :
        $sql = "DELETE FROM users_coocking_origins
                WHERE id_users_uco = " . $userId;
        $request = $db->prepare($sql);
        $request->execute();
        // debugP($_POST['coocking_origin'], 'coocking_origin');
        foreach ($_POST['coocking_origin'] as $value) :
            $data = ['id_users_uco' => $userId, 'id_coocking_origins' => $value];
            $sql = "INSERT INTO users_coocking_origins (id_users_uco, id_coocking_origins)
                    VALUES (:id_users_uco, :id_coocking_origins)";
            $request = $db->prepare($sql);
            $request->execute($data);
        endforeach;
    endif;


    $_SESSION['flash'][] = [
        'message' => 'Les informations sur votre établissement sont bien enregistrées.',
        'status' => 'success'
    ];
    $userData = getAll($db, $userId);
endif;

?>
<?php require_once('header.php'); ?>

<section class="section-form">
    <h2> Votre compte utilisateur </h2>
    <span class="username"> Bienvenue <?= $_SESSION['auth']->username; ?>.</span>

    <h3>Ajouter / modifier votre établissement</h3>
    <div class="notice">
        <p>Les champs marqués d'un astérisque (*) sont obligatoires</p>
    </div>
    <?= flash() ?>
    <div class="form-container">
        <form action="" method="post">
            <div class="form-group" name="companyNameGroup">
                <label for="company-name">* Nom de l'établissement :</label>
                <div class="contain-input">
                    <input id="company-name" type="text" name="company_name" required value="<?php echo isset($userData[0]->company_name) ? $userData[0]->company_name : ""; ?>">
                </div>
                <?= !empty($errors['name']) ? '<div class="error-field">' . $errors['name'] . '</div>' : '' ?>
            </div>

            <div class="form-group" name="situationGroup">
                <label for="company-situation">* Adresse : <strong class="strong"><?php echo isset($userData[0]->company_label) ? $userData[0]->company_label : ""; ?></strong></label>
                <select id="company-situation" name="company_situation" value="<?= isset($userData[0]->company_label) ? $userData[0]->company_label : ""; ?>   "></select>
                <option></option>
                <?= !empty($errors['situation']) ? '<div class="error-field">' . $errors['situation'] . '</div>' : '' ?>
            </div>
            <input id="company-label" type="hidden" name="company_label" />
            <input id="company-street" type="hidden" name="company_street" />
            <input id="company-postcode" type="hidden" name="company_postcode" />
            <input id="company-city" type="hidden" name="company_city" />
            <input id="company-latitude" type="hidden" name="company_latitude" />
            <input id="company-longitude" type="hidden" name="company_longitude" />

            <div class="form-group" name="coockingDietsGroup">
                <label for="coocking-diet"> Types de régimes alimentaires :</label>
                <select id="coocking-diet" name="coocking_diet[]" multiple="multiple">
                    <?php foreach ($coockingDiets as $value) : ?>
                        <option <?php
                                    foreach ($userData as $diet) :
                                        echo (isset($diet->coocking_diet_name) && $value->coocking_diet_name === $diet->coocking_diet_name) ? ' selected' : '';
                                    endforeach;
                                    ?> value=" <?= $value->diet_id ?> "><?= $value->coocking_diet_name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" name="coockingTypesGroup">
                <label for="coocking-type"> Types des plats :</label>
                <select id="coocking-type" name="coocking_type[]" multiple="multiple">
                    <?php foreach ($coockingTypes as $value) : ?>
                        <option <?php
                                    foreach ($userData as $type) :
                                        // debugP($type);
                                        // die();
                                        echo (isset($type->coocking_type_name) && $value->coocking_type_name === $type->coocking_type_name) ? ' selected' : '';
                                    endforeach;
                                    ?> value=" <?= $value->type_id ?> "><?= $value->coocking_type_name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" name="coockingOriginsGroup">
                <label for="coocking-origin"> Origine des plats :</label>
                <select id="coocking-origin" name="coocking_origin[]" multiple="multiple">
                    <?php foreach ($coockingOrigins as $value) : ?>
                        <option <?php
                                    foreach ($userData as $origin) :
                                        echo (isset($origin->coocking_origin_name) && $value->coocking_origin_name === $origin->coocking_origin_name) ? ' selected' : '';
                                    endforeach;
                                    ?> value=" <?= $value->origin_id ?> "><?= $value->coocking_origin_name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" name="companyForm">Valider</button>

        </form>
    </div>
</section>
<section>
    <div>
        <p>Guidés par l'envie de répondre au mieux à vos attentes, n'hésitez pas à nous contacter à cette adresse : <span class="text-link"><a href="mailto:serviceclient@openfoodtruck.fr?subject=Demande%20de%20renseignements">serviceclient@openfoodtruck.fr</a></span>
            pour nous faire part de vos remarques, souhaits ou suggestions.</p>
    </div>
</section>

<?php require_once('footer.php'); ?>