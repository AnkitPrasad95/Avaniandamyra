<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

	if(empty($_POST['name'])) {
		$valid = 0;
		$error_message .= 'Name can not be empty<br>';
	}
	
	if(empty($_POST['area_type'])) {
		$valid = 0;
		$error_message .= 'Area Type can not be empty<br>';
	}




	if($valid == 1) {

		$statement = $pdo->prepare("SELECT * FROM tbl_market_area WHERE id=?");
		$statement->execute(array($_REQUEST['id']));
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach($result as $row) {
			$current_name = $row['name'];
		}


		if($_POST['slug'] == '') {
    		// generate slug
    		$temp_string = strtolower($_POST['name']);
    		$slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $temp_string);;
    	} else {
    		$temp_string = strtolower($_POST['slug']);
    		$slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $temp_string);
    	}

    	// if slug already exists, then rename it
		$statement = $pdo->prepare("SELECT * FROM tbl_market_area WHERE slug=? AND name!=?");
		$statement->execute(array($slug,$current_name));
		$total = $statement->rowCount();
		if($total) {
			$slug = $slug.'-1';
		}

			$statement = $pdo->prepare("UPDATE tbl_market_area SET area_type=?, name=?, slug=?, meta_title=?,meta_keyword=?,meta_description=? WHERE id=?");
    		$statement->execute(array($_POST['area_type'],$_POST['name'],$slug,$_POST['meta_title'],$_POST['meta_keyword'],$_POST['meta_description'],$_REQUEST['id']));
    		
		$success_message = 'Market Area is updated successfully!';
	}
}
?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_market_area WHERE id=?");
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
		<h1>Edit Market Area</h1>
	</div>
	<div class="content-header-right">
		<a href="marketArea.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_market_area WHERE id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $area_type         = $row['area_type'];
	$name              = $row['name'];
	$slug              = $row['slug'];
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
				<div class="box box-info">
					<div class="box-body">
						<div class="form-group">
							<label class="copy-head">Note: For Area Location Copy this</label>
							<input type="text" class="area-text" style="height: 37px;text-align: center;" value="[{area}]" id="myArea" readonly="">
							<button type="button" title="copy" onclick="myFunction()" class="btn btn-info btn-sm copy-btn"><i class="fa fa-copy" aria-hidden="true"></i></button> 
						</div>
                        <div class="form-group">
				            <label for="" class="col-sm-2 control-label">Select Area Type <span>*</span></label>
				            <div class="col-sm-9">
				            	<select class="form-control select2" id="area_type" name="area_type">
				            		<option value="">Select Area Type</option>
									<option <?php if($area_type == 'Country') { echo 'selected'; } ?> value="Country">Country</option>
									<option <?php if($area_type == 'State') { echo 'selected'; } ?> value="State">State</option>
									<option <?php if($area_type == 'City') { echo 'selected'; } ?> value="City">City</option>
				            	</select>
				            </div>
				        </div>
				        
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Name <span>*</span></label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" class="form-control" name="name" value="<?php echo $name; ?>" onchange="convertSlugOutput(this, 'gen_url')">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Slug </label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" class="form-control" id="gen_url" name="slug" value="<?php echo $slug; ?>">
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