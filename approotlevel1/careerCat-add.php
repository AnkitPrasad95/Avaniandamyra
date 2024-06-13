<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

	if(empty($_POST['name'])) { 
		$valid = 0;
		$error_message .= 'Job Category can not be empty<br>';
	}

    if(empty($_POST['type'])) { 
		$valid = 0;
		$error_message .= 'Job Type can not be empty<br>';
	}

	if($valid == 1) {


		$statement = $pdo->prepare("INSERT INTO tbl_career_category (name,type,location,created_at) VALUES (?,?,?,?)");
		$statement->execute(array($_POST['name'],$_POST['type'],$_POST['location'],date('Y-m-d H:i:s')));
			
		$success_message = 'Career Category is added successfully!';

		unset($_POST['name']);
		unset($_POST['type']);
		unset($_POST['location']);
	}
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Add Career Category</h1>
	</div>
	<div class="content-header-right">
		<a href="careerCat.php" class="btn btn-primary btn-sm">View All</a>
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
							<label for="" class="col-sm-2 control-label">Job Category <span>*</span></label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" class="form-control" name="name" value="<?php if(isset($_POST['name'])){echo $_POST['name'];} ?>" >
							</div>
						</div>
						 <div class="form-group">
							<label for="" class="col-sm-2 control-label">Job Type </label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" class="form-control" name="type" value="<?php if(isset($_POST['type'])){echo $_POST['type'];} ?>">
							</div>
						</div>
                        <div class="form-group">
							<label for="" class="col-sm-2 control-label">Job Location </label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" class="form-control" name="location" value="<?php if(isset($_POST['location'])){echo $_POST['location'];} ?>">
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