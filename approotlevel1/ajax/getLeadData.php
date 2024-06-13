<?php
ob_start();
session_start();
include("../../app/config.php");
if (isset($_POST['contact_id'])) {

    $query = $pdo->prepare("update tbl_contact set read_status =? where id =? and read_status = 0");
    $query->execute(array(1, $_REQUEST['contact_id']));

    $statement = $pdo->prepare("SELECT * FROM tbl_contact where id =?");
    $statement->execute(array($_POST['contact_id']));
    $result = $statement->fetch(PDO::FETCH_OBJ);  ?>


    <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">

        <div class="box box-info">
            <div class="box-body">
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">Theme <span></span></label>
                    <div class="col-sm-6">
                        <input type="text" autocomplete="off" class="form-control" name="name" value="<?= $result->theme; ?>" readonly />
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">Email <span></span></label>
                    <div class="col-sm-6">
                        <input type="text" autocomplete="off" class="form-control" name="name" value="<?= $result->email; ?>" readonly />
                    </div>
                </div>
                <?php if (!empty($result->attachment)) { ?>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Attachment</label>
                        <div class="col-sm-9" style="padding-top:5px">
                            <img src="<?php echo BASE_URL . $result->file_path . $result->attachment; ?>" alt="event Photo" style="width:400px;">
                        </div>
                    </div>
                <?php } ?>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">Comments <span></span></label>
                    <div class="col-sm-9">
                        <textarea class="form-control" rows='2' name="description" readonly><?= $result->comment; ?></textarea>
                    </div>
                </div>


            </div>
        </div>

    </form>

<?php }

if (isset($_POST['career_id'])) {

    $query = $pdo->prepare("update tbl_career set read_status =? where id =? and read_status = 0");
    $query->execute(array(1, $_REQUEST['career_id']));

    $statement = $pdo->prepare("SELECT * FROM tbl_career where id =?");
    $statement->execute(array($_POST['career_id']));
    $result = $statement->fetch(PDO::FETCH_OBJ);  ?>


    <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">

        <div class="box box-info">
            <div class="box-body">
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">Name <span>*</span></label>
                    <div class="col-sm-6">
                        <input type="text" autocomplete="off" class="form-control" name="name" value="<?= $result->name; ?>" readonly />
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">Email <span>*</span></label>
                    <div class="col-sm-6">
                        <input type="text" autocomplete="off" class="form-control" name="name" value="<?= $result->email; ?>" readonly />
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">Contact No. <span>*</span></label>
                    <div class="col-sm-6">
                        <input type="text" autocomplete="off" class="form-control" name="name" value="<?= $result->phone; ?>" readonly />
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">Comments <span>*</span></label>
                    <div class="col-sm-9">
                        <textarea class="form-control" rows='8' name="description" readonly><?= $result->message; ?></textarea>
                    </div>
                </div>


            </div>
        </div>

    </form>

<?php }


if (isset($_POST['requestLead_id'])) {

    $query = $pdo->prepare("update tbl_user_request set read_status =? where id =? and read_status = 0");
    $query->execute(array(1, $_REQUEST['requestLead_id']));

    $statement = $pdo->prepare("SELECT * FROM tbl_user_request where id =?");
    $statement->execute(array($_POST['requestLead_id']));
    $result = $statement->fetch(PDO::FETCH_OBJ);  ?>


    <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">

        <div class="box box-info">
            <div class="box-body">
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">Organization name <span></span></label>
                    <div class="col-sm-6">
                        <input type="text" autocomplete="off" class="form-control" name="name" value="<?= $result->organization_name; ?>" readonly />
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">Person <span></span></label>
                    <div class="col-sm-6">
                        <input type="text" autocomplete="off" class="form-control" name="name" value="<?= $result->person; ?>" readonly />
                    </div>
                </div>



                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">Email<span></span></label>
                    <div class="col-sm-6">
                        <input type="text" autocomplete="off" class="form-control" name="name" value="<?= $result->email; ?>" readonly />
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">Phone<span></span></label>
                    <div class="col-sm-6">
                        <input type="text" autocomplete="off" class="form-control" name="name" value="<?= $result->phone; ?>" readonly />
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">Address<span></span></label>
                    <div class="col-sm-6">
                        <input type="text" autocomplete="off" class="form-control" name="name" value="<?= $result->address; ?>" readonly />
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">Buyer Type<span></span></label>
                    <div class="col-sm-6">
                        <input type="text" autocomplete="off" class="form-control" name="name" value="<?= $result->type_of_buyer; ?>" readonly />
                    </div>
                </div>



                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">Message <span></span></label>
                    <div class="col-sm-9">
                        <textarea class="form-control" rows='3' name="description" readonly><?= $result->message; ?></textarea>
                    </div>
                </div>


            </div>
        </div>

    </form>

<?php }



if (isset($_POST['order_id'])) {

    $query = $pdo->prepare("update tbl_orders set read_status =? where id =? and read_status = 0");
    $query->execute(array(1, $_REQUEST['order_id']));

    $statement = $pdo->prepare("SELECT * FROM tbl_order_details where order_id = ?");
    $statement->execute(array($_POST['order_id']));
    $result = $statement->fetchAll(PDO::FETCH_OBJ);

//     echo "<pre>";
//     print_r($result);
//     echo "</pre>";
// ?>
    <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="50">SL</th>
                <th width="140">Thumbnail</th>
                <th width="140">Name</th>
                <th width="140">Quantity</th>
                <th width="140">Message</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach ($result as $row) {
                $statement = $pdo->prepare("SELECT id, name, file_path, thumbnail_image FROM tbl_product_list where id =?");
                $statement->execute(array($row->product_id));
                $product = $statement->fetch(PDO::FETCH_ASSOC);
                $i++;
            ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td style="width:150px;"><img src="<?php echo BASE_URL . $product['file_path'] . $product['thumbnail_image']; ?>" alt="<?php echo $product['name']; ?>" style="width:100px;height:100px;"></td>
                    <td><?php echo $product['name']; ?></td>
                    <td><?php echo $row->quantity; ?></td>
                    <td><?php echo $row->message; ?></td>
                    

                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
<?php }

if (isset($_POST['user_id'])) {
    $statement = $pdo->prepare("SELECT * FROM tbl_wishlist where user_id = ?");
    $statement->execute(array($_POST['user_id']));
    $result = $statement->fetchAll(PDO::FETCH_OBJ);

//     echo "<pre>";
//     print_r($result);
//     echo "</pre>";
// ?>
    <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="50">SL</th>
                <th width="140">Thumbnail</th>
                <th width="140">Name</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach ($result as $row) {
                $statement = $pdo->prepare("SELECT id, name, file_path, thumbnail_image FROM tbl_product_list where id =?");
                $statement->execute(array($row->product_id));
                $product = $statement->fetch(PDO::FETCH_ASSOC);
                $i++;
            ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td style="width:150px;"><img src="<?php echo BASE_URL . $product['file_path'] . $product['thumbnail_image']; ?>" alt="<?php echo $product['name']; ?>" style="width:100px;height:100px;"></td>
                    <td><?php echo $product['name']; ?></td>
                    

                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
<?php }

?>