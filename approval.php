<?php
session_start();
require_once('app/autoload.php');
//print_r($_GET);
if(isset($_GET['id']) && isset($_GET['type'])) {
    $user_id = base64_decode($_GET['id']);
    $type = base64_decode($_GET['type']);
    $custDetails = $query->getCustApproveRejectByid($user_id);
    // echo "<pre>";
    // print_r($custDetails);
    // echo "</pre>";
    $userName = $custDetails->first_name.' '.$custDetails->last_name;
    if(!empty($custDetails) && $type == 'approve' && $custDetails->status == 0) {
        $data = $query->approveRejectUser($custDetails->id);

        $useremail = strip_tags($custDetails->email);

        $mail2->addAddress($useremail);

        //$mail->addCC('cc@example.com'); 
        //$mail->addBCC('bcc@example.com'); 

        // Set email format to HTML 
        $mail2->isHTML(true);
        $mail2->Subject = 'Login Credentials';

        $message2 = "
            <html>
            <head>
            <title>HTML email</title>
            </head>
            <body>
        
            <table>";

        $message2 .= "<tr style=''>
            <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 12px;padding-bottom: 12px;'>Hi ".$userName.",</th>
            </tr>";

        $message2 .= "<tr style='background-color:#dcdbdb'>
            <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>Your username : </th>
            <td style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>" . strip_tags($useremail) . "</td>
            </tr>";

        $message2 .= "<tr style='background-color:#dcdbdb'>
            <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>Password : </th>
            <td style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>" . $data['password'] . "</td>
            </tr>";
        $message2 .= "</table>
            </body>
            </html>
            ";


        $mail2->Body    = $message2;

        $mail2->send();
        
        if($mail2){
            echo "<script> alert('User has been approved successfully, Login credential will be sent on user registered email Id - " . $useremail . "'); </script>";
            echo "User has been approved successfully, Login credential will be sent on user registered email Id - " . $useremail;
        } else {
            echo "<script> alert('Something went wrong, Might be a network issue.'); </script>";
            echo "Something went wrong, Might be a network issue.";
        }
    } else if(!empty($custDetails) && $type == 'reject' && $custDetails->status == 0) {
        echo "<script> alert('User has been rejected successfully.'); </script>";
        echo "User has been rejected successfully.";
    } else if(!empty($custDetails) && $type == 'reject' && $custDetails->status == 1) {
        echo "<script> alert('User can not be rejected, It has been already approved.'); </script>";
        echo "User can not be rejected, It has been already approved.";
    } else {
        echo "<script> alert('User has been already approved.'); </script>";
        echo "User has been already approved.";
    }
   

}