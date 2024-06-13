<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Order Reports</h1>
	</div>
	<div class="content-header-right">
		<!--<a href="subscriber-remove.php" class="btn btn-primary btn-sm">Remove Pending Subscribers</a>-->
		<a href="contact-csv.php" class="btn btn-primary btn-sm">Export as CSV</a>
	</div>
</section>


<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-info">        
        <div class="box-body table-responsive">
          <table id="example1" class="table table-bordered table-striped">
			<thead>
			    <tr>
			        <th>SL</th>
			        <th>Customer Name</th>
			        <th>Product Name</th>
                    <th>Comment</th>
                    <th>Quantity</th>
			         <th>Order Date</th>
                    <th>Action</th>
			    </tr>
			</thead>
            <tbody>
            	<?php
            	$i=0;
            	$statement = $pdo->prepare("SELECT * FROM tbl_order_list where mail_status = 1 order by id desc");
            	$statement->execute();
            	$result = $statement->fetchAll(PDO::FETCH_ASSOC);	
                		
            	foreach ($result as $row) {
            		$i++;

                    //get user details	
                    $user_id = $row['user_id'];
                    $query = "SELECT first_name, last_name FROM tbl_customers where id = $user_id";
                    $statement = $pdo->prepare($query);
                    $statement->execute();
                    $user = $statement->fetch(PDO::FETCH_OBJ);	
                    //print_r($user); 
                    //echo $user->first_name;
                    //die;

                    $statement = $pdo->prepare("SELECT * FROM tbl_product_list where id = ?");
                    $statement->execute(array($row['product_id']));
                    $product = $statement->fetch(PDO::FETCH_OBJ);	
            		?>
					<tr>
	                    <td><?php echo $i; ?></td>
	                    <td><?php if(!empty($user)) { echo $user->first_name.' '.$user->last_name; } ?></td>
	                    <td><?php echo $product->name; ?></td>
                        <td><?php echo $row['comment']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
	                    <td><?php echo date('d M, Y - h:i A', strtotime($row['created_at'])); ?></td>
                        <td>
						<button value="<?php echo $row['id']; ?>" class="btn btn-success btn-xs myForm" title="view"><i class="fa fa-eye"></i></button>
                            <a href="#" class="btn btn-danger btn-xs" data-href="order-delete.php?id=<?php echo $row['id']; ?>" title="delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a>  
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
                <h4 class="modal-title" id="myModalLabel">View Data</h4>
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
               
             }
            }); 
        });
</script>