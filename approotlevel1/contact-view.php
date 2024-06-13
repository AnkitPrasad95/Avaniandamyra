<?php require_once('header.php'); ?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_contact WHERE id=?");
	$statement->execute(array($_REQUEST['id']));
	$total = $statement->rowCount();
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	if( $total == 0 ) {
		header('location: logout.php');
		exit;
	} else {
	    $query = $pdo->prepare("update tbl_contact set read_status =? where id =? and read_status = 0" );
	    $query->execute(array(1, $_REQUEST['id']));
	}
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Contact Details</h1>
	</div>
	<div class="content-header-right backbtns">
		<a href="contact-leads.php" class="btn btn-primary btn-sm">Back</a>
	</div>
	
</section>

<?php							
foreach ($result as $row) {
    $name = $row['name'];
    $email = $row['email'];
    $phone = $row['phone'];
    $comments = $row['comment'];
    $date = date('d M, Y', strtotime($row['created_at']));
}
?>


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
							<div class="col-sm-6">
								<input type="text" autocomplete="off" class="form-control" name="name" value="<?php echo $name; ?>" readonly/>
							</div>
						</div>
						
						
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Email <span>*</span></label>
							<div class="col-sm-6">
								<input type="text" autocomplete="off" class="form-control" name="name" value="<?php echo $email; ?>" readonly/>
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Phone <span>*</span></label>
							<div class="col-sm-6">
								<input type="text" autocomplete="off" class="form-control" name="name" value="<?php echo $phone; ?>" readonly/>
							</div>
						</div>
						
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Date <span>*</span></label>
							<div class="col-sm-6">
								<input type="text" autocomplete="off" class="form-control" name="name" value="<?php echo $date; ?>" readonly/>
							</div>
						</div>
						
						
						
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Comments <span>*</span></label>
							<div class="col-sm-9">
								<textarea class="form-control" name="description" readonly/><?php echo $comments; ?></textarea>
							</div>
						</div>
						
					
					</div>
				</div>

			</form>


		</div>
	</div>

</section>

<?php require_once('footer.php'); ?>