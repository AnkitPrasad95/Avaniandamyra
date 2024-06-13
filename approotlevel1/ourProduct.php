<?php require_once('header.php'); 
$pro_id = filter_input(INPUT_GET, 'id');

$state = $pdo->prepare("select * from tbl_product_list where id =?");
$state->execute(array($pro_id));
$result = $state->fetch(PDO::FETCH_OBJ);
if(!empty($result->name && $result->id)){
    $proName = $result->name;
    $proId = $result->id;
} else {
    $proName = "";
    $proId = "";
}

if(isset($_POST['form1'])) {
    $valid = 1;

	if(empty($_POST['title'])) {
		$valid = 0;
		$error_message .= 'Title can not be empty<br>';
    }

    
	$path = $_FILES['photo']['name'];
    $path_tmp = $_FILES['photo']['tmp_name'];

    if($path!='') {
        $ext = pathinfo( $path, PATHINFO_EXTENSION );
        $file_name = basename( $path, '.' . $ext );
        if($ext!='jpg' && $ext!='png' && $ext!='jpeg' && $ext!='gif' &&  $ext!='pdf') {
            $valid = 0;
            $error_message .= 'You must have to upload pdf file for pdf<br>';
        }
    } else {
    	$valid = 0;
        $error_message .= 'You must have to select a pdf for featured pdf<br>';
    }
    
	if($valid == 1) {

	$statement = $pdo->prepare("SHOW TABLE STATUS LIKE 'tbl_product_list'");
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row) {
		$ai_id=$row[10];
	}	

	$file_path = 'assets/uploads/pdf_files/';
	$final_name = 'display-'.$ai_id.'-'.date('dmyhis').'.'.$ext;
	move_uploaded_file( $path_tmp, '../assets/uploads/pdf_files/'.$final_name );	

    $statement = $pdo->prepare("INSERT INTO tbl_our_product (product_id, title, description, file_path, pdf, created_at) VALUES (?,?,?,?,?,?)");
	$statement->execute(array($_POST['product_id'], $_POST['title'], $_POST['description'], $file_path, $final_name, date('Y-m-d H:i:s')));
    unset($_POST['title']);
	unset($_POST['description']); 
	$success_message = "Our Product is added Successfully.";  
	}
	
}

if(isset($_POST['update_form'])) {
	$valid = 1;

	if(empty($_POST['title'])) {
		$valid = 0;
		$error_message .= 'Title can not be empty<br>';
    }

    
	$path = $_FILES['photo']['name'];
    $path_tmp = $_FILES['photo']['tmp_name'];

    if($path!='') {
        $ext = pathinfo( $path, PATHINFO_EXTENSION );
        $file_name = basename( $path, '.' . $ext );
        if($ext!='jpg' && $ext!='png' && $ext!='jpeg' && $ext!='gif' &&  $ext!='pdf') {
            $valid = 0;
			$error_message .= 'You must have to upload pdf file for pdf<br>';
        }
    }

	if($valid == 1) {

	if($path == '') {	
		$statement = $pdo->prepare("update tbl_our_product SET title = ?, description = ? WHERE id = ?");
		$statement->execute(array($_POST['title'], $_POST['description'], $_POST['ourproduct_id']));
	} 
	if($path != '') {
        $file_path = 'assets/uploads/pdf_files/';
		if(!empty($_POST['current_photo'])) {
            unlink('../assets/uploads/pdf_files/'.$_POST['current_photo']);
        }
		$final_name = 'display-'.$_REQUEST['ourproduct_id'].'-'.date('dmyhis').'.'.$ext;
		move_uploaded_file( $path_tmp, '../assets/uploads/pdf_files/'.$final_name );

		$statement = $pdo->prepare("update tbl_our_product SET title = ?, description = ?, file_path=?, pdf=? WHERE id = ?");
		$statement->execute(array($_POST['title'], $_POST['description'], $file_path, $final_name, $_POST['ourproduct_id']));
	}
	$success_message = "Our Product is updated Successfully.";

	}
}

if(isset($_POST['delete_ourproduct'])) {
    //Getting photo ID to unlink from folder
	$statement = $pdo->prepare("SELECT * FROM tbl_our_product WHERE id = ?");
	$statement->execute(array($_POST['ourproduct_id']));
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
	foreach ($result as $row) {
		$photo = $row['pdf'];
	}

	//Unlink the photo
	if($photo!='') {
		unlink('../assets/uploads/pdf_files/'.$photo);	
	}

	// Delete from tbl_solution
	$statement = $pdo->prepare("DELETE FROM tbl_our_product WHERE id = ?");
	$statement->execute(array($_POST['ourproduct_id']));
	$success_message = "Our Product is deleted Successfully.";
}



?>

