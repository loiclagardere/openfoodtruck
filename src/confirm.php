<?php
session_start(); // use to connect user
require_once('includes/db.php');
require_once('includes/functions.php'); // USE FOR DEBUG

// Check token valitdity
$user_id = $_GET['id'];
$token = $_GET['token'];

$data = ['id' => $user_id];

$sql = 'SELECT * FROM users WHERE id = :id';
$request = $db->prepare($sql);
$request->execute($data);
$user = $request->fetch();


if ($user && $user->token_confirm === $token) :
    // update the user table to prevent new connections by using the link in the mail
    $sql = 'UPDATE users SET token_confirm = NULL, token_confirmed_at = NOW() WHERE id = :id';
    $request = $db->prepare($sql);
    $request->execute($data);

    $_SESSION['auth'] = $user;
    $_SESSION['flash'][] = [
        'label' => 'Vous êtes connecté.',
        'status' => 'success'
    ];
    header('Location: account.php');
    die();

else :
    $_SESSION['flash'][] = [
        'label' => "Le lien utilisé pour valider votre compte n'est plus valide.",
        'status' => "error"
    ];
    header('Location: signin.php');
    die();
endif;
