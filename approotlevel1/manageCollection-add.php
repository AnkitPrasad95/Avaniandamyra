<?php require_once('header.php');
$statement = $pdo->prepare("select * from tbl_product_category where parent_category = 0 order by id asc");
$statement->execute();
$categories = $statement->fetchAll(PDO::FETCH_OBJ);

if (isset($_POST['form1'])) {

	$valid = 1;

	if (empty($_POST['collection_id'])) {
		$valid = 0;
		$error_message .= 'collection can not be empty<br>';
	}

	if (empty($_POST['products'])) {
		$valid = 0;
		$error_message .= 'Short description can not be empty<br>';
	}

	$statement = $pdo->prepare("select * from manage_collection where collection_id = ?");
	$statement->execute(array($_POST['collection_id']));
	$is_created_collection = $statement->rowCount();
	if ($is_created_collection > 0) {
		$valid = 0;
		$error_message .= 'You can not create duplicate collection.<br>';
	}


	if ($valid == 1) {

		if (!empty($_POST['show_on_header'])) {
			$show_on_header = $_POST['show_on_header'];
		} else {
			$show_on_header = 0;
		}


		$categories = implode(",", $_POST['categories']);
		$products = implode(",", $_POST['products']);


		$statement = $pdo->prepare("INSERT INTO manage_collection (collection_id, categories, products, title, show_on_header, status, created_at) VALUES (?,?,?,?,?,?,?)");
		$statement->execute(array($_POST['collection_id'], $categories, $products, '', $show_on_header, $_POST['status'], date('Y-m-d H:i:s')));

		// update tbl_collection
		$statement = $pdo->prepare("update tbl_collection set show_on_header =? where id =?");
		$statement->execute(array($show_on_header, $_POST['collection_id']));

		$success_message = 'Collection is added successfully!';
		unset($_POST['collection_id']);
		unset($_POST['categories']);
		unset($_POST['products']);
		unset($_POST['status']);
		unset($_POST['show_on_header']);
	}
}

?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Add Collection</h1>
	</div>
	<div class="content-header-right">
		<a href="manageCollection.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>


