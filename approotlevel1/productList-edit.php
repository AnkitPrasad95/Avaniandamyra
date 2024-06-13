<?php require_once('header.php'); 

$statement = $pdo->prepare("select * from tbl_product_category where parent_category = 0 order by id asc");
$statement->execute();
$categoryList = $statement->fetchAll(PDO::FETCH_OBJ);


if(isset($_POST['form1'])) {
	
	$valid = 1;

	if(empty($_POST['name'])) {
		$valid = 0;
		$error_message .= 'name can not be empty<br>';
    }

	// if(empty($_POST['short_description'])) {
	// 	$valid = 0;
	// 	$error_message .= 'Short description can not be empty<br>';
    // }

	if(empty($_POST['categories'])) {
		$valid = 0;
		$error_message .= 'Category can not be empty<br>';
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

		$categories = implode(",", $_POST['categories']);
		if(!empty($_POST['materials'])) {
			$materials = implode(",", $_POST['materials']);
		} else {
			$materials = "";
		}

		if(!empty($_POST['sizes'])) {
			$sizes = implode(",", $_POST['sizes']);
		} else {
			$sizes = "";
		}

		if(!empty($_POST['guest_collection'])) {
			$guest_collection = $_POST['guest_collection'];
		} else {
			$guest_collection = 0;
		}

		if(!empty($_POST['latest_collection'])) {
			$latest_collection = $_POST['latest_collection'];
		} else {
			$latest_collection = 0;
		}

		if (!empty($_POST['price'])) {
			$price = $_POST['price'];
		} else {
			$price = 0;
		}

		if (isset($_POST['show_on_top']) && $_POST['show_on_top'] == 1) {
			$statement = $pdo->prepare("SELECT max(show_on_top) as max_value FROM tbl_product_list");
			$statement->execute();
			$MaxNumbers = $statement->fetch(PDO::FETCH_OBJ);
			$show_on_top = $MaxNumbers->max_value + 1;
		} else {
			$show_on_top = 0;
		}
       
		$statement = $pdo->prepare("UPDATE tbl_product_list SET categories=?,  materials=?, sizes=?, name=?, slug=?, short_description=?, price=?, remarks=?, guest_collection=?, latest_collection=?, show_on_top=?, meta_title=?, meta_keyword=?, meta_description=?, tags=? WHERE id=?");
		$res = $statement->execute(array($categories, $materials, $sizes, $_POST['name'], $_POST['slug'], $_POST['short_description'], $price, $_POST['remarks'], $guest_collection, $latest_collection, $show_on_top, $_POST['meta_title'], $_POST['meta_keyword'], $_POST['meta_description'],$_POST['tags'],$_REQUEST['id']));
			
         if($path != '') {
            if(!empty($_POST['current_photo'])) {
            unlink('../assets/uploads/product-list/'.$_POST['current_photo']);
            }
			$file_path = 'assets/uploads/product-list/';
			$final_name = 'thumbnail-'.str_replace(' ', '-', $_POST['name']).'.'.$ext;
        	move_uploaded_file( $path_tmp, '../assets/uploads/product-list/'.$final_name );
        	
        	$statement = $pdo->prepare("UPDATE tbl_product_list SET file_path=?, thumbnail_image=? WHERE id=?");
    		$res = $statement->execute(array($file_path, $final_name, $_REQUEST['id']));
        }

		echo "<script> alert('Product is updated successfully!'); </script>";
		echo "<meta http-equiv='refresh' content='0'>";
		//echo "<script>location.reload();</script>";
		$success_message = 'Product is updated successfully!';
			
	}
}
?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_product_list WHERE id=?");
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
		<h1>Edit Product</h1>
	</div>
	<div class="content-header-right">
		<a href="productList.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_product_list WHERE id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
	// echo "<pre>";
	// print_r($row);
	// echo "</pre>";
	if(!empty($row['categories'])) { 
		$categories = explode(",",$row['categories']); 
	} else {
		$categories = 0;
	}

	if(!empty($row['materials'])) { 
		$materials = explode(",",$row['materials']); 
	} else {
		$materials = 0;
	}

	if(!empty($row['sizes'])) { 
		$sizes = explode(",",$row['sizes']); 
	} else {
		$sizes = 0;
	}
	
	$name       = $row['name'];
	$slug       = $row['slug'];
	$short_description       = $row['short_description'];
	$price       = $row['price'];
	$remarks       = $row['remarks'];
	$guest_collection       = $row['guest_collection'];
	$latest_collection       = $row['latest_collection'];
	$show_on_top       = $row['show_on_top'];
	$file_path         = $row['file_path'];
	$thumbnail_image             = $row['thumbnail_image'];
	$meta_title        = $row['meta_title'];
	$meta_keyword      = $row['meta_keyword'];
	$meta_description  = $row['meta_description'];
	$tags = $row['tags'];
}
if(!empty($tags)) {
$arr = array();
foreach (json_decode($tags) as $key => $details){
	$data = $details->value;	
	array_push($arr, $data);
}
$tags = implode(',',$arr);
} else {
	$tags = "";
}

