<?php require_once('header.php'); ?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_customers WHERE id=?");
	$statement->execute(array($_REQUEST['id'])); 
	$total = $statement->rowCount();
	if( $total == 0 ) {
		header('location: logout.php');
		exit;
	}
}
?>

<?php

	// Getting photo ID to unlink from folder
	

	// Delete from tbl_customers
	$statement = $pdo->prepare("DELETE FROM tbl_customers WHERE id=?");
	$statement->execute(array($_REQUEST['id']));
	header('location: users.php');
?>