<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once('../../vendor/autoload.php');



// require 'config-mail.php';
// require '../../vendor/phpmailer/phpmailer/src/Exception.php';
// require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
// require '../../vendor/phpmailer/phpmailer/src/SMTP.php';

function sendMail() {
// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = 2;  // Enable verbose debug output /!\ 0 = off , forproduction use
    $mail->isSMTP();    // Set mailer to use SMTP
    $mail->Host       = 'smtp.google.com';  // Specify main and backup SMTP servers //// gmail
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'noobdoggydogg@gmail.com';// /!\                    // SMTP username
    $mail->Password   = '/!\TheFacebook'; // /!\                           // SMTP password
    $mail->SMTPSecure = 'ssl';    // /!\ pour gmail SSL                              // Enable TLS encryption, `ssl` also accepted default 'tls'
    $mail->Port       = 465;      // Default 587                              // TCP port to connect to

    //Recipients
    $mail->setFrom('noobdoggydogg@gmail.com', 'Openfoodtruck');
    $mail->addAddress('noobdoggydogg@gmail.com', 'Joe User');     // Add a recipient
    $mail->addReplyTo('no-reply@openfoodtruck.fr', 'Information');
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');

    // Content
    // $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = '$subject';
    $mail->msgHTML = 'Hello html';
    $mail->Body    = '$message';
    $mail->AltBody = '$messag en texte';
    // $mail->setLanguage('fr');

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
}
sendMail();

