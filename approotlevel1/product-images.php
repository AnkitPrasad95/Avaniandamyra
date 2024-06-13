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
    
$total = count($_FILES['upload']['name']);

// getting auto increment id
$statement = $pdo->prepare("SHOW TABLE STATUS LIKE 'tbl_gallery'");
$statement->execute();
$result = $statement->fetchAll();
foreach($result as $row) {
	$ai_id=$row[10];
}

// Loop through each file
for( $i=0 ; $i < $total ; $i++ ) {

  //Get the temp file path
  $path = $_FILES['upload']['name'][$i];
  $path_tmp = $_FILES['upload']['tmp_name'][$i];

  $ext = pathinfo( $path, PATHINFO_EXTENSION );
  $file_name = basename( $path, '.' . $ext );
  $file_path = 'assets/uploads/product_photo/';	
  $final_name = 'img-'.$ai_id.'-'.$i.'.'.$ext;


  //Make sure we have a file path
  if ($path_tmp != ""){
    //Setup our new file path
    $newFilePath = '../assets/uploads/product_photo/'.$final_name;

    //Upload the file into the temp dir
	if(move_uploaded_file($path_tmp, $newFilePath)) {	
    $statement = $pdo->prepare("INSERT INTO tbl_gallery (title, file_path, photo_name, p_category_id, created_at) VALUES (?,?,?,?,?)");
	$statement->execute(array($_POST['title'], $file_path, $final_name,$_POST['photocat_id'],date('Y-m-d H:i:s')));
        

    }
  }
}
}

if(isset($_POST['delete_photo'])) {
    //Getting photo ID to unlink from folder
	$statement = $pdo->prepare("SELECT * FROM tbl_gallery WHERE photo_id=?");
	$statement->execute(array($_POST['photo_id']));
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
	foreach ($result as $row) {
		$photo = $row['photo_name'];
	}

	//Unlink the photo
	if($photo!='') {
		unlink('../assets/uploads/product_photo/'.$photo);	
	}

	// Delete from tbl_solution
	$statement = $pdo->prepare("DELETE FROM tbl_gallery WHERE photo_id=?");
	$statement->execute(array($_POST['photo_id']));
}


?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Add Photos</h1>
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

			<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">

				<div class="box box-info">
					<div class="box-body">
					    <div class="form-group">
							<label for="" class="col-sm-2 control-label"> Name<span>*</span></label>
							<div class="col-sm-4">
							    <input type="text" class="form-control" value="<?=$proName;?>" readonly/>
							    <input type="hidden" class="form-control" name="photocat_id" value="<?=$proId;?>" readonly/>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Title <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" class="form-control"  name="title" required>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Upload Photo <span>*</span></label>
							<div class="col-sm-4" style="padding-top:6px;">
								<input type="file" name="upload[]" multiple="multiple">
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

<section class="content-header">
	<div class="content-header-left">
		<h1>View Photos</h1>
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
			        <th class="text-center">SL</th>
			        <th class="text-center">Product Name</th>
					<th class="text-center">Title</th>
			        <th class="text-center">Photo</th>
			        
			        <th class="text-center">Delete</th>
			    </tr>
			</thead>
            <tbody>

            	<?php
            	$i=0;
            	$statement = $pdo->prepare("SELECT 
            	                           
											t1.photo_id,
											t1.title,
											t1.photo_name,
											t1.file_path,
											t1.p_category_id,

											t2.id,
											t2.name

            	                           	FROM  tbl_gallery t1
            	                           	JOIN tbl_product_list t2
            	                           	ON t1.p_category_id = t2.id where t1.p_category_id =? order by t1.photo_id desc");
            	$statement->execute(array($pro_id));
            	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
            	foreach ($result as $row) {
            		$i++;
	            	?>
	                <tr class="text-center">
	                    <td style="vertical-align: inherit;"><?php echo $i; ?></td>
						<td style="vertical-align: inherit;"><?php echo $row['name']; ?></td>
						<td style="vertical-align: inherit;"><?php echo $row['title']; ?></td>
	                    <td style="vertical-align: inherit;">
	                    	<img src="<?php echo BASE_URL.$row['file_path'].$row['photo_name']; ?>" width="50">
	                    </td>
	                    
	                   <td style="vertical-align: inherit;">
	                        <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
	                            <input type="hidden" name="photo_id" value="<?php echo $row['photo_id']; ?>">
	                            <button type="submit" class="btn btn-danger btn-xs ms-0" style="margin-top:0px !important;  margin-left: 0px !important;" onclick="return confirm('Are you sure?')" name="delete_photo"><i class="fa fa-trash"></i></button>
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