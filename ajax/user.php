<?php
session_start();
include('../app/autoload.php');

if(isset($_POST['action']) && $_POST['action'] == 'new_rqst_email_sender') {
	// echo "<pre>";
	// print_r($_POST); die();
   $username = $_POST['user_email'];
   $rqst_msg =  $_POST['rqst_msg'];

    // Mail subject 
   // Add a recipient 
   $mail->addAddress($receiver_email);

   //$mail->addCC('cc@example.com'); 
   //$mail->addBCC('bcc@example.com'); 

   // Set email format to HTML 
   $mail->isHTML(true);
   $mail->Subject = 'New user request';
   $message = "
    <html>
    <head>
    <title>HTML email</title>
    </head>
    <body>
    
    <table>";
        $message .= "<tr style='background-color:#dcdbdb'>
    <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>User Email : </th>
    <td style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>" . $username . "</td>
    </tr>";

        $message .= "<tr style='background-color:#dcdbdb'>
    <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>Message : </th>
    <td style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>" . $rqst_msg . "</td>
    </tr>";

        $message .= "</table>
    </body>
    </html>
    ";
    $mail->Body    = $message;

   if ($mail->send()) {
    echo 1; 
   } else { 
    echo 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo; 
       
   }

}

if(isset($_POST['action']) && $_POST['action'] == 'user_login') {
    //print_r($_POST); die;
   $username = $_POST['username'];
   $password =  $_POST['password'];
   
   $res = $query->user_login($username, $password);
   if($res == 'user_error') {
      echo 'user_error';
   } 
   if($res == 'password_error') {
      echo 'password_error';
   } 

   if(!empty($res->id)) {
        if(!empty($_POST['myData'])) {
            $data = $_REQUEST['myData'];
            $data_cart = json_decode($data, true);
            
            foreach ($data_cart as $orderListRow) {
            if($orderListRow['quantity'] > 0) {
                $quantity = $orderListRow['quantity'];
            } else {
                $quantity = 1;
            }

            $checkOrder = $query->checkOrder($orderListRow['id'], $res->id, $orderListRow['msg'], $quantity); 
           
            }
        }
       $_SESSION['customer'] = $res->id;
       echo 'logged_in_seccuess';
   } 
   

}

?>