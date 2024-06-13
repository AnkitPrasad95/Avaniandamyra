<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

	/*if(empty($_POST['short_description'])) {
		$valid = 0;
		$error_message .= 'Short Description can not be empty<br>';
    }*/
    
    if(empty($_POST['index_value'])) {
		$valid = 0;
		$error_message .= 'index value can not be empty<br>';
    }
    
	$path = $_FILES['photo']['name'];
    $path_tmp = $_FILES['photo']['tmp_name'];

    if($path!='') {
        $ext = pathinfo( $path, PATHINFO_EXTENSION );
        $file_name = basename( $path, '.' . $ext );
        if( $ext!='jpg' && $ext!='png' && $ext!='jpeg' && $ext!='gif' ) {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file for featured photo<br>';
        }
    } else {
    	$valid = 0;
        $error_message .= 'You must have to select a photo for featured photo<br>';
    }

	if($valid == 1) {

        $statement = $pdo->prepare("SHOW TABLE STATUS LIKE 'tbl_slider'");
		$statement->execute();
		$result = $statement->fetchAll();
		foreach($result as $row) {
        $ai_id=$row[10];
        }
        $file_path = "assets/uploads/slider/";
        $final_name = 'slider-'.$ai_id.date('dmYHis').'.'.$ext;
        move_uploaded_file( $path_tmp, '../assets/uploads/slider/'.$final_name );

		$statement = $pdo->prepare("INSERT INTO tbl_slider (short_description,index_value,file_path,photo,created_at) VALUES (?,?,?,?,?)");
		$statement->execute(array($_POST['short_description'], $_POST['index_value'], $file_path,$final_name,date('Y-m-d H:i:s')));
			
		$success_message = 'Slider is added successfully!';

		
		unset($_POST['short_description']);
		unset($_POST['index_value']);
	}
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Add Slider</h1>
	</div>
	<div class="content-header-right">
		<a href="slider.php" class="btn btn-primary btn-sm">View All</a>
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
							<label for="" class="col-sm-2 control-label">Short Description <span>*</span></label>
							<div class="col-sm-9">
								<textarea class="form-control" name="short_description" style="height:140px;"><?php if(isset($_POST['short_description'])){echo $_POST['short_description'];} ?></textarea>
							</div>
						</div>
						
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Index Value <span>*</span></label>
							<div class="col-sm-9">
								<input type="number" class="form-control" name="index_value" value="<?php if(isset($_POST['index_value'])){echo $_POST['index_value'];} ?>">
							</div>
						</div>

                        <div class="form-group">
							<label for="" class="col-sm-2 control-label">Banner Photo <span>*</span></label>
							<div class="col-sm-9" style="padding-top:5px">
								<input type="file" name="photo">(Only jpg, jpeg, gif and png are allowed)
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