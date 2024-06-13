<?php require_once('header.php'); ?>

<section class="content-header">
  <h1>Dashboard</h1>
</section>

<?php





$statement = $pdo->prepare("SELECT * FROM tbl_subscriber");
$statement->execute();
$total_subscriber = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_contact where read_status=?");
$statement->execute(array(0));
$total_contact = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_user_request where read_status=?");
$statement->execute(array(0));
$total_request = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_orders where read_status=?");
$statement->execute(array(0));
$total_orders = $statement->rowCount();


?>

<section class="content">
  <div class="bgwithshadow row">



    <a href="contact-leads.php" class="col-md-3 col-sm-6 col-xs-12">
      <div>
        <div class="info-box">
          <div class="info-box-content bg-green">
            <span class="info-box-text darkbrowntext header"><b>Contact Detail</b></span>
            <span class="info-box-text darkbrowntext">Total Active Contact</span>
            <span class="info-box-number bluetext"><?php echo $total_contact; ?></span>
          </div>
        </div>
      </div>
    </a>



    <a href="subscriber.php" class="col-md-3 col-sm-6 col-xs-12">
      <div>
        <div class="info-box">
          <div class="info-box-content bg-green">
          <span class="info-box-text darkbrowntext header"><b>Subcriber Detail</b></span>
            <span class="info-box-text darkbrowntext">Total Subcriber</span>
            <span class="info-box-number browntext"><?php echo $total_subscriber; ?></span>
          </div>
        </div>
      </div>
    </a>

    <a href="requestLeads.php" class="col-md-3 col-sm-6 col-xs-12">
      <div>
        <div class="info-box">
          <div class="info-box-content bg-green">
          <span class="info-box-text darkbrowntext header"><b>Request Detail</b></span>
            <span class="info-box-text darkbrowntext">Total Active Request</span>
            <span class="info-box-number browntext"><?php echo $total_request; ?></span>
          </div>
        </div>
      </div>
    </a>

    <a href="orders.php" class="col-md-3 col-sm-6 col-xs-12">
      <div>
        <div class="info-box">
          <div class="info-box-content bg-green">
          <span class="info-box-text darkbrowntext header"><b>Order Detail</b></span>
            <span class="info-box-text darkbrowntext">Total Active Orders</span>
            <span class="info-box-number browntext"><?php echo $total_orders; ?></span>
          </div>
        </div>
      </div>
    </a>

  </div>

</section>

<?php require_once('footer.php'); ?>