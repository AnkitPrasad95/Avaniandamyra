<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>View static content</h1> 
	</div>
	<div class="content-header-right">
		<a href="static-content-add.php" class="btn btn-primary btn-sm" target="_blank">Add static content</a>
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
								<th width="140">Page ID</th>
								<th width="100">Page Name</th>
                                <th width="100">Meta Title</th>
								<th width="140">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;
							$statement = $pdo->prepare("SELECT
                                                        *
							                           	FROM tbl_static_content
							                           	order by page_id desc
							                           	");
							$statement->execute();
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);	
                            //print_r($result);						
							foreach ($result as $row) {
								$i++;
								?>
								<tr>
									<td><?php echo $i; ?></td>
									
									<td><?php echo $row['page_id']; ?></td>
									<td><?php echo $row['page_name']; ?></td>
                                    <td><?php echo $row['meta_title']; ?></td>
									<td>										
										<a href="static-content-edit.php?id=<?php echo $row['page_id']; ?>" class="btn btn-primary btn-xs" target="_blank">Edit</a>
										<a href="#" class="btn btn-danger btn-xs" data-href="static-content-delete.php?id=<?php echo $row['page_id']; ?>" data-toggle="modal" data-target="#confirm-delete">Delete</a>  
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