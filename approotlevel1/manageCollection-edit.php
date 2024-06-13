<?php require_once('header.php');

$statement = $pdo->prepare("select * from tbl_product_category where parent_category = 0 order by id asc");
$statement->execute();
$categoryList = $statement->fetchAll(PDO::FETCH_OBJ);


if (isset($_POST['form1'])) {

	$valid = 1;

	if (empty($_POST['collection_id'])) {
		$valid = 0;
		$error_message .= 'collection can not be empty<br>';
	}

	if (empty($_POST['categories'])) {
		$valid = 0;
		$error_message .= 'Categories can not be empty<br>';
	}

	if ($valid == 1) {

		if (!empty($_POST['show_on_header'])) {
			$show_on_header = $_POST['show_on_header'];
		} else {
			$show_on_header = 0;
		}

		$categories = implode(",", $_POST['categories']);
		$statement = $pdo->prepare("UPDATE manage_collection SET collection_id=?, categories=?, show_on_header=?, status=?, title=? WHERE id=?");
		$res = $statement->execute(array($_POST['collection_id'], $categories, $show_on_header, $_POST['status'], '', $_REQUEST['id']));

		$statement = $pdo->prepare("update tbl_collection set show_on_header =? where id =?");
		$statement->execute(array($show_on_header, $_POST['collection_id']));
		echo "<script>alert('Collection is updated successfully');</script>";
		//echo "<script>window.location.href='".$_SERVER['REQUEST_URI']."';</script>";
		echo "<script>window.location.href='manageCollection.php';</script>";
		
	}
}
?>

