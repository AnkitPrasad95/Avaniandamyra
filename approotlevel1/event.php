<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>View Event</h1> 
	</div>
	<div class="content-header-right">
		<a href="event-add.php" class="btn btn-primary btn-sm" target="_blank">Add Event</a>
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
								<th width="140">Photo</th>
								
								<th width="100">Name</th>
								<th width="100">Slug</th>
								<th width="100">Venue</th>
								
								<th width="140">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;
							$statement = $pdo->prepare("SELECT
														
														id,
														name,
														slug,
														
														file_path,
														photo,
														venue
							                           	FROM tbl_event
							                           	order by id desc
							                           	");
							$statement->execute();
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
							foreach ($result as $row) {
							    
								$i++;
								?>
								<tr>
									<td><?php echo $i; ?></td>
									<td style="width:150px;"><img src="<?php echo BASE_URL.$row['file_path'].$row['photo']; ?>" alt="<?php echo $row['name']; ?>" style="width:140px;"></td>
									
									<td><?php echo $row['name']; ?></td>
									<td><?php echo $row['slug']; ?></td>
									<td><?php echo $row['venue']; ?></td>
									
									<td>										
										<a href="event-edit.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-xs" target="_blank">Edit</a>
										<a href="#" class="btn btn-danger btn-xs" data-href="event-delete.php?id=<?php echo $row['id']; ?>" data-toggle="modal" data-target="#confirm-delete">Delete</a>  
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