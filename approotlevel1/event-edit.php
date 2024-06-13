<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;
	

	if(empty($_POST['name'])) {
		$valid = 0;
		$error_message .= 'Name can not be empty<br>';
	}
	
	if(empty($_POST['venue'])) { 
		$valid = 0;
		$error_message .= 'Venue can not be empty<br>';
	}
	
	if(empty($_POST['venue_date'])) { 
		$valid = 0;
		$error_message .= 'Venue Date can not be empty<br>';
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

    $path1 = $_FILES['banner']['name'];
    $path_tmp1 = $_FILES['banner']['tmp_name'];

    if($path1!='') {
        $ext1 = pathinfo( $path1, PATHINFO_EXTENSION );
        $file_name1 = basename( $path1, '.' . $ext1 );
        if( $ext1!='jpg' && $ext1!='png' && $ext1!='jpeg' && $ext1!='gif' ) {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file for Banner<br>';
        }
    }

	if($valid == 1) {
	    
	    $eventDate = $_POST['venue_date'];

		$statement = $pdo->prepare("SELECT * FROM tbl_event WHERE id=?");
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
		$statement = $pdo->prepare("SELECT * FROM tbl_event WHERE slug=? AND name!=?");
		$statement->execute(array($slug,$current_name));
		$total = $statement->rowCount();
		if($total) {
			$newSlug = $total + 1;
			$slug = $slug.'-'.$newSlug;
		}

		if($path == '' && $path1 == '') {
			$statement = $pdo->prepare("UPDATE tbl_event SET  name=?, slug=?,  description=?, description2=?,  venue=?, venue_date=?, meta_title=?, meta_keyword=?, meta_description=? WHERE id=?");
    		$statement->execute(array($_POST['name'],$slug,  $_POST['description'],$_POST['description2'],  $_POST['venue'], $eventDate, $_POST['meta_title'], $_POST['meta_keyword'], $_POST['meta_description'], $_REQUEST['id']));
		}
		
		if($path != '' && $path1 == '') {
			unlink('../assets/uploads/event/'.$_POST['current_photo']);
            $file_path = 'assets/uploads/event/';
			$final_name = 'display-'.$_REQUEST['id'].'-'.date('dmyhis').'.'.$ext;
        	move_uploaded_file( $path_tmp, '../assets/uploads/event/'.$final_name );

        	$statement = $pdo->prepare("UPDATE tbl_event SET  name=?, slug=?,  description=?, description2=?,  file_path=?, photo=?, venue=?, venue_date=?, meta_title=?, meta_keyword=?, meta_description=? WHERE id=?");
    		$statement->execute(array($_POST['name'], $slug,  $_POST['description'],$_POST['description2'],  $file_path, $final_name, $_POST['venue'], $eventDate, $_POST['meta_title'],$_POST['meta_keyword'],$_POST['meta_description'],$_REQUEST['id']));
		}
		
		if($path == '' && $path1 != '') {
			unlink('../assets/uploads/event/'.$_POST['current_banner']);
            $file_path = 'assets/uploads/event/';
			$final_name1 = 'banner-'.$_REQUEST['id'].'-'.date('dmyhis').'.'.$ext1;
        	move_uploaded_file( $path_tmp1, '../assets/uploads/event/'.$final_name1 );

        	$statement = $pdo->prepare("UPDATE tbl_event SET  name=?, slug=?,  description=?, description2=?,  file_path=?, banner=?, venue=?, venue_date=?, meta_title=?, meta_keyword=?, meta_description=? WHERE id=?");
    		$statement->execute(array($_POST['name'],$slug,  $_POST['description'],$_POST['description2'],$file_path, $final_name1,$_POST['venue'], $eventDate, $_POST['meta_title'],$_POST['meta_keyword'],$_POST['meta_description'],$_REQUEST['id']));
		}
		if($path != '' && $path1 != '') {

			unlink('../assets/uploads/event/'.$_POST['current_photo']);
			unlink('../assets/uploads/event/'.$_POST['current_banner']);
            $file_path = 'assets/uploads/event/';
			$final_name = 'display-'.$_REQUEST['id'].'-'.date('dmyhis').'.'.$ext;
        	move_uploaded_file( $path_tmp, '../assets/uploads/event/'.$final_name );

			$final_name1 = 'banner-'.$_REQUEST['id'].'-'.date('dmyhis').'.'.$ext1;
        	move_uploaded_file( $path_tmp1, '../assets/uploads/event/'.$final_name1 );

        	$statement = $pdo->prepare("UPDATE tbl_event SET  name=?, slug=?,  description=?, description2=?,  file_path=?, photo=?, banner=?, venue=?, venue_date=?, meta_title=?, meta_keyword=?, meta_description=? WHERE id=?");
    		$statement->execute(array($_POST['name'],$slug,  $_POST['description'],$_POST['description2'],$file_path,$final_name,$final_name1,$_POST['venue'], $eventDate, $_POST['meta_title'],$_POST['meta_keyword'],$_POST['meta_description'],$_REQUEST['id']));
		}

		$success_message = 'Event is updated successfully!';
	}
}
?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_event WHERE id=?");
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
		<h1>Edit Event</h1>
	</div>
	<div class="content-header-right">
		<a href="event.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_event WHERE id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
	$name              = $row['name'];
	$slug              = $row['slug'];
	$description       = $row['description'];
	$description2      = $row['description2'];
	$venue             = $row['venue'];
	$venue_date        = $row['venue_date'];
	$file_path         = $row['file_path'];
	$photo             = $row['photo'];
	$banner            = $row['banner'];
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
				<input type="hidden" name="current_photo" value="<?php echo $photo; ?>">
				<input type="hidden" name="current_banner" value="<?php echo $banner; ?>">
				<div class="box box-info">
					<div class="box-body">
					    
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
					
					    
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Description <span>*</span></label>
							<div class="col-sm-9">
								<textarea class="form-control tinyTextArea" name="description" id=""><?php echo $description; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Description 2 <span>*</span></label>
							<div class="col-sm-9">
								<textarea class="form-control tinyTextArea" name="description2" id=""><?php echo $description2; ?></textarea>
							</div>
						</div>
						
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Venue<span>*</span></label>
							<div class="col-sm-9">
							    <input type="text" autocomplete="off" class="form-control" name="venue" value="<?php echo $venue; ?>">
								
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Venue Date<span>*</span></label>
							<div class="col-sm-9">
							    <input type="text" autocomplete="off" id="" class="form-control" name="venue_date" value="<?php echo $venue_date; ?>">
								
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
							<label for="" class="col-sm-2 control-label">Existing Banner</label>
							<div class="col-sm-9" style="padding-top:5px">
								<img src="<?php echo BASE_URL.$file_path.$banner; ?>" alt="event Banner Photo" style="width:400px;">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Banner </label>
							<div class="col-sm-6" style="padding-top:5px">
								<input type="file" name="banner">(Only jpg, jpeg, gif and png are allowed)
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