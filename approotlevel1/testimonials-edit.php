<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	//print_r($_POST);
	$valid = 1;

	if(empty($_POST['name'])) {
		$valid = 0;
		$error_message .= 'Name can not be empty<br>';
	}

    $path = $_FILES['photo']['name'];
    $path_tmp = $_FILES['photo']['tmp_name'];

    if($path!='') {
        $ext = pathinfo( $path, PATHINFO_EXTENSION );
        $file_name = basename( $path, '.' . $ext );
        if( $ext!='jpg' && $ext!='png' && $ext!='jpeg' && $ext!='gif' ) {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file<br>';
        }
    }

	if($valid == 1) {

		if($path == '') {
			$statement = $pdo->prepare("UPDATE tbl_testimonial SET name=?, description=?, designation=? WHERE id=?");
    		$statement->execute(array($_POST['name'],$_POST['description'],$_POST['designation'],$_REQUEST['id']));
		} 

		$file_path = "assets/uploads/testimonials/";

		if($path != '') {
            if(!empty($_POST['current_photo'])) {
                unlink('../assets/uploads/testimonials/'.$_POST['current_photo']);
            }

			$final_name = 'testimonial-'.$_REQUEST['id'].date('dmyhis').'.'.$ext;
        	move_uploaded_file( $path_tmp, '../assets/uploads/testimonials/'.$final_name );

        	$statement = $pdo->prepare("UPDATE tbl_testimonial SET name=?, description=?, designation=?, file_path=?, photo=? WHERE id=?");
    		$statement->execute(array($_POST['name'], $_POST['description'],$_POST['designation'], $file_path, $final_name, $_REQUEST['id']));
		}	  
		
		

	    $success_message = 'Testimonial is updated successfully!';
	}
}
?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_testimonial WHERE id=?");
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
		<h1>Edit Testimonial</h1>
	</div>
	<div class="content-header-right">
		<a href="testimonials.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_testimonial WHERE id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
//print_r($result);
foreach ($result as $row) {
    $name  = $row['name'];
	$description  = $row['description'];
	$designation  = $row['designation'];
    $file_path  = $row['file_path'];
	$photo = $row['photo'];
	
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
				<input type="hidden" name="current_photo" value="<?php echo $photo; ?>">
				<div class="box box-info">
					<div class="box-body">
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Name <span>*</span></label>
							<div class="col-sm-6">
								<input type="text" autocomplete="off" class="form-control" name="name" value="<?php echo $name; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Short Description <span>*</span></label>
							<div class="col-sm-9">
								<textarea class="form-control" name="description" style="height:140px;"><?php echo $description; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Dsignation <span>*</span></label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" class="form-control" name="designation" value="<?php echo $designation; ?>">
							</div>
						</div>
				
						<?php if(!empty($photo)) { ?> 
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Existing Photo </label>
							<div class="col-sm-9" style="padding-top:5px">
								<img src="<?php echo BASE_URL.$file_path.$photo; ?>" alt="Slider Photo" style="width:180px;">
							</div>
						</div>
						<?php } ?>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Photo </label>
							<div class="col-sm-6" style="padding-top:5px">
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