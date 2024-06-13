<?php
ob_start();
session_start();
error_reporting(1);
include("../../app/config.php");

if (isset($_POST["action"]) && $_POST["action"] == 'add_product_modal' && !empty($_POST["cat_ids"])) {
    $cat_ids = explode(',', $_POST["cat_ids"]);
    $products = explode(',', $_POST["products"]);
    $cat_id = end($cat_ids);
    //print_r($cat_ids); 
    //print_r($myLastElement); 

    //$data = array();
    //foreach ($cat_ids as $cat_id) {
    $statement = $pdo->prepare("SELECT id, categories, name, file_path, thumbnail_image FROM tbl_product_list where SUBSTRING_INDEX(categories, ',', 1)='$cat_id' GROUP BY id ORDER BY name ASC");
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    //array_push($data, $result);
    //}

    // $data = array_unique($data);
    // print_r($data); die;
    // $result = array();
    // foreach ($data as $array) {
    //     $result = array_merge($result, $array);
    // }

    // $uniqueArry = array();

    // foreach ($result as $val) { //Loop1 

    //     foreach ($uniqueArry as $uniqueValue) { //Loop2 

    //         if ($val == $uniqueValue) {
    //             continue 2; // Referring Outer loop (Loop 1)
    //         }
    //     }
    //     $uniqueArry[] = $val;
    // }
    if (count($result) > 0) { ?>

        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="50">SL</th>
                    <th width="50"><input type="checkbox" class="check-all"></th>
                    <th width="140">Thumbnail</th>
                    <th width="140">Name</th>
                    <th width="140">Categories</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                foreach ($result as $row) {
                    $categories = $row['categories'];
                    $sql = "SELECT `name` FROM `tbl_product_category` where id IN($categories)";
                    $name = $pdo->query($sql)->fetchAll(PDO::FETCH_COLUMN);
                    $categories = implode(',', $name);
                    $i++;
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>

                        <td><input type="checkbox" <?php if (in_array($row['id'], $products)) {
                                                        echo 'checked';
                                                    } ?> class="check-one" id="products" name="product_id[]" value="<?php echo $row['id']; ?>"></td>
                        <td style="width:150px;">
                            <img class="lazyloadimages" src="<?php echo BASE_URL; ?>assets/images/lazyloader.gif" data-src="<?php echo BASE_URL . $row['file_path'] . $row['thumbnail_image']; ?>" data-srcset="<?php echo BASE_URL . $row['file_path'] . $row['thumbnail_image']; ?>" alt="<?php echo $row['name']; ?>" height="50px;">
                        </td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $categories; ?></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <div class="fixed-bottom">
            <button onclick="bulk_product()" class="btn btn-success bulk_save">Save Product </button>
        </div>

        <script>
            //lazyloader
            let lazyimages = [].slice.call(document.querySelectorAll(".lazyloadimages"));
            //debugger;
            //console.log(lazyimages);
            //alert('test');
            if ("IntersectionObserver" in window) {
                
                //console.log("Am there");
                let observer = new IntersectionObserver((entries, observer) => {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            let lazyimage = entry.target;
                            console
                            lazyimage.src = lazyimage.dataset.src;
                            lazyimage.srcset = lazyimage.dataset.srcset;
                            lazyimage.classList.remove("lazyloadimages");
                            observer.unobserve(lazyimage);
                        }
                    });
                });
                //loop through all imgaes
                lazyimages.forEach((lazyimage) => {
                    observer.observe(lazyimage);
                });
            } else {
                console.log("Am Not");
            }
    </script>

    <?php } else {
        echo '<p>Product not available</p>';
    }

}

if (!empty($_POST['product_cat_ids'])) {
    $product_cat_ids = $_POST['product_cat_ids'];
    $sub_categories = explode(',', $_POST["sub_categorie"]);
    $query = "SELECT * FROM tbl_product_category where parent_category IN($product_cat_ids) AND parent_category > 0";
    $statement = $pdo->prepare($query);
    $statement->execute();
    $childrens = $statement->fetchAll(PDO::FETCH_OBJ);
    ?>
    <?php foreach ($childrens as $children) { ?>
        <option <?php if (in_array($children->id, $sub_categories)) {
                    echo 'selected';
                } ?> value="<?= $children->id; ?>"><?= $children->name; ?></option>";
    <?php }
}

