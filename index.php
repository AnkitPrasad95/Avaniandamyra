<?php
session_start();
require_once('app/autoload.php');
$meta_title = "Avani & Amyra by A&A Accessories";
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
                // echo "<pre>";
                // print_r($mail);
                // echo "</pre>"; 
                // die;
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
    <section class="home-top-banner">
        <a href="<?= BASE_URL . 'product-category/bags' ?>" class="banner-image">
            <img src="./assets/images/banner-new.png" alt="bags-banner" />
        </a>
    </section>
    <section class="full-container py-4">
        <div class="heading py-7 px-3">
            <p class="font-lg font-mid-dark py-4 mb-0 ">
                OUR BRAND
            </p>
        </div>
        <div class="brand-content">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="brand-content-item">
                        <div class="brand-content-data">
                            <div class="image">
                                <img src="./assets/images/avani.png" alt="avani" />
                            </div>
                            <p class="name headingFont font-xxl2 bold">Avani</p>
                            <div class="discription">
                                <p>Avani is 26. She is smart and fiercely independent. She is stylish and chic. Classic
                                    yet daring. She is fun but determined. A dreamer of big dreams, she flies high but
                                    remains grounded.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="brand-content-item">
                        <div class="brand-content-data">
                            <div class="image">
                                <img src="./assets/images/amyra.png" alt="amyra" />
                            </div>
                            <p class="name headingFont font-xxl2 bold">Amyra</p>
                            <div class="discription">
                                <p>Amyra is 5 and born in the world of environmental awareness. She is natural and raw,
                                    preparing to understand her surroundings and the world that she has to nurture. She
                                    is an adventurer and dares to overcome challenges.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="brand-content-item">
                        <div class="brand-content-data">
                            <div class="image">
                                <img src="./assets/images/aa.png" alt="aa" />
                            </div>
                            <p class="name headingFont font-xxl2 bold">A & A</p>
                            <div class="discription">
                                <p>Avani & Amyra is an amalgamation of the two - natural, raw, chic and daring.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="full-container py-4 white">
        <div class="heading py-7 about-heading">
            <p class="font-lg font-mid-dark ">
                OUR ETHOS
            </p>
            <div class="about-vector">
                <img src="./assets/images/about-40-years.svg" alt="40 years" />
            </div>
        </div>
        <div class="about-content">
            <div class="row d-flex align-items-center">
                <div class="col-md-8 mb-4">
                    <p class="font-lg mb-4">A free spirited assemblage of resort wear with environment issues is at the
                        heart of this story. We are conscious of our eco-friendly responsibility and keep pace with the
                        needs of the planet by weaving these aspirations into our collections.
                    </p>
                    <p class="font-lg mb-4">
                        The celebration of human endeavor, skills and workmanship thrives in our community. Attention to
                        every detail and the integrity of products is paramount. This is a labour of love, slow fashion
                        striving to be as ethical as possible.
                    </p>
                    <p class="font-lg mb-4">
                        Providing employment to scores of women across the country, empowering them and reducing the gap
                        between those considered to be backward and the west has always been a great motivator.
                    </p>
                </div>
                <div class="col-md-4">
                    <img src="./assets/images/about1.png" class="w-100" />
                </div>
            </div>
            <div class="row d-flex align-items-center">
                <div class="col-md-8 mb-4 order-md-last ">
                    <p class="font-lg mb-4"> Centuries old art and craft from rural India is brought to the forefront in
                        our collections. It is displayed in the world stage thus ensuring that the rural artisanal and
                        artist thrive
                    </p>
                    <p class="font-lg mb-4">
                        We have a big in-house facility spanning over 20000 sq feet area. Atleast 100 machines are
                        deployed to manufacture 2000 units of bags and 2000 units of scarves daily. We have the ability
                        to tap into the interiors of the country for their skill in crochet
                        and macramé creating beautiful artistic handmade accessories.
                    </p>
                </div>
                <div class="col-md-4  mb-4">
                    <img src="./assets/images/about2.png" class="w-100" />
                </div>
            </div>

            <div class="row d-flex align-items-center">
                <div class="col-md-8 mb-4 ">
                    <p class="font-lg mb-4"> Our experience and goodwill enables us to dive into every corner of the
                        country and source materials from deep southern India to the northern most parts and from desert
                        lands of western India to the hilly terrains of the north east.
                    </p>
                    <p class="font-lg mb-4">
                        We are proud to have a strict quality control mechanism in place which enables us to have our
                        partners work with us for long periods of time.
                    </p>
                </div>
                <div class="col-md-4  mb-4">
                    <img src="./assets/images/about3.png" class="w-100" />
                </div>
            </div>
            <div class="row d-flex align-items-center mt-4 pt-3">
                <div class="col-md-4  mb-4 d-flex justify-content-center">
                    <div class="smeta-logo">
                        <img src="./assets/images/smeta-logo.png" alt="smeta" />
                    </div>
                </div>
                <div class="col-md-8 mb-4 ">
                    <p class="font-lg mb-3 bold">Audit Enabled
                    </p>
                    <p class="font-lg mb-4">A&A Accessories is a SMETA audit complaint firm which ensures that all
                        Labour Standards, Health & Safety, Environment and Business Ethics are practiced.</p>
                </div>
            </div>


        </div>

    </section>

    <section class="full-container py-7">
        <div class="heading py-7 px-3 d-flex justify-content-between">
            <p class="font-lg font-mid-dark ">
                PRODUCTS
            </p>
            <div class="product-slider-btns">
                <a id="productPrev" class="btn-slider prev">
                    <img src="./assets/images/icon-arrow-left.svg" alt="prev" />
                </a>
                <a id="productNext" class="btn-slider next">
                    <img class="rotate-180" src="./assets/images/icon-arrow-left.svg" alt="next" />
                </a>
            </div>

        </div>
        <div class="products">
            <?php if (!empty($categories)) { ?>
                <div class="product-slider" id="products">
                    <?php foreach ($categories as $key => $categoryRow) { ?>
                        <div class="product-item p-4">
                            <div class="product-card">
                                <!-- <a class="wishlist-icon">
                            <svg width="34" height="31" viewBox="0 0 34 31" fill="#fff">
                                <path d="M27.4644 0.265504C22.6752 -0.974341 18.5546 2.42916 16.7677 4.26461C14.9687 2.44131 10.8359 -0.974341 6.04672 0.27766C-1.64761 2.28329 -0.0309528 9.66159 0.540348 12.1291C2.97142 22.9474 15.6373 29.9853 16.1843 30.2771C16.3635 30.3738 16.5641 30.4239 16.7677 30.4229C16.9714 30.4239 17.172 30.3738 17.3512 30.2771C17.886 29.9853 30.5762 22.9838 32.9951 12.044C33.5786 9.3577 35.1588 2.25898 27.4644 0.265504Z" />
                            </svg>
                        </a> -->
                                <div class="product-image">
                                    <?php if (!empty($categoryRow->thumbnail_image)) { ?>
                                        <img src="<?= BASE_URL . $categoryRow->file_path . $categoryRow->thumbnail_image; ?>" alt="<?= $categoryRow->name; ?>" />
                                    <?php } else { ?>
                                        <img src="<?= BASE_URL; ?>assets/uploads/placeholder.jpg" alt="<?= $categoryRow->name; ?>" />
                                    <?php } ?>

                                </div>
                                <div class="product-details text-center">
                                    <h4 class="product-heading font-lg caps mb-1 mid-dark-text">
                                        <a href="<?= BASE_URL . 'product-category/' . $categoryRow->slug; ?>" class="catUrl"><?= $categoryRow->name; ?></a>
                                    </h4>
                                    <!-- <p class="font-lg mb-0  mid-dark-text">
                                Lorem ipsum dolor sit amet
                            </p> -->
                                </div>
                                <!-- <div class="card-btns">
                            <a class="btn btn-outline-primary btn-animation">
                                <span class="font-lg"> View Detail</span>
                            </a>
                            <a class="btn btn-primary btn-animation ">
                                <span class="font-lg"> Add to Order</span>
                            </a>
                        </div> -->
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>

    </section>


    <section class="full-container py-4 primary-light">
        <div class="vido-content">
            <div class="row d-flex align-items-center">
                <div class="col-lg-4">
                    <div class="video-text-content mb-4">
                        <p class="font-xxl2 mb-1 headingFont bold dark-text">The Difference</p>
                        <p class="font-xxl2 mb-5 headingFont bold dark-text">We Make </p>
                        <p class="font-lg mb-2">From the beginning, we've worked with local craftspeople in villages and
                            small communities to give them sustainable independence. We’ve been at the forefront of
                            driving sustainable improvements in working conditions and livelihoods
                            wherever we do business. We have more than 600 designers and a workforce.

                        </p>
                    </div>
                </div>
                <div class=" col-lg-8">
                    <iframe id="video" class="embed-responsive-item" width="100%" src="https://www.youtube.com/embed/AcAYGZK2ccI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </section>
    <section class="full-container py-4 brands">
        <div class="about-brands">
            <div class="row">
                <div class="col-lg-6">
                    <p class="font-xxl2 mb-2 headingFont white-text">Fashion Partners</p>
                    <p class="font-xl mb-5 headingFont white-text">Leading International Brands
                    </p>
                    <div class="row py-6">
                        <div class="col-sm-6">
                            <div class="partner-item">
                                <img src="./assets/images/Valentino.png" alt="Valentino" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="partner-item">
                                <img src="./assets/images/dorothy-Perkins-logo.png" alt="dorothy-Perkins" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="partner-item">
                                <img src="./assets/images/River-Island.png" alt="River-Island" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="partner-item">
                                <img src="./assets/images/lee-cooper-logo.png" alt="lee-cooper" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="partner-item">
                                <img src="./assets/images/rb.png" alt="rb" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="partner-item">
                                <img src="./assets/images/liujo.png" alt="liujo" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <section class="full-container py-4 brands-sec2">
        <div class="about-brands-sec2">
            <div class="row">
                <div class="col-lg-6">
                    <p class="font-xl mb-5 headingFont mid-dark-text">Exhibition Participation
                    </p>
                    <div class="row py-6">
                        <div class="col-sm-6">
                            <div class="partner-item primary-light">
                                <img src="./assets/images/logo-messe-frankfurt.png" alt="messe-frankfurt" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="partner-item primary-light">
                                <img src="./assets/images/logo-FM.png" alt="FM" />
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="partner-item primary-light multi-partners">
                                <img src="./assets/images/whosnext.png" alt="whosnext" />
                                <img src="./assets/images/logo-impact.png" alt="impact" />
                                <img src="./assets/images/traffic-logo.png" alt="traffic" />
                                <img src="./assets/images/logo-bijorhca.png" alt="bijorhca" />
                                <img src="./assets/images/logo-riviera-paris.png" alt="riviera-paris" />
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-6 d-lg-block d-none ">
                    <div class="about-brands-sec2-image">
                        <div class="brands-sec2-image">
                            <img src="./assets/images/about-brand.png" alt="about-brand" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="full-container py-7 white ">
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
        <div class="contact-form pb-4">

            <div class="required_fields" style="color:red; font-size: 14px;"><?php if (!empty($msg)) {
                                                                                    echo $msg;
                                                                                } ?></div>
            <!--id="contact_form"-->
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
    <?php include_once('inc/footer.php') ?>
    <?php include_once('inc/footer-script.php') ?>
</body>

</html>