?>

<section class="content">

	<div class="row">
		<div class="col-md-12">
			<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
				<input type="hidden" name="current_photo" value="<?php echo $thumbnail_image; ?>">
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
							<label for="" class="col-sm-2 control-label">Short Description <span>*</span></label>
							<div class="col-sm-9">
								<textarea class="form-control" name="short_description" id=""><?php echo $short_description; ?></textarea>
							</div>
						</div>
						
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Price </label>
							<div class="col-sm-9">
								<input type="number" autocomplete="off" class="form-control" name="price" value="<?php echo $price; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Categories <span>*</span></label>
							<div class="col-sm-9">
								<select autocomplete="off" class="form-control select2"  name="categories[]" multiple>
									<option value="">Select Category</option>
									<?php foreach($categoryList as $category) { 
										$statement = $pdo->prepare("SELECT * FROM tbl_product_category where parent_category = ?");
										$statement->execute(array( $category->id));
										$childrens = $statement->fetchAll(PDO::FETCH_OBJ);
									?>
										<option <?php if(in_array($category->id, $categories)) echo "selected";  ?> value="<?=$category->id;?>"><?=$category->name;?></option>	
											<?php foreach($childrens as $children) { ?> 
												<option <?php if(in_array($children->id, $categories)) echo "selected";  ?> value="<?=$children->id;?>">-- <?=$children->name;?></option>
											<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Sizes </label>
							<div class="col-sm-9">
								<select autocomplete="off" class="form-control select2"  name="sizes[]" multiple>
								<option value="">Select Size</option>
								<?php 
								$statement = $pdo->prepare("select * from tbl_size order by id desc");
								$statement->execute();
								$sizeList = $statement->fetchAll(PDO::FETCH_OBJ);
								if(!empty($sizeList)) {
									foreach($sizeList as $sizeListRow) {
									?>
								<option <?php if($sizes >0 && in_array($sizeListRow->id, $sizes)) echo "selected";  ?> value="<?=$sizeListRow->id;?>"><?=$sizeListRow->size_name;?></option>	
								<?php } } ?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Materials </label>
							<div class="col-sm-9">
								<select autocomplete="off" class="form-control select2" name="materials[]" multiple>
								<option value="">Select Materials</option>
								<?php 
								$statement = $pdo->prepare("select * from 	tbl_material order by id desc");
								$statement->execute();
								$matList = $statement->fetchAll(PDO::FETCH_OBJ);
								if(!empty($matList)) {
									foreach($matList as $matListRow) {
									?>
								<option <?php if($materials > 0 && in_array($matListRow->id, $materials)) echo "selected";  ?> value="<?=$matListRow->id;?>"><?=$matListRow->name;?></option>	
								<?php } } ?>
								</select>
							</div>
						</div>

						
						
						

						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Remarks <span>*</span></label>
							<div class="col-sm-9">
								<textarea class="form-control" name="remarks" id="" style="height:100px"><?php echo $remarks; ?></textarea>
							</div>
						</div>

						<!-- <div class="form-group">
							<label for="" class="col-sm-2 control-label">Guest Collection </label>
							<div class="col-sm-9">
								<input type="checkbox" name="guest_collection" <?php if($guest_collection == 1) { echo "checked"; } ?> value="1">
							</div>
						</div> -->

						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Latest Collection </label>
							<div class="col-sm-9">
								<input type="checkbox" name="latest_collection" <?php if($latest_collection == 1) { echo "checked"; } ?> value="1">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Show on Top</label>
							<div class="col-sm-9">
								<input type="checkbox" <?php if($show_on_top != 0) { echo "checked"; } ?> name="show_on_top" value="1">
							</div>
						</div>
                        
						<?php if(!empty($thumbnail_image))  { ?>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Existing Thumbnail</label>
							<div class="col-sm-9" style="padding-top:5px">
								<img src="<?php echo BASE_URL.$file_path.$thumbnail_image; ?>" alt="event Photo" style="width:100px;">
							</div>
						</div>
						<?php } ?>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Thumbnail </label>
							<div class="col-sm-6" style="padding-top:5px">
								<input type="file" name="photo">(Only jpg, jpeg, gif and png are allowed)
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
							<label for="" class="col-sm-2 control-label">Tags </label>
							<div class="col-sm-9">
								<input type="text" name='tags' value="<?=$tags;?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label"></label>
							<div class="col-sm-6">
								<button type="submit" class="btn btn-success pull-left" name="form1">Update</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>

</section>

<?php require_once('footer.php'); ?>