if (isset($_POST["acton"]) && $_POST["acton"] == 'view_manage_collection') {
    $collection_id = $_POST["collection_id"];
    // Fetch state data based on the specific country 
    $statement = $pdo->prepare("select * from manage_collection where id = ?");
    $statement->execute(array($collection_id));
    $res = $statement->fetch(PDO::FETCH_OBJ);
    //print_r($res);
    $products = $res->products;
    $category_ids = explode(',', $res->categories); ?>
    <div style="margin-bottom:15px;">
        <?php foreach ($category_ids as $key => $category_id) {
            if ($key == 0) {
                $first_category_id = $category_id;
            }
            $sql = "SELECT `name` FROM `tbl_product_category` where id = $category_id";
            $cat_name = $pdo->query($sql)->fetch(PDO::FETCH_COLUMN);
        ?>
            <button onclick="show_collection_product(<?= $collection_id . ',' . $category_id; ?>)" id="btn_<?= $category_id; ?>" class="btn <?php if (!empty($_POST['category_id']) && $_POST['category_id'] == $category_id) {
                                                                                                                                        echo 'btn-success';
                                                                                                                                    } else if (empty($_POST['category_id']) && $key == 0) {
                                                                                                                                        echo 'btn-success';
                                                                                                                                    } ?> "><?= $cat_name; ?></button>
        <?php } ?>
    </div>
    <?php
    if (!empty($_POST['category_id'])) {
        $first_category_id = $_POST['category_id'];
        $statement = $pdo->prepare("SELECT id, categories, name, file_path, thumbnail_image FROM tbl_product_list where id IN($products) AND SUBSTRING_INDEX(categories, ',', 1)='$first_category_id'");
    } else {
        $statement = $pdo->prepare("SELECT id, categories, name, file_path, thumbnail_image FROM tbl_product_list where id IN($products) AND SUBSTRING_INDEX(categories, ',', 1)='$first_category_id'");
    }

    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <table id="example2" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="50">SL</th>
                <!-- <th width="50"><input type="checkbox" class="check-all"></th> -->
                <th width="140">Thumbnail</th>
                <th width="140">Name</th>
                <th width="140">Categories</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach ($result as $row) {
                $categories = $row['categories'];
                $sql = "SELECT `name` FROM `tbl_product_category` where id IN($categories)";
                $name = $pdo->query($sql)->fetchAll(PDO::FETCH_COLUMN);
                $categories = implode(',', $name);

                $i++;
            ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <!-- <td><input type="checkbox" class="check-one" id="products" name="product_id[]" value="<?php echo $row['id']; ?>"></td> -->
                    <td style="width:150px;">
                        <img class="lazyloadimages" src="<?php echo BASE_URL; ?>assets/images/lazyloader.gif" data-src="<?php echo BASE_URL . $row['file_path'] . $row['thumbnail_image']; ?>" data-srcset="<?php echo BASE_URL . $row['file_path'] . $row['thumbnail_image']; ?>" alt="<?php echo $row['name']; ?>" height="50px;">
                    </td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $categories; ?></td>

                </tr>
            <?php
            }
            ?>
        </tbody>

    </table>

    <script>
		//lazyloader
		let lazyimages = [].slice.call(document.querySelectorAll(".lazyloadimages"));
		//debugger;
		//console.log(lazyimages);
		//alert('test');
		if ("IntersectionObserver" in window) {
			
			//console.log("Am there");
			let observer = new IntersectionObserver((entries, observer) => {
				entries.forEach(function(entry) {
					if (entry.isIntersecting) {
						let lazyimage = entry.target;
						console
						lazyimage.src = lazyimage.dataset.src;
						lazyimage.srcset = lazyimage.dataset.srcset;
						lazyimage.classList.remove("lazyloadimages");
						observer.unobserve(lazyimage);
					}
				});
			});
			//loop through all imgaes
			lazyimages.forEach((lazyimage) => {
				observer.observe(lazyimage);
			});
		} else {
			console.log("Am Not");
		}
</script>       

<?php }


