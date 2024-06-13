<?php
session_start();
require_once('app/autoload.php');

use JasonGrimes\Paginator;

if (isset($_GET['CatUrl']) && isset($_GET['childUrl'])) {
    $Cat_title = ucwords($_GET['CatUrl']) . ', ' . ucwords($_GET['childUrl']);
    $typeUrl = 2;
    $return_slug = 'product-category/' . $_GET['CatUrl'] . '/' . $_GET['childUrl'];
    $filterData = $query->getFilter($_GET['CatUrl'], $_GET['childUrl']);
} else if (isset($_GET['CatUrl'])) {
    $typeUrl = 1;
    $Cat_title = ucwords($_GET['CatUrl']);
    $return_slug = 'product-category/' . $_GET['CatUrl'];
    $filterData = $query->getFilter($_GET['CatUrl']);
} else {
    echo "<script>window.location.href='" . BASE_URL . "';</script>";
}

if(isset($_GET['filter'])) {
    $filter = $_GET['filter'];
} else {
    $filter = '';
}

if(isset($_GET['orderby'])) {
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
        $productListByUrl = $query->getProductCatSubcat($_GET['CatUrl'], $_GET['childUrl'], $orderby, $filter, $startFrom, $showRecordPerPage);
    } else if ($typeUrl == 1) {
        $productListByUrl = $query->getProductCat($_GET['CatUrl'], $orderby, $filter, $startFrom, $showRecordPerPage);
    }
}


// echo "<pre>";
// print_r($productListByUrl);
// echo "</pre>";


