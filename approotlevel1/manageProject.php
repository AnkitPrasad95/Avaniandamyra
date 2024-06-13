<?php require_once('header.php'); 

if(isset($_POST['form1'])) {
	$valid = 1;
	//print_r($_POST);

	if(empty($_POST['type'])) { 
		$valid = 0;
		$error_message .= 'Type can not be empty<br>';
	}

    if(empty($_POST['title'])) { 
		$valid = 0;
		$error_message .= 'Title can not be empty<br>';
	}
	
    $path = $_FILES['photo']['name'];
    $path_tmp = $_FILES['photo']['tmp_name'];
    
    
	if($valid == 1) {
	    $file_path = "assets/uploads/project/";
	    if($path!='') {
            $ext = pathinfo( $path, PATHINFO_EXTENSION );
            $file_name = basename( $path, '.' . $ext );
            $final_name = 'image-'.date('dmyhis').'.'.$ext;
            move_uploaded_file( $path_tmp, '../assets/uploads/project/'.$final_name );
           
        } else {
        	$final_name = "";
        }
		$value = array($_POST['type'], $_POST['title'], $file_path, $final_name, date('Y-m-d H:i:s'));

		$statement = $pdo->prepare("INSERT INTO tbl_project_images (type, title, file_path, photo, created_at) VALUES (?,?,?,?,?)");
		$statement->execute($value);
		$success_message = "Added successfully.";
        unset($_POST['type']);
		unset($_POST['title']);
			
	}	
}



if(isset($_GET['id'])) {
     $statement = $pdo->prepare("SELECT * FROM tbl_project_images WHERE id=?");
	 $statement->execute(array($_GET['id']));
	 $result = $statement->fetchAll(PDO::FETCH_ASSOC);							
	 foreach ($result as $row) {
	     $type = $row['type'];
		 $title = $row['title'];
		 $file_path = $row['file_path'];
		 $photo = $row['photo'];
	 }
}

if(isset($_POST['edit_form'])) {

    $valid = 1;

	if(empty($_POST['type'])) { 
		$valid = 0;
		$error_message .= 'Type can not be empty<br>';
	}

    if(empty($_POST['title'])) { 
		$valid = 0;
		$error_message .= 'Title can not be empty<br>';
	}

	$path = $_FILES['photo']['name'];
    $path_tmp = $_FILES['photo']['tmp_name'];
    
    if($valid == 1) {
        
    $file_path = "assets/uploads/project/";
        
    if($path!='') {
        if(!empty($photo)) {
            // removing the existing photo
    	    unlink('../assets/uploads/project/'.$photo);
        }
        $ext = pathinfo( $path, PATHINFO_EXTENSION );
        $file_name = basename( $path, '.' . $ext );
        $final_name = 'project-'.date('dmyhis').'.'.$ext;
        move_uploaded_file( $path_tmp, '../assets/uploads/project/'.$final_name );
       
    } else {
    	$final_name = $photo;
    }

     $value = array($_POST['type'], $_POST['title'], $file_path, $final_name, $_POST['edit_id']);
        
    $statement = $pdo->prepare("update tbl_project_images set type=?, title=?, file_path=?, photo=? where id=?");
    $statement->execute($value);

    echo "<script>alert('Updated successfully.')</script>";
    echo "<script>window.location.href='$cur_page';</script>";
    }
}

if(isset($_POST['delete_activity'])) {
	$statement = $pdo->prepare("DELETE FROM tbl_project_images WHERE id=?");
	$statement->execute(array($_POST['activity_id']));
	$success_message = "Deleted successfully.";
}

?>
<style>
.btn-info.bkbtn {
    position: relative;
    background-color: #1b1c1c;
    border-color: #d60012;
    float: right;
    margin-right: -100px;
    bottom: 28px;
}
</style>
<section class="content-header">
	<div class="content-header-left">
		<h1>Manage Projects</h1>
	</div>
	<!-- <div class="content-header-right backbtns">
		<a href="productList.php" class="btn btn-primary btn-sm">Back</a>
	</div> -->
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
            <?php if(isset($_GET['id'])) { ?>
            <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
				<div class="box box-info">
					<div class="box-body">
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Type <span>*</span></label>
							<div class="col-sm-9">
								<select autocomplete="off" class="form-control select2" name="type">
								<option value="">Select type</option>
								<option <?php if($type == 'House') { echo "selected"; } ?> value="House">House</option>
								<option <?php if($type == 'Interior') { echo "selected"; } ?> value="Interior">Interior</option>
								<option <?php if($type == 'Living') { echo "selected"; } ?> value="Living">Living</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label"> Title <span>*</span></label>
							<div class="col-sm-6">
								<input type="text" class="form-control" name="title" value="<?=$title;?>">
							</div>										
						</div>

						
						<?php if(!empty($photo)) { ?> 
						<div class="form-group">
				            <label for="" class="col-sm-2 control-label">Existing Photo</label>
				            <div class="col-sm-6" style="padding-top:6px;">
				                <img src="<?php echo BASE_URL.$file_path.$photo; ?>" class="existing-photo" width="140">
				            </div>
				        </div>
				        <?php } ?>
						<div class="form-group">
				            <label for="" class="col-sm-2 control-label">Photo</label>
				            <div class="col-sm-6" style="padding-top:6px;">
    				            <input type="file" name="photo">
    				        </div>
                        </div>
                        
						<div class="form-group">
							<label for="" class="col-sm-2 control-label"></label>
							<div class="col-sm-6">
							    <input type="hidden" name="edit_id" value="<?=$_GET['id'];?>">
								<button type="submit" class="btn btn-success pull-left" name="edit_form">Update</button>
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
							<label for="" class="col-sm-2 control-label">Type <span>*</span></label>
							<div class="col-sm-9">
								<select autocomplete="off" class="form-control select2" name="type">
								<option value="">Select type</option>
								<option value="House">House</option>
								<option value="Interior">Interior</option>
								<option value="Living">Living</option>
								</select>
							</div>
						</div>
					    <div class="form-group">
							<label for="" class="col-sm-2 control-label">Title <span>*</span></label>
							<div class="col-sm-6">
								<input type="text" class="form-control" name="title" value="<?php if(isset($_POST['title'])){echo $_POST['title'];} ?>">
							</div>										
						</div>
						<div class="form-group">
				            <label for="" class="col-sm-2 control-label">Photo</label>
				            <div class="col-sm-6" style="padding-top:6px;">
    				            <input type="file" name="photo"> 
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
		<h1>Projects</h1>
	</div>
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
					<th width="140">Photo</th>
                    <th>Type</th>
                    <th>Title</th>
			        <th>Delete</th>
			    </tr>
			</thead>
            <tbody>

            	<?php
            	$i=0;
            	$statement = $pdo->prepare("SELECT * from tbl_project_images order by id desc");
            	$statement->execute(array());
            	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
            	foreach ($result as $row) {
            		$i++;
	
	            	?>
	                <tr>
	                    <td><?php echo $i; ?></td>
						<td style="width:150px;"><img src="<?php echo BASE_URL.$row['file_path'].$row['photo']; ?>" style="width:140px;"></td>
	                    <td><?php echo $row['type']; ?></td>
						<td><?php echo $row['title']; ?></td>
	                    
						<td>
						    <a href="?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
							
	                        <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
	                            <input type="hidden" name="activity_id" class="actcls" value="<?php echo $row['id']; ?>">
	                            <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure, you want to delete?')" name="delete_activity"><i class="fa fa-trash"></i></button>
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