if (isset($_POST['product_id'])) {
    $statement = $pdo->prepare("select * from tbl_product_category where parent_category = 0 order by id asc");
    $statement->execute();
    $categoryList = $statement->fetchAll(PDO::FETCH_OBJ);

    $statement = $pdo->prepare("SELECT * FROM tbl_product_list WHERE id=?");
    $statement->execute(array($_POST['product_id']));
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        // echo "<pre>";
        // print_r($row);
        // echo "</pre>"; die();
        if (!empty($row['categories'])) {
            $categories = explode(",", $row['categories']);
        } else {
            $categories = 0;
        }

        if (!empty($row['materials'])) {
            $materials = explode(",", $row['materials']);
        } else {
            $materials = 0;
        }

        if (!empty($row['sizes'])) {
            $sizes = explode(",", $row['sizes']);
        } else {
            $sizes = 0;
        }

        $name       = $row['name'];
        $slug       = $row['slug'];
        $short_description       = $row['short_description'];
        $price       = $row['price'];
        $remarks       = $row['remarks'];
        $guest_collection       = $row['guest_collection'];
        $latest_collection       = $row['latest_collection'];
        $file_path         = $row['file_path'];
        $thumbnail_image             = $row['thumbnail_image'];
        $meta_title        = $row['meta_title'];
        $meta_keyword      = $row['meta_keyword'];
        $meta_description  = $row['meta_description'];
        $tags = $row['tags'];
    }
    if (!empty($tags)) {
        $arr = array();
        foreach (json_decode($tags) as $key => $details) {
            $data = $details->value;
            array_push($arr, $data);
        }
        $tags = implode(',', $arr);
    } else {
        $tags = "";
    } ?>
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
                    <input type="hidden" name="current_photo" value="<?php echo $thumbnail_image; ?>">
                    <div class="box box-info">
                        <div class="box-body">
                            <!-- <div class="form-group">
                        <label class="copy-head">Note: For Area Location Copy this</label>
                        <input type="text" class="area-text" style="height: 37px;text-align: center;" value="[{area}]" id="myArea" readonly="">
                        <button type="button" title="copy" onclick="myFunction()" class="btn btn-info btn-sm copy-btn"><i class="fa fa-copy" aria-hidden="true"></i></button> 
                    </div> -->



                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Name <span>*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" autocomplete="off" class="form-control" name="name" value="<?php echo $name; ?>" onchange="convertSlugOutput(this, 'gen_url')">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Slug </label>
                                <div class="col-sm-9">
                                    <input type="text" autocomplete="off" class="form-control" id="gen_url" name="slug" value="<?php echo $slug; ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Short Description <span>*</span></label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="short_description" id=""><?php echo $short_description; ?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Price </label>
                                <div class="col-sm-9">
                                    <input type="number" autocomplete="off" class="form-control" name="price" value="<?php echo $price; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Categories <span>*</span></label>
                                <div class="col-sm-9">
                                    <select autocomplete="off" class="form-control select2" name="categories[]" multiple>
                                        <option disabled="disabled" value="">Select Category</option>
                                        <?php foreach ($categoryList as $category) {
                                            $statement = $pdo->prepare("SELECT * FROM tbl_product_category where parent_category = ?");
                                            $statement->execute(array($category->id));
                                            $childrens = $statement->fetchAll(PDO::FETCH_OBJ);
                                        ?>
                                            <option disabled="disabled" <?php if (in_array($category->id, $categories)) echo "selected";  ?> value="<?= $category->id; ?>"><?= $category->name; ?></option>
                                            <?php foreach ($childrens as $children) { ?>
                                                <option disabled="disabled" <?php if (in_array($children->id, $categories)) echo "selected";  ?> value="<?= $children->id; ?>">-- <?= $children->name; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Sizes </label>
                                <div class="col-sm-9">
                                    <select autocomplete="off" class="form-control select2" name="sizes[]" multiple>
                                        <option disabled="disabled" value="">Select Size</option>
                                        <?php
                                        $statement = $pdo->prepare("select * from tbl_size order by id desc");
                                        $statement->execute();
                                        $sizeList = $statement->fetchAll(PDO::FETCH_OBJ);
                                        if (!empty($sizeList)) {
                                            foreach ($sizeList as $sizeListRow) {
                                        ?>
                                                <option disabled="disabled" <?php if ($sizes > 0 && in_array($sizeListRow->id, $sizes)) echo "selected";  ?> value="<?= $sizeListRow->id; ?>"><?= $sizeListRow->size_name; ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Materials </label>
                                <div class="col-sm-9">
                                    <select autocomplete="off" class="form-control select2" name="materials[]" multiple>
                                        <option disabled="disabled" value="">Select Materials</option>
                                        <?php
                                        $statement = $pdo->prepare("select * from 	tbl_material order by id desc");
                                        $statement->execute();
                                        $matList = $statement->fetchAll(PDO::FETCH_OBJ);
                                        if (!empty($matList)) {
                                            foreach ($matList as $matListRow) {
                                        ?>
                                                <option disabled="disabled" <?php if ($materials > 0 && in_array($matListRow->id, $materials)) echo "selected";  ?> value="<?= $matListRow->id; ?>"><?= $matListRow->name; ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>





                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Remarks <span>*</span></label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="remarks" id="" style="height:50px"><?php echo $remarks; ?></textarea>
                                </div>
                            </div>



                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Latest Collection </label>
                                <div class="col-sm-9">
                                    <input type="checkbox" name="latest_collection" <?php if ($latest_collection == 1) {
                                                                                        echo "checked";
                                                                                    } ?> value="1">
                                </div>
                            </div>

                            <?php if (!empty($thumbnail_image)) { ?>
                                <div class="form-group">
                                    <label for="" class="col-sm-2 control-label">Existing Thumbnail</label>
                                    <div class="col-sm-9" style="padding-top:5px">
                                        <img src="<?php echo BASE_URL . $file_path . $thumbnail_image; ?>" alt="event Photo" style="width:100px;">
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Thumbnail </label>
                                <div class="col-sm-6" style="padding-top:5px">
                                    <input type="file" name="photo">(Only jpg, jpeg, gif and png are allowed)
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </section>

<?php }

if (isset($_POST['action']) && $_POST['action'] == 'save_collection') {
    $valid = 1;
    $error_message = '';

    if (empty($_POST['col_id'])) {
        $valid = 0;
        $error_message .= 'collection can not be empty';
    }

    if (empty($_POST['cate_id'])) {
        $valid = 0;
        $error_message .= 'Categories can not be empty';
    }

    if (empty($_POST['products'])) {
        $valid = 0;
        $error_message .= 'Short description can not be empty';
    }


    $products = $_POST['products'];
    $products = json_decode($products, true);

    $pData = array();
    foreach ($products as $product) {

        array_push($pData, $product['id']);
    }
    $products = implode(",", $pData);

    $statement = $pdo->prepare("select * from manage_collection where collection_id = ?");
    $statement->execute(array($_POST['col_id']));
    $is_created_collection = $statement->rowCount();
    if ($is_created_collection > 0) {
        $valid = 0;
        $error_message .= 'You can not create duplicate collection.';
    }

    if ($valid == 1) {
        if (!empty($_POST['show_on_header'])) {
            $show_on_header = $_POST['show_on_header'];
        } else {
            $show_on_header = 0;
        }
        $categories = implode(",", $_POST['cate_id']);

        $data = array($_POST['col_id'], $categories, $products, '', $show_on_header, $_POST['status'], date('Y-m-d H:i:s'));
        //print_r($data); die;

        $statement = $pdo->prepare("INSERT INTO manage_collection (collection_id, categories, products, title, show_on_header, status, created_at) VALUES (?,?,?,?,?,?,?)");
        $statement->execute($data);

        // update tbl_collection
        $statement = $pdo->prepare("update tbl_collection set show_on_header =? where id =?");
        $statement->execute(array($show_on_header, $_POST['col_id']));
        echo 'Collection_added_successfully';
    } else {
        echo $error_message;
    }
}

if (isset($_POST['action']) && $_POST['action'] == 'showProductModal') {
    $collection_id = $_POST["manage_col_id"];
    $cat_id = $_POST["cat_id"];
    // Fetch state data based on the specific country 
    $statement = $pdo->prepare("select * from manage_collection where id = ?");
    $statement->execute(array($collection_id));
    $res = $statement->fetch(PDO::FETCH_OBJ);

    $statement = $pdo->prepare("select * from tbl_product_category where id = ?");
    $statement->execute(array($cat_id));
    $CatRes = $statement->fetch(PDO::FETCH_OBJ);

    //print_r($res);
    $products = $res->products;
    $statement = $pdo->prepare("SELECT id, categories, name, file_path, thumbnail_image FROM tbl_product_list where id IN($products) AND SUBSTRING_INDEX(categories, ',', 1)='$cat_id'");
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
?>
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-6">
            <label for="category_name"><?= $CatRes->name; ?></label>
        </div>
        <div class="col-md-6 text-right">
            <a type="botton" onclick="AddNewProduct(<?= $collection_id . ',' . $CatRes->id; ?>);" title="Add More Products" class="btn btn-primary btn-xs">Add More Products</a>
        </div>

    </div>

    <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="50">SL</th>
                <!-- <th width="50"><input type="checkbox" class="check-all"></th> -->
                <th width="140">Thumbnail</th>
                <th width="140">Name</th>
                <th width="140">Categories</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach ($result as $row) {
                $categories = $row['categories'];
                $sql = "SELECT `name` FROM `tbl_product_category` where id IN($categories)";
                $name = $pdo->query($sql)->fetchAll(PDO::FETCH_COLUMN);
                $categories = implode(',', $name);

                $i++;
            ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <!-- <td><input type="checkbox" class="check-one" id="products" name="product_id[]" value="<?php echo $row['id']; ?>"></td> -->
                    <td style="width:150px;">
                        <img class="lazyloadimages" src="<?php echo BASE_URL; ?>assets/images/lazyloader.gif" data-src="<?php echo BASE_URL . $row['file_path'] . $row['thumbnail_image']; ?>" data-srcset="<?php echo BASE_URL . $row['file_path'] . $row['thumbnail_image']; ?>" alt="<?php echo $row['name']; ?>" height="50px;">
                    </td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $categories; ?></td>

                </tr>
            <?php } ?>
        </tbody>

    </table>
    <script>
		//lazyloader
		let lazyimages = [].slice.call(document.querySelectorAll(".lazyloadimages"));
		//debugger;
		//console.log(lazyimages);
		//alert('test');
		if ("IntersectionObserver" in window) {
			
			//console.log("Am there");
			let observer = new IntersectionObserver((entries, observer) => {
				entries.forEach(function(entry) {
					if (entry.isIntersecting) {
						let lazyimage = entry.target;
						console
						lazyimage.src = lazyimage.dataset.src;
						lazyimage.srcset = lazyimage.dataset.srcset;
						lazyimage.classList.remove("lazyloadimages");
						observer.unobserve(lazyimage);
					}
				});
			});
			//loop through all imgaes
			lazyimages.forEach((lazyimage) => {
				observer.observe(lazyimage);
			});
		} else {
			console.log("Am Not");
		}
</script>  

<?php }

if (isset($_POST['action']) && $_POST['action'] == 'AddNewProduct') {
    $collection_id = $_POST["manage_col_id"];
    $cat_id = $_POST["cat_id"];
    // Fetch state data based on the specific country 
    $statement = $pdo->prepare("select * from manage_collection where id = ?");
    $statement->execute(array($collection_id));
    $res = $statement->fetch(PDO::FETCH_OBJ);

    $statement = $pdo->prepare("select * from tbl_product_category where id = ?");
    $statement->execute(array($cat_id));
    $CatRes = $statement->fetch(PDO::FETCH_OBJ);

    //print_r($res);
    $exist_products = $res->products;
    $exist_products = explode(',', $exist_products);
    $statement = $pdo->prepare("SELECT id, categories, name, file_path, thumbnail_image FROM tbl_product_list where SUBSTRING_INDEX(categories, ',', 1)='$cat_id'");
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
?>
    <div class="row">
        <div class="col-md-4">
            <label for="category_name"><?= $CatRes->name; ?></label>
        </div>
        <!-- <div class="col-md-4">
                <a type="botton" onclick="AddNewProduct(<?= $collection_id . ',' . $CatRes->id; ?>);" title="Add More Products" class="btn btn-primary btn-xs">Add More Products</a>
            </div> -->

    </div>

    <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="50">SL</th>
                <th width="50"><input type="checkbox" class="check-all"></th>
                <!-- <th width="140">Thumbnail</th> -->
                <th width="140">Name</th>
                <th width="140">Categories</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach ($result as $row) {
                $categories = $row['categories'];
                $sql = "SELECT `name` FROM `tbl_product_category` where id IN($categories)";
                $name = $pdo->query($sql)->fetchAll(PDO::FETCH_COLUMN);
                $categories = implode(',', $name);
                $i++;
            ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><input type="checkbox" <?php if (in_array($row['id'], $exist_products)) {
                                                    echo 'checked';
                                                } ?> class="check-one" id="products" name="product_id[]" value="<?php echo $row['id']; ?>"></td>
                   <td style="width:150px;">
                        <img class="lazyloadimages" src="<?php echo BASE_URL; ?>assets/images/lazyloader.gif" data-src="<?php echo BASE_URL . $row['file_path'] . $row['thumbnail_image']; ?>" data-srcset="<?php echo BASE_URL . $row['file_path'] . $row['thumbnail_image']; ?>" alt="<?php echo $row['name']; ?>" height="50px;">
                    </td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $categories; ?></td>

                </tr>
            <?php } ?>
        </tbody>

    </table>
    <div class="fixed-bottom">
        <div class="row">
            <div class="col-lg-6">
                <button onclick="update_bulk_product(<?= $collection_id; ?>, <?= $cat_id; ?>)" class="btn btn-success"> Update Product </button>
            </div>
            <div class="col-lg-6 text-right">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>

    <script>
		//lazyloader
		let lazyimages = [].slice.call(document.querySelectorAll(".lazyloadimages"));
		//debugger;
		//console.log(lazyimages);
		//alert('test');
		if ("IntersectionObserver" in window) {
			
			//console.log("Am there");
			let observer = new IntersectionObserver((entries, observer) => {
				entries.forEach(function(entry) {
					if (entry.isIntersecting) {
						let lazyimage = entry.target;
						console
						lazyimage.src = lazyimage.dataset.src;
						lazyimage.srcset = lazyimage.dataset.srcset;
						lazyimage.classList.remove("lazyloadimages");
						observer.unobserve(lazyimage);
					}
				});
			});
			//loop through all imgaes
			lazyimages.forEach((lazyimage) => {
				observer.observe(lazyimage);
			});
		} else {
			console.log("Am Not");
		}
</script>  

<?php }

if (isset($_POST['action']) && $_POST['action'] == 'update_collection') {
    //print_r($_POST); 
    $collection_id = $_POST['col_id'];
    $categoty_id = $_POST['categoty_id'];
    $products = $_POST['products'];
    $statement = $pdo->prepare("select * from manage_collection where id = ?");
    $statement->execute(array($collection_id));
    $ExistProductres = $statement->fetch(PDO::FETCH_OBJ);
    $query = "select id from tbl_product_list where id IN($ExistProductres->products) AND categories NOT LIKE '$categoty_id%'";
    $statement = $pdo->prepare($query);
    $statement->execute();
    $ExistProduct_ids = $statement->fetchAll(PDO::FETCH_COLUMN);
    //print_r($ExistProduct_ids);   
    if (!empty($products)) {
        $merge_products = array_merge($ExistProduct_ids, $products);
        $unique_product = array_unique($merge_products);
        $final_products = implode(",", $unique_product);
    } else {
        $final_products = implode(",", $ExistProduct_ids);
    }

    $data = array($final_products, $collection_id);

    $statement = $pdo->prepare("update manage_collection set products =? where id =?");
    $res = $statement->execute($data);
    if ($res == true) {
        echo "updated";
    } else {
        echo "Error update";
    }
}

if (isset($_POST['action']) && $_POST['action'] == 'update_collection_categories') {
    //print_r($_POST); 
    $collection_id = $_POST["manage_collection_id"];
    $cat_ids = explode(',', $_POST["update_cat_ids"]);
    $statement = $pdo->prepare("select * from manage_collection where id = ?");
    $statement->execute(array($collection_id));
    $ExistProductres = $statement->fetch(PDO::FETCH_OBJ);
    $data = array();
    foreach ($cat_ids as $cat_id) {
        $statement = $pdo->prepare("SELECT id FROM tbl_product_list where id IN($ExistProductres->products) AND categories LIKE ?");
        $statement->execute(array("$cat_id%"));
        $result = $statement->fetchAll(PDO::FETCH_COLUMN);
        array_push($data, $result);
    }
    $result = array();
    foreach ($data as $array) {
        $result = array_merge($result, $array);
    }

    $uniqueArry = array();

    foreach ($result as $val) { //Loop1 

        foreach ($uniqueArry as $uniqueValue) { //Loop2 

            if ($val == $uniqueValue) {
                continue 2; // Referring Outer loop (Loop 1)
            }
        }
        $uniqueArry[] = $val;
    }
    $final_products = implode(",", $uniqueArry);
    //print_r($final_products);

    $data = array($_POST["update_cat_ids"], $final_products, $collection_id);

    $statement = $pdo->prepare("update manage_collection set categories=?, products =? where id =?");
    $res = $statement->execute($data);
    if ($res == true) {
        echo "updated";
    } else {
        echo "Error update";
    }
}
?>

