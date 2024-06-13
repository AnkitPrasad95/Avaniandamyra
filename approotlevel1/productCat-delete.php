<?php require_once('header.php'); ?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_product_category WHERE id=?");
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
	$statement = $pdo->prepare("SELECT * FROM tbl_product_category WHERE id=?");
	$statement->execute(array($_REQUEST['id']));
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
	foreach ($result as $row) {
		$photo = $row['thumbnail_image'];
	}

	// Unlink the photo
	if($photo!='') {
		unlink('../assets/uploads/product/'.$photo);	
	}
	


	// Delete sub categories
	// $statement = $pdo->prepare("DELETE FROM tbl_product_category WHERE id=?");
	// $statement->execute(array($result['parent_category']));

	// Delete categories
	$statement = $pdo->prepare("DELETE FROM tbl_product_category WHERE id=?");
	$statement->execute(array($_REQUEST['id']));

	


	header('location: productCat.php');
?>