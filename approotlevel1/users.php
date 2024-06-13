<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>View Users</h1> 
	</div>
	<div class="content-header-right">
		<a href="user-add.php" class="btn btn-primary btn-sm">Add User</a>
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
								<th width="50">SL</th>
								<th width="100">User Name</th>
								<th width="100">Name</th>
								<th width="100">Email</th>
								<th width="100">Role</th>
								<th width="100">Status</th>
								<th width="140">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;
							$statement = $pdo->prepare("SELECT
														
														*
							                           	FROM tbl_customers
							                           	order by id desc
							                           	");
							$statement->execute();
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
							foreach ($result as $row) {
							    
								$i++;
								?>
								<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo $row['user_name']; ?></td>
                                    <td><?php echo $row['first_name'].' '.$row['last_name']; ?></td>
									<td><?php echo $row['email']; ?></td>
									<td><?php echo $row['role']; ?></td>
									<td><?php if($row['status'] == 1) { echo 'Active'; } else { echo 'Inactive'; } ?></td>
									<td>										
										<a  href="user-edit.php?id=<?php echo $row['id']; ?>" title="edit" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
										<a href="#" title="delete" class="btn btn-danger btn-xs" data-href="user-delete.php?id=<?php echo $row['id']; ?>" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a>  
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