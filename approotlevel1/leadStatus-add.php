<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

	if(empty($_POST['status_name'])) {
		$valid = 0;
		$error_message .= 'Leas Status can not be empty<br>';
    }



	if($valid == 1) {


		$statement = $pdo->prepare("INSERT INTO tbl_lead_status (status_name, created_at) VALUES (?,?)");
		$statement->execute(array($_POST['status_name'], date('Y-m-d H:i:s')));
			
		$success_message = 'Leas Status is added successfully!';

		unset($_POST['status_name']);
		
	
	}
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Add Lead Status</h1>
	</div>
	<div class="content-header-right">
		<a href="leadStatus.php" class="btn btn-primary btn-sm">View All</a>
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
							<label for="" class="col-sm-2 control-label">Status name <span>*</span></label>
							<div class="col-sm-9">
								<input type="text" autocomplete="off" class="form-control" name="status_name" value="<?php if(isset($_POST['status_name'])){echo $_POST['status_name'];} ?>">
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