<?php require_once('header.php');

$statement = $pdo->prepare("select * from tbl_collection where show_on_header = 0 order by id asc");
$statement->execute();
$collectionList = $statement->fetchAll(PDO::FETCH_OBJ);

if (isset($_POST['form1'])) {
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



    if ($valid == 1) {
        $colletion_ids = implode(",", $_POST['colletion_ids']);
        $statement = $pdo->prepare("UPDATE tbl_customers SET  user_name=?, email=?, first_name=?, last_name=?, contact=?, website=?,  role=?, status=?, colletion_ids=? WHERE id=?");
        $statement->execute(array($_POST['user_name'], $_POST['email'], $_POST['first_name'], $_POST['last_name'], $_POST['contact'],  $_POST['website'], $_POST['role'], $_POST['status'], $colletion_ids, $_REQUEST['id']));
        
        if(!empty($_POST['password'])) {
            $statement = $pdo->prepare("UPDATE tbl_customers SET password=?, updated_password=? WHERE id=?");
            $statement->execute(array(md5($_POST['password']), $_POST['password'], $_REQUEST['id']));
        }

        $success_message = 'User is updated successfully!';
    }
}
?>

<?php
if (!isset($_REQUEST['id'])) {
    header('location: logout.php');
    exit;
} else {
    // Check the id is valid or not
    $statement = $pdo->prepare("SELECT * FROM tbl_customers WHERE id=?");
    $statement->execute(array($_REQUEST['id']));
    $total = $statement->rowCount();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    if ($total == 0) {
        header('location: logout.php');
        exit;
    }
}
?>

<section class="content-header">
    <div class="content-header-left">
        <h1>Edit User</h1>
    </div>
    <div class="content-header-right">
        <a href="users.php" class="btn btn-primary btn-sm">View All</a>
    </div>
</section>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_customers WHERE id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $user_name              = $row['user_name'];
    $email              = $row['email'];
    $first_name       = $row['first_name'];
    $last_name       = $row['last_name'];
    $contact   = $row['contact'];
    $website       = $row['website'];
    $role         = $row['role'];
    $status       = $row['status'];
    $colletion_ids             = explode(',',$row['colletion_ids']);
}
?>

<section class="content">

    <div class="row">
        <div class="col-md-12">

            <?php if ($error_message) : ?>
                <div class="callout callout-danger">
                    <p>
                        <?php echo $error_message; ?>
                    </p>
                </div>
            <?php endif; ?>

            <?php if ($success_message) : ?>
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
                                <input type="text" autocomplete="off" class="form-control" name="user_name" value="<?php echo $user_name; ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Email<span>*</span></label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" name="email" value="<?php echo $email; ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">First Name<span>*</span> </label>
                            <div class="col-sm-9">
                                <input type="text" autocomplete="off" class="form-control" name="first_name" value="<?php echo $first_name; ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Last Name<span>*</span> </label>
                            <div class="col-sm-9">
                                <input type="text" autocomplete="off" class="form-control" name="last_name" value="<?php echo $last_name; ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Contact No. <span>*</span></label>
                            <div class="col-sm-9">
                                <input type="number" autocomplete="off" class="form-control" name="contact" value="<?php echo $contact; ?>">

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Website </label>
                            <div class="col-sm-9">
                                <input type="text" autocomplete="off" class="form-control" name="website" value="<?php echo $website; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-9">
                                <input type="password" autocomplete="off" class="form-control" name="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Role <span>*</span></label>
                            <div class="col-sm-9">
                                
                                <select autocomplete="off" class="form-control" name="role" required>
                                    <option <?php if($role == 'customer') { echo 'selected'; } ?> value="customer">Customer</option>
                                    <option <?php if($role == 'visitor') { echo 'selected'; } ?> value="visitor">Visitor</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
				            <label for="" class="col-sm-2 control-label">Status <span>*</span></label>
				            <div class="col-sm-9">
				            	<select class="form-control select2" name="status">
				            		<option <?php if($status == 1) {echo 'selected'; } ?> value="1">Active</option>
				            		<option <?php if($status == 0) {echo 'selected'; } ?> value="0">Inactive</option>
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

                                    if (!empty($collectionList)) {
                                        foreach ($collectionList as $collectionListRow) {
                                    ?>
                                            <option <?php if(in_array($collectionListRow->id, $colletion_ids)) { echo 'selected'; } ?> value="<?= $collectionListRow->id; ?>"><?= $collectionListRow->name; ?></option>
                                    <?php }
                                    } ?>
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