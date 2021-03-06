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
 * Perform a regular expression match for username
 * 
 * @param mixed
 * @return bool
 */
function usernamePregMatch($string)
{
    $regex = '/^[a-zA-Z0-9_]+$/';
    $stringPregMatch = preg_match($regex, $string);
    if ($stringPregMatch === 1) :
        return true;
    else :
        return false;
    endif;
}


/**
 * Perform a regular expression match for username
 * 
 * @param mixed
 * @return bool
 */
function passwordPregMatch($string)
{
    $regex = '/^(?=.{8,})/';
    $stringPregMatch = preg_match($regex, $string);
    if ($stringPregMatch === 1) :
        return true;
    else :
        return false;
    endif;
}


/**
 * Perform a regular expression match for Siret number
 * 
 * @param mixed
 * @return bool
 */
function SiretPregMatch($string)
{
    $regex = '/^[0-9]{14}$/';
    $siretPregMatch = preg_match($regex, $string);
    if ($siretPregMatch === 1) :
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
 * FIeld content posted
 * 
 * @param string
 */
function valueField($field) {
	if (!empty($_POST[$field])) :
		return $_POST[$field];
	endif;
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
            $content = '<div class="message-flash ' .  $value['status'] . '">';
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

/**
 * 
 * Clean string
 * @param string
 * 
 * @return string
 */
function cleanerString($string) {
    return $string;
}

/**
 * 
 * removeAccent
 * @param string
 * 
 * @return string
 */
function removeAccent($string) {
	$string = str_replace(
		['à','á','â','ã','ä', 'ç', 'è','é','ê','ë', 'ì','í','î','ï', 'ñ', 'ò','ó','ô','õ','ö', 'ù','ú','û','ü', 'ý','ÿ', 'À','Á','Â','Ã','Ä', 'Ç', 'È','É','Ê','Ë', 'Ì','Í','Î','Ï', 'Ñ', 'Ò','Ó','Ô','Õ','Ö', 'Ù','Ú','Û','Ü', 'Ý'], 
		['a','a','a','a','a', 'c', 'e','e','e','e', 'i','i','i','i', 'n', 'o','o','o','o','o', 'u','u','u','u', 'y','y', 'A','A','A','A','A', 'C', 'E','E','E','E', 'I','I','I','I', 'N', 'O','O','O','O','O', 'U','U','U','U', 'Y'], 
		$string
	);
	return $string;
}

/**
 * 
 * replace characters
 * @param string
 * 
 * @return 
 */
function replaceCharacter($string) {
    $search = [" ", ".", "/"];
    $replace = ["+", "+", "+"];
    $stringReplace = str_replace( $search, $replace, $string);
    return $stringReplace;
}


/**
 *prepareString
 *
 *@param string
 *replace characters with accent and put in lower case
 *
 */
function prepareString($string) {
        $string = removeAccent($string);
        $string = mb_strtolower($string, 'utf8');
        $string = trim($string);
        return $string;
}