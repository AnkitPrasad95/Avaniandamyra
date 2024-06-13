<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>View payment</h1>
	</div>
	<!--<div class="content-header-right">-->
	<!--	<a href="manageClass-add.php" class="btn btn-primary btn-sm" target="_blank">Add Class</a>-->
	<!--</div>-->
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-body table-responsive">
					<table id="example1" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th width="50">SL</th>
								<th width="100">Pay Id</th>
								<th width="100">Name</th>
								<th width="100">Class name</th>
                                <th width="100">Amount</th>
                                <th width="100">Status</th>
                                <th width="100">Date</th>
								<th width="140">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;
							$statement = $pdo->prepare("SELECT
														
													    *
							                           	FROM tbl_payment
                                                        order by id desc
							                           	");
							$statement->execute();
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
							foreach ($result as $row) {
								$i++;
								?>
								<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo $row['payment_id']; ?></td>
									<td><?php echo $row['std_name']; ?></td>
                                    <td><?php echo $row['class_name']; ?></td>
                                    <td><?php echo $row['amount']; ?></td>
                                    <td><?php echo $row['status']; ?></td>
                                    <td><?php echo date('d-m-Y h:i A', strtotime($row['created_at'])); ?></td>
									<td>										
										<!--<a href="manageClass-edit.php?id=<?php echo $row['id']; ?>" target="_blank" class="btn btn-primary btn-xs">Edit</a>-->
										<a href="#" class="btn btn-danger btn-xs" data-href="manageClass-delete.php?id=<?php echo $row['id']; ?>" data-toggle="modal" data-target="#confirm-delete">Delete</a>  
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
                <p>Are you sure want to delete this item?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>


<?php require_once('footer.php'); ?>