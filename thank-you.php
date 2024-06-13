<?php
session_start();
require_once('app/autoload.php');
if(isset($_SESSION['backtoshop'])){
    $backtoshop = BASE_URL.$_SESSION['backtoshop'];
} else {
    $backtoshop = BASE_URL;
}

$meta_title = "Thank you : Avani & Amyra by A&A Accessories";
$meta_keyword = "";
$meta_description = "";
// echo 
// print_r($indexServiceList);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once('inc/meta-head.php') ?>
</head>

<body>
    <?php include_once('inc/header.php') ?>

    <section class="full-container py-4">
        <div class="thank-you-content text-center">
            <div class="icon mb-3">
                <img class="icon-96" src="./assets/images/thank-you.svg" alt="thank-you" />
            </div>
            <p class="font-xxl2 headingFont bold font-mid-dark mb-2">
                Thank You!
            </p>
            <p class="font-lg mid-dark-text pb-5">We have received your requirement and will get back to you soon.</p>
            <a href="<?=$backtoshop;?>" class="btn btn-primary btn-animation mt-5">
                <span class="font-lg px-4">View Collection</span>
            </a>
        </div>

    </section>



    <?php include_once('inc/footer.php') ?>
    <?php include_once('inc/footer-script.php') ?>

</body>


</html>