<?php require_once('header.php');

if (isset($_POST['add_form'])) {
    $valid = 1;


    if (empty($_POST['name'])) {
        $valid = 0;
        $error_message .= 'Material can not be empty<br>';
    }

    if ($valid == 1) {
        $statement = $pdo->prepare("INSERT INTO tbl_material (name, description, created_at) VALUES (?,?,?)");
        $statement->execute(array($_POST['name'], $_POST['description'], date('Y-m-d H:i:s')));
        $success_message = "Size added successfully.";
        unset($_POST['name']);
        unset($_POST['description']);
    }
}


if (isset($_POST['update_form'])) {

    //print_r($_POST); die();
    $statement = $pdo->prepare("update tbl_material set name=?, description=? where id=?");
    $statement->execute(array($_POST['name'], $_POST['description'], $_POST['material_id']));

    unset($_POST['name']);
    unset($_POST['description']);
    echo "<script>alert('Material updated successfully.')</script>";
    echo "<script>window.location.href='material.php'</script>";
}


if (isset($_POST['delete_activity'])) {
    // Delete from tbl_solution
    $statement = $pdo->prepare("DELETE FROM tbl_material WHERE id=?");
    $statement->execute(array($_POST['delete_material_id']));
    $success_message = "Material is deleted successfully.";
}

if (isset($_POST['material_id'])) {
    $statement = $pdo->prepare("SELECT * FROM tbl_material WHERE id=?");
    $statement->execute(array($_POST['material_id']));
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        $material_id = $row['id'];
        $name = $row['name'];
        $description = $row['description'];
    }
} else {
    $name = "";
    $description = "";
}



?>
<style>
    .btn-info.bkbtn {
        position: relative;
        background-color: #1b1c1c;
        border-color: #d60012;
        float: right;
        margin-right: -70px;
        bottom: 28px;
    }
</style>
<section class="content-header">
    <div class="content-header-left">
        <h1><?php if (isset($_POST['material_id'])) { ?> Edit <?php } else { ?> Add <?php } ?> Material</h1>
        <!-- <a class="btn btn-info bkbtn" href="statisticalCountry.php"><i class="fa fa-arrow-left"></i></a> -->
    </div>
</section>

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
                            <label for="" class="col-sm-2 control-label">Name <span>*</span></label>
                            <div class="col-sm-9">
                                <input type="text" autocomplete="off" class="form-control" name="name" value="<?= $name; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Description <span>*</span></label>
                            <div class="col-sm-9">
                                <input type="text" autocomplete="off" class="form-control" name="description" value="<?= $description; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label"></label>
                            <div class="col-sm-6">
                                <?php if (isset($_POST['material_id'])) { ?>
                                    <input type="hidden" name="material_id" value="<?= $material_id; ?>">
                                    <button type="submit" class="btn btn-success pull-left" name="update_form">Update</button>
                                <?php } else { ?>
                                    <button type="submit" class="btn btn-success pull-left" name="add_form">Save</button>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>

</section>

<section class="content-header">
    <div class="content-header-left">
        <h1>View Materials</h1>
    </div>
    <!-- <div class="content-header-right">
		<a href="?add=true" class="btn btn-primary btn-sm">Add Service</a>
	</div> -->
</section>

<section class="content">

    <div class="row">
        <div class="col-md-12">


            <div class="box box-info">

                <div class="box-body table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $i = 0;
                            $statement = $pdo->prepare("SELECT * from tbl_material order by id desc");
                            $statement->execute();
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($result as $row) { ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['description']; ?></td>
                                    <td>
                                        <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="material_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" title="edit" class="btn btn-info btn-xs" name="edit_activity"><i class="fa fa-pencil"></i></button>
                                        </form>
                                        <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="delete_material_id" value="<?php echo $row['id']; ?>">
                                            <button title="delete" type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure, you want to delete?')" name="delete_activity"><i class="fa fa-trash"></i></button>
                                        </form>

                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>


<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
            </div>
            <div class="modal-body">
                Are you sure want to delete this item?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>