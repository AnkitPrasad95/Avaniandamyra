<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Subscriber</h1>
	</div>
	<div class="content-header-right">
		<!-- <a href="subscriber-remove.php" class="btn btn-primary btn-sm">Remove Pending Subscribers</a> -->
		<a href="subscriber-csv.php" class="btn btn-primary btn-sm">Export as CSV</a>
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
			        <th>Subscriber Email</th>
                    <th>Subscribed Date</th>
                    <th>Action</th>
			    </tr>
			</thead>
            <tbody>
            	<?php
            	$i=0;
            	$statement = $pdo->prepare("SELECT * FROM tbl_subscriber WHERE subs_active=1");
            	$statement->execute();
            	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
            	foreach ($result as $row) {
            		$i++;
            		?>
					<tr>
	                    <td><?php echo $i; ?></td>
	                    <td><?php echo $row['subs_email']; ?></td>
                        <td><?php echo date('d M, Y - h:i A', strtotime($row['subs_date_time'])); ?></td>
                        <td>
                        <!-- <button value="<?php echo $row['subs_id']; ?>" class="btn btn-success btn-xs myForm"><i class="fa fa-info-circle" aria-hidden="true"></i></button>   -->
                        <a href="Javascript:void(0);" class="btn btn-danger btn-xs" data-href="subscriber-delete.php?id=<?php echo $row['subs_id']; ?>" title="delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>  
                        </td>
	                </tr>
            		<?php
            	}
            	?>
            </tbody>
          </table>
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
// when DOM is ready
$(document).ready(function () { 
	$('.myForm').on('click', function(e){
	 var id = $(this).val();
	 //alert(id);
	$('#view-data').modal('show'); 

	
	$.ajax({
         url: 'ajax/getLeadData.php',
         type: 'post',
		 data:{news_id:id},
         success: function(html)
         {
			
        	$('#recievedData').html(html);
           

         }
        }); 

	e.preventDefault();
	});
});
</script>