<section class="content">

	<div class="row" id="showProductModal">
		<div class="col-md-12">

			<?php if ($error_message) : ?>
				<div class="callout callout-danger">
					<p>
						<?php echo $error_message; ?>
					</p>
				</div>
			<?php endif; ?>

			<?php if ($success_message) : ?>
				<div class="callout callout-success">
					<p><?php echo $success_message; ?></p>
				</div>
			<?php endif; ?>

			<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
				<div class="box box-info">
					<div class="box-body">
						<div class="form-group">
							<!-- <label for="" class="col-sm-2 control-label">Title <span>*</span></label>
                        <div class="col-sm-9">
                            <input type="text" autocomplete="off" class="form-control" name="title" value="" required>
                        </div>
                    </div>     -->
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Collections </label>
								<div class="col-sm-6">
									<select autocomplete="off" class="form-control select2" id="collection_id" name="collection_id" required>
										<option value="">Select Collection</option>
										<?php
										$statement = $pdo->prepare("select * from tbl_collection order by id asc");
										$statement->execute();
										$collectionList = $statement->fetchAll(PDO::FETCH_OBJ);
										if (!empty($collectionList)) {
											foreach ($collectionList as $Row) {
										?>
												<option value="<?= $Row->id; ?>"><?= $Row->name; ?></option>
										<?php }
										} ?>
									</select>
								</div>
								<label for="" class="col-sm-2 control-label">Visible on menu header </label>
								<div class="col-sm-2">
									<input type="checkbox" class="headermenu" name="show_on_header" id="show_on_header" value="1">
								</div>
							</div>

							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Categories <span>*</span></label>
								<div class="col-sm-9">
									<select autocomplete="off" class="form-control select2 categories" id="categories" name="categories[]" multiple>
										<option value="">Select Category</option>
										<?php foreach ($categories as $categorie) { ?>
											<option value="<?= $categorie->id; ?>"><?= $categorie->name; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>


							<!-- <div class="form-group">
								<label for="" class="col-sm-2 control-label">Products </label>
								<div class="col-sm-9">
									<select autocomplete="off" id="products" class="form-control select2" id="products" name="products[]" multiple required>
										<option value="">Select Products</option>
										<?php
										$statement = $pdo->prepare("select * from tbl_product_list order by id desc");
										$statement->execute();
										$productList = $statement->fetchAll(PDO::FETCH_OBJ);
										if (!empty($productList)) {
											foreach ($productList as $productListRow) {
										?>
												<option value="<?= $productListRow->id; ?>"><?= $productListRow->name; ?></option>
										<?php }
										} ?>
									</select>
								</div>
							</div> -->

							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Status <span>*</span></label>
								<div class="col-sm-9">
									<select class="form-control select2" id="status" name="status">
										<option value="1">Active</option>
										<option value="0">Inactive</option>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label for="" class="col-sm-2 control-label"></label>
								<div class="col-sm-6">
									<button type="button" class="btn btn-success pull-left crate_collection" name="form1">Submit</button>
								</div>
							</div>
						</div>
					</div>
			</form>
		</div>
	</div>

</section>
<div class="modal fade View-Products-modal" id="manage-product-view-data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Products</h4>
			</div>
			<div class="modal-body" id="recievedProductData">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

			</div>
		</div>
	</div>
</div>
<?php require_once('footer.php'); ?>

<script>
	$("#showProductModal").on("change", ".categories", function() {
		var cat_ids = $(this).val();
		var Exist_products = document.querySelectorAll('input[name="product_id[]"]:checked');
		var aIds = [];
		for (var x = 0, l = Exist_products.length; x < l; x++) {
			aIds.push(Exist_products[x].value);

		}

		var str = aIds.join(',');
		if (cat_ids != null) {
			var dataString = "cat_ids=" + cat_ids + '&products=' + str + '&action=add_product_modal';
			$.ajax({
				url: 'ajax/getAjaxData.php',
				type: 'post',
				data: dataString,
				success: function(html) {
					$('#manage-product-view-data').modal('show')
					$('#recievedProductData').html(html);

				}
			});
		}

	});

	$(document).on("change", ".check-all", function() {
		//localStorage.clear();
		if (this.checked) {
			// Iterate each checkbox
			$('.check-one:checkbox').each(function() {
				this.checked = true;
			});
		} else {
			$('.check-one:checkbox').each(function() {
				this.checked = false;
			});
		}
		//console.log(localStorage.getItem('product_ids'));
	});

	function bulk_product() {
		//window.localStorage.removeItem('product_ids');

		var all_location_id = document.querySelectorAll('input[name="product_id[]"]:checked');

		var aIds = [];
		for (var x = 0, l = all_location_id.length; x < l; x++) {
			//aIds.push(all_location_id[x].value);
			var procuctObject = new Object();
			procuctObject.id = all_location_id[x].value;
			var retrievedObject = null;
			if (localStorage) {
				retrievedObject = localStorage.getItem('product_ids');
				//alert(retrievedObject);
			} else {
				alert("Error: This browser is still not supported; Please use google chrome!");
			}
			var parsedArray = null;

			if (retrievedObject) {
				parsedArray = JSON.parse(retrievedObject);
			}

			if (parsedArray == null) {
				parsedArray = [];
			}

			var found = false;

			if (parsedArray.length == 0) {
				found = true;
			} else {
				for (var i = 0; i < parsedArray.length; i++) {
					if (parsedArray[i].id == procuctObject.id) {
						found = false;
						break;
					} else {
						found = true;
					}
				}
			}
			if (found == true) {
				var cartArrayCount = parsedArray.push(procuctObject);
			}
			localStorage.setItem('product_ids', JSON.stringify(parsedArray));
		}

		console.log(localStorage.getItem('product_ids'));

	}

	$(document).on("click", ".crate_collection", function() {
		var collection_id = $('#collection_id').val();
		if (collection_id == '') {
			alert('Collection can not empty.');
			return false;
		}
		var cat_ids = $('#categories').val();
		if (cat_ids == null) {
			alert('Categories can not empty.');
			return false;
		}
		var show_on_header = document.querySelector('#show_on_header').checked;
		var status = $('#status').val();
		var product_ids = localStorage.getItem('product_ids');

		if (product_ids == null) {
			alert('Products can not empty.');
			return false;
		}
		if (show_on_header == false) {
			show_on_header = '';
		} else {
			show_on_header = 1;
		}

		$.ajax({
			url: 'ajax/getAjaxData.php',
			cache: false,
			type: 'post',
			data: {
				action: 'save_collection',
				'col_id': collection_id,
				'cate_id': cat_ids,
				'products': product_ids,
				'status': status,
				'show_on_header': show_on_header
			},
			success: function(response) {
				console.log(response);
				if (response == 'Collection_added_successfully') {
					window.localStorage.removeItem('product_ids');
					alert('Collection added successfully');
					window.location.href = 'manageCollection.php';
				} else {
					window.localStorage.removeItem('product_ids');
					alert(response);
					window.location.href = 'manageCollection.php';

				}

			}
		});
	});
</script>