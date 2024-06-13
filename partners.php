<?php
session_start();
require_once('app/autoload.php');
$query = new query();
$meta_title = "Partners : Avani & Amyra by A&A Accessories";
$meta_keyword = "";
$meta_description = "";
$partnerList = $query->getPartners();
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

    <section class="full-container py-5 partners-bg ">
        <div class="heading py-7  ">
            <p class="font-lg font-mid-dark ">
                PARTNERS
            </p>
        </div>
        <div class="subheading mb-5 row">
            <div class="col-lg-5 col-md-6 p-0">
                <p class="font-xxl2 headingFont bold">
                    Our partnerships with global brands
                </p>
            </div>
        </div>
        <div class="row py-6">
            <?php if(!empty($partnerList)) { 
            foreach($partnerList as $partnerListRow) {    
            ?> 
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="partner-item">
                    <img src="<?php echo BASE_URL.$partnerListRow->file_path.$partnerListRow->photo; ?>" alt="<?=$partnerListRow->name;?>" />
                </div>
            </div>
            <?php } } ?>
        </div>
    </section>


    <?php include_once('inc/footer.php') ?>
    <?php include_once('inc/footer-script.php') ?>

</body>


</html>