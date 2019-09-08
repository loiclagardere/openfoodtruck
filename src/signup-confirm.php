<?php
session_start();
require_once('includes/bootstrap.php');

// check data in URL
if (isset($_GET['user_id']) && isset($_GET['token'])) :
    $userId = $_GET['user_id'];
    $token = $_GET['token'];
    $data = ['user_id' => $userId];
    // Request to select user
    $sql = "SELECT *
            FROM users
            WHERE user_id = :user_id"; //  AND token_confirm = :token_confirm - as php check
    $request = $db->prepare($sql);
    $request->execute($data);
    $user = $request->fetch();
    // debugP($user, 'token');


    // Check user token valitdity
    if ($user && $user->token_confirm === $token) : // as sql query
        // update the user table to prevent new connections by using the link in the mail
        // Request tu update user
        $sql = 'UPDATE users
                SET token_confirm = NULL, token_confirmed_at = NOW()
                WHERE user_id = :user_id';
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
