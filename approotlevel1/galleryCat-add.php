<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {

	
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
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file for featured photo<br>';
        }
    } 
    
    

	if($valid == 1) {

        $statement = $pdo->prepare("SHOW TABLE STATUS LIKE 'tbl_gallery_category'");
		$statement->execute();
		$result = $statement->fetchAll();
		foreach($result as $row) {
			$ai_id=$row[10];
		}

        $file_path = 'assets/uploads/gallery/';
        if(!empty($ext)) {
            $final_name = $ai_id.'-cat-'.date('dmyhis').'.'.$ext;
            move_uploaded_file( $path_tmp, '../assets/uploads/gallery/'.$final_name );
        } else {
            $final_name = '';
        }

		
		$statement = $pdo->prepare("INSERT INTO tbl_gallery_category (name, slug, file_path, photo, created_at) VALUES (?,?,?,?,?)");
		$statement->execute(array($_POST['name'], $_POST['slug'], $file_path, $final_name, date('Y-m-d H:i:s')));
			
		$success_message = 'Category is added successfully!';
		unset($_POST['name']);		
        unset($_POST['slug']);
	
	}
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Add gallery Category</h1>
	</div>
	<div class="content-header-right">
		<a href="galleryCat.php" class="btn btn-primary btn-sm">View All</a>
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
							<label for="" class="col-sm-2 control-label">name <span>*</span></label>
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

                        <div class="form-group">
							<label for="" class="col-sm-2 control-label">Featured Photo <span>*</span></label>
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
