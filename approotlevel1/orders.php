<?php require_once('header.php'); 

if(isset($_GET['delete_id'])) {
    $statement = $pdo->prepare("update tbl_orders set status = 0, deleted_at=? WHERE id =?");
	$statement->execute(array(date('Y-m-d H:i:s'), $_REQUEST['delete_id']));

    $statement = $pdo->prepare("update tbl_order_details set status = 0, deleted_at=? WHERE order_id=?");
	$statement->execute(array(date('Y-m-d H:i:s'), $_REQUEST['delete_id']));
    header("location:orders.php");
    //echo "<script> alert('Order Deleted'); </script>";
    //echo "<script> window.location.href='orders.php' </script>";
}
if(isset($_GET['from_date'])){
    if(!empty($_GET['to_date'])) {
        $from_date = date('Y-m-d 00:00:00', strtotime($_GET['from_date']));
        $to_date = date('Y-m-d 23:59:59', strtotime($_GET['to_date']));
        $statement = $pdo->prepare("SELECT tbl_orders.*,tbl_customers.first_name, tbl_customers.last_name, tbl_customers.email FROM tbl_orders 
        inner join tbl_customers on tbl_customers.id = tbl_orders.user_id
        where tbl_orders.status = 1 and tbl_orders.created_at >= '$from_date' and tbl_orders.created_at <= '$to_date' order by tbl_orders.id desc");
        $statement->execute();
    } else {
        $from_date = date('Y-m-d 00:00:00', strtotime($_GET['from_date']));
        $statement = $pdo->prepare("SELECT tbl_orders.*,tbl_customers.first_name, tbl_customers.last_name, tbl_customers.email FROM tbl_orders 
        inner join tbl_customers on tbl_customers.id = tbl_orders.user_id
        where tbl_orders.status = 1 and tbl_orders.created_at >= '$from_date' order by tbl_orders.id desc");
        $statement->execute();
    }
   
} else {
    $statement = $pdo->prepare("SELECT tbl_orders.*,tbl_customers.first_name, tbl_customers.last_name, tbl_customers.email FROM tbl_orders 
    inner join tbl_customers on tbl_customers.id = tbl_orders.user_id
    where tbl_orders.status = 1 AND tbl_orders.deleted_at IS NULL order by tbl_orders.id desc");
    $statement->execute();
    	
}
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Order Reports</h1>
	</div>
	<div class="content-header-right">
		<!--<a href="subscriber-remove.php" class="btn btn-primary btn-sm">Remove Pending Subscribers</a>-->
		<!-- <a href="contact-csv.php" class="btn btn-primary btn-sm">Export as CSV</a> -->
	</div>
</section>


<section class="content">
  <div class="row">
    <form action="" method="get">
        <div class="col-lg-2">
            <label>From Order Date</label>
            <input type="text" id="datepicker1" class="form-control" value="<?php if(isset($_GET['from_date'])) { echo $_GET['from_date']; } ?>" name="from_date" required>
        </div>
        <div class="col-lg-2">
            <div class="form-group">
                <label for="first_name">To Order Date</label>
                <input type="text" id="datepicker" class="form-control" value="<?php if(isset($_GET['to_date'])) { echo $_GET['to_date']; } ?>" name="to_date">
            </div>
        </div>
        <div class="col-lg-8">
            <div class="pull-left" style="margin-top: 27px;">
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </div>
    </form>
    <div class="col-md-12">
      <div class="box box-info">        
        <div class="box-body table-responsive">
          <table id="example1" class="table table-bordered table-striped">
			<thead>
			    <tr>
			        <th>SL</th>
			        <th>Order Id</th>
			        <th>Customer Name</th>
                    <th>Comment</th>
			         <th>Order Date</th>
                    <th>Action</th>
			    </tr>
			</thead>
            <tbody>
            	<?php
            	$i=0;
            	
                // echo "<pre>";
                // print_r($result); die;
                // echo "</pre>";

            	foreach ($result as $row) {
            		$i++;

                   
            		?>
					<tr <?php if($row['read_status'] == 0){ ?> style="background-color:#E2F9F0;" <?php } ?> id="row_<?php echo $row['id']; ?>">
	                    <td><?php echo $i; ?></td>
                        <td><?php echo $row['order_id']; ?></td>
	                    <td><?php echo $row['first_name'].' '.$row['last_name'].' '.'('.$row['email'].')';  ?></td>
                        <td><?php echo $row['message']; ?></td>
	                    <td><?php echo date('d M, Y - h:i A', strtotime($row['created_at'])); ?></td>
                        <td>
						<button value="<?php echo $row['id']; ?>" class="btn btn-success btn-xs myForm" title="view"><i class="fa fa-eye"></i></button>
                            <a href="#" class="btn btn-danger btn-xs" data-href="?delete_id=<?php echo $row['id']; ?>" title="delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a>  
                        </td>
	                </tr>
            		<?php
            	}
            	?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>


<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
            </div>
            <div class="modal-body">
                Are you sure want to delete this item?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="view-data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">View Order Details</h4>
            </div>
            <div class="modal-body" id="recievedData">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                
            </div>
        </div>
    </div>
</div>


<?php require_once('footer.php'); ?>
<script>
$("#example1").on("click", ".myForm", function() {
            var id = $(this).val();
            //alert(id);
            $('#view-data').modal('show') 
            $.ajax({
             url: 'ajax/getLeadData.php',
             type: 'post',
    		 data:{order_id:id},
             success: function(html)
             {
            	$('#recievedData').html(html);
                $('#row_'+id).css('background-color','#f4f4f4');
             }
            }); 
        });
</script>