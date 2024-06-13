<?php require_once('header.php'); 
$pro_id = filter_input(INPUT_GET, 'id');

$state = $pdo->prepare("select * from tbl_product_list where id =?");
$state->execute(array($pro_id));
$result = $state->fetch(PDO::FETCH_OBJ);
if(!empty($result->name && $result->id)){
    $proName = $result->name;
    $proId = $result->id;
    $small = $result->small;
    $medium = $result->medium;
    $large = $result->large;
    $ex_large = $result->ex_large;
    $xxl = $result->xxl;
} else {
    $proName = "";
    $proId = "";
    $small = "";
    $medium = "";
    $large = "";
    $ex_large = "";
    $xxl = "";
    
}

if(isset($_POST['form1'])) {
    $color = str_replace(' ', '', $_POST['product_color']);
    $size = $_POST['product_size'];
    $proId = $_POST['product_id'];

    $statement = $pdo->prepare("select * from tbl_size where size =? AND color=? AND product_id =?");
	$statement->execute(array($size, $color, $proId));
	if($statement->rowCount() > 0) {
		echo "<script>alert('$color color is already exist!');</script>";
	} else {
		
		$statement = $pdo->prepare("INSERT INTO tbl_size (product_id, size, color, created_at) VALUES (?,?,?,?)");
		$statement->execute(array($_POST['product_id'],$_POST['product_size'], $_POST['product_color'],date('Y-m-d H:i:s')));
	}      
}

if(isset($_POST['delete_size'])) {
    //print_r($_POST); die();
	$statement = $pdo->prepare("DELETE FROM tbl_size WHERE id = ?");
	$statement->execute(array($_POST['size_id']));
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
							<label for="" class="col-sm-2 control-label">Product Name<span>*</span></label>
							<div class="col-sm-6">
							    <input type="text" class="form-control" value="<?=$proName;?>" readonly/>
							    <input type="hidden" class="form-control" name="product_id" value="<?=$proId;?>" readonly/>
							</div>
						</div>
                        <div class="form-group">
				            <label for="" class="col-sm-2 control-label">Select Category <span>*</span></label>
				            <div class="col-sm-6">
				            	<select class="form-control select2" name="product_size" required>
				            		<option value="">Select a category</option>
                                    <?php if(!empty($small)) { ?> 
								    <option value="<?php echo $small; ?>"><?php echo $small; ?></option>
                                    <?php } ?>
                                    <?php if(!empty($medium)) { ?> 
								    <option value="<?php echo $medium; ?>"><?php echo $medium; ?></option>
                                    <?php } ?>
                                    <?php if(!empty($large)) { ?> 
								    <option value="<?php echo $large; ?>"><?php echo $large; ?></option>
                                    <?php } ?>
                                    <?php if(!empty($ex_large)) { ?> 
								    <option value="<?php echo $ex_large; ?>"><?php echo $ex_large; ?></option>
                                    <?php } ?>
                                    <?php if(!empty($xxl)) { ?> 
								    <option value="<?php echo $xxl; ?>"><?php echo $xxl; ?></option>
                                    <?php } ?>
				            	</select>
				            </div>
				        </div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Color Name<span>*</span></label>
							<div class="col-sm-6">
							    <input type="text" name="product_color" class="form-control" value="" required/>
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
		<h1>View Color</h1>
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
			        <th>Product Name</th>
			        <th>Size</th>
                    <th>Color</th>
			        <th>Delete</th>
			    </tr>
			</thead>
            <tbody>

            	<?php
            	$i=0;
            	$statement = $pdo->prepare("SELECT 
            	                           
											t1.id,
											t1.size,
											t1.color,
											t2.name
            	                           	FROM  tbl_size t1
            	                           	JOIN tbl_product_list t2
            	                           	ON t1.product_id = t2.id where t1.product_id =? order by t1.id desc");
            	$statement->execute(array($pro_id));
            	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
            	foreach ($result as $row) {
                    //print_r($row);
            		$i++;
	            	?>
	                <tr>
	                    <td><?php echo $i; ?></td>
						<td><?php echo $row['name']; ?></td>
	                    <td><?php echo $row['size']; ?></td>
                        <td><?php echo $row['color']; ?></td>
	                   <td>
	                        <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
	                            <input type="hidden" name="size_id" value="<?php echo $row['id'];?>">
	                            <button type="submit" title="delete" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure?')" name="delete_size"><i class="fa fa-trash"></i></button>
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