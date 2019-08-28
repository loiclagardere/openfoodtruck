<?php
require_once('config-mail.php');
require_once('mail-inscription.php');

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// require_once('../../vendor/autoload.php'); // test page
require_once('../vendor/autoload.php');

function sendMail($username, $mailTo, $subject, $confirmationLink)
{

    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //     //Server settings
        //     $mail->SMTPDebug = 2;  // Enable verbose debug output /!\ 0 = off , forproduction use
        $mail->isSMTP();    // Set mailer to use SMTP
        $mail->Host       = EMAIL_HOST;  // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;   // Enable SMTP authentication
        $mail->Username   = EMAIL_USER;  // SMTP username
        $mail->Password   = EMAIL_PASSWORD;   // SMTP password
        $mail->SMTPSecure = 'ssl';  // Enable TLS encryption, `ssl` also accepted default 'tls'
        $mail->Port       = 465;    // TCP port to connect to

        //     //Recipients
        $mail->setFrom('noobdoggydogg@gmail.com', 'Openfoodtruck');
        $mail->addAddress($mailTo, 'Openfoodtruck - Inscription');     // Add a recipient

        //     // Content
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);    // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = messageInscription($username, $mailTo, $confirmationLink);

        //     $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        // $mail->setLanguage('fr', 'phpmailer.lang-fr.php');

        $result = $mail->send();

        if ($result === 1) :
            return true;
        else :
            return false;
        endif;
        
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
