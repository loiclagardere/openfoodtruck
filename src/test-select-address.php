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
            <input id="street" type="hidden" name="street" />
            <input id="postcode" type="hidden" name="postcode" />
            <input id="city" type="hidden" name="city" />
            <input id="latitude" type="hidden" name="latitude" />
            <input id="longitude" type="hidden" name="longitude" />

            <button type="submit" name="signinForm">Valider</button>
        </form>
    </div>
</section>

<?php require_once('template/footer.php'); ?>