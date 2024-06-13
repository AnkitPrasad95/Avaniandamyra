<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;
	
	if(empty($_POST['area_type'])) {
		$valid = 0;
		$error_message .= 'Area Type can not be empty<br>';
	}

	if(empty($_POST['name'])) {
		$valid = 0;
		$error_message .= 'Name can not be empty<br>';
	}

	

		if($_POST['slug'] == '') {
    		// generate slug
    		$temp_string = strtolower($_POST['name']);
    		$slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $temp_string);
    	} else {
    		$temp_string = strtolower($_POST['slug']);
    		$slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $temp_string);
    	}

 

		$statement = $pdo->prepare("INSERT INTO tbl_market_area (area_type, name,slug,meta_title,meta_keyword,meta_description,created_at) VALUES (?,?,?,?,?,?,?)");
		$statement->execute(array($_POST['area_type'], $_POST['name'],$slug,$_POST['meta_title'],$_POST['meta_keyword'],$_POST['meta_description'],date('Y-m-d H:i:s')));
			
		$success_message = 'Market Area is added successfully!';
		
        unset($_POST['area_type']);
		unset($_POST['name']);
		unset($_POST['slug']);
		unset($_POST['meta_title']);
		unset($_POST['meta_keyword']);
		unset($_POST['meta_description']);

	
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Add Market Area</h1>
	</div>
	<div class="content-header-right">
		<a href="marketArea.php" class="btn btn-primary btn-sm">View All</a>
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
							<label class="copy-head">Note: For Area Location Copy this</label>
							<input type="text" class="area-text" style="height: 37px;text-align: center;" value="[{area}]" id="myArea" readonly="">
							<button type="button" title="copy" onclick="myFunction()" class="btn btn-info btn-sm copy-btn"><i class="fa fa-copy" aria-hidden="true"></i></button> 
						</div>
						
						<div class="form-group">
				            <label for="" class="col-sm-2 control-label">Select Area Type <span>*</span></label>
				            <div class="col-sm-9">
				            	<select class="form-control select2" id="area_type" name="area_type">
				            		<option value="">Select Area Type</option>
									<option value="Country">Country</option>
									<option value="State">State</option>
									<option value="City">City</option>
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
								<input type="text" autocomplete="off" class="form-control" name="slug" id="gen_url" value="<?php if(isset($_POST['slug'])){echo $_POST['slug'];} ?>">
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