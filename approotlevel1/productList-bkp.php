<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>View Product List</h1>
	</div>
	<div class="content-header-right">
		<a href="productList-add.php" class="btn btn-primary btn-sm">Add Product</a>
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
								<th width="10px">SL</th>
								<th width="100px">Thumbnail</th>
								<th width="50px">Name</th>
								<th width="100px">Categories</th>
								<th width="80px">Date</th>
								<th width="70px">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;
							$statement = $pdo->prepare("SELECT
														id, name, categories, file_path, thumbnail_image, created_at
							                           	FROM tbl_product_list
                                                        order by created_at desc
							                           	");
							$statement->execute();
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);		
												
							foreach ($result as $row) {
								$categories = $row['categories'];
								if(!empty($categories)) {
									$sql = "SELECT `name` FROM `tbl_product_category` where id IN($categories)";
									$name = $pdo->query($sql)->fetchAll(PDO::FETCH_COLUMN);
									$categories = implode(',', $name);	
								} else {
									$categories = '';
								}
								
								//print_r($name);
								$i++;
								?>
								<tr>
									<td><?php echo $i; ?></td>
									<td style="width:100px;">
									
									<?php if(!empty($row['thumbnail_image'])) { ?>
										<img src="<?php echo BASE_URL.$row['file_path'].$row['thumbnail_image']; ?>" alt="<?php echo $row['name']; ?>" style="width:30px;height:30px"> 
									<?php } else { ?>
										<img src="<?php echo BASE_URL.'assets/uploads/placeholder.jpg'; ?>" alt="<?php echo $row['name']; ?>" style="width:30px;height:30px">
										<?php } ?>
									</td>	
									<td><?php echo $row['name']; ?></td>
									<td><?php echo $categories; ?></td>
									<td><?php echo date('d-m-Y H:i A', strtotime($row['created_at'])); ?></td>
									<td>
										<button value="<?php echo $row['id']; ?>" class="btn btn-info btn-xs myForm" title="view"><i class="fa fa-eye"></i></button>	
										<a href="productList-add.php?copy_id=<?php echo $row['id']; ?>" title="copy" class="btn btn-success btn-xs"><i class="fa fa-copy"></i></a>									
										<a href="productList-edit.php?id=<?php echo $row['id']; ?>" title="Edit" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                                        <a href="product-images.php?id=<?php echo $row['id']; ?>" title="Add Product Images" class="btn btn-info btn-xs"><i class="fa fa-upload"></i></a>
										<a href="#" class="btn btn-danger btn-xs" title="delete" data-href="productList-delete.php?id=<?php echo $row['id']; ?>" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a>  
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

<div class="modal fade product_view" id="view-data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Product Data</h4>
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
             url: 'ajax/getAjaxData.php',
             type: 'post',
    		 data:{product_id:id},
             success: function(html)
             {
            	$('#recievedData').html(html);
               
             }
            }); 
        });
</script>