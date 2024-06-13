<?php
session_start();
require_once('app/autoload.php');

$meta_title = "Contact Us : Avani & Amyra by A&A Accessories";
$meta_keyword = "";
$meta_description = "";

if (isset($_POST['contact_form'])) {
    $userIP = $_SERVER['REMOTE_ADDR'];
    $secretKey = $google_secret_key;
    $responseKey = $_POST['g-recaptcha-response'];


    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey&remoteip=$userIP";
    $response = file_get_contents($url);
    $response = json_decode($response);
    if ($response->success) {
    $theme = $_POST['theme'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Validate theme (allow only alphabetic characters and spaces)
    if (!preg_match("/^[a-zA-Z ]+$/", $theme)) {
        $themeError = "URLs and special characters such as <,>,=,?,.$,#, etc. are not allowed.";
    }


    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Email should be in a valid format.";
    }

    // Validate message (allow only alphanumeric characters)
    // if (!ctype_alnum($message)) {
    //     $messageError = "URLs and special characters such as <,>,=,?,.$,#, etc. are not allowed.";
    // }

    if (!preg_match("/^[a-zA-Z0-9 ]+$/", $message)) {
        $messageError = "URLs and special characters such as <,>,=,?,.$,#, etc. are not allowed.";
    }



    if (empty($themeError) && empty($emailError) && empty($messageError)) {

        // Check for offensive content
        if ($query->check_offensive_content($theme) || $query->check_offensive_content($email) || $query->check_offensive_content($message)) {
            // Offensive content found, handle accordingly (e.g., display error message)
            echo "<script> alert('Sorry, your message contains offensive content. Please revise and try again.'); </script>";
            
        } else {
            //echo "<script> alert('No offensive content found, proceed with sending email or saving to database.'); </script>";
            //die;
            $image = $_FILES["pic"]["tmp_name"];
            if (!empty($image)) {
                $imageName = date('dmYhis') . '-' . str_replace(' ', '-', $_FILES["pic"]["name"]);
                $res = move_uploaded_file($image, 'assets/uploads/form_attachment/' . $imageName);
                $link = BASE_URL . 'assets/uploads/form_attachment/' . $imageName;
            } else {
                $link = '';
                $imageName = '';
            }

            $data = array(strip_tags($_POST['theme']), strip_tags($_POST['email']), 'assets/uploads/form_attachment/', $imageName, strip_tags($_POST['message']), date('Y-m-d H:i:s'), $userIP);
            $query->contactEnquirySave($data);
            // Mail subject 
            // Add a recipient 
            $mail->addAddress($receiver_email);

            //$mail->addCC('cc@example.com'); 
            //$mail->addBCC('bcc@example.com'); 

            // Set email format to HTML 
            $mail->isHTML(true);
            $mail->Subject = 'Contact Us';

            $message = "
                <html>
                <head>
                <title>HTML email</title>
                </head>
                <body>
                
                <table>";


            $message .= "<tr style='background-color:#dcdbdb'>
                <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>Theme : </th>
                <td style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>" . strip_tags($_POST['theme']) . "</td>
                </tr>";

            $message .= "<tr style='background-color:#dcdbdb'>
                <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>Email : </th>
                <td style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>" . strip_tags($_POST['email']) . "</td>
                </tr>";

            $message .= "<tr style='background-color:#dcdbdb'>
                <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>Message : </th>
                <td style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>" . strip_tags($_POST['message']) . "</td>
                </tr>";

            if (!empty($image)) {
                $message .= "<tr style='background-color:#dcdbdb'>
                <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'> Attachment: </th>
                <td style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'><a href='" . $link . "' target='_blank'>View</a></td>
                </tr>";
            }




            $message .= "</table>
                </body>
                </html>
                ";

            $mail->Body    = $message;
            if ($mail->send()) {
                echo "<script> alert('Your message has been sent Successfully'); </script>";
            } else {
                echo "<script> alert('Error in Successfully'); </script>";
            }
            echo "<script> window.location.href='" . $_SERVER['REQUEST_URI'] . "'; </script>";

        }
        

    }
    } else {
        echo "<script> alert('Please check reCAPTCHA'); </script>";
        $capchaErr =  "The reCAPTCHA field is telling that you are a robot.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once('inc/meta-head.php') ?>
</head>


<body>
    <?php include_once('inc/header.php') ?>

    <section class="full-container py-5">
        <div class="heading py-7">
            <p class="font-lg font-mid-dark ">
                CONTACT US
            </p>
        </div>
        <div class="subheading mb-5">
            <p class="font-xxl2 headingFont bold">
                You can reach us
            </p>
        </div>
        <div class="address-content pb-5">
            <div class="address-content-item d-flex mb-4 pb-3">
                <div class="item-icon">
                    <img src="./assets/images/location-icon.svg" alt="location" />
                </div>
                <div class="address-content-text">
                    <p class="font-lg bold mid-dark-text mb-0">A&A Accessories (estd 1978)</p>
                    <p class="font-lg mid-dark-text mb-0"><?= $contact_address; ?><br><?= $contact_address2; ?></p>
                </div>
            </div>
            <div class="address-content-item d-flex mb-4 pb-3">
                <div class="item-icon">
                    <img src="./assets/images/call-icon.svg" alt="call-icon" />
                </div>
                <div class="address-content-text">
                    <p class="font-lg mid-dark-text mb-0"><?= $contact_phone; ?><br><?= $contact_phone2; ?></p>
                </div>
            </div>
            <div class="address-content-item d-flex mb-4 pb-3">
                <div class="item-icon">
                    <img src="./assets/images/email-icon.svg" alt="email-icon" />
                </div>
                <div class="address-content-text">
                    <p class="font-lg mid-dark-text mb-0"> <?= $contact_email; ?></p>
                </div>
            </div>
        </div>

        <div class="contact-form">
            <div class="required_fields" style="color:red;"><?php if (!empty($msg)) {
                                                                echo $msg;
                                                            } ?></div>
                                                            <!-- id="contact_form" -->
            <form method="post" id="contact_form" autocomplete="off" class="modal-form row mb-0" novalidate="novalidate" enctype="multipart/form-data">
                <div class="form-group col-md-4 mb-5">
                    <label class="font-sm mb-2 dark-text regular">Theme *</label>
                    <div class="position-relative">
                        <input type="text" name="theme" class="form-control" placeholder="Customer Service" value="<?= isset($_POST['theme']) ? $_POST['theme'] : ''; ?>" name="theme" required />
                        <div class="required_fields" style="color:red; font-size: 14px;"><?php if (!empty($themeError)) {
                                                                                                echo $themeError;
                                                                                            } ?></div>
                    </div>
                </div>


                <div class="form-group col-md-4 mb-5">
                    <label class="font-sm mb-2 dark-text regular">Attactment</label>
                    <div class="input-upload form-control font-md mb-3 position-relative ">
                        <label for="file-upload" class="custom-file-upload font-xs">
                            Upload File
                        </label>
                        <input name='pic' id="file-upload" name='upload_cont_img' type="file" style="display:none;">
                        <a class="btn btn-animation btn-primary">
                            <span class="font-md regular">Select a File</span>
                        </a>
                    </div>
                </div>
                <div class="form-group col-md-4 mb-5">
                    <label class="font-sm mb-2 dark-text regular">Email Address *</label>
                    <div class="position-relative">
                        <input type="email" class="form-control" placeholder="Enter the email" value="<?= isset($_POST['email']) ? $_POST['email'] : ''; ?>" name="email" required />
                        <div class="required_fields" style="color:red; font-size: 14px;"><?php if (!empty($emailError)) {
                                                                                                echo $emailError;
                                                                                            } ?></div>
                    </div>
                </div>
                <div class="form-group col-md-12 mb-5">
                    <label class="font-sm mb-2 dark-text regular">Message *</label>
                    <div class="position-relative">
                        <textarea rows="3" class="form-control" placeholder="How can we help you?" name="message" required><?= isset($_POST['message']) ? $_POST['message'] : ''; ?></textarea>
                        <div class="required_fields" style="color:red; font-size: 14px;"><?php if (!empty($messageError)) {
                                                                                                echo $messageError;
                                                                                            } ?></div>
                    </div>
                </div>
                <div class="form-group col-md-12 mb-5">
                    <div class="form-check font-sm">
                        <input class="form-check-input mt-02" name="tandc" type="checkbox" <?= isset($_POST['tandc']) ? 'checked' : ''; ?> value="" id="defaultCheck1" required />
                        <label class="form-check-label font-sm" for="defaultCheck1">
                            I agree to use my information to provide the best possible communication between me and
                            Avani & Amary Accessories.
                        </label>
                    </div>
                </div>
                <?php if (!empty($google_site_key)) { ?>
                    <div class="form-group col-md-12 mb-5">
                        <div class="g-recaptcha" data-sitekey="<?= $google_site_key; ?>" data-callback="recaptchaCallback"></div>
                        <span id="reCaptchaError" class="fa fa-danger"><?php if (!empty($capchaErr) && isset($_POST['contact_form'])) {
                                                                            echo $capchaErr;
                                                                        } ?></span>
                    </div>
                <?php } ?>
                <div class="form-group col-md-12 mb-5">
                    <button name="contact_form" type="submit" name="contact_form" class="btn btn-animation btn-primary">
                        <span class="font-md regular px-5">Submit</span>
                    </button>
                </div>

            </form>
        </div>
    </section>
    <?php

    ?>


    <?php include_once('inc/footer.php') ?>
    <?php include_once('inc/footer-script.php') ?>
    <script>
        function recaptchaCallback() {
            $('#hiddenRecaptcha').valid();
        };
    </script>

</body>


</html>