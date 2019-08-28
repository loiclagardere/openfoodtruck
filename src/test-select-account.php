<?php
require_once('includes/db.php');
require_once('includes/functions.php');
require_once('includes/queries.php');

// $coockingDiets = selectAll($db, 'coocking-diets') ? selectAll($db, 'coocking-diets') : "";
// $coockingTypes = selectAll($db, 'coocking-types') ? selectAll($db, 'coocking-types') : "";
// $coockingOrigins = selectAll($db, 'coocking-origins') ? selectAll($db, 'coocking-origins') : "";
// $coockingDiets = selectAll($db) ? selectAll($db) : "";
// $coockingTypes = selectAll($db, 'coocking-types');
// $coockingOrigins = selectAll($db, 'coocking-origins');

// debugV($_POST);

// if (!empty($_POST)) :
    
// endif;



?>

<?php require_once('template/header.php'); ?>

<section>
    <div class="form-container">
        <form action="" method="post">
            <div class="form-group">
                <label for="company-name">* Nom de l'établissement</label>
                <input id="company-name" type="text" name="name" />
                <?= !empty($errors['company_name']) ? '<div class="error-field">' . $errors['company_name'] . '</div>' : '' ?>
            </div>
            <div class="form-group" name="addressGroup">
                <label for="address">* Adresse </label>
                <select id="address" name="address"></select>
            </div>
            <input id="street" type="hidden" name="street" />
            <input id="postcode" type="hidden" name="postcode" />
            <input id="city" type="hidden" name="city" />
            <input id="lat" type="hidden" name="lat" />
            <input id="lng" type="hidden" name="lng" />
            <div class="form-group" name="coockingDietsGroup">
                <label for="coocking-diets"> Types de régimes alimentaires </label>
                <select id="coocking-diets" name="diets-states[]" multiple="multiple">
                    <?php foreach ($coockingDiets as $value) : ?>
                    <option value=" <?= $value->id ?> "><?= $value->coocking_diet_name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" name="coockingTypesGroup">
                <label for="Types-types"> Types des plats </label>
                <select id="Types-types" name="types-states[]" multiple="multiple">
                    <?php foreach ($coockingTypes as $value) : ?>
                    <option value=" <?= $value->id ?> "><?= $value->coocking_type_name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" name="coockingOriginsGroup">
                <label for="coocking-origins"> Origine des plats </label>
                <select id="coocking-origins" name="origins-states[]" multiple="multiple">
                    <?php foreach ($coockingOrigins as $value) : ?>
                    <option value=" <?= $value->id ?> "><?= $value->coocking_origin_name ?></option>
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