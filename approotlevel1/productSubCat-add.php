<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

	if(empty($_POST['cat_id'])) {
		$valid = 0;
		$error_message .= 'Category can not be empty<br>';
    }

	if(empty($_POST['name'])) {
		$valid = 0;
		$error_message .= 'name not be empty<br>';
    }

	$path = $_FILES['thumbnail_image']['name'];
    $path_tmp = $_FILES['thumbnail_image']['tmp_name'];

    if($path!='') {
        $ext = pathinfo( $path, PATHINFO_EXTENSION );
        $file_name = basename( $path, '.' . $ext );
        if( $ext!='jpg' && $ext!='png' && $ext!='jpeg' && $ext!='gif' ) {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file for featured photo<br>';
        }
    } 


	
	if($valid == 1) {

		$statement = $pdo->prepare("SHOW TABLE STATUS LIKE 'tbl_product_sub_cat'");
		$statement->execute();
		$result = $statement->fetchAll();
		foreach($result as $row) {
			$ai_id=$row[10];
		}

		if($_POST['slug'] == '') {
    		// generate slug
    		$temp_string = strtolower($_POST['name']);
    		$slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $temp_string);
    	} else {
    		$temp_string = strtolower($_POST['slug']);
    		$slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $temp_string);
    	}

		$file_path = 'assets/uploads/product/';
		if(!empty($ext)) {
			$final_name = 'sub_cat-'.$ai_id.'-'.date('dmyhis').'.'.$ext;
        	move_uploaded_file( $path_tmp, '../assets/uploads/product/'.$final_name );
		} else {
			$final_name = '';
        
		}

		$statement = $pdo->prepare("INSERT INTO tbl_product_sub_cat (cat_id, name, slug, file_path, thumbnail_image, meta_title, meta_keyword, meta_description, created_at) VALUES (?,?,?,?,?,?,?,?,?)");
		$statement->execute(array($_POST['cat_id'], $_POST['name'], $slug, $file_path, $final_name, $_POST['meta_title'], $_POST['meta_keyword'], $_POST['meta_description'], date('Y-m-d H:i:s')));
			
		$success_message = 'Sub Category is added successfully!';
		unset($_POST['cat_id']);
		unset($_POST['name']);
		unset($_POST['slug']);
		unset($_POST['meta_title']);
		unset($_POST['meta_keyword']);
		unset($_POST['meta_description']);
	}
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Add Sub Category</h1>
	</div>
	<div class="content-header-right">
		<a href="productSubCat.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>


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
				<div class="box box-info">
					<div class="box-body">
					<div class="form-group">
				            <label for="" class="col-sm-2 control-label">Select category <span>*</span></label>
				            <div class="col-sm-9">
				            	<select class="form-control select2" name="cat_id">
				            		<option value="">Select a category</option>
				            		<?php
						            	$i=0;
						            	$statement = $pdo->prepare("SELECT * FROM tbl_product_category ORDER BY name ASC");
						            	$statement->execute();
						            	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
						            	foreach ($result as $row) {
						            		?>
											<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
						            		<?php
						            	}
					            	?>
				            	</select>
				            </div>
				        </div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Name <span>*</span></label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" class="form-control" name="name" value="<?php if(isset($_POST['name'])){echo $_POST['name'];} ?>" onchange="convertSlugOutput(this, 'gen_url')">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Slug </label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" id="gen_url" class="form-control" name="slug" value="<?php if(isset($_POST['slug'])){echo $_POST['slug'];} ?>">
							</div>
						</div>
                       
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Thumbnail <span>*</span></label>
							<div class="col-sm-9" style="padding-top:5px">
								<input type="file" name="thumbnail_image">(Only jpg, jpeg, gif and png are allowed)
							</div>
						</div>

						<h3 class="seo-info">SEO Information</h3>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Meta Title </label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" class="form-control" name="meta_title" value="<?php if(isset($_POST['meta_title'])){echo $_POST['meta_title'];} ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Meta Keywords </label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" class="form-control" name="meta_keyword" value="<?php if(isset($_POST['meta_keyword'])){echo $_POST['meta_keyword'];} ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Meta Description </label>
							<div class="col-sm-9">
								<textarea class="form-control" name="meta_description" style="height:140px;"><?php if(isset($_POST['meta_description'])){echo $_POST['meta_description'];} ?></textarea>
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