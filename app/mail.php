<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
 
// Create an instance; Pass `true` to enable exceptions 
$mail = new PHPMailer; 
 
// Server settings 
//$mail->SMTPDebug = SMTP::DEBUG_SERVER;    //Enable verbose debug output 
$mail->isSMTP(); 
 


//Prod
$mail->Host = 'smtpout.secureserver.net'; // for example, smtp.mailtrap.io
$mail->SMTPAuth = true;
$mail->Username = 'office@aanda.net.in'; // your SMTP username
$mail->Password = 'Summer2023!'; // your SMTP password
$mail->SMTPSecure ='tls';// 'tls'; // preferable but optional
$mail->Port = 25; // set the appropriate port: 465, 2525, etc.

                    
// Sender info 
$mail->setFrom('office@aanda.net.in', 'A&A'); 
//$mail->addReplyTo('reply@example.com', 'SenderName'); 



/*---------------------------------For user email ------------------------------ */
// Create an instance; Pass `true` to enable exceptions 
$mail2 = new PHPMailer; 
 
// Server settings 
//$mail->SMTPDebug = SMTP::DEBUG_SERVER;    //Enable verbose debug output 
$mail2->isSMTP(); 



//Prod
$mail2->Host = 'smtpout.secureserver.net'; // for example, smtp.mailtrap.io
$mail2->SMTPAuth = true;
$mail2->Username = 'office@aanda.net.in'; // your SMTP username
$mail2->Password = 'Summer2023!'; // your SMTP password
$mail2->SMTPSecure ='tls';// 'tls'; // preferable but optional
$mail2->Port = 25; // set the appropriate port: 465, 2525, etc.

                    
// Sender info 
$mail2->setFrom('office@aanda.net.in', 'A&A'); 
//$mail->addReplyTo('reply@example.com', 'SenderName'); 
 

?>