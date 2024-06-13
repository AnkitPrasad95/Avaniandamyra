<?php require_once('header.php'); ?>
<section class="content-header">
	<div class="content-header-left">
		<h1>View Collectios</h1>
	</div>
	<div class="content-header-right">
		<a href="manageCollection-add.php" class="btn btn-primary btn-sm">Add Collection</a>
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
								<!-- <th width="140">Title</th> -->
								<th width="140">Collection</th>
								<th width="140">Show on header</th>
								<th width="100">Categories</th>
								<th width="100">Products</th>
								<th width="100">Status</th>
								<th width="140">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 0;
							$statement = $pdo->prepare("SELECT
														*
							                           	FROM manage_collection
                                                        order by id desc
							                           	");
							$statement->execute();
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);

							//print_r($result);die;
							foreach ($result as $row) {
								$collection_id = $row['collection_id'];
								$categories = $row['categories'];
								$products = $row['products'];

								$sql = "SELECT `name` FROM `tbl_collection` where id = $collection_id";
								$name = $pdo->query($sql)->fetchAll(PDO::FETCH_COLUMN);
								$collection = implode(',', $name);

								$sql = "SELECT `name` FROM `tbl_product_category` where id IN($categories)";
								$name = $pdo->query($sql)->fetchAll(PDO::FETCH_COLUMN);
								$categories = implode(',', $name);

								$sql = "SELECT `name` FROM `tbl_product_list` where id IN($products)";
								$name = $pdo->query($sql)->fetchAll(PDO::FETCH_COLUMN);
								$products = implode(',', $name);
								$i++;
							?>
								<tr>
									<td><?php echo $i; ?></td>
									<!-- <td><?php echo $row['title']; ?></td> -->
									<td><?php echo $collection; ?></td>
									<td style="text-align:center"><?php if ($row['show_on_header'] == 1) {
																		echo 'Yes';
																	} else {
																		echo 'No';
																	}  ?></td>
									<td><?php echo $categories; ?></td>
									<td style="text-align:center"><button value="<?php echo $row['id']; ?>" class="btn btn-success btn-xs myForm">View</button></td>
									<td><?php if ($row['status'] == 1) {
											echo "Active";
										} else {
											echo "Inactive";
										} ?></td>
									<td>
										<a href="manageCollection-edit.php?id=<?php echo $row['id']; ?>" title="edit" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
										<a href="#" class="btn btn-danger btn-xs" title="delete" data-href="manageCollection-delete.php?id=<?php echo $row['id']; ?>" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a>
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

<div class="modal fade View-Products-modal" id="view-data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
			url: 'ajax/getAjaxData.php',
			type: 'post',
			data: {
				collection_id: id,
				"acton": "view_manage_collection"
			},
			success: function(html) {
			//	console.log(html);
				$('#recievedData').html(html);

			}
		});
	});


	function show_collection_product(collection_id, category_id) {
		let v = "btn_" + category_id;
		
		$.ajax({
			url: 'ajax/getAjaxData.php',
			type: 'post',
			data: {
				collection_id: collection_id,
				"acton": "view_manage_collection",
				"category_id": category_id
			},
			beforeSend: function() {
				document.getElementById("loading").style.display = "block";
				//debugger;
			},
			success: function(html) {
				document.getElementById("loading").style.display = "none";
				$('#recievedData').html(html);

			}
		});


	}

	
	
</script>