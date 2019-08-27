<?php
require_once('db.php');
require_once('functions.php');


function selectAllDiets($database)
{
    $sql = "SELECT *
            FROM users_coocking_diets
            JOIN users
            on users.id = id_users
            JOIN coocking_diets
            ON coocking_diets.id = id_coocking_diets";
    $request = $database->query($sql);
    $result = $request->fetchAll();
    return $result;
}


$select = selectAllDiets($db);

debugV($select);


// foreach($select as $value) :
// debugV($value->id);
// debugV($value->coocking_diet_name);
// endforeach;

function InsertPivotDiets($database, $usersId, $coockingDietsId)
{
    $sql = "INSERT INTO users_coocking_diets (id_users, id_coocking_diets)
            SELECT users.id, coocking_diets.id
            FROM users, coocking_diets
            WHERE users.id = $usersId AND coocking_diets.id= $coockingDietsId";
    $request = $database->query($sql);
    return $request;
}

// debugV(InsertPivotDiets($db, 125, 6));
