<?php
session_start();
require_once('app/autoload.php');

use JasonGrimes\Paginator;

if (isset($_GET['CatUrl']) && isset($_GET['childUrl'])) {
    $Cat_title = ucwords(str_replace('-', ' ', $_GET['CatUrl'])) . ', ' . ucwords(str_replace('-', ' ', $_GET['childUrl']));
    $typeUrl = 2;
    $return_slug = 'product-category/' . $_GET['CatUrl'] . '/' . $_GET['childUrl'];
    $filterData = $query->getFilter($_GET['CatUrl'], $_GET['childUrl']);
} else if (isset($_GET['CatUrl'])) {
    $typeUrl = 1;
    $Cat_title = ucwords(str_replace('-', ' ', $_GET['CatUrl']));
    $return_slug = 'product-category/' . $_GET['CatUrl'];
    $filterData = $query->getFilter($_GET['CatUrl']);
} else {
    echo "<script>window.location.href='" . BASE_URL . "';</script>";
}
//echo $typeUrl;
$_SESSION['backtoshop'] = $return_slug;

if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];
} else {
    $filter = '';
}

if (isset($_GET['orderby'])) {
    $orderby = $_GET['orderby'];
} else {
    $orderby = 'desc';
}



$showRecordPerPage = 15;
if (isset($_GET['page']) && !empty($_GET['page'])) {
    $currentPage = $_GET['page'];
} else {
    $currentPage = 1;
}
$startFrom = ($currentPage - 1) * $showRecordPerPage;
//echo $typeUrl;
if (isset($_SESSION['customer']) && $customerDtails->role == 'visitor') {

    if ($typeUrl == 2) {
        $listProductCount = $query->visitorProductCount($_GET['CatUrl'], $_GET['childUrl'], $filter);
        $totalProduct = $listProductCount;
        $urlPattern = '?page=(:num)';
        $paginator = new Paginator($totalProduct, $showRecordPerPage, $currentPage, $urlPattern);
        $productListByUrl = $query->getProductCatSubcat($_GET['CatUrl'], $_GET['childUrl'], $orderby, $filter, $startFrom, $showRecordPerPage);
    } else if ($typeUrl == 1) {
        $listProductCount = $query->visitorProductCount($_GET['CatUrl'], $filter);
        $totalProduct = $listProductCount;
        $urlPattern = '?page=(:num)';
        $paginator = new Paginator($totalProduct, $showRecordPerPage, $currentPage, $urlPattern);
        $productListByUrl = $query->getProductCat($_GET['CatUrl'], $orderby, $filter, $startFrom, $showRecordPerPage);
    }
} else if (isset($_SESSION['customer']) && $customerDtails->role == 'customer') {

    $existProduct = $getCollections['user_collentions'];
    //echo $existProduct[0]->products;
    if ($typeUrl == 2) {
        $totalProduct = $query->getCustomerProductCountByCatSubcat($_GET['CatUrl'], $_GET['childUrl'], $existProduct[0]->products, $filter);

        $urlPattern = '?page=(:num)';
        $paginator = new Paginator($totalProduct, $showRecordPerPage, $currentPage, $urlPattern);
        $productListByUrl = $query->getProductCatSubcatByCustomer($_GET['CatUrl'], $_GET['childUrl'], $orderby, $existProduct[0]->products, $filter, $startFrom, $showRecordPerPage);
    } else if ($typeUrl == 1) {
        $totalProduct = $query->getCustomerProductCountByCat($_GET['CatUrl'], $existProduct[0]->products, $filter);
        $urlPattern = '?page=(:num)';
        $paginator = new Paginator($totalProduct, $showRecordPerPage, $currentPage, $urlPattern);
        $productListByUrl = $query->getProductCatByCustomer($_GET['CatUrl'], $orderby, $existProduct[0]->products, $filter, $startFrom, $showRecordPerPage);
        $Productcount = array();
    }
} else if (!isset($_SESSION['customer'])) {

    if ($typeUrl == 2) {
        $listProductCount = $query->visitorProductCount($_GET['CatUrl'], $_GET['childUrl'], $filter);
        $productListByUrl = $query->getProductCatSubcat($_GET['CatUrl'], $_GET['childUrl'], $orderby, $filter, $startFrom, $showRecordPerPage);
    } else if ($typeUrl == 1) {
        $listProductCount = $query->visitorProductCount($_GET['CatUrl'], $filter);
        $productListByUrl = $query->getProductCat($_GET['CatUrl'], $orderby, $filter, $startFrom, $showRecordPerPage);
    }
}

