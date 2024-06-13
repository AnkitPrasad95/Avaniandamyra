<?php
session_start();
require_once('app/autoload.php');

$meta_title = "Thank you";
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

    <!-- <section class="full-container py-4">
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
        </div> -->

    </section>



    <?php include_once('inc/footer.php') ?>
    <?php include_once('inc/footer-script.php') ?>
    <script>
        let itemsArray = [];
        var  obj =
        {
        "product_id":1,
        "qty":30,
        "msh":'test'
        };

        itemsArray.push(obj);

        var  obj =
        {
        "product_id":11,
        "qty":36,
        "msh":'te55st'
        };
        itemsArray.push(obj);

        var  obj =
        {
        "product_id":111,
        "qty":365,
        "msh":'te55s555t'
        };
        itemsArray.push(obj);

        //obj = itemsArray;
        // itemsArray.push('product_id');
        // itemsArray.push('qty');
        // itemsArray.push('msg');
        localStorage.setItem('items', JSON.stringify(itemsArray));
        const data = JSON.parse(localStorage.getItem('items'));
        console.log(data);
    </script>

</body>


</html>