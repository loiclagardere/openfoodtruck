<?php
require_once('includes/functions.php');



// test regex
// Un regex est toujours entourée de caractéres spéciaux utilisés comme délimiteur, ici /.
//
// l'expression doit contenir un minimum de 8 caractéres et doit être composée d'au moins un lettre en minuscule,
// une letre en majuscule, un chiffre et un caractére
$regex ="/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/";
$pregMatch = preg_match($regex, "Pala3!");

if ($pregMatch === 1):
    echo 'regex est contenu dans phrase';
    debugV($pregMatch);
else:
    echo 'regex n\'est pas contenu dans la phrase';
    debugV($pregMatch);
endif;