if (isset($_POST['view_more_product'])) {

    $organization_name = $_POST['organization_name'];
    $Person = $_POST['Person'];
    $Email = $_POST['Email'];
    $Phone = $_POST['Phone'];
    $Address = $_POST['Address'];
    $Buyer_type = $_POST['Buyer_type'];
    $message = $_POST['message'];

    // Validate theme (allow only alphabetic characters and spaces)
    if (!preg_match("/^[a-zA-Z0-9 ]+$/", $organization_name)) {
        $organization_nameError = "URLs and special characters such as <,>,=,?,.$,#, etc. are not allowed.";
    }

    // Validate theme (allow only alphabetic characters and spaces)
    if (!preg_match("/^[a-zA-Z ]+$/", $Person)) {
        $PersonError = "URLs and special characters such as <,>,=,?,.$,#, etc. are not allowed.";
    }

    // Validate email
    if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        $EmailError = "Email should be in a valid format.";
    }

    if (!preg_match("/^\d{7,15}$/", $Phone) && !empty($Phone)) {
        $PhoneError = "Please enter a valid number with 7 to 15 digits.";
    }

    // Validate theme (allow only alphabetic characters and spaces)
    if (!preg_match("/^[a-zA-Z0-9 ]+$/", $Address) && !empty($Address)) {
        $AddressError = "URLs and special characters such as <,>,=,?,.$,#, etc. are not allowed.";
    }

    if (!preg_match("/^[a-zA-Z0-9 ]+$/", $message) && !empty($message)) {
        $messageError = "URLs and special characters such as <,>,=,?,.$,#, etc. are not allowed.";
    }

    if (empty($organization_nameError) && empty($PersonError) && empty($EmailError) && empty($PhoneError) && empty($AddressError) && empty($messageError)) {

        if ($query->check_offensive_content($organization_name) || $query->check_offensive_content($Person) || $query->check_offensive_content($Email) || $query->check_offensive_content($Address) || $query->check_offensive_content($Buyer_type) || $query->check_offensive_content($message)) {
            // Offensive content found, handle accordingly (e.g., display error message)
            echo "<script> alert('Sorry, your message contains offensive content. Please revise and try again.'); </script>";
        } else {

            $useremail = strip_tags($Email);
            $checkExistingUser = $query->checkExistingUser($_POST);
            //print_r($checkExistingUser);
            if (isset($_POST['Email']) && $contact_email != '' && ($checkExistingUser['message'] != 'already_exist' || $checkExistingUser['user_status'] == 0)) {
                //if ($contact_email != '') {    
                $mail->addAddress($receiver_email);
                //echo "admin email generating ".$receiver_email;
                //$mail->addCC('cc@example.com'); 
                //$mail->addBCC('bcc@example.com'); 
                $user_id = base64_encode($checkExistingUser['user_id']);
                $approve = base64_encode('approve');
                $reject = base64_encode('reject');
                $approveUrl =  BASE_URL . "approval.php?id=" . $user_id . "&type=" . $approve;
                $rejectUrl =  BASE_URL . "approval.php?id=" . $user_id . "&type=" . $reject;
                // Set email format to HTML 
                $mail->isHTML(true);
                $mail->Subject = 'User request';
                $message = "
                <html>
                <head>
                <title>HTML email</title>
                </head>
                <body>
            
                <table>";

                $message .= "<tr style='background-color:#dcdbdb'>
                <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>Organization name : </th>
                <td style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>" . strip_tags($_POST['organization_name']) . "</td>
                </tr>";

                $message .= "<tr style='background-color:#dcdbdb'>
                <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>Person : </th>
                <td style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>" . strip_tags($_POST['Person']) . "</td>
                </tr>";

                $message .= "<tr style='background-color:#dcdbdb'>
                <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>Email : </th>
                <td style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>" . strip_tags($_POST['Email']) . "</td>
                </tr>";

                $message .= "<tr style='background-color:#dcdbdb'>
                <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>Phone : </th>
                <td style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>" . strip_tags($_POST['Phone']) . "</td>
                </tr>";



                $message .= "<tr style='background-color:#dcdbdb'>
                <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>Address : </th>
                <td style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>" . strip_tags($_POST['Address']) . "</td>
                </tr>";

                $message .= "<tr style='background-color:#dcdbdb'>
                <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>Buyer type : </th>
                <td style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>" . strip_tags($_POST['Buyer_type']) . "</td>
                </tr>";

                $message .= "<tr style='background-color:#dcdbdb'>
                <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>Message : </th>
                <td style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>" . strip_tags($_POST['message']) . "</td>
                </tr>";

                $message .= "<tr>
                <td colspan='2' style='text-align:center;padding-top: 20px;padding-bottom: 25px;'>
                    <a href='" . $approveUrl . "' style='background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; margin-right: 20px;'>Approve</a>
                    <a href='" . $rejectUrl . "' style='background-color: #f44336; color: white; padding: 10px 20px; text-decoration: none;'>Reject</a>
                </td>
                </tr>";
                $message .= "</table>
                </body>
                </html>
                ";

                $mail->Body    = $message;
                $mail->send();
                //die;    
                if ($mail) {
                    $useremail = strip_tags($_POST['Email']);

                    $mail2->addAddress($useremail);

                    //$mail->addCC('cc@example.com'); 
                    //$mail->addBCC('bcc@example.com'); 

                    // Set email format to HTML 
                    $mail2->isHTML(true);
                    $mail2->Subject = 'User request';

                    $message2 = "
                    <html>
                    <head>
                    <title>HTML email</title>
                    </head>
                    <body>
                
                    <table>";

                    $message2 .= "<tr style=''>
                    <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 12px;padding-bottom: 12px;'>Thank you for request with us. we will get back to you soon.</th>
                    </tr>";
                    $message2 .= "</table>
                    </body>
                    </html>
                    ";


                    $mail2->Body    = $message2;

                    $mail2->send();
                }
                //print_r($mail);

            } else if (isset($Email) && $checkExistingUser['message'] == 'already_exist' && $checkExistingUser['user_status'] == 1) {
                $useremail = strip_tags($_POST['Email']);

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
                <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 12px;padding-bottom: 12px;'>Hi " . strip_tags($_POST['Person']) . ",</th>
                </tr>";

                $message2 .= "<tr style='background-color:#dcdbdb'>
                <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>Your username : </th>
                <td style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>" . strip_tags($_POST['Email']) . "</td>
                </tr>";

                $message2 .= "<tr style='background-color:#dcdbdb'>
                <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>Password : </th>
                <td style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>" . $checkExistingUser['password'] . "</td>
                </tr>";
                $message2 .= "</table>
                </body>
                </html>
                ";


                $mail2->Body    = $message2;

                $mail2->send();
            }

            //this mail triggered to user with credential

            if ($checkExistingUser['message'] == 'already_exist' && $checkExistingUser['user_status'] == 1) {
                //echo "<script> alert('Your request has been submitted successfully.'); </script>";
                echo "<script> alert('Your request has been submitted successfully, Login credential will be sent on your registered email " . $_POST['Email'] . "'); </script>";
            } else if ($checkExistingUser['message'] == 'new_visitor') {
                echo "<script> alert('Your request has been submitted successfully, We will get back to you soon.'); </script>";
            } else if ($checkExistingUser['user_status'] == 0) {
                echo "<script> alert('Your request has been submitted successfully, We will get back to you soon.'); </script>";
            }
            echo "<script> window.location.href='" . $_SERVER['REQUEST_URI'] . "'; </script>";

        }
    }
}
$meta_title = $Cat_title;
$meta_keyword = "";
$meta_description = "";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once('inc/meta-head.php') ?>
</head>

