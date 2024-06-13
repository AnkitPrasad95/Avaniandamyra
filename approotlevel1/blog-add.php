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
    } else {
    	$valid = 0;
        $error_message .= 'You must have to select a photo for featured photo<br>';
    }

    $path1 = $_FILES['banner']['name'];
    $path_tmp1 = $_FILES['banner']['tmp_name'];

    if($path1!='') {
        $ext1 = pathinfo( $path1, PATHINFO_EXTENSION );
        $file_name1 = basename( $path1, '.' . $ext1 );
        if( $ext1!='jpg' && $ext1!='png' && $ext1!='jpeg' && $ext1!='gif' ) {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file for banner<br>';
        }
    } else {
    	$valid = 0;
        $error_message .= 'You must have to select a photo for banner<br>';
    }

	if($valid == 1) {

		$statement = $pdo->prepare("SHOW TABLE STATUS LIKE 'tbl_blog'");
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

    	// if slug already exists, then rename it
		$statement = $pdo->prepare("SELECT * FROM tbl_blog WHERE slug=?");
		$statement->execute(array($slug));
		$total = $statement->rowCount();
		if($total) {
			$newSlug = $total + 1;
			$slug = $slug.'-'.$newSlug;
		}
        $file_path = 'assets/uploads/blog/';
		$final_name = 'display-'.$ai_id.'-'.date('dmyhis').'.'.$ext;
        move_uploaded_file( $path_tmp, '../assets/uploads/blog/'.$final_name );

        $final_name1 = 'banner-'.$ai_id.'-'.date('dmyhis').'.'.$ext1;
        move_uploaded_file( $path_tmp1, '../assets/uploads/blog/'.$final_name1 );

		$statement = $pdo->prepare("INSERT INTO tbl_blog (name,slug,short_description,description,quoted_description,description2,file_path,photo,banner,blog_by,meta_title,meta_keyword,meta_description,created_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$statement->execute(array($_POST['name'],$slug,$_POST['short_description'],$_POST['description'],$_POST['quoted_description'],$_POST['description2'],$file_path,$final_name,$final_name1,$_POST['blog_by'],$_POST['meta_title'],$_POST['meta_keyword'],$_POST['meta_description'],date('Y-m-d H:i:s')));
			
		$success_message = 'Blog is added successfully!';
        // unset($_POST['cat_id']);
		unset($_POST['name']);
		unset($_POST['slug']);
		unset($_POST['short_description']);
		unset($_POST['description']);
		unset($_POST['quoted_description']);
		unset($_POST['description2']);
		unset($_POST['blog_by']);
		
		unset($_POST['meta_title']);
		unset($_POST['meta_keyword']);
		unset($_POST['meta_description']);
	}
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Add Blog</h1>
	</div>
	<div class="content-header-right">
		<a href="blog.php" class="btn btn-primary btn-sm">View All</a>
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
					    
                        <!-- <div class="form-group">
							<label for="" class="col-sm-2 control-label">Category <span>*</span></label>
							<div class="col-sm-9">
								<select autocomplete="off" class="form-control" name="cat_id">
								<option value="">Select Category</option>
								<?php 
								$statement = $pdo->prepare("select * from tbl_blog_category order by id desc");
								$statement->execute();
								$catList = $statement->fetchAll(PDO::FETCH_OBJ);
								if(!empty($catList)) {
									foreach($catList as $catListRow) {
									?>
								<option value="<?=$catListRow->id;?>"><?=$catListRow->name;?></option>	
								<?php } } ?>
								</select>
							</div>
						</div> -->
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
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Design<span>*</span></label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="short_description" value="<?php if(isset($_POST['short_description'])){echo $_POST['short_description'];} ?>">
							</div>
						</div>
						
					
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Description <span>*</span></label>
							<div class="col-sm-9">
								<textarea class="form-control tinyTextArea" name="description" id=""><?php if(isset($_POST['description'])){echo $_POST['description'];} ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Quoted Description <span>*</span></label>
							<div class="col-sm-9">
								<textarea class="form-control tinyTextArea" name="quoted_description" id=""><?php if(isset($_POST['quoted_description'])){echo $_POST['quoted_description'];} ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Description 2 <span>*</span></label>
							<div class="col-sm-9">
								<textarea class="form-control tinyTextArea" name="description2" id=""><?php if(isset($_POST['description2'])){echo $_POST['description2'];} ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Blog By <span>*</span></label>
							<div class="col-sm-9">
							    <input type="text" autocomplete="off" class="form-control" name="blog_by" value="<?php if(isset($_POST['blog_by'])){echo $_POST['blog_by'];} ?>">
								
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Featured Photo <span>*</span></label>
							<div class="col-sm-9" style="padding-top:5px">
								<input type="file" name="photo">(Only jpg, jpeg, gif and png are allowed)
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Banner Photo <span>*</span></label>
							<div class="col-sm-9" style="padding-top:5px">
								<input type="file" name="banner">(Only jpg, jpeg, gif and png are allowed)
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