<section class="content-header">
	<div class="content-header-left">
		<h1><?php if(isset($_POST['edit_product'])) { ?> Edit <?php  } else { ?> Add <?php }?>  Our Product</h1>
	</div>
	<div class="content-header-right backbtns">
		<a href="productList.php" class="btn btn-primary btn-sm">Back</a>
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

			
			<?php if(isset($_POST['edit_product'])) { 
			$state = $pdo->prepare("select * from tbl_our_product where id =?");
            $state->execute(array($_POST['ourproduct_id']));
            $result = $state->fetch(PDO::FETCH_OBJ);
			
			?> 
            <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
			<input type="hidden" name="current_photo" value="<?php echo $result->pdf; ?>">

				<div class="box box-info">
					<div class="box-body">
					    <div class="form-group">
							<label for="" class="col-sm-2 control-label">Project Name<span>*</span></label>
							<div class="col-sm-4">
							    <input type="text" class="form-control" value="<?=$proName;?>" readonly/>
							    <input type="hidden" class="form-control" name="ourproduct_id" value="<?=$result->id;?>" readonly/>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Title <span>*</span></label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" class="form-control" name="title" value="<?php echo $result->title; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Description <span>*</span></label>
							<div class="col-sm-9">
								<textarea class="form-control tinyTextArea" name="description" id=""><?php echo $result->description; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Existing Pdf <span>*</span></label>
							<div class="col-sm-9" style="padding-top:5px">
								<a href="<?=BASE_URL.$result->file_path.$result->pdf?>" target="_blank">View</a>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Featured Pdf <span>*</span></label>
							<div class="col-sm-9" style="padding-top:5px">
								<input type="file" name="photo">(Only pdf is allowed)
							</div>
						</div>
						
						<div class="form-group">
							<label for="" class="col-sm-2 control-label"></label>
							<div class="col-sm-6">
								<button type="submit" class="btn btn-success pull-left" name="update_form">Submit</button>
							</div>
						</div>
					</div>
				</div>

			</form>
			<?php } else { ?> 
			<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">

				<div class="box box-info">
					<div class="box-body">
					    <div class="form-group">
							<label for="" class="col-sm-2 control-label">Product Name<span>*</span></label>
							<div class="col-sm-9">
							    <input type="text" class="form-control" value="<?=$proName;?>" readonly/>
							    <input type="hidden" class="form-control" name="product_id" value="<?=$proId;?>" readonly/>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Title <span>*</span></label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" class="form-control" name="title" value="<?php if(isset($_POST['title'])){echo $_POST['title'];} ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Description <span>*</span></label>
							<div class="col-sm-9">
								<textarea class="form-control tinyTextArea" name="description" id=""><?php if(isset($_POST['description'])){echo $_POST['description'];} ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Featured Pdf <span>*</span></label>
							<div class="col-sm-9" style="padding-top:5px">
								<input type="file" name="photo">(Only pdf is allowed)
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
			<?php } ?>

		</div>
	</div>

</section>

<section class="content-header">
	<div class="content-header-left">
		<h1>View Our Product</h1>
	</div>
	<!--<div class="content-header-right">-->
	<!--	<a href="photo-add.php" class="btn btn-primary btn-sm">Add New</a>-->
	<!--</div>-->
</section>

<section class="content">

  <div class="row">
    <div class="col-md-12">


      <div class="box box-info">
        
        <div class="box-body table-responsive">
          <table id="example1" class="table table-bordered table-striped">
			<thead>
			    <tr>
			        <th>SL</th>
			        <th>Product</th>
			        <th>Title</th>
			        <th>Pdf</th>
			        <th>Edit</th>
			        <th>Delete</th>
			    </tr>
			</thead>
            <tbody>

            	<?php
            	$i=0;
            	$statement = $pdo->prepare("select * from tbl_our_product where product_id =? ");
            	$statement->execute(array($pro_id));
            	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
            	foreach ($result as $row) {
            		$i++;
	            	?>
	                <tr>
	                    <td><?php echo $i; ?></td>
						<td><?php echo $proName; ?></td>
						<td><?php echo $row['title']; ?></td>
	                    <td>
	                    	<a href="<?php echo BASE_URL.$row['file_path'].$row['pdf']; ?>" target="_blank">View</a>
	                    </td>
	                    <td>
	                        <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
	                            <input type="hidden" name="ourproduct_id" value="<?php echo $row['id']; ?>">
	                            <button type="submit" class="btn btn-info btn-xs" name="edit_product"><i class="fa fa-pencil"></i></button>
	                        </form>
	                        
	                        
	                    </td>
	                    
	                   <td>
	                        
	                        <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
	                            <input type="hidden" name="ourproduct_id" value="<?php echo $row['id']; ?>">
	                            <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure want to delete this item?')" name="delete_ourproduct"><i class="fa fa-trash"></i></button>
	                        </form>
	                        
	                    </td>
	                </tr>
	                <?php
            	}
            	?>
            </tbody>
          </table>
        </div>
      </div> 
    </div>
  </div>    
</section>


<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
            </div>
            <div class="modal-body">
                Are you sure want to delete this item?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>