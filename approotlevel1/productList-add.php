<?php require_once('header.php');
$statement = $pdo->prepare("select * from tbl_product_category where parent_category = 0 order by id asc");
$statement->execute();
$categories = $statement->fetchAll(PDO::FETCH_OBJ);

// for copy product
if (isset($_GET['copy_id'])) {

	$statement = $pdo->prepare("SELECT * FROM tbl_product_list WHERE id=?");
	$statement->execute(array($_REQUEST['copy_id']));
	$copyDetals = $statement->fetch(PDO::FETCH_OBJ);
	$data = array($copyDetals->categories, $copyDetals->materials, $copyDetals->sizes,
		$copyDetals->name, $copyDetals->short_description, 0, $copyDetals->remarks, $copyDetals->guest_collection, $copyDetals->latest_collection,
		$copyDetals->meta_title, $copyDetals->meta_keyword, $copyDetals->meta_description, date('Y-m-d H:i:s')
	);
	$date =  date('Y-m-d H:i:s');
	//print_r($data);
	$query ="INSERT INTO tbl_product_list (categories, materials, sizes, name, short_description, price, remarks, 
	guest_collection, latest_collection, meta_title, meta_keyword, meta_description, created_at) 
	VALUES ('$copyDetals->categories', '$copyDetals->materials', '$copyDetals->sizes',
		'$copyDetals->name', '$copyDetals->short_description', 0, '$copyDetals->remarks', $copyDetals->guest_collection, $copyDetals->latest_collection,
		'$copyDetals->meta_title', '$copyDetals->meta_keyword', '$copyDetals->meta_description', '$date')";
	$statement = $pdo->prepare($query);
	// echo "<pre>";
	// print_r($statement); die;
	$statement->execute();
	
	echo "<script>alert('Product Copied successfully.');</script>";
	echo "<script>window.location.href='productList.php';</script>";
	die;
}

