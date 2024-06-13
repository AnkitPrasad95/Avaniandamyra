<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'app/PHPMailer/src/Exception.php';
require 'app/PHPMailer/src/PHPMailer.php';
require 'app/PHPMailer/src/SMTP.php';
 
// Create an instance; Pass `true` to enable exceptions 
$mail = new PHPMailer; 
 
// Server settings 
//$mail->SMTPDebug = SMTP::DEBUG_SERVER;    //Enable verbose debug output 
$mail->isSMTP(); 
$mail->SMTPDebug  = 1; // enables SMTP debug information (for testing)
                   // 1 = errors and messages
                   // 2 = messages only                           // Set mailer to use SMTP 
// $mail->Host = 'smtpout.secureserver.net';           // Specify main and backup SMTP servers 
// $mail->SMTPAuth = true;                     // Enable SMTP authentication 
// $mail->Username = 'office@aanda.net.in';       // SMTP username 
// $mail->Password = 'Summer2023!';         // SMTP password 
// $mail->SMTPSecure = 'tls';                  // Enable TLS encryption, `ssl` also accepted 
// $mail->Port = 25;    

// // Sender info 
$mail->setFrom('office@aanda.net.in', 'Avani'); 

// fot Dev     
$mail->Host = 'smtp.gmail.com';           // Specify main and backup SMTP servers 
$mail->SMTPAuth = true;                     // Enable SMTP authentication 
$mail->Username = 'ankitakp1995@gmail.com';       // SMTP username 
$mail->Password = 'zkymlbfeobnshzkd';         // SMTP password 
$mail->SMTPSecure = 'tsl';                  // Enable TLS encryption, `ssl` also accepted 
$mail->Port = 587;   
                    
// Sender info 
//$mail->setFrom('ankitakp1995@gmail.com', 'A&A'); 
//$mail->addReplyTo('reply@example.com', 'SenderName'); 
 
// Add a recipient 
$mail->addAddress('ankit.prasad@interactive12.com'); 
 
//$mail->addCC('cc@example.com'); 
//$mail->addBCC('bcc@example.com'); 
 
// Set email format to HTML 
$mail->isHTML(true); 

 
 
// Mail subject 
$mail->Subject = 'Email from Localhost by CodexWorld'; 
 
// // Mail body content 
$bodyContent = '<h1>How to Send Email from Localhost using PHP by CodexWorld</h1>'; 
$bodyContent .= '<p>This HTML email is sent from the localhost server using PHP by <b>CodexWorld</b></p>'; 
$mail->Body    = $bodyContent;
 //var_dump($mail->send()); die;
// Send email 
if(!$mail->send()) { 
    echo 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo; 
} else { 
    echo 'Message has been sent.'; 
}