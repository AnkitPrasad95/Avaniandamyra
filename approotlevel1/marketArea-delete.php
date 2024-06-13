<?php require_once('header.php'); ?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_market_area WHERE id=?");
	$statement->execute(array($_REQUEST['id']));
	$total = $statement->rowCount();
	if( $total == 0 ) {
		header('location: logout.php');
		exit;
	}
}


	// Delete from tbl_market_area
	$statement = $pdo->prepare("DELETE FROM tbl_market_area WHERE id=?");
	$statement->execute(array($_REQUEST['id']));

	header('location: marketArea.php');
?>