<?php
if (!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM manage_collection WHERE id=?");
	$statement->execute(array($_REQUEST['id']));
	$total = $statement->rowCount();
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	if ($total == 0) {
		header('location: logout.php');
		exit;
	}
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Edit Manage Collection</h1>
	</div>
	<div class="content-header-right">
		<a href="manageCollection.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>

<?php
$statement = $pdo->prepare("SELECT * FROM manage_collection WHERE id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
	$categories            = explode(",", $row['categories']);
	$products              = explode(",", $row['products']);
	$title       = $row['title'];
	$status       = $row['status'];
	$collection_id       = $row['collection_id'];
	$show_on_header       = $row['show_on_header'];
}

$data = array();
foreach ($categories as $cat_id) {
	$statement = $pdo->prepare("SELECT id, name FROM tbl_product_list where categories LIKE ? GROUP BY id ORDER BY name ASC");
	$statement->execute(array("%$cat_id%"));
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	array_push($data, $result);
}

// $data = array_unique($data);
// print_r($data); die;
$result = array();
foreach ($data as $array) {
	$result = array_merge($result, $array);
}

$uniqueArry = array();

foreach ($result as $val) { //Loop1 

	foreach ($uniqueArry as $uniqueValue) { //Loop2 

		if ($val == $uniqueValue) {
			continue 2; // Referring Outer loop (Loop 1)
		}
	}
	$uniqueArry[] = $val;
}

?>

<section class="content">

	<div class="row" id="product_edit">
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
				<input type="hidden" name="current_photo" value="<?php echo $thumbnail_image; ?>">
				<div class="box box-info">
					<div class="box-body">

						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Collections </label>
							<div class="col-sm-6">
								<select autocomplete="off" class="form-control select2" name="collection_id" required>
									<!-- <option value="">Select Collection</option> -->
									<?php
									$statement = $pdo->prepare("select * from tbl_collection where id =?");
									$statement->execute(array($collection_id));
									$collectionList = $statement->fetchAll(PDO::FETCH_OBJ);
									if (!empty($collectionList)) {
										foreach ($collectionList as $Row) {
									?>
											<option <?php if ($collection_id == $Row->id) echo "selected";  ?> value="<?= $Row->id; ?>"><?= $Row->name; ?></option>
									<?php }
									} ?>
								</select>
							</div>
							<label for="" class="col-sm-2 control-label">Visible on menu header </label>
							<div class="col-sm-2">
								<input type="checkbox" class="headermenu" <?php if ($show_on_header == 1) echo 'checked="checked"'; ?> name="show_on_header" value="1">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Categories <span>*</span></label>
							<div class="col-sm-9">
								<select autocomplete="off" class="form-control select2 categories"  id="categories" name="categories[]" multiple>
									<option value="">Select Category</option>
									<?php foreach ($categoryList as $category) { ?>
										<option <?php if (in_array($category->id, $categories)) echo "selected";  ?> value="<?= $category->id; ?>"><?= $category->name; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Products <span>*</span></label>
							<div class="col-sm-9">
								<?php foreach ($categoryList as $category) { 
									if (in_array($category->id, $categories)) {
									?>
								<a type="botton" onclick="showProduct(<?php echo $_REQUEST['id']; ?>, <?= $category->id; ?>);" value="<?php echo $_REQUEST['id']; ?>" title="Show Product Data" id="show_modal" class="btn btn-primary btn-xs"><?= $category->name; ?></a>
								<?php } } ?>
							</div>
						</div>

						<!-- <div class="form-group">
							<label for="" class="col-sm-2 control-label">Products </label>
							<div class="col-sm-9">
								<select autocomplete="off" id="products" class="form-control select2" name="products[]" multiple required>
									<option value="">Select Products</option>
									<?php

									if (!empty($uniqueArry)) {
										foreach ($uniqueArry as $productListRow) {
									?>
											<option <?php if (in_array($productListRow['id'], $products)) echo "selected";  ?> value="<?= $productListRow['id']; ?>"><?= $productListRow['name']; ?></option>
									<?php }
									} ?>
								</select>
							</div>
						</div> -->

						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Status <span>*</span></label>
							<div class="col-sm-9">
								<select class="form-control select2" name="status">
									<option <?php if ($status == 1) echo "selected";  ?> value="1">Active</option>
									<option <?php if ($status == 0) echo "selected";  ?> value="0">Inactive</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label"></label>
							<div class="col-sm-6">
								<button type="submit" class="btn btn-success pull-left" name="form1">Submit</button>
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
	$("#product_edit").on("change", "#categories", function() {
		//alert($(this).val());
		var cat_ids = $(this).val();
		var manage_collection_id = <?=$_REQUEST['id'];?>;
		//alert(cat_ids);
		//var Exist_products = document.querySelectorAll('input[name="product_id[]"]:checked');
	
		if (cat_ids != null) {
			var dataString = "action=" + "update_collection_categories" + "&update_cat_ids=" + cat_ids +"&manage_collection_id=" + manage_collection_id;
			$.ajax({
				url: 'ajax/getAjaxData.php',
				type: 'post',
				data: dataString,
				success: function(response) {
					console.log(response);
					if (response == 'updated') {
						alert('Category updated successfully');
						location.reload();
					} else {
						alert(response);
						location.reload();
					}

				}
			});
		}

	});
	function showProduct(manage_col_id, cat_id) {
		//var manage_col_id = $(this).val();

		
			var dataString = "manage_col_id=" + manage_col_id + '&action=' + 'showProductModal' + '&cat_id='+cat_id;
			console.log(dataString);
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

	function AddNewProduct(manage_col_id, cat_id) {
		//var manage_col_id = $(this).val();

		
			var dataString = "manage_col_id=" + manage_col_id + '&action=' + 'AddNewProduct' + '&cat_id='+cat_id;
			console.log(dataString);
			$.ajax({
				url: 'ajax/getAjaxData.php',
				type: 'post',
				data: dataString,
				success: function(html) {
					$('#recievedProductData').html(html);

				}
			});
	

	}

	$(document).on("change", ".check-all", function() {

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


	function update_bulk_product(collection_id, categoty_id){
		var all_location_id = document.querySelectorAll('input[name="product_id[]"]:checked');

		var aIds = [];
		for (var x = 0, l = all_location_id.length; x < l; x++) {
			aIds.push(all_location_id[x].value);
		}
		//alert(aIds);
		if (aIds == '') {
			alert('Products can not empty.');
			return false;
		}
		$.ajax({
			url: 'ajax/getAjaxData.php',
			cache: false,
			type: 'post',
			data: {
				action: 'update_collection',
				'col_id': collection_id,
				'categoty_id': categoty_id,
				'products': aIds
			},
			success: function(response) {
				console.log(response);
				if (response == 'updated') {
					alert('Collection updated successfully');
					location.reload();
				} else {
					alert(response);
					location.reload();
				}

			}
		});
		console.log(aIds);
	}


</script>