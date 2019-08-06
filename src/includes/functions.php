<?php

/**
 * Debug with print_r
 * 
 * @param $variable mixed
 * @param $title string
 */
function debugP($variable, $die = '')
{
    echo $die;
    echo '<br> ' . '<pre>' . print_r($variable, true) . '</pre>';
    if (!empty($die)) {
        echo  die();
    }
}


/**
 * Debug with var_dump
 * 
 * @param $variable mixed
 * @param $title string
 */
function debugV($variable, $die = '')
{
    echo $die;
    echo '<br> ' . '<pre>' . var_dump($variable) . '</pre>';
    if (!empty($die)) {
        echo  die();
    }
}

/**
 * 
 * Create a password hash
 * 
 * @param $pwd mixed;
 * 
 */
function h($pwd)
{
    $pwdHash = password_hash($pwd, PASSWORD_DEFAULT);
    echo 'hash de ' .  $pwd . ': <br>' . $pwdHash;
}


/**
 * Validates whether the value is a valid e-mail address.  
 * 
 * @param   mixed $email
 * @return bool
 */
function emailFilterVar($email)
{
    $emailFilterVar = filter_var($email, FILTER_VALIDATE_EMAIL);
    if ($emailFilterVar != false) :
        return true;
    else :
        return false;
    endif;
}


/**
 * Perform a regular expression match
 * 
 * @param mixed
 * @return bool
 */
function passwordPregMatch($password)
{
    $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/';
    $passwordPregMatch = preg_match($regex, $password);
    if ($passwordPregMatch === 1) :
        return true;
    else :
        return false;
    endif;
}


/**
 * Generate a random string
 * 
 * @param number $lenght
 * @return string $string
 */
function stringRandom($length)
{
    $string = '_0123456789azertyuiopqsdfghjklmxcvbnAZERTYUIOPQSDFGHJKLMWXXCVBN';
    $stringRandom = substr(str_shuffle(str_repeat($string, $length)), 0, $length);
    return $stringRandom;
}


/**
 * Display a global message
 * 
 * @param string $content
 */
function flash()
{
    if (isset($_SESSION['flash'])) :
        foreach ($_SESSION['flash'] as $value) :
            $content = '<div class="message ' .  $value['status'] . '">';
            $content .= $value['message'];
            $content .= '</div>';
        endforeach;
        unset($_SESSION['flash']);
        return $content;
    endif;
}

/**
 * Check user authentification
 * 
 * @return bool
 */
function authentificated()
{
    if (session_status() == PHP_SESSION_NONE) :
        session_start();
    endif;
    if (!isset($_SESSION['auth'])) :
        $_SESSION['flash'][] = [
            'message' => "Veuillez vous identifier pour avoir accés à votre compte.",
            'status' => 'error'
        ];
        header('Location: signin.php');
        die();
    endif;
}
