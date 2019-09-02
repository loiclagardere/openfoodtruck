<?php
require_once('db.php');
require_once('functions.php');


/**
 * Select All from table
 * 
 * @param Object PDO $db
 */
function selectDatabase($database, $table, $params = "*")
{
    $sql = "SELECT $params FROM $table";
    $request = $database->query($sql);
    $result = $request->fetchAll();
    return $result;
}


// $coockingDiets = selectAllcoockingDiets($db);
// foreach ($coockingDiets as $value) :
//     debugV($value->id);
//     debugV($value->coocking_diet_name);
// endforeach;

// function getAll($db, $userId)
// {
//     $data = ['user_id' => $userId];
//     $sql = "SELECT * from users, coocking_diets cd, coocking_origins co, coocking_types ct
//             JOIN users_coocking_diets ucd
//             ON ucd.id_users = users.user_id
//             JOIN coocking_diets
//             ON coocking_diets.diet_id = id_coocking_diets
//             JOIN co
//             ON co.origin_id = id_coocking_origins
//             JOIN ct
//             ON ct.type_id = id_coocking_types
//             WHERE user_id = :user_id";
//     $request = $db->prepare($sql);
//     $request->execute($data);
//     $result = $request->fetchAll();
//     return $result;
// }

function getAll($db, $userId)
{
    $data = ['user_id' => $userId];

    $sql = "SELECT *
            FROM users u
            LEFT JOIN users_coocking_diets ucd
            ON ucd.id_users = u.user_id
            LEFT JOIN coocking_diets cd
            ON cd.diet_id = ucd.id_coocking_diets
            LEFT JOIN users_coocking_origins uco
            ON u.user_id = uco.id_users
            LEFT JOIN coocking_origins co
            ON uco.id_coocking_origins = co.origin_id
            LEFT JOIN users_coocking_types uct
            ON u.user_id = uct.id_users
            LEFT JOIN coocking_types ct
            ON uct.id_coocking_types = ct.type_id
            WHERE user_id = :user_id";
    $request = $db->prepare($sql);
    $request->execute($data);
    $result = $request->fetchAll();
    return $result;
}
////////////////////////    TEST    ////////////////////////////////

function selectAllUsersCoockingDiets($database)
{
    $sql = "SELECT *
            FROM users_coocking_diets
            JOIN users
            ON users.user_id = id_users
            JOIN coocking_diets
            ON coocking_diets.diet_id = id_coocking_diets";
    $request = $database->query($sql);
    $result = $request->fetchAll();
    return $result;
}

function InsertPivotDiets($database, $usersId, $coockingDietsId)
{
    $sql = "INSERT INTO users_coocking_diets (id_users, id_coocking_diets)
            SELECT users.user_id, coocking_diets.diet_id
            FROM users, coocking_diets
            WHERE users.user_id = $usersId AND coocking_diets.diet_id= $coockingDietsId";
    $request = $database->query($sql);
    return $request;
}

// debugV(InsertPivotDiets($db, 125, 6));
