<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

	if(empty($_POST['name'])) { 
		$valid = 0;
		$error_message .= 'Name can not be empty<br>';
	}

	

	




	if($valid == 1) {
		// getting auto increment id
		$statement = $pdo->prepare("SHOW TABLE STATUS LIKE 'tbl_blog_category'");
		$statement->execute();
		$result = $statement->fetchAll();
		foreach($result as $row) {
			$ai_id=$row[10];
		}


		$statement = $pdo->prepare("INSERT INTO tbl_collection (name,slug,status,created_at) VALUES (?,?,?,?)");
		$statement->execute(array($_POST['name'],$_POST['slug'],$_POST['status'],date('Y-m-d H:i:s')));
			
		$success_message = 'Collection is added successfully!';

		unset($_POST['name']);
		unset($_POST['slug']);
		unset($_POST['status']);
	}
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Add Collection</h1>
	</div>
	<div class="content-header-right">
		<a href="collection.php" class="btn btn-primary btn-sm">View All</a>
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
				            <label for="" class="col-sm-2 control-label">Status <span>*</span></label>
				            <div class="col-sm-9">
				            	<select class="form-control select2" name="status">
				            		<option value="1">Enable</option>
				            		<option value="0">Disable</option>
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