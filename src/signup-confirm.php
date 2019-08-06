<?php
session_start();
require_once('includes/db.php');
require_once('includes/functions.php'); // USE FOR DEBUG

// check data in URL
if (isset($_GET['id']) && isset($_GET['token'])) :

    $userId = $_GET['id'];
    $token = $_GET['token'];

    $data = ['id' => $userId];

    // Request to select user
    $sql = "SELECT *
            FROM users
            WHERE id = :id"; //  AND token_confirm = :token_confirm - REDONDANT avec requete sql /!\
    $request = $db->prepare($sql);
    $request->execute($data);
    $user = $request->fetch();
    // debugP($user, 'mauqe token');
    
    // Check user token valitdity
    if ($user && $user->token_confirm === $token) : // REDONDANT avec requete sql /!\
// debugP($user, '$user');
        // update the user table to prevent new connections by using the link in the mail
        // Request tu update user
        $sql = 'UPDATE users
                SET token_confirm = NULL, token_confirmed_at = NOW()
                WHERE id = :id';
        $request = $db->prepare($sql);
        $request->execute($data);

        $_SESSION['auth'] = $user; // connect user
        $_SESSION['flash'][] = [
            'message' => 'Vous êtes connecté.',
            'status' => 'success'
        ];
        header('Location: account.php');
        die();

    else :
        $_SESSION['flash'][] = [
            'message' => "Le lien utilisé pour valider votre compte n'est plus valide.",
            'status' => "error"
        ];
        header('Location: signin.php');
        die();
    endif;
else :
    header('Location: signin.php');
    die();
endif;
