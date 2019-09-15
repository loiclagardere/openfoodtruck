<?php
require_once('db.php');
header('Content-Type: application/json');

$sql = "SELECT * FROM users";

$request = $db->prepare($sql);
$request->execute();
$user = $request->fetchAll();

$userRespons = json_encode($user, JSON_NUMERIC_CHECK);

echo $userRespons;

?>