<?php require_once('header.php'); ?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_product_list WHERE id=?");
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
	$statement = $pdo->prepare("SELECT * FROM tbl_product_list WHERE id=?");
	$statement->execute(array($_REQUEST['id']));
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
	foreach ($result as $row) {
		$photo = $row['photo'];
		$banner = $row['banner'];
	}

	// Unlink the photo
	if($photo!='') {
		unlink('../assets/uploads/product-list/'.$photo);	
	}

	if($banner!='') {
		unlink('../assets/uploads/product-list/'.$banner);	
	}

	$statement = $pdo->prepare("SELECT * FROM tbl_gallery WHERE p_category_id=?");
	$statement->execute(array($_REQUEST['id']));
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
	foreach ($result as $row) {
		$photo_name = $row['photo_name'];
		if($photo_name!='') {
			unlink('../assets/uploads/product_photo/'.$photo_name);	
		}
	}

	$statement = $pdo->prepare("DELETE FROM tbl_gallery WHERE p_category_id=?");
	$statement->execute(array($_REQUEST['id']));
	// Delete from tbl_product_list
	$statement = $pdo->prepare("DELETE FROM tbl_product_list WHERE id=?");
	$statement->execute(array($_REQUEST['id']));

	header('location: productList.php');
?>