<?php

require_once('config.php');

// Création et test de la connexion
try {
    // $db instance de la classe PDO : permet la connexion à la base de donnéess
    $db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
    // on modifie le comportemnet du mode fetch
    // $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ); // graf
    // format sous lequel les infos sont renvoyées par la base
    $db->exec('SET CHARACTER SET utf8');
    //Debug en local:  vaut true
    if (DEBUG) :
        // $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    endif;
}
// $e recupere les exceptions
catch (Exception $e) {

    if (DEBUG) :
        // get message affiche au format texte $e
        echo utf8_encode($e->getMessage());
    else :
        echo 'Erreur de connexion à la base de données';
    endif;
    // die pour arreter le code si erreur
    // le code ne sera pas interprété
    die();
}