if (isset($_POST['view_more_product'])) {


    $useremail = strip_tags($_POST['Email']);
    $checkExistingUser = $query->checkExistingUser($_POST);
    //echo "<pre>";
    //print_r($checkExistingUser);
    //echo "</pre>"; die();

    //this mail triggered to admin
    if ($contact_email != '') {
        $to = "ankitogen@gmail.com";
        $subject2 = "User request";
        $message = "
            <html>
            <head>
            <title>HTML email</title>
            </head>
            <body>
        
            <table>";

        $message .= "<tr style='background-color:#dcdbdb'>
            <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>Organization name : </th>
            <td style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>" . $_POST['organization_name'] . "</td>
            </tr>";

        $message .= "<tr style='background-color:#dcdbdb'>
            <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>Person : </th>
            <td style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>" . $_POST['Person'] . "</td>
            </tr>";

        $message .= "<tr style='background-color:#dcdbdb'>
            <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>Email : </th>
            <td style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>" . $_POST['Email'] . "</td>
            </tr>";

        $message .= "<tr style='background-color:#dcdbdb'>
            <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>Phone : </th>
            <td style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>" . $_POST['Phone'] . "</td>
            </tr>";



        $message .= "<tr style='background-color:#dcdbdb'>
            <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>Address : </th>
            <td style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>" . $_POST['Address'] . "</td>
            </tr>";

        $message .= "<tr style='background-color:#dcdbdb'>
            <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>Buyer type : </th>
            <td style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>" . $_POST['Buyer_type'] . "</td>
            </tr>";

        $message .= "<tr style='background-color:#dcdbdb'>
            <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>Message : </th>
            <td style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>" . $_POST['message'] . "</td>
            </tr>";
        $message .= "</table>
            </body>
            </html>
            ";

        echo $message;

        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers .= 'From: A&A Accessories <' . $contact_email . '>' . "\r\n";
        $headers .= 'Cc: ' . "\r\n";
        $sendMail = mail($to, $subject2, $message, $headers);
    }

    //this mail triggered to user with credential
    if ($_POST['Email'] != '') {
        $useremail = strip_tags($_POST['Email']);
        $to2 = $useremail;
        $subject3 = "User request";

        $message2 = "
            <html>
            <head>
            <title>HTML email</title>
            </head>
            <body>
        
            <table>";

        $message2 .= "<tr'>
            <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>Thank you for request with us. </th>
            </tr>";

        $message2 .= "<tr style='background-color:#dcdbdb'>
            <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>Your user name is : : </th>
            <td style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>" . $_POST['Email'] . "</td>
            </tr>";

        $message2 .= "<tr style='background-color:#dcdbdb'>
            <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>Password : </th>
            <td style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'>" . $checkExistingUser['password'] . "</td>
            </tr>";
        $message2 .= "</table>
            </body>
            </html>
            ";

        //echo $message2; DIE();

        // Always set content-type when sending HTML email
        $headers2 = "MIME-Version: 1.0" . "\r\n";
        $headers2 .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers2 .= 'From: A&A Accessories <' . $contact_email . '>' . "\r\n";
        $headers2 .= 'Cc: ' . "\r\n";
        $sendMail2 = mail($to2, $subject3, $message2, $headers2);
    }

    if ($checkExistingUser['message'] == 'already_exist') {
        echo "<script> alert('Your request has been submitted successfully, Login credential will be sent on your registered email " . $_POST['Email'] . "'); </script>";
    } else if ($checkExistingUser['message'] == 'new_visitor') {
        echo "<script> alert('Your request has been submitted successfully, Login credential will be sent on your registered email " . $_POST['Email'] . "'); </script>";
    }
    echo "<script> window.location.href='" . $_SERVER['REQUEST_URI'] . "'; </script>";
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
                Latest Collection
            </p>
            <div class="product-heading-action">
                <a class="text-with-icon btn-transprent font-md me-4">
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
                <a data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalFilter" class="text-with-icon btn-transprent font-md ms-4">
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
                                <a class="wishlist-icon" id="wishlist-<?= $product->id; ?>" onclick="wishlist(<?= rand(); ?>, <?= $product->id; ?>)">
                                    <?php if (!empty($get_product_wishlist)) { ?>
                                        <svg width="34" height="31" viewBox="0 0 34 31" fill="#9F9987">
                                            <path d="M27.4644 0.265504C22.6752 -0.974341 18.5546 2.42916 16.7677 4.26461C14.9687 2.44131 10.8359 -0.974341 6.04672 0.27766C-1.64761 2.28329 -0.0309528 9.66159 0.540348 12.1291C2.97142 22.9474 15.6373 29.9853 16.1843 30.2771C16.3635 30.3738 16.5641 30.4239 16.7677 30.4229C16.9714 30.4239 17.172 30.3738 17.3512 30.2771C17.886 29.9853 30.5762 22.9838 32.9951 12.044C33.5786 9.3577 35.1588 2.25898 27.4644 0.265504Z"></path>
                                        </svg>
                                    <?php } else { ?>
                                        <svg width="34" height="31" viewBox="0 0 34 31" fill="#9F9987">
                                            <path d="M27.4644 0.265504C22.6752 -0.974341 18.5546 2.42916 16.7677 4.26461C14.9687 2.44131 10.8359 -0.974341 6.04672 0.27766C-1.64761 2.28329 -0.0309528 9.66159 0.540348 12.1291C2.97142 22.9474 15.6373 29.9853 16.1843 30.2771C16.3635 30.3738 16.5641 30.4239 16.7677 30.4229C16.9714 30.4239 17.172 30.3738 17.3512 30.2771C17.886 29.9853 30.5762 22.9838 32.9951 12.044C33.5786 9.3577 35.1588 2.25898 27.4644 0.265504Z" />
                                            </path>
                                        </svg>
                                    <?php } ?>

                                </a>
                                <div class="product-image">
                                    <?php if (!empty($product->thumbnail_image)) { ?>
                                        <img src="<?= BASE_URL . $product->file_path . $product->thumbnail_image; ?>" alt="<?= $product->name; ?>" />
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
                                    <?php if (isset($_SESSION['customer'])) { ?>
                                        <a href="javascript:void(0)" onclick="add_to_cart(<?= rand(1111, 9999); ?>, <?= $product->id; ?>)" id="prd_crt-<?= $product->id; ?>" class="btn btn-primary btn-animation">
                                            <span class="font-lg"> Add to Order</span>
                                        </a>
                                    <?php } else { ?>
                                        <a data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#loginSignup" class="btn btn-primary btn-animation ">
                                            Add to Order
                                        </a>
                                    <?php } ?>

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
                                            <a class="wishlist-icon active">
                                                <svg width="34" height="31" viewBox="0 0 34 31" fill="#9F9987">
                                                    <path d="M27.4644 0.265504C22.6752 -0.974341 18.5546 2.42916 16.7677 4.26461C14.9687 2.44131 10.8359 -0.974341 6.04672 0.27766C-1.64761 2.28329 -0.0309528 9.66159 0.540348 12.1291C2.97142 22.9474 15.6373 29.9853 16.1843 30.2771C16.3635 30.3738 16.5641 30.4239 16.7677 30.4229C16.9714 30.4239 17.172 30.3738 17.3512 30.2771C17.886 29.9853 30.5762 22.9838 32.9951 12.044C33.5786 9.3577 35.1588 2.25898 27.4644 0.265504Z" />
                                                </svg>
                                            </a>
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
                                                                </div>
                                                            </div>
                                                            <div class="col-4">
                                                                <div class="form-group">
                                                                    <label class="font-sm mb-2" for="Quantity">Quantity</label>
                                                                    <input type="number" class="form-control font-md" name="quantity" id="quantity-<?= $product->id; ?>" />
                                                                    <input class="form-control" type="hidden" id="order_id" name="product_id" value="<?= $product->order_id; ?>" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-btns">
                                                            <?php if (isset($_SESSION['customer'])) { ?>
                                                                <a href="javascript:void(0)" onclick="add_comment_order(<?= rand(1111, 9999); ?>, <?= $product->id; ?>)" class="btn btn-primary btn-animation w-100 ">
                                                                    <span class="font-lg">Add to Order</span>
                                                                </a>
                                                            <?php } else { ?>
                                                                <a data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#loginSignup" class="btn btn-primary btn-animation">
                                                                    Add to Order
                                                                </a>
                                                            <?php } ?>
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


                <?php } } //}  ?>
                <?php if ($existPdoduct == 0) { ?>
                    <p class="woocommerce-info">No products were found matching your selection.</p>
                <?php } ?>

                <div class="pagination-content">
                    <nav aria-label="navigation">
                        <?php if (isset($_SESSION['customer'])) { ?>
                            <ul class="pagination mb-0 pt-3 pb-3">

                                <?php if ($paginator->getPrevUrl()) : ?>
                                    <li><a class="page-link btn btn-primary btn-animation px-3" href="<?php echo $paginator->getPrevUrl();
                                                                                                        if (isset($_GET['orderby'])) echo '&orderby=' . $orderby; ?>"><img class=" icon-14 " src="<?= BASE_URL; ?>assets/images/b-arrow-white.svg"></a></li>
                                <?php endif; ?>
                                <?php foreach ($paginator->getPages() as $page) : ?>
                                    <?php if ($page['url']) : ?>
                                        <li <?php echo $page['isCurrent'] ? 'class="active page-item"' : ''; ?> class="page-item">
                                            <a class="page-link " href="<?php echo $page['url'];
                                                                        if (isset($_GET['orderby'])) echo '&orderby=' . $orderby; ?>"><?php echo $page['num']; ?></a>
                                        </li>
                                    <?php else : ?>
                                        <li class="disabled"><span><?php echo $page['num'];
                                                                    if (!($orderby)) echo '&orderby=' . $orderby; ?></span></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                                <?php if ($paginator->getNextUrl()) : ?>
                                    <li><a class="page-link btn btn-primary btn-animation px-3" href="<?php echo $paginator->getNextUrl();
                                                                                                        if (isset($_GET['orderby'])) echo '&orderby=' . $orderby; ?>"><img class="icon-14 rotate-180" src="<?= BASE_URL; ?>assets/images/b-arrow-white.svg"></a></li>
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
                                            <?=$Cat_title;?> (<?php if(!empty($totalProduct)) { echo $totalProduct; }  ?>)
                                        </label>
                                    </div>
                                </div>
                                <!-- <div class="filter-list-item  mb-3">
                                    <div class="form-check font-md">
                                        <input class="form-check-input mt-02" type="checkbox" value="" id="scarves" />
                                        <label class="form-check-label mid-dark-text font-sm" for="scarves">
                                            Scarves (306)
                                        </label>
                                    </div>
                                </div>
                                <div class="filter-list-item  mb-3">
                                    <div class="form-check font-md">
                                        <input class="form-check-input mt-02" type="checkbox" value="" id="kaftans" />
                                        <label class="form-check-label mid-dark-text font-sm" for="kaftans">
                                            Kaftans (201)
                                        </label>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                        <div class="filter-data">
                        <h4 class="font-md dark-text semibold mb-4">
                            <?=$Cat_title;?>
                            </h4>
                            <?php if(!empty($filterData)) { ?> 
                            <div class="row">
                                <?php foreach($filterData as $filter) {?>
                                <div class="col-md-3 col-6 mb-4">
                                    <div class="form-check font-md">
                                        <input class="form-check-input mt-02" <?php if (isset($_GET['filter'])) { if ($filter->slug == $_GET['filter']) { echo 'checked'; } } ?> type="radio" name="filter" value="<?=$filter->slug;?>" id="<?=$filter->slug;?>" />
                                        <label class="form-check-label mid-dark-text font-sm" for="<?=$filter->slug;?>">
                                            <?=$filter->name;?>
                                        </label>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                            <?php } ?>
                            <!-- <h4 class="font-md dark-text semibold mb-4">
                                Material
                            </h4>
                            <div class="row">
                                <div class="col-md-3 col-6 mb-4">
                                    <div class="form-check font-md">
                                        <input class="form-check-input mt-02" type="checkbox" value="" id="jute" />
                                        <label class="form-check-label mid-dark-text font-sm" for="jute">
                                            Jute
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-4">
                                    <div class="form-check font-md">
                                        <input class="form-check-input mt-02" type="checkbox" value="" id="linen" />
                                        <label class="form-check-label mid-dark-text font-sm" for="linen">
                                            Linen
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-4">
                                    <div class="form-check font-md">
                                        <input class="form-check-input mt-02" type="checkbox" value="" id="grey" />
                                        <label class="form-check-label mid-dark-text font-sm" for="grey">
                                            Grey Fabric
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-4">
                                    <div class="form-check font-md">
                                        <input class="form-check-input mt-02" type="checkbox" value="" id="cane" />
                                        <label class="form-check-label mid-dark-text font-sm" for="cane">
                                            Cane
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-3 col-6 mb-4">
                                    <div class="form-check font-md">
                                        <input class="form-check-input mt-02" type="checkbox" value="" id="canvas" />
                                        <label class="form-check-label mid-dark-text font-sm" for="canvas">
                                            Canvas
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-4">
                                    <div class="form-check font-md">
                                        <input class="form-check-input mt-02" type="checkbox" value="" id="Dori" />
                                        <label class="form-check-label mid-dark-text font-sm" for="Dori">
                                            Dori
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-4">
                                    <div class="form-check font-md">
                                        <input class="form-check-input mt-02" type="checkbox" value="" id="Jacquard" />
                                        <label class="form-check-label mid-dark-text font-sm" for="Jacquard">
                                            Jacquard Fabric
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-4">
                                    <div class="form-check font-md">
                                        <input class="form-check-input mt-02" type="checkbox" value="" id="Foil" />
                                        <label class="form-check-label mid-dark-text font-sm" for="Foil">
                                            Foil
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-4">
                                    <div class="form-check font-md">
                                        <input class="form-check-input mt-02" type="checkbox" value="" id="Raffia" />
                                        <label class="form-check-label mid-dark-text font-sm" for="Raffia">
                                            Raffia
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-4">
                                    <div class="form-check font-md">
                                        <input class="form-check-input mt-02" type="checkbox" value="" id="Prints" />
                                        <label class="form-check-label mid-dark-text font-sm" for="Prints">
                                            Prints
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-4">
                                    <div class="form-check font-md">
                                        <input class="form-check-input mt-02" type="checkbox" value="" id="Water" />
                                        <label class="form-check-label mid-dark-text font-sm" for="Water ">
                                            Water Hyacinth
                                        </label>
                                    </div>
                                </div>
                            </div> -->
                            <!-- <h4 class="font-md dark-text semibold mb-4 mt-4">
                                Size
                            </h4>
                            <div class="row">
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="form-check font-md">
                                        <input class="form-check-input mt-02" type="checkbox" value="" id="Medium" />
                                        <label class="form-check-label mid-dark-text font-sm" for="Medium">
                                            Medium (20 - 39 cm)
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="form-check font-md">
                                        <input class="form-check-input mt-02" type="checkbox" value="" id="Small" />
                                        <label class="form-check-label mid-dark-text font-sm" for="Small">
                                            Small (Up to 19 cm)
                                        </label>
                                    </div>
                                </div>
                            </div> -->
                            <!-- <h4 class="font-md dark-text semibold mb-4 mt-4">
                                Collection
                            </h4>
                            <div class="row">
                                <div class="col-md-3 col-12 mb-4">
                                    <div class="form-check font-md">
                                        <input class="form-check-input mt-02" type="checkbox" value="" id="Beach" />
                                        <label class="form-check-label mid-dark-text font-sm" for="Beach">
                                            Beach
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-12 mb-4">
                                    <div class="form-check font-md">
                                        <input class="form-check-input mt-02" type="checkbox" value="" id="Bottle" />
                                        <label class="form-check-label mid-dark-text font-sm" for="Bottle">
                                            Bottle
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-12 mb-4">
                                    <div class="form-check font-md">
                                        <input class="form-check-input mt-02" type="checkbox" value="" id="Promotional" />
                                        <label class="form-check-label mid-dark-text font-sm" for="Promotional">
                                            Promotional
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-12 mb-4">
                                    <div class="form-check font-md">
                                        <input class="form-check-input mt-02" type="checkbox" value="" id="Fashion" />
                                        <label class="form-check-label mid-dark-text font-sm" for="Fashion">
                                            Fashion
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-12 mb-4">
                                    <div class="form-check font-md">
                                        <input class="form-check-input mt-02" type="checkbox" value="" id="Corporate" />
                                        <label class="form-check-label mid-dark-text font-sm" for="Corporate">
                                            Corporate
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-12 mb-4">
                                    <div class="form-check font-md">
                                        <input class="form-check-input mt-02" type="checkbox" value="" id="Storage" />
                                        <label class="form-check-label mid-dark-text font-sm" for="Storage">
                                            Storage
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-12 mb-4">
                                    <div class="form-check font-md">
                                        <input class="form-check-input mt-02" type="checkbox" value="" id="Shopping" />
                                        <label class="form-check-label mid-dark-text font-sm" for="Shopping">
                                            Shopping
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-12 mb-4">
                                    <div class="form-check font-md">
                                        <input class="form-check-input mt-02" type="checkbox" value="" id="Evening" />
                                        <label class="form-check-label mid-dark-text font-sm" for="Evening">
                                            Evening
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-12 mb-4">
                                    <div class="form-check font-md">
                                        <input class="form-check-input mt-02" type="checkbox" value="" id="Reversible" />
                                        <label class="form-check-label mid-dark-text font-sm" for="Reversible">
                                            Reversible
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-12 mb-4">
                                    <div class="form-check font-md">
                                        <input class="form-check-input mt-02" type="checkbox" value="" id="Pouches" />
                                        <label class="form-check-label mid-dark-text font-sm" for="Pouches">
                                            Pouches
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-12 mb-4">
                                    <div class="form-check font-md">
                                        <input class="form-check-input mt-02" type="checkbox" value="" id="Canvas" />
                                        <label class="form-check-label mid-dark-text font-sm" for="Canvas">
                                            Canvas
                                        </label>
                                    </div>
                                </div>
                            </div> -->
                            <div class="d-flex align-items-center mt-4">
                                <button type="submit" class="btn btn-primary btn-animation me-4 py-3">
                                    <span class="font-md px-4">Apply</span>
                                </button>
                                <a class="btn btn-outline-primary btn-animation ms-3 py-3">
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
                    <form method="post">
                        <div class="row">
                            <div class="form-group col-md-6 mb-5">
                                <label class="font-sm mb-2 dark-text regular"><span class="required">Name of Organization *</span></label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" placeholder="Enter Organization Name" name="organization_name" required />
                                </div>
                            </div>
                            <div class="form-group col-md-6 mb-5">
                                <label class="font-sm mb-2 dark-text regular"><span class="required">Name of Person *</span></label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" placeholder="Enter Person Name" name="Person" required />
                                </div>
                            </div>
                            <div class="form-group col-md-6 mb-5">
                                <label class="font-sm mb-2 dark-text regular"><span class="required">Email ID *</span></label>
                                <div class="position-relative">
                                    <input type="email" class="form-control" placeholder="Enter Email ID" name="Email" required />
                                </div>
                            </div>
                            <div class="form-group col-md-6 mb-5">
                                <label class="font-sm mb-2 dark-text regular">Phone Number</label>
                                <div class="position-relative">
                                    <input type="number" class="form-control" placeholder="Enter Phone Number" name="Phone" required />
                                </div>
                            </div>
                            <div class="form-group col-md-6 mb-5">
                                <label class="font-sm mb-2 dark-text regular">Address</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" placeholder="Enter Address" name="Address" required />
                                </div>
                            </div>
                            <div class="form-group col-md-6 mb-5">
                                <label class="font-sm mb-2 dark-text regular"><span class="required">Type Of Buyers *</span></label>
                                <div class="position-relative">

                                    <select class="orgn-type form-control" name="Buyer_type" required />
                                    <option value="">Select</option>
                                    <option value="Wholesale">Wholesale</option>
                                    <option value="Retail">Retail</option>
                                    <option value="Manufacturing">Manufacturing</option>
                                    <option value="Agent">Agent</option>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group col-md-12 mb-5">
                                <label class="font-sm mb-2 dark-text regular">Message</label>
                                <div class="position-relative">
                                    <textarea rows="3" class="form-control" name="message" placeholder="Enter Message"></textarea>

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
    $("#example1").on("click", ".myForm", function() {
        var id = $(this).val();

        if (id != '') {
            setTimeout(function() {

                $('#productDetails-' + id).modal('show')

            }, 500)
        }
        // $.ajax({
        //  url: '<?= BASE_URL ?>ajax/addToCart.php',
        //  type: 'post',
        //  data:{action:'show_product_detail_modal', product_id:id, cat_title:'<?= $Cat_title; ?>'},
        //  success: function(html)
        //  {
        //     $('#productDetails').modal('show') 
        //     $('#recievedData').html(html);

        //  }
        // }); 
    });

    // const category = document.querySelector('.category-dropdown');
    // const sortingCategory = new Choices(category, {
    //     placeholder: true,
    //     placeholderValue: 'Please Choose…',
    //     itemSelectText: '',
    //     removeItemButton: false,
    // });

    <?php if (isset($_SESSION['customer'])) { ?>

        function add_to_cart(rand, product_id) {
            $.ajax({
                type: "POST",
                url: "<?= BASE_URL ?>ajax/addToCart.php",
                data: {
                    action: "add_to_cart",
                    product_id: product_id,
                    user_id: <?= $user_id; ?>,
                    comment: '',
                    quantity: 1,
                    page_url: "<?= $return_slug; ?>"
                },

                beforeSend: function() {
                    document.getElementById("loading").style.display = "block";
                },
                success: function(response) {
                    document.getElementById("loading").style.display = "none";
                    if (response == 'product_already_exist') {
                        alert('Product already added in order list.');
                        //$('#prd_crt-'+product_id).text('Added to Cart').css('disabled');
                        //return false; 
                        setTimeout(function() {
                            //location.reload();
                            window.location.href = '<?= BASE_URL; ?>cart';
                        }, 500)
                    } else if (response == 'product_added') {
                        alert('Product added in order list.');
                        setTimeout(function() {
                            //location.reload();
                            window.location.href = '<?= BASE_URL; ?>cart';
                        }, 500)
                        //$('#prd_crt-'+product_id).text('Added to Cart').css('disabled');
                        //return false;
                    }

                },

            });
        }


        function add_comment_order(rand, product_id) {

            var comment = $('#details-' + product_id).val();
            var quantity = $('#quantity-' + product_id).val();
            //alert(rand + comment + product_id);
            if (comment == '') {
                alert('please enter message');
                return false;
            }
            if (quantity == '') {
                alert('please enter your quantity');
                return false;
            }


            $.ajax({
                type: "POST",
                url: "<?= BASE_URL ?>ajax/addToCart.php",
                data: {
                    action: "add_to_cart",
                    product_id: product_id,
                    user_id: <?= $user_id; ?>,
                    comment: comment,
                    quantity: quantity,
                    page_url: "<?= $return_slug; ?>"
                },

                beforeSend: function() {
                    document.getElementById("loading").style.display = "block";
                },
                success: function(response) {
                    document.getElementById("loading").style.display = "none";
                    if (response == 'product_already_exist') {
                        alert('Product already added in order list.');
                        //$('#prd_crt-'+product_id).text('Added to Cart').css('disabled');
                        //return false; 
                        setTimeout(function() {
                            //location.reload();
                            window.location.href = '<?= BASE_URL; ?>cart';
                        }, 500)
                    } else if (response == 'product_added') {
                        alert('Product added in order list.');
                        setTimeout(function() {
                            //location.reload();
                            window.location.href = '<?= BASE_URL; ?>cart';
                        }, 500)
                        //$('#prd_crt-'+product_id).text('Added to Cart').css('disabled');
                        //return false;
                    }

                },

            });
        }

        function wishlist(rand, product_id) {
            //alert(product_id);
            const button = document.getElementById('wishlist-' + product_id);
            $.ajax({
                type: "POST",
                url: "<?= BASE_URL ?>ajax/addToCart.php",
                data: {
                    action: "add_to_wishlist",
                    product_id: product_id,
                    user_id: <?= $user_id; ?>
                },

                beforeSend: function() {
                    document.getElementById("loading").style.display = "block";
                },
                success: function(response) {
                    document.getElementById("loading").style.display = "none";
                    console.log(response);
                    if (response == 'added_in_wishlist') {
                        alert('Product added in wishlist.');
                    } else {
                        alert('Product removed from wishlist.');
                    }

                },

            });


        }



    <?php } ?>

    function orderBy() {
        var orderBy = $('#orderby').val();
        //alert(orderBy);
        window.location.href = "?orderby=" + orderBy;

    }
</script>


</html>