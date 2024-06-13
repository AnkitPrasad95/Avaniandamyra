<?php require_once('header.php'); 

$statement = $pdo->prepare("select * from tbl_collection where show_on_header = 0 order by id asc");
$statement->execute();
$collectionList = $statement->fetchAll(PDO::FETCH_OBJ);

if(isset($_POST['form1'])) {
    // echo "<pre>"; 
    // print_r($_POST); die;
	$valid = 1;
	

	if(empty($_POST['user_name'])) { 
		$valid = 0;
		$error_message .= 'User Name can not be empty<br>';
	}
    if(empty($_POST['email'])) { 
		$valid = 0;
		$error_message .= 'Email can not be empty<br>';
	}
    if(empty($_POST['first_name'])) { 
		$valid = 0;
		$error_message .= 'First name can not be empty<br>';
	}
    if(empty($_POST['last_name'])) { 
		$valid = 0;
		$error_message .= 'Last name can not be empty<br>';
	}
    // if(empty($_POST['contact'])) { 
	// 	$valid = 0;
	// 	$error_message .= 'contact can not be empty<br>';
	// }

    $statement = $pdo->prepare("select * from tbl_customers where (user_name = ? OR email = ?)");
    $statement->execute(array(strip_tags($_POST['user_name']), strip_tags($_POST['email'])));
	
    $checkUser = $statement->rowCount();

    if($checkUser > 0) {
        $valid = 0;
		$error_message .= 'User name already exist, please try new user name. <br>';
    }

	if($valid == 1) {

        $colletion_ids = implode(",", $_POST['colletion_ids']);

		$statement = $pdo->prepare("INSERT INTO tbl_customers (user_name,email,first_name,last_name,contact,website,password,role,status,colletion_ids,created_at) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
		$statement->execute(array($_POST['user_name'],$_POST['email'],$_POST['first_name'],$_POST['last_name'],$_POST['contact'],$_POST['website'],md5($_POST['password']),$_POST['role'],$_POST['status'],$colletion_ids,date('Y-m-d H:i:s')));
			
		$success_message = 'User is added successfully!';
		unset($_POST['user_name']);
		unset($_POST['email']);
		unset($_POST['first_name']);
		unset($_POST['last_name']);
		unset($_POST['contact']);
		unset($_POST['website']);
		unset($_POST['password']);
		unset($_POST['role']);
		unset($_POST['colletion_ids']);
	}
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Add User</h1>
	</div>
	<div class="content-header-right">
		<a href="users.php" class="btn btn-primary btn-sm">View All</a>
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
							<label for="" class="col-sm-2 control-label">User Name <span>*</span></label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" class="form-control" name="user_name" value="<?php if(isset($_POST['user_name'])){echo $_POST['user_name'];} ?>" required>
							</div>
						</div>
                        <div class="form-group">
							<label for="" class="col-sm-2 control-label">Email<span>*</span></label>
							<div class="col-sm-9">
								<input type="email" class="form-control" name="email" value="<?php if(isset($_POST['email'])){echo $_POST['email'];} ?>" required>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">First Name<span>*</span> </label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" class="form-control" name="first_name"  value="<?php if(isset($_POST['first_name'])){echo $_POST['first_name'];} ?>" required>
							</div>
						</div>
                        <div class="form-group">
							<label for="" class="col-sm-2 control-label">Last Name<span>*</span> </label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" class="form-control" name="last_name"  value="<?php if(isset($_POST['last_name'])){echo $_POST['last_name'];} ?>" required>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Contact No. <span>*</span></label>
							<div class="col-sm-9">
							    <input type="number" autocomplete="off" class="form-control" name="contact" value="<?php if(isset($_POST['contact'])){echo $_POST['contact'];} ?>">
								
							</div>
						</div>
						
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Website </label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" class="form-control" name="website" value="<?php if(isset($_POST['website'])){echo $_POST['website'];} ?>">
							</div>
						</div>
                        <div class="form-group">
							<label for="" class="col-sm-2 control-label">Password <span>*</span></label>
							<div class="col-sm-9">
								<input type="password" autocomplete="off" class="form-control" name="password" value="" required>
							</div>
						</div>
                        <div class="form-group">
							<label for="" class="col-sm-2 control-label">Role <span>*</span></label>
							<div class="col-sm-9">
								<select autocomplete="off" class="form-control" name="role" required>
								<option value="customer">Customer</option>
								</select>
							</div>
						</div>
						<div class="form-group">
				            <label for="" class="col-sm-2 control-label">Status <span>*</span></label>
				            <div class="col-sm-9">
				            	<select class="form-control select2" name="status">
				            		<option value="1">Active</option>
				            		<option value="0">Inactive</option>
				            	</select>
				            </div>
				        </div>
                        <h3 class="seo-info">User Collection</h3>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Collection Name <span>*</span></label>
							<div class="col-sm-9">
								<select autocomplete="off" class="form-control select2" name="colletion_ids[]" multiple>
								<option value="">Select</option>
								<?php 
								
								if(!empty($collectionList)) {
									foreach($collectionList as $collectionListRow) {
									?>
								<option value="<?=$collectionListRow->id;?>"><?=$collectionListRow->name;?></option>	
								<?php } } ?>
								</select>
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