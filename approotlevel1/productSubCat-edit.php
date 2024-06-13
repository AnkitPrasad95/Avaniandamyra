<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

	if(empty($_POST['cat_id'])) {
		$valid = 0;
		$error_message .= 'category can not be empty<br>';
	}

	if(empty($_POST['name'])) {
		$valid = 0;
		$error_message .= 'Name can not be empty<br>';
	}


    $path = $_FILES['thumbnail_image']['name'];
    $path_tmp = $_FILES['thumbnail_image']['tmp_name'];

    if($path!='') {
        $ext = pathinfo( $path, PATHINFO_EXTENSION );
        $file_name = basename( $path, '.' . $ext );
        if( $ext!='jpg' && $ext!='png' && $ext!='jpeg' && $ext!='gif' ) {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file for Featured Photo<br>';
        }
    }


	if($valid == 1) {

		
		$statement = $pdo->prepare("UPDATE tbl_product_sub_cat SET cat_id=?, name=?, slug=?, meta_title=?, meta_keyword=?, meta_description=? WHERE id=?");
		$res = $statement->execute(array($_POST['cat_id'],$_POST['name'], $_POST['slug'], $_POST['meta_title'],$_POST['meta_keyword'],$_POST['meta_description'],$_REQUEST['id']));

		if($path != '') {
			if(!empty($_POST['current_photo'])) {
				unlink('../assets/uploads/product/'.$_POST['current_photo']);
			}
            $file_path = 'assets/uploads/product/';
		    $final_name = 'sub_cat-'.$_REQUEST['id'].'.'.$ext;
        	move_uploaded_file( $path_tmp, '../assets/uploads/product/'.$final_name );

        	$statement = $pdo->prepare("UPDATE tbl_product_sub_cat SET file_path=?,  thumbnail_image=? WHERE id=?");
    		$res = $statement->execute(array($file_path, $final_name, $_REQUEST['id']));
		}
		
		$success_message = 'Product Sub Category is updated successfully!';
		
		
	}
}
?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_product_sub_cat WHERE id=?");
	$statement->execute(array($_REQUEST['id']));
	$total = $statement->rowCount();
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	if( $total == 0 ) {
		header('location: logout.php');
		exit;
	}
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Edit Sub Category</h1>
	</div>
	<div class="content-header-right">
		<a href="productSubCat.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_product_sub_cat WHERE id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
	$cat_id              = $row['cat_id'];
	$name              = $row['name'];
    $slug              = $row['slug'];
    $file_path         = $row['file_path'];
	$thumbnail_image   = $row['thumbnail_image'];
	$meta_title        = $row['meta_title'];
	$meta_keyword      = $row['meta_keyword'];
	$meta_description  = $row['meta_description'];
}
?>

<section class="content">

	<div class="row">
		<div class="col-md-12">

			<?php if($error_message): ?>
			<div class="callout callout-danger">
				<p>
				<?php echo $error_message; ?>
				</p>
			</div>
			<?php endif; ?>

			<?php if($success_message): ?>
			<div class="callout callout-success">
				<p><?php echo $success_message; ?></p>
			</div>
			<?php endif; ?>

			<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
				<input type="hidden" name="current_photo" value="<?php echo $thumbnail_image; ?>">
				<div class="box box-info">
					<div class="box-body">
					<div class="form-group">
				            <label for="" class="col-sm-2 control-label">Select Category <span>*</span></label>
				            <div class="col-sm-6">
				            	<select class="form-control select2" name="cat_id">
				            		<option value="">Select a category</option>
				            		<?php
						            	$i=0;
						            	$statement = $pdo->prepare("SELECT * FROM tbl_product_category ORDER BY name ASC");
						            	$statement->execute();
						            	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
						            	foreach ($result as $row) {
						            		?>
											<option <?php if($cat_id == $row['id']) { echo 'selected';} ?> value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
						            		<?php
						            	}
					            	?>
				            	</select>
				            </div>
				        </div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Name <span>*</span></label>
							<div class="col-sm-6">
								<input type="text" autocomplete="off" class="form-control" name="name" value="<?php echo $name; ?>" onchange="convertSlugOutput(this, 'gen_url')">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Slug </label>
							<div class="col-sm-6">
								<input type="text" autocomplete="off" class="form-control" id="gen_url" name="slug" value="<?php echo $slug; ?>">
							</div>
						</div>
						<?php if(!empty($thumbnail_image)) { ?> 
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Existing Photo</label>
							<div class="col-sm-9" style="padding-top:5px">
								<img src="<?php echo BASE_URL.$file_path.$thumbnail_image; ?>" alt="Service Photo" style="width:400px;">
							</div>
						</div>
						<?php } ?>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">thumbnail_image </label>
							<div class="col-sm-6" style="padding-top:5px">
								<input type="file" name="thumbnail_image">(Only jpg, jpeg, gif and png are allowed)
							</div>
						</div>
						
						<h3 class="seo-info">SEO Information</h3>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Meta Title </label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" class="form-control" name="meta_title" value="<?php echo $meta_title; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Meta Keywords </label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" class="form-control" name="meta_keyword" value="<?php echo $meta_keyword; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Meta Description </label>
							<div class="col-sm-9">
								<textarea class="form-control" name="meta_description" style="height:140px;"><?php echo $meta_description; ?></textarea>
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