<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Request Leads</h1>
	</div>
	<div class="content-header-right">
		<!--<a href="subscriber-remove.php" class="btn btn-primary btn-sm">Remove Pending Subscribers</a>-->
		<a href="productLeads-csv.php" class="btn btn-primary btn-sm">Export as CSV</a>
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
			        <th>Organization name</th>
			        <th>Person</th>
					<th>Email</th>
                    <th>Phone</th>
			        <th>Date</th>
                    <th>Action</th>
			    </tr>
			</thead>
            <tbody>
            	<?php
            	$i=0;
            	$statement = $pdo->prepare("SELECT * FROM tbl_user_request order by id desc");
            	$statement->execute();
            	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
            	foreach ($result as $row) {
            		$i++;
            		?>
					<tr <?php if($row['read_status'] == 0){ ?> style="background-color:#E2F9F0;" <?php } ?> id="row_<?php echo $row['id']; ?>">
	                    <td><?php echo $i; ?></td>
	                    <td><?php echo $row['organization_name']; ?></td>
	                    <td><?php echo $row['person']; ?></td>
						<td><?php echo $row['email']; ?></td>
	                    <td><?php echo $row['phone']; ?></td>
	                    <td><?php echo date('d M, Y - h:i A', strtotime($row['created_at'])); ?></td>
                        <td>
                            <button value="<?php echo $row['id']; ?>" class="btn btn-success btn-xs myForm" title="view"><i class="fa fa-eye"></i></button>
                            <a href="#" class="btn btn-danger btn-xs" title="delete" data-href="productLeads-delete.php?id=<?php echo $row['id']; ?>" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a>  
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
    		 data:{requestLead_id:id},
             success: function(html)
             {
            	$('#recievedData').html(html);
                $('#row_'+id).css('background-color','#f4f4f4');
             }
            }); 
        });
</script>