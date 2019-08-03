<?php

/**
 * Debug with print_r
 * 
 * @param $variable mixed
 * @param $title string
 */
function debugP($variable, $title = '___ Debug ___') {
    echo '<br> ' . $title . '<pre>' . print_r($variable, true) . '</pre>';
}


/**
 * Debug with var_dump
 * 
 * @param $variable mixed
 * @param $title string
 */
function debugV($variable, $title = '___ Debug ___') {
    echo '<br> ' . $title . '<pre>' . var_dump($variable) . '</pre>';
}

/**
 * 
 * Create a password hash
 * 
 * @param $pwd mixed;
 * 
 */
function h($pwd) {
    $pwdHash = password_hash($pwd, PASSWORD_DEFAULT);
    echo 'hash de ' .  $pwd . ': <br>' . $pwdHash ;
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
