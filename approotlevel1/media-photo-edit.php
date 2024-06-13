<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

	if(empty($_POST['name'])) {
		$valid = 0;
		$error_message .= 'Title can not be empty<br>';
	}


	
    $path = $_FILES['photo']['name'];
    $path_tmp = $_FILES['photo']['tmp_name'];

    if($path!='') {
        $ext = pathinfo( $path, PATHINFO_EXTENSION );
        $file_name = basename( $path, '.' . $ext );
        if( $ext!='jpg' && $ext!='png' && $ext!='jpeg' && $ext!='gif' ) {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file for Featured Photo<br>';
        }
    }


	if($valid == 1) {


		if($path == '') {
			$statement = $pdo->prepare("UPDATE tbl_media SET title=? WHERE id=?");
    		$statement->execute(array($_POST['name'], $_REQUEST['id']));
		}
		
		if($path != '') {
			unlink('../assets/uploads/media/'.$_POST['current_photo']);
            $file_path = 'assets/uploads/media/';
			$final_name = 'media-'.$_REQUEST['id'].'-'.date('dmyHis').'.'.$ext;
        	move_uploaded_file( $path_tmp, '../assets/uploads/media/'.$final_name );

        	$statement = $pdo->prepare("UPDATE tbl_media SET title=?, file_path=?, photo=? WHERE id=?");
    		$statement->execute(array($_POST['name'], $file_path, $final_name, $_REQUEST['id']));
    		$showUrl = BASE_URL.$file_path.$final_name;
		}
		
		
		
		$success_message = 'Media is updated successfully!'; ?>
		
	<?php }
}
?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_media WHERE id=?");
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
		<h1>Edit media</h1>
	</div>
	<div class="content-header-right">
		<a href="media-photo.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_media WHERE id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
	$name            = $row['title'];
	$file_path         = $row['file_path'];
	$photo             = $row['photo'];
	
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

			<?php if(!empty($showUrl)): ?>
			<div class="callout showUrl plr0">
			    <div class="row m-0">
			        <div class="col-md-10 p-0">
            <input type="text" class="area-text" style="height: 37px;text-align: center; width:100%;" value="<?=$showUrl;?>" id="myArea" readonly="">
            </div>
            <div class="col-md-2 text-right copycls p-0">
            <button type="button" title="copy" onclick="myFunction()" class="btn btn-info btn-sm copy-btn"><i class="fa fa-copy" aria-hidden="true"></i></button>
            </div>
            </div>
				
			</div>
			<?php endif; ?>

			<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
				<input type="hidden" name="current_photo" value="<?php echo $photo; ?>">
				<div class="box box-info">

						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Title <span>*</span></label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" class="form-control" name="name" value="<?php echo $name; ?>">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Existing Photo</label>
							<div class="col-sm-9" style="padding-top:5px">
								<img src="<?php echo BASE_URL.$file_path.$photo; ?>" alt="event Photo" style="width:400px;">
							</div>
						</div>
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