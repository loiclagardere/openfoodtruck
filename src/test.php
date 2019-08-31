<?php
require_once('includes/db.php');
require_once('includes/functions.php');
?>

<?php require_once('template/header.php'); ?>

<section>
    <div class="form-container">
        <form action="" method="post">
            <div class="form-group" name="addressGroup">
                <label for="address">* Adresse </label>
                <select id="address" name="address"></select>
            </div>
            <div class="form-group" name="streetGroup">
                <label for="street">* Rue </label>
                <input id="street" type="text" name="street" />
            </div>
            <div class="form-group" name="postcodeGroup">
                <label for="postcode">* Code postal</label>
                <input id="postcode" type="text" name="postcode" />
            </div>
            <div class="form-group" name="cityGroup">
                <label for="city">* Ville</label>
                <input id="city" type="text" name="city" />
            </div>
            <div class="form-group" name="latGroup">
                <label for="lat">Latitude</label>
                <input id="lat" type="text" name="lat" />
            </div>
            <div class="form-group" name="lngGroup">
                <label for="lng">Longitude</label>
                <input id="lng" type="text" name="lng" />
            </div>
            <button type="submit" name="signinForm">Valider</button>
        </form>
    </div>
</section>

<?php require_once('template/footer.php'); ?>