<body>
    <?php include_once('inc/header.php') ?>
    <section class="full-container py-4">
        <div class="heading py-7 d-flex justify-content-between align-items-center">
            <p class="font-xxl2 headingFont bold font-mid-dark mb-0">
                <?= $Cat_title; ?>
            </p>
            <div class="product-heading-action">
                <a class="text-with-icon btn-transprent font-md me-lg-4 me-md-4 me-sm-0">
                    <img class="me-3 mb-2" src="<?= BASE_URL; ?>assets/images/i-sort.svg" alt="sort" />
                    <select name="orderby" onchange="orderBy()" id="orderby" class="orderby" aria-label="Shop order">
                        <option value="">Sort</option>
                        <option <?php if (isset($_GET['orderby']) && $_GET['orderby'] == 'asc') {
                                    echo 'selected';
                                }
                                ?> value="asc">Sort by asc</option>
                        <option <?php if (isset($_GET['orderby']) && $_GET['orderby'] == 'desc') {
                                    echo 'selected';
                                }
                                ?> value="desc">Sort by desc</option>
                    </select>
                </a>
                <a data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalFilter" class="text-with-icon btn-transprent font-md ms-lg-4 ms-md-4 ms-sm-0">
                    <img class="me-3" src="<?= BASE_URL; ?>assets/images/i-filter.svg" alt="filter" /> Filter
                </a>
            </div>
        </div>
        <div class="products">
            <div class="row category-products" id="example1">

                <?php
                $existPdoduct = 0;
                if (!empty($productListByUrl)) {

                    foreach ($productListByUrl as $key => $product) {
                        $existPdoduct = 1;
                        $productImages = $query->get_images($product->id);
                        $get_product_wishlist = $query->get_product_wishlist($product->id, $user_id);

                ?>
                        <div class="col-lg-4 col-md-6 p-4">
                            <div class="product-card">
                                <?php if (isset($_SESSION['customer'])) { ?>
                                    <button class="wishlist-icon add_to_wishlist <?php if (!empty($get_product_wishlist)) { ?>active<?php } ?>" id="wishlist_<?= $product->id; ?>" value="<?= $product->id; ?>">
                                        <svg width="34" height="31" viewBox="0 0 34 31" fill="#9F9987">
                                            <path d="M27.4644 0.265504C22.6752 -0.974341 18.5546 2.42916 16.7677 4.26461C14.9687 2.44131 10.8359 -0.974341 6.04672 0.27766C-1.64761 2.28329 -0.0309528 9.66159 0.540348 12.1291C2.97142 22.9474 15.6373 29.9853 16.1843 30.2771C16.3635 30.3738 16.5641 30.4239 16.7677 30.4229C16.9714 30.4239 17.172 30.3738 17.3512 30.2771C17.886 29.9853 30.5762 22.9838 32.9951 12.044C33.5786 9.3577 35.1588 2.25898 27.4644 0.265504Z"></path>
                                        </svg>
                                    </button>
                                <?php } else { ?>
                                    <a data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#loginSignup" class="wishlist-icon">
                                        <svg width="34" height="31" viewBox="0 0 34 31" fill="#9F9987">
                                            <path d="M27.4644 0.265504C22.6752 -0.974341 18.5546 2.42916 16.7677 4.26461C14.9687 2.44131 10.8359 -0.974341 6.04672 0.27766C-1.64761 2.28329 -0.0309528 9.66159 0.540348 12.1291C2.97142 22.9474 15.6373 29.9853 16.1843 30.2771C16.3635 30.3738 16.5641 30.4239 16.7677 30.4229C16.9714 30.4239 17.172 30.3738 17.3512 30.2771C17.886 29.9853 30.5762 22.9838 32.9951 12.044C33.5786 9.3577 35.1588 2.25898 27.4644 0.265504Z"></path>
                                        </svg>
                                    </a>
                                <?php } ?>
                                <div class="product-image">
                                    <?php if (!empty($product->thumbnail_image)) { ?>
                                        <img class="lazyload" src="<?php echo BASE_URL; ?>assets/images/lazyloader.gif" data-src="<<?= BASE_URL . $product->file_path . $product->thumbnail_image; ?>" data-srcset="<?= BASE_URL . $product->file_path . $product->thumbnail_image; ?>">
                                        <!-- <img src="<?= BASE_URL . $product->file_path . $product->thumbnail_image; ?>" alt="<?= $product->name; ?>" /> -->
                                    <?php } else { ?>
                                        <img src="<?= BASE_URL; ?>assets/uploads/placeholder.jpg" alt="<?= $product->name; ?>" />
                                    <?php } ?>

                                </div>
                                <div class="product-details text-center">
                                    <h4 class="product-heading font-lg caps mb-1 mid-dark-text">
                                        <?= $product->name; ?>
                                    </h4>
                                    <p class="font-lg mb-0  mid-dark-text">
                                        <?php if (!empty($product->short_description)) {
                                            echo strlen($product->short_description) > 30 ? substr($product->short_description, 0, 30) . "..." : $product->short_description;
                                        }  ?>

                                    </p>
                                </div>
                                <div class="card-btns">
                                    <!-- <button value="<?= $product->id; ?>" class="btn btn-outline-primary btn-animation myForm"> <span class="font-lg"> View Detail</span></button> -->
                                    <a data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#productDetails-<?= $key ?>" class="btn btn-outline-primary btn-animation">
                                        <span class="font-lg"> View Detail</span>
                                    </a>
                                    <?php //if (isset($_SESSION['customer'])) { 
                                    ?>
                                    <!-- <a href="javascript:void(0)" onclick="add_to_cart(<?= rand(1111, 9999); ?>, <?= $product->id; ?>)" id="prd_crt-<?= $product->id; ?>" class="btn btn-primary btn-animation">
                                            <span class="font-lg"> Add to Order</span>
                                        </a> -->
                                    <?php //} else { 
                                    ?>
                                    <button id="cartadd_<?= $product->id; ?>" class="btn btn-primary btn-animation" onclick="AddToCart(<?php echo $product->id; ?>,'','')"><span class="font-lg"> Add to Order</span></button>
                                    <?php //} 
                                    ?>



                                </div>
                            </div>
                        </div>

                        <!-- product details modal -->
                        <div class="modal fade " id="productDetails-<?= $key; ?>" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" data-dismiss="modal" data-toggle="modal">
                            <div class="modal-dialog modal-dialog-centered productDetails-modal">
                                <div class="modal-content" id="recievedData">
                                    <a data-bs-dismiss="modal" class="modal-close">
                                        <img src="<?= BASE_URL; ?>assets/images/i-cross.svg" alt="cross" />
                                    </a>
                                    <div class="product-image-slider">
                                        <div class="productSlider">
                                            <?php if (!empty($productImages)) {
                                                foreach ($productImages as $key => $productImagesRow) {
                                            ?>
                                                    <div class="product-slider-item <?php if ($key == 0) {
                                                                                        echo "active";
                                                                                    } else {
                                                                                        echo '';
                                                                                    } ?>">
                                                        <img src="<?= BASE_URL . $productImagesRow->file_path . $productImagesRow->photo_name; ?>" alt="<?= $product->name; ?>" />
                                                    </div>
                                                <?php }
                                            } else { ?>
                                                <div class="product-slider-item">
                                                    <img src="<?= BASE_URL . $product->file_path . $product->thumbnail_image; ?>" alt="<?= $product->name; ?>" />
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="modal-product-details">
                                        <div class="product-details-card">
                                            <?php if (isset($_SESSION['customer'])) { ?>
                                                <button class="wishlist-icon add_to_wishlist <?php if (!empty($get_product_wishlist)) { ?>active<?php } ?>" id="wishlist_<?= $product->id; ?>" value="<?= $product->id; ?>">
                                                    <svg width="34" height="31" viewBox="0 0 34 31" fill="#9F9987">
                                                        <path d="M27.4644 0.265504C22.6752 -0.974341 18.5546 2.42916 16.7677 4.26461C14.9687 2.44131 10.8359 -0.974341 6.04672 0.27766C-1.64761 2.28329 -0.0309528 9.66159 0.540348 12.1291C2.97142 22.9474 15.6373 29.9853 16.1843 30.2771C16.3635 30.3738 16.5641 30.4239 16.7677 30.4229C16.9714 30.4239 17.172 30.3738 17.3512 30.2771C17.886 29.9853 30.5762 22.9838 32.9951 12.044C33.5786 9.3577 35.1588 2.25898 27.4644 0.265504Z"></path>
                                                    </svg>
                                                </button>
                                            <?php } else { ?>
                                                <a data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#loginSignup" class="wishlist-icon">
                                                    <svg width="34" height="31" viewBox="0 0 34 31" fill="#9F9987">
                                                        <path d="M27.4644 0.265504C22.6752 -0.974341 18.5546 2.42916 16.7677 4.26461C14.9687 2.44131 10.8359 -0.974341 6.04672 0.27766C-1.64761 2.28329 -0.0309528 9.66159 0.540348 12.1291C2.97142 22.9474 15.6373 29.9853 16.1843 30.2771C16.3635 30.3738 16.5641 30.4239 16.7677 30.4229C16.9714 30.4239 17.172 30.3738 17.3512 30.2771C17.886 29.9853 30.5762 22.9838 32.9951 12.044C33.5786 9.3577 35.1588 2.25898 27.4644 0.265504Z"></path>
                                                    </svg>
                                                </a>
                                            <?php } ?>
                                            <div class="heading">
                                                <h4 class="font-lg dark-text mb-2"><?= $Cat_title; ?></h4>
                                                <h3 class="headingFont bold font-xxl2 dark-text mb-4"><?= $product->name; ?></h3>
                                                <?php
                                                if (!empty($product->tags)) { ?>
                                                    <div class="tags">
                                                        <?php
                                                        foreach (json_decode($product->tags) as $key => $details) { ?>
                                                            <div class="tag-item font-sm"><?= $details->value; ?></div>
                                                        <?php } ?>


                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <div class="product-discription-form form-active">
                                                <!-- <div class="product-discription">
                                                    <p class="discription-text mid-dark-text font-lg  mb-3 custom_scrollbar">
                                                       
                                                        <?= $product->short_description; ?></br>
                                                        <?= $product->remarks ?>
                                                    </p>
                                                   

                                                    <div class="card-btns">
                                                        <a class="customization btn btn-outline-primary btn-animation">
                                                            <span class="font-lg">Customization</span>
                                                        </a>
                                                        <?php if (isset($_SESSION['customer'])) { ?>
                                                            <a href="javascript:void(0)" onclick="add_to_cart(<?= rand(1111, 9999); ?>, <?= $product->id; ?>)" class="btn btn-primary btn-animation ">
                                                                <span class="font-lg">Add to Order</span>
                                                            </a>
                                                        <?php } else { ?>
                                                            <a data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#loginSignup" class="btn btn-primary btn-animation">
                                                                Add to Order
                                                            </a>
                                                        <?php } ?>

                                                    </div>
                                                </div> -->
                                                <!-- Order with comment start -->
                                                <div class="product-form ">
                                                    <form method="post" name="productDetails" autocomplete="off" novalidate="novalidate">
                                                        <div class="row">
                                                            <div class="col-12 mb-5">
                                                                <div class="form-group">
                                                                    <label class="font-sm mb-2" for="details">Message</label>
                                                                    <textarea rows="3" class="form-control font-md" placeholder="How can we help you?" name="details" id="details-<?= $product->id; ?>"></textarea>
                                                                    <span id="comment_msg_<?= $product->id; ?>"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-4">
                                                                <div class="form-group">
                                                                    <label class="font-sm mb-2" for="Quantity">Quantity</label>
                                                                    <input type="number" onKeyDown="if(this.value.length==6) return false;" class="form-control font-md" name="quantity" id="quantity-<?= $product->id; ?>" />
                                                                    <span id="qty_msg_<?= $product->id; ?>"></span>
                                                                    <input class="form-control" type="hidden" id="order_id" name="product_id" value="<?= $product->order_id; ?>" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-btns">
                                                            <?php //if (isset($_SESSION['customer'])) { 
                                                            ?>
                                                            <!-- <a href="javascript:void(0)" onclick="add_comment_order(<?= rand(1111, 9999); ?>, <?= $product->id; ?>)" class="btn btn-primary btn-animation w-100 ">
                                                                    <span class="font-lg">Add to Order</span>
                                                                </a> -->
                                                            <?php //} else { 
                                                            ?>
                                                            <a href="javascript:void(0)" id="cartadd_<?= $product->id; ?>" onclick="add_comment_order(<?= $product->id; ?>)" class="btn btn-primary btn-animation w-100 ">
                                                                <span class="font-lg">Add to Order</span>
                                                            </a>
                                                            <?php //} 
                                                            ?>
                                                            <!-- <button id="cartadd_<?= $product->id; ?>" class="btn btn-primary btn-animation w-100" onclick="add_comment_order(<?php echo $product->id; ?>)"><span class="font-lg"> Add to Order</span></button> -->
                                                        </div>
                                                    </form>
                                                </div>
                                                <!-- Order with comment end -->
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- product details modal end -->


                <?php }
                } //}  
                ?>
                <?php if ($existPdoduct == 0) { ?>
                    <p class="woocommerce-info">No products were found matching your selection.</p>
                <?php } ?>

                <div class="pagination-content">
                    <nav aria-label="navigation">
                        <?php if (isset($_SESSION['customer'])) { ?>
                            <ul class="pagination mb-0 pt-3 pb-3">

                                <?php if ($paginator->getPrevUrl()) : ?>
                                    <li><a class="page-link btn btn-primary btn-animation px-3" href="<?php echo $paginator->getPrevUrl();
                                                                                                        if (isset($_GET['filter'])) {
                                                                                                            echo '&filter=' . $filter;
                                                                                                        }
                                                                                                        if (isset($_GET['orderby'])) echo '&orderby=' . $orderby;  ?>">
                                            <img class=" icon-14 " src="<?= BASE_URL; ?>assets/images/b-arrow-white.svg"></a></li>
                                <?php endif; ?>
                                <?php foreach ($paginator->getPages() as $page) : ?>
                                    <?php if ($page['url']) : ?>
                                        <li <?php echo $page['isCurrent'] ? 'class="active page-item"' : ''; ?> class="page-item">
                                            <a class="page-link " href="<?php echo $page['url'];
                                                                        if (isset($_GET['filter'])) {
                                                                            echo '&filter=' . $filter;
                                                                        }
                                                                        if (isset($_GET['orderby'])) {
                                                                            echo '&orderby=' . $orderby;
                                                                        }
                                                                        ?>">
                                                <?php echo $page['num']; ?></a>
                                        </li>
                                    <?php else : ?>
                                        <li class="disabled"><span><?php echo $page['num'];
                                                                    if (isset($_GET['filter'])) {
                                                                        echo '&filter=' . $filter;
                                                                    }
                                                                    if (!($orderby)) echo '&orderby=' . $orderby;  ?></span></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                                <?php if ($paginator->getNextUrl()) : ?>
                                    <li><a class="page-link btn btn-primary btn-animation px-3" href="<?php echo $paginator->getNextUrl();
                                                                                                        if (isset($_GET['filter'])) {
                                                                                                            echo '&filter=' . $filter;
                                                                                                        }
                                                                                                        if (isset($_GET['orderby'])) echo '&orderby=' . $orderby;  ?>">
                                            <img class="icon-14 rotate-180" src="<?= BASE_URL; ?>assets/images/b-arrow-white.svg"></a></li>
                                <?php endif; ?>

                            </ul>

                        <?php } else { ?>
                            <ul class="pagination mb-0 pt-3 pb-3">
                                <li class="page-item me-3">
                                    <a class="page-link btn btn-primary btn-animation px-3 " href="#"><img class=" icon-14 " src="<?= BASE_URL; ?>assets/images/b-arrow-white.svg"></a>
                                </li>
                                <li class="page-item"><a class="page-link primary-text" href="#!">1</a></li>
                                <li class="page-item "><a class="viewMore btn btn-outline-primary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#viewMoreProducts">View More</a></li>
                                <li class="page-item ms-3">
                                    <a class="page-link btn btn-primary btn-animation px-3 disabled" href="#"><img class="icon-14 rotate-180" src="<?= BASE_URL; ?>assets/images/b-arrow-white.svg"></a>
                                </li>
                            </ul>

                        <?php } ?>

                    </nav>
                </div>
            </div>
        </div>
    </section>
    <?php include_once('inc/footer.php') ?>




    <!-- filter modal -->
    <div class="modal fade " id="modalFilter" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" data-dismiss="modal" data-toggle="modal">
        <div class="modal-dialog modal-dialog-centered filter-modal">
            <form class="" id="search-form" action="" method="GET">
                <div class="modal-content">
                    <div class="filter-heading">
                        <h4 class="font-lg mb-0">FILTER BY</h4>
                        <a data-bs-dismiss="modal" class="modal-close">
                            <img src="<?= BASE_URL; ?>assets/images/i-cross.svg" alt="cross" />
                        </a>
                    </div>
                    <div class="filter-content">
                        <div class="filter-category">
                            <h4 class="font-md semibold dark-text mb-4">Category</h4>
                            <div class="filter-category-list">
                                <div class="filter-list-item mb-3">
                                    <div class="form-check font-md">
                                        <input class="form-check-input mt-02" type="checkbox" checked value="" id="bags" />
                                        <label class="form-check-label mid-dark-text font-sm" for="bags">
                                            <?= $Cat_title; ?> (<?php if (!empty($listProductCount)) {
                                                                    echo $listProductCount;
                                                                }  ?>)
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="filter-data">
                            <h4 class="font-md dark-text semibold mb-4">
                                <?= $Cat_title; ?>
                            </h4>
                            <?php if (!empty($filterData)) { ?>
                                <div class="row">
                                    <?php foreach ($filterData as $filter) { ?>
                                        <div class="col-md-3 col-sm-6 col-12 mb-4">
                                            <div class="form-check font-md">
                                                <input class="form-check-input mt-02" <?php if (isset($_GET['filter'])) {
                                                                                            if ($filter->slug == $_GET['filter']) {
                                                                                                echo 'checked';
                                                                                            }
                                                                                        } ?> type="radio" name="filter" value="<?= $filter->slug; ?>" id="<?= $filter->slug; ?>" />
                                                <label class="form-check-label mid-dark-text font-sm" for="<?= $filter->slug; ?>">
                                                    <?= $filter->name; ?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>

                            <div class="d-flex align-items-center mt-4">
                                <button type="submit" class="btn btn-primary btn-animation me-4 py-3">
                                    <span class="font-md px-4">Apply</span>
                                </button>
                                <a href="<?= BASE_URL . $return_slug; ?>" class="btn btn-outline-primary btn-animation ms-3 py-3">
                                    <span class="font-md  px-4"> Reset</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- filter modal end -->


    <!-- view more product modal start -->
    <div class="modal fade " id="viewMoreProducts" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" data-dismiss="modal" data-toggle="modal">
        <div class="modal-dialog modal-dialog-centered filter-modal">
            <div class="modal-content">
                <div class="filter-heading border-none pb-0">
                    <h4 class="font-lg mb-0">Welcome to A & A ACCESSORIES</h4>

                    <a data-bs-dismiss="modal" class="modal-close">
                        <img src="<?= BASE_URL; ?>assets/images/i-cross.svg" alt="cross" />
                    </a>
                </div>
                <div class="request-form px-5">
                    <p class="font-xxl mb-5 bold">Request</p>
                    <!-- id="request_form" -->
                    <form id="request_form" method="post">
                        <div class="row">
                            <div class="form-group col-md-6 mb-5">
                                <label class="font-sm mb-2 dark-text regular"><span class="required">Name of Organization *</span></label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" placeholder="Enter Organization Name" value="<?= isset($_POST['organization_name']) ? $_POST['organization_name'] : ''; ?>" name="organization_name" required />
                                    <div class="required_fields" style="color:red; font-size: 14px;"><?php if (!empty($organization_nameError)) {
                                                                                                            echo $organization_nameError;
                                                                                                        } ?></div>
                                </div>
                            </div>
                            <div class="form-group col-md-6 mb-5">
                                <label class="font-sm mb-2 dark-text regular"><span class="required">Name of Person *</span></label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" placeholder="Enter Person Name" value="<?= isset($_POST['Person']) ? $_POST['Person'] : ''; ?>" name="Person" required />
                                    <div class="required_fields" style="color:red; font-size: 14px;"><?php if (!empty($PersonError)) {
                                                                                                            echo $PersonError;
                                                                                                        } ?></div>
                                </div>
                            </div>
                            <div class="form-group col-md-6 mb-5">
                                <label class="font-sm mb-2 dark-text regular"><span class="required">Email ID *</span></label>
                                <div class="position-relative">
                                    <input type="email" class="form-control" value="<?= isset($_POST['Email']) ? $_POST['Email'] : ''; ?>" placeholder="Enter Email ID" name="Email" required />
                                    <div class="required_fields" style="color:red; font-size: 14px;"><?php if (!empty($EmailError)) {
                                                                                                            echo $EmailError;
                                                                                                        } ?></div>
                                </div>
                            </div>
                            <div class="form-group col-md-6 mb-5">
                                <label class="font-sm mb-2 dark-text regular">Phone Number</label>
                                <div class="position-relative">
                                    <input name="Phone" class="form-control" value="<?= isset($_POST['Phone']) ? $_POST['Phone'] : ''; ?>" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type="text" maxlength="15" maxlength="15" placeholder="Enter Phone Number">
                                    <div class="required_fields" style="color:red; font-size: 14px;"><?php if (!empty($PhoneError)) {
                                                                                                            echo $PhoneError;
                                                                                                        } ?></div>
                                </div>
                            </div>
                            <div class="form-group col-md-6 mb-5">
                                <label class="font-sm mb-2 dark-text regular">Address</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" placeholder="Enter Address" value="<?= isset($_POST['Address']) ? $_POST['Address'] : ''; ?>" name="Address" />
                                    <div class="required_fields" style="color:red; font-size: 14px;"><?php if (!empty($AddressError)) {
                                                                                                            echo $AddressError;
                                                                                                        } ?></div>
                                </div>
                            </div>
                            <div class="form-group col-md-6 mb-5">
                                <label class="font-sm mb-2 dark-text regular"><span class="required">Type Of Buyers *</span></label>
                                <div class="position-relative">

                                    <select class="orgn-type form-control" name="Buyer_type" required />
                                    <option value="">Select</option>
                                    <option <?= isset($_POST['Buyer_type']) && $_POST['Buyer_type'] == 'Wholesale' ? 'selected' : ''; ?> value="Wholesale">Wholesale</option>
                                    <option <?= isset($_POST['Buyer_type']) && $_POST['Buyer_type'] == 'Retail' ? 'selected' : ''; ?> value="Retail">Retail</option>
                                    <option <?= isset($_POST['Buyer_type']) && $_POST['Buyer_type'] == 'Manufacturing' ? 'selected' : ''; ?> value="Manufacturing">Manufacturing</option>
                                    <option <?= isset($_POST['Buyer_type']) && $_POST['Buyer_type'] == 'Agent' ? 'selected' : ''; ?> value="Agent">Agent</option>
                                    </select>
                                    <div class="required_fields" style="color:red; font-size: 14px;"><?php if (!empty($Buyer_typeError)) {
                                                                                                            echo $Buyer_typeError;
                                                                                                        } ?></div>

                                </div>
                            </div>
                            <div class="form-group col-md-12 mb-5">
                                <label class="font-sm mb-2 dark-text regular">Message</label>
                                <div class="position-relative">
                                    <textarea rows="3" class="form-control" name="message" placeholder="Enter Message"><?= isset($_POST['message']) ? $_POST['message'] : ''; ?></textarea>
                                    <div class="required_fields" style="color:red; font-size: 14px;"><?php if (!empty($messageError)) {
                                                                                                            echo $messageError;
                                                                                                        } ?></div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center mt-4 pb-5">
                                <button type="submit" name="view_more_product" class="btn btn-primary btn-animation me-4 py-3">
                                    <span class="font-md px-4">Send</span>
                                </button>
                                <a data-bs-dismiss="modal" class="btn btn-outline-primary btn-animation ms-3 py-3 modal-close">
                                    <span class="font-md  px-4"> Cancel</span>
                                </a>
                            </div>

                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- view more product modal end -->



</body>
<!-- JavaScript Bundle  -->
<?php include_once('inc/footer-script.php') ?>

<script>
    //function lazyloader() {
    //lazyloader
    let lazyimages = [].slice.call(document.querySelectorAll("img.lazyload"));
    //console.log(lazyimages);
    //alert('test');
    if ("IntersectionObserver" in window) {
        //console.log("Am there");
        let observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    let lazyimage = entry.target;
                    console
                    lazyimage.src = lazyimage.dataset.src;
                    lazyimage.srcset = lazyimage.dataset.srcset;
                    lazyimage.classList.remove("lazyload");
                    observer.unobserve(lazyimage);
                }
            });
        });
        //loop through all imgaes
        lazyimages.forEach((lazyimage) => {
            observer.observe(lazyimage);
        });
    } else {
        console.log("Am Not");
    }
    //}

    function AddToCart(id, msg, qty) {
        <?php if (isset($_SESSION['customer'])) { ?>
            $("#cartadd_" + id).text("Cart Added");

            $.ajax({
                type: "POST",
                url: "<?= BASE_URL ?>ajax/addToCart.php",
                data: {
                    action: "add_to_cart",
                    product_id: id,
                    user_id: <?= $user_id; ?>,
                    comment: msg,
                    quantity: qty,
                    page_url: "<?= $return_slug; ?>"
                },
                success: function(response) {
                    console.log(response);
                    $("#id-cart-value").html(response);
                    $("#id-cart-value2").html(response);
                },

            });
        <?php } else { ?>
            var cartObject = new Object();
            cartObject.id = id;
            cartObject.quantity = qty;
            cartObject.msg = msg;


            var retrievedObject = null;
            if (localStorage) {
                retrievedObject = localStorage.getItem('myArray');
                //alert(retrievedObject);
            } else {
                alert("Error: This browser is still not supported; Please use google chrome!");
            }
            var parsedArray = null;

            if (retrievedObject) {
                parsedArray = JSON.parse(retrievedObject);
            }

            if (parsedArray == null) {
                parsedArray = [];
            }

            var found = false;

            if (parsedArray.length == 0) {
                found = true;
            } else {
                for (var i = 0; i < parsedArray.length; i++) {
                    if (parsedArray[i].id == cartObject.id) {

                        found = false;
                        break;
                    } else {
                        found = true;
                    }
                }
            }
            if (found == true) {
                var cartArrayCount = parsedArray.push(cartObject);
            }

            $("#cartadd_" + id).text("Cart Added");


            $("#id-cart-value").html(cartArrayCount);
            $("#id-cart-value2").html(cartArrayCount);

            var localData = localStorage.setItem('myArray', JSON.stringify(parsedArray));

            console.log(localStorage.getItem('myArray'));
            ///////       notification added  /// 

            $("div.success").fadeIn(400).delay(1500).fadeOut(400);
        <?php } ?>


        /////  notification added ////
    }

    function add_comment_order(product_id) {
        //debugger;
        var comment = $('#details-' + product_id).val();
        var quantity = $('#quantity-' + product_id).val();
        // if (comment == '') {
        //     $('#comment_msg_' + product_id).html('please enter message').css('color', 'red');
        //     return false;
        // }
        if (quantity == '') {
            //alert('please enter your quantity');
            $('#qty_msg_' + product_id).html('Please enter your quantity').css('color', 'red');
            return false;
        }
        AddToCart(product_id, comment, quantity);
        setTimeout(function() {
            //location.reload();
            window.location.href = '<?= BASE_URL; ?>cart';
        }, 1000)
    }


    function orderBy() {
        var orderBy = $('#orderby').val();
        //alert(orderBy);
        window.location.href = "?orderby=" + orderBy;

    }

    $("#example1").on("click", ".add_to_wishlist", function() {
        var id = $(this).val();
        var element = document.getElementById("wishlist_" + id);
        //alert(id);
        $.ajax({
            url: '<?= BASE_URL; ?>ajax/addToCart.php',
            type: 'post',
            data: {
                action: 'add_to_wislist',
                product_id: id
            },
            success: function(data) {
                data = JSON.parse(data)
                console.log(data);
                if (data.message == 'already_added_in_wishlist') {
                    //alert('Product already added in wishlist.');
                    $('#wishlist-value').html(data.product_count);
                    $('#wishlist-value2').html(data.product_count);

                } else {
                    //alert('Product added to wishlist');
                    $('#wishlist-value').html(data.product_count);
                    $('#wishlist-value2').html(data.product_count);
                }
            }
        });
    });
</script>


</html>