if (isset($_POST['form1'])) {
	// $tags = json_decode($_POST['tags']);
	// echo "<pre>";
	// print_r(implode(",", $tags)); 
	// die();


	$valid = 1;

	if (empty($_POST['name'])) {
		$valid = 0;
		$error_message .= 'name can not be empty<br>';
	}

	// if (empty($_POST['short_description'])) {
	// 	$valid = 0;
	// 	$error_message .= 'Short description can not be empty<br>';
	// }

	if (empty($_POST['categories'])) {
		$valid = 0;
		$error_message .= 'Category can not be empty<br>';
	}




	$path = $_FILES['photo']['name'];
	$path_tmp = $_FILES['photo']['tmp_name'];

	if ($path != '') {
		$ext = pathinfo($path, PATHINFO_EXTENSION);
		$file_name = basename($path, '.' . $ext);
		if ($ext != 'jpg' && $ext != 'png' && $ext != 'jpeg' && $ext != 'gif') {
			$valid = 0;
			$error_message .= 'You must have to upload jpg, jpeg, gif or png file for featured photo<br>';
		}
	} else {
		$valid = 0;
		$error_message .= 'You must have to select a photo for featured photo<br>';
	}




	if ($valid == 1) {


		$categories = implode(",", $_POST['categories']);
		if(!empty($_POST['materials'])) {
			$materials = implode(",", $_POST['materials']);
		} else {
			$materials = "";
		}

		if(!empty($_POST['sizes'])) {
			$sizes = implode(",", $_POST['sizes']);
		} else {
			$sizes = "";
		}
		
		

		if (!empty($_POST['guest_collection'])) {
			$guest_collection = $_POST['guest_collection'];
		} else {
			$guest_collection = 0;
		}

		if (!empty($_POST['price'])) {
			$price = $_POST['price'];
		} else {
			$price = 0;
		}

		if (!empty($_POST['latest_collection'])) {
			$latest_collection = $_POST['latest_collection'];
		} else {
			$latest_collection = 0;
		}

		if (isset($_POST['show_on_top']) && $_POST['show_on_top'] == 1) {
			$statement = $pdo->prepare("SELECT max(show_on_top) as max_value FROM tbl_product_list");
			$statement->execute();
			$MaxNumbers = $statement->fetch(PDO::FETCH_OBJ);
			$show_on_top = $MaxNumbers->max_value + 1;
		} else {
			$show_on_top = 0;
		}

		
		//die;
		// getting auto increment id
		$statement = $pdo->prepare("SHOW TABLE STATUS LIKE 'tbl_product_list'");
		$statement->execute();
		$result = $statement->fetchAll();
		foreach ($result as $row) {
			$ai_id = $row[10];
		}

		$file_path = 'assets/uploads/product-list/';
		$thumbnail = 'thumbnail-'.str_replace(' ', '-', $_POST['name']).'.'.$ext;
		move_uploaded_file($path_tmp, '../assets/uploads/product-list/' . $thumbnail);

		$data = array($categories, $materials, $sizes, $_POST['name'], $_POST['slug'], $_POST['short_description'], $price, $_POST['remarks'], $guest_collection, $latest_collection, $show_on_top, $file_path, $thumbnail, $_POST['meta_title'], $_POST['meta_keyword'], $_POST['meta_description'], $_POST['tags'], date('Y-m-d H:i:s'));
		// print_r($data);
		// die;
		$statement = $pdo->prepare("INSERT INTO tbl_product_list (categories, materials, sizes, name, slug, short_description, price, remarks, guest_collection, latest_collection, show_on_top, file_path, thumbnail_image, meta_title, meta_keyword, meta_description, tags, created_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$statement->execute($data);
		echo "<script> alert('Product is added successfully!'); </script>";
		//echo "<script>location.reload();</script>";
		echo "<meta http-equiv='refresh' content='0'>";

		$success_message = 'Product is added successfully!';
		unset($_POST['categories']);
		unset($_POST['materials']);
		unset($_POST['sizes']);
		unset($_POST['name']);
		unset($_POST['slug']);
		unset($_POST['short_description']);
		unset($_POST['price']);
		unset($_POST['remarks']);
		unset($_POST['guest_collection']);
		unset($_POST['latest_collection']);
		unset($_POST['show_on_top']);
		unset($_POST['meta_title']);
		unset($_POST['meta_keyword']);
		unset($_POST['meta_description']);
		unset($_POST['tags']);
	}
}

?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify@3.1.0/dist/tagify.css" />

<section class="content-header">
	<div class="content-header-left">
		<h1>Add Product</h1>
	</div>
	<div class="content-header-right">
		<a href="productList.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>


<section class="content">

	<div class="row">
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
							<label for="" class="col-sm-2 control-label">Name <span>*</span></label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" class="form-control" name="name" value="<?php if (isset($_POST['name'])) {
																													echo $_POST['name'];
																												} ?>" onchange="convertSlugOutput(this, 'gen_url')">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Slug<span>*</span> </label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" class="form-control" name="slug" id="gen_url" value="<?php if (isset($_POST['slug'])) {
																																echo $_POST['slug'];
																															} ?>">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Price </label>
							<div class="col-sm-9">
								<input type="number" autocomplete="off" class="form-control" name="price" value="<?php if (isset($_POST['price'])) {
																														echo $_POST['price'];
																													} ?>">
							</div>
						</div>


						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Short Description <span></span></label>
							<div class="col-sm-9">
								<textarea class="form-control" name="short_description" id=""><?php if (isset($_POST['short_description'])) {
																									echo $_POST['short_description'];
																								} ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Categories <span>*</span></label>
							<div class="col-sm-9">
								<select autocomplete="off" class="form-control select2" name="categories[]" multiple>
									<option value="">Select Category</option>
									<?php foreach ($categories as $categorie) {
										$statement = $pdo->prepare("SELECT * FROM tbl_product_category where parent_category = ?");
										$statement->execute(array($categorie->id));
										$childrens = $statement->fetchAll(PDO::FETCH_OBJ);
									?>
										<option value="<?= $categorie->id; ?>"><?= $categorie->name; ?></option>
										<?php foreach ($childrens as $children) { ?>
											<option value="<?= $children->id; ?>">-- <?= $children->name; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Sizes </label>
							<div class="col-sm-9">
								<select autocomplete="off" class="form-control select2" name="sizes[]" multiple>
									<option value="">Select Size</option>
									<?php
									$statement = $pdo->prepare("select * from tbl_size order by id desc");
									$statement->execute();
									$sizeList = $statement->fetchAll(PDO::FETCH_OBJ);
									if (!empty($sizeList)) {
										foreach ($sizeList as $sizeListRow) {
									?>
											<option value="<?= $sizeListRow->id; ?>"><?= $sizeListRow->size_name; ?></option>
									<?php }
									} ?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Materials </label>
							<div class="col-sm-9">
								<select autocomplete="off" class="form-control select2" name="materials[]" multiple>
									<option value="">Select Materials</option>
									<?php
									$statement = $pdo->prepare("select * from 	tbl_material order by id desc");
									$statement->execute();
									$matList = $statement->fetchAll(PDO::FETCH_OBJ);
									if (!empty($matList)) {
										foreach ($matList as $matListRow) {
									?>
											<option value="<?= $matListRow->id; ?>"><?= $matListRow->name; ?></option>
									<?php }
									} ?>
								</select>
							</div>
						</div>


						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Remarks <span></span></label>
							<div class="col-sm-9">
								<textarea class="form-control" style="height:100px" name="remarks" id=""><?php if (isset($_POST['remarks'])) {
																										echo $_POST['remarks'];
																									} ?></textarea>
							</div>
						</div>


						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Thumbnail <span>*</span></label>
							<div class="col-sm-9" style="padding-top:5px">
								<input type="file" name="photo">(Only jpg, jpeg, gif and png are allowed)
							</div>
						</div>

						<!-- <div class="form-group">
							<label for="" class="col-sm-2 control-label">Guest Collection </label>
							<div class="col-sm-9">
								<input type="checkbox" name="guest_collection" value="1">
							</div>
						</div> -->

						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Latest Collection </label>
							<div class="col-sm-9">
								<input type="checkbox" name="latest_collection" value="1">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Show on Top</label>
							<div class="col-sm-9">
								<input type="checkbox" <?php if (isset($_POST['show_on_top']) && $_POST['show_on_top'] == 1) { echo "checked"; } ?> name="show_on_top" value="1">
							</div>
						</div>

						<h3 class="seo-info">SEO Information</h3>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Meta Title </label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" class="form-control" name="meta_title" value="<?php if (isset($_POST['meta_title'])) {
																														echo $_POST['meta_title'];
																													} ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Meta Keywords </label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" class="form-control" name="meta_keyword" value="<?php if (isset($_POST['meta_keyword'])) {
																															echo $_POST['meta_keyword'];
																														} ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Meta Description </label>
							<div class="col-sm-9">
								<textarea class="form-control" name="meta_description" style="height:140px;"><?php if (isset($_POST['meta_description'])) {
																													echo $_POST['meta_description'];
																												} ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Tags </label>
							<div class="col-sm-9">
								<input type="text" name='tags' value="" />
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

<?php require_once('footer.php'); ?>