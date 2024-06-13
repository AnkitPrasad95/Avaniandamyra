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

$statement = $pdo->prepare("SELECT * FROM tbl_contact");
$statement->execute(array());
$total_contact_cnt = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_user_request where read_status=?");
$statement->execute(array(0));
$total_request = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_user_request ");
$statement->execute(array());
$total_request_cnt = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_orders where read_status=?");
$statement->execute(array(0));
$total_orders = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_orders");
$statement->execute(array());
$total_orders_cnt = $statement->rowCount();

$dataPoints = array(
  //array("label" => "Total Active Contact", "y" => $total_contact),
  array("label" => "Total Contact", "y" => $total_contact_cnt),
  array("label" => "Total Subcriber", "y" => $total_subscriber),
  //array("label" => "Total Active Request", "y" => $total_request),
  array("label" => "Total Request", "y" => $total_request_cnt),
  //array("label" => "Total Active Orders", "y" => $total_orders),
  array("label" => "Total Orders", "y" => $total_orders_cnt)
);

// echo "</pre>";
// print_r($dataPoints)

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
  <br>
  <!-- <div id="chartContainer" style="height: 370px; width: 100%;"></div> -->

  <!-- Display the pie chart -->
  <div id="piechart"></div>


</section>


<?php require_once('footer.php'); ?>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>
  window.onload = function() {


    var chart = new CanvasJS.Chart("chartContainer", {
      animationEnabled: true,
      title: {
        text: ""
      },
      subtitles: [{
        text: ""
      }],
      data: [{
        type: "pie",
        yValueFormatString: "#,##0",
        indexLabel: "{label} ({y})",
        dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
      }]
    });
    chart.render();

  }
</script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load('current', {
    'packages': ['corechart']
  });
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {

    var data = google.visualization.arrayToDataTable([
      ['Lead', 'Count'],
      <?php
      if(!empty($dataPoints)){
          foreach($dataPoints as $row){
            echo "['".$row['label']."', ".$row['y']."],";
          }
      }
      ?>
    ]);

    var options = {
      title: '',
      width: '100%',
      height: 350,
    };

    var chart = new google.visualization.PieChart(document.getElementById('piechart'));

    chart.draw(data, options);
  }
</script>