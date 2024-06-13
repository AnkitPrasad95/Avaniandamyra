<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>View Category</h1>
	</div>
	<div class="content-header-right">
		<a href="productCat-add.php" class="btn btn-primary btn-sm">Add Category</a>
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
								<th width="100">Parent Category</th>
								<th width="100">Name</th>
								<th width="100">Slug</th>
								<th width="100">Status</th>
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
														thumbnail_image,
														parent_category, 
														status

							                           	FROM tbl_product_category
                                                        order by id asc
							                           	");
							$statement->execute();
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);		
							//print_r($result);					
							foreach ($result as $row) {
								$statement = $pdo->prepare("SELECT * FROM tbl_product_category where id = ?");
								$statement->execute(array( $row['parent_category']));
								$parentCat = $statement->fetch(PDO::FETCH_OBJ);
								// echo "<pre>";
								// print_r($parentCat);
								// echo "</pre>";
								$i++;
								?>
								<tr>
									<td><?php echo $i; ?></td>
									<!-- <td style="width:150px;"> -->
									<?php //if(!empty($row['thumbnail_image'])) { ?> 
										<!-- <img src="<?php //echo BASE_URL.$row['file_path'].$row['thumbnail_image']; ?>" alt="<?php //echo $row['name']; ?>" style="width:140px;"> -->
										<?php //} else { echo 'NA'; } ?>
									<!-- </td> -->
									<td><?php if(!empty($parentCat)) { echo $parentCat->name; }  else { echo "NA"; }  ?></td>
									<td><?php echo $row['name']; ?></td>
									<td><?php echo $row['slug']; ?></td>
									
									<td><?php if($row['status'] == 1) { echo 'Active'; } else { echo 'Inactive'; } ?></td>	
									<td>										
										<a href="productCat-edit.php?id=<?php echo $row['id']; ?>" title="edit" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                                        <!-- <a href="gallery.php?id=<?php echo $row['id']; ?>" target="_blank" title="Add Images" class="btn btn-success btn-xs">Image</a> -->
										<a href="#" title="delete" class="btn btn-danger btn-xs" data-href="productCat-delete.php?id=<?php echo $row['id']; ?>" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a>  
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