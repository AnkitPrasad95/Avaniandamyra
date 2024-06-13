<?php require_once('header.php'); 
if(isset($_GET['user_id'])) {
$statement = $pdo->prepare("delete from tbl_wishlist where user_id =?");
$statement->execute(array($_GET['user_id']));
echo "<script>window.location.href='wishlist.php';</script>";
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Wishlist Reports</h1>
	</div>
	<div class="content-header-right">
		<!--<a href="subscriber-remove.php" class="btn btn-primary btn-sm">Remove Pending Subscribers</a>-->
		<!-- <a href="contact-csv.php" class="btn btn-primary btn-sm">Export as CSV</a> -->
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
			         <th>Created Date</th>
                    <th>Action</th>
			    </tr>
			</thead>
            <tbody>
            	<?php
            	$i=0;
                $query = "SELECT DISTINCT user_id  FROM tbl_wishlist";
            	$statement = $pdo->prepare($query);
            	$statement->execute();
            	$result = $statement->fetchAll(PDO::FETCH_ASSOC);	
                //print_r($result); 		die;    
            	foreach ($result as $row) {
                    $user_id = $row['user_id'];
                    $query = "SELECT *  FROM tbl_wishlist where user_id = $user_id";
                    $statement = $pdo->prepare($query);
                    $statement->execute();
                    $wishListDetails = $statement->fetch(PDO::FETCH_OBJ);	
            		$i++;

                    //get user details	
                   
                    $query = "SELECT first_name, last_name, email FROM tbl_customers where id = $user_id";
                    $statement = $pdo->prepare($query);
                    $statement->execute();
                    $user = $statement->fetch(PDO::FETCH_OBJ);	
                    //print_r($user); 
                    //echo $user->first_name;
                    //die;

                   
            		?>
					<tr>
	                    <td><?php echo $i; ?></td>
	                    <td><?php if(!empty($user)) { echo $user->first_name.' '.$user->last_name.' ('.$user->email.')'; } ?></td>
	                    
	                    <td><?php echo date('d M, Y - h:i A', strtotime($wishListDetails->created_at)); ?></td>
                        <td>
						    <button value="<?php echo $user_id; ?>" class="btn btn-success btn-xs myForm" title="view"><i class="fa fa-eye"></i></button>
                            <a href="#" class="btn btn-danger btn-xs" data-href="?user_id=<?php echo $user_id; ?>" title="delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a>  
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
                <h4 class="modal-title" id="myModalLabel">View Products</h4>
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
    		 data:{user_id:id},
             success: function(html)
             {
            	$('#recievedData').html(html);
               
             }
            }); 
        });
</script>