<?php
require_once('db.php');

$sql = "SELECT * FROM users";

$request = $db->prepare($sql);
$request->execute();
$user = $request->fetchAll();

$userRespons = json_encode($user);

print_r($userRespons);

?>