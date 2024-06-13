
<?php
session_start();
require_once('app/autoload.php');
if (!empty($_SESSION['backtoshop'])) {
    $backtoshop = BASE_URL . $_SESSION['backtoshop'];
} else {
    $backtoshop = BASE_URL;
}

if (isset($_GET['remove_order'])) {
    //echo $_GET['remove_order'];
    $remove = $query->orderRemove($_GET['remove_order']);
    if ($remove == 'deleted') {
        //echo "<script>alert('Order deleted.');</script>";
        echo "<script>window.location.href='cart';</script>";
    } else {
        //echo "<script>alert('Order not deleted.');</script>";
        echo "<script>window.location.href='cart';</script>";
    }
}


if (isset($_REQUEST['myData'])) {
    if (isset($_SESSION['customer'])) {
        $data_cart = $query->orderList($user_id);
    } else {
        $data = $_REQUEST['myData'];
        $data_cart = json_decode($data, true);
    }
    

    // echo "<pre>";
    // print_r($data_cart);
    // echo "</pre>";
    //die;
    $cartitems = '';

    if (!empty($data_cart)) { ?>
        <div class="order-table">
            <div class="order-table-heading">
                <div class="row">
                    <div class="col-md-4">
                        <div class="product">
                            <p class="font-lg bold mid-dark-text mb-0">Product</p>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="comment">
                            <p class="font-lg bold mid-dark-text mb-0">Comment for Customization</p>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex justify-content-between align-items-center">
                        <div class="quantity">
                            <p class="font-lg bold mid-dark-text mb-0">Quantity</p>
                        </div>
                        <div class="remove">
                            <p class="font-lg bold mid-dark-text mb-0">Remove</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="order-table-data">
                <?php foreach ($data_cart as $orderListRow) { 
                    $product_id = $orderListRow['id'];
                    $get_product = $query->getProducts($product_id);
                    if (isset($_SESSION['customer'])) {
                        $message = $orderListRow['comment'];
                        $quantity = $orderListRow['quantity'];
                    } else {
                        $message = $orderListRow['msg'];
                        $quantity = $orderListRow['quantity'];
                    }
                    $product_name  = $get_product->name;
                    if(!empty($get_product->thumbnail_image)) {
                        $image = BASE_URL.$get_product->file_path.$get_product->thumbnail_image;
                    } else {
                        $image = BASE_URL.'assets/uploads/placeholder.jpg';
                    }
                    ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="product">
                                <div class="product-image">
                                    <img src="<?= $image; ?>" alt="product1" />
                                </div>
                                <div class="product-details">
                                    <p class="font-md mb-1 mid-dark-text"><?= $product_name; ?></p>

                                </div>
                            </div>
                        </div>
                        <?php if ($message != '') { ?>
                            <div class="col-md-5 align-self-center">
                                <div class="comment">
                                    <div class="product-discription">
                                        <p class="font-sm bold mid-dark-text d-md-none d-sm-block mb-1">Comment for Customization</p>
                                        <p class="font-lg mid-dark-text mb-0"><?= $message; ?></p>

                                    </div>
                                </div>
                            </div>

                        <?php } else { ?>
                            <div class="col-md-5">
                                <div class="comment product-discription-form">
                                    <div class="product-form">

                                        <div class="form-group">
                                            <p class="font-sm bold mid-dark-text d-md-none d-sm-block mb-1">Comment for Customization</p>
                                            <textarea rows="3" class="form-control font-md" placeholder="How can we help you?" name="details-<?= $product_id; ?>" id="comment-<?= $product_id; ?>"></textarea>
                                            <span id="comment_msg_<?=$product_id?>"></span>  
                                        </div>

                                        <div class="d-flex align-items-center mt-4">
                                            <button class="save btn btn-primary btn-animation me-4 add_comment"  value="<?php echo $product_id; ?>">
                                                <span class="font-md px-4">Save</span>
                                            </button>
                                            <a class="cancel-comment btn btn-outline-primary btn-animation ms-3 ">
                                                <span class="font-md  px-4">Cancel</span>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="product-discription">
                                        <a class="customization btn btn-outline-primary mt-5 ps-4 ">
                                            <img class=" icon-32 me-3" src="./assets/images/icon-comment.svg" alt="comment" /> Add Comment
                                        </a>
                                    </div>
                                   
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-md-3 d-flex justify-content-between align-items-center">
                            <div class="quantity">
                                <p class="font-sm bold mid-dark-text d-md-none d-sm-block mb-1">Quantity</p>
                                <input class="form-control" type="number"  id="quantity-<?= $product_id; ?>" name="quantity-<?= $product_id; ?>" value="<?php if($quantity > 1) { echo $quantity; } else { echo 1; } ?>" <?php if ($quantity > 1) { echo 'readonly'; } ?> />
                                <span id="qty_msg_<?=$product_id?>"></span>  
                            </div>
                            </form>
                            <div class="remove">
                                <?php if (isset($_SESSION['customer'])) { ?>
                                    <a class="remove-btn icon-btn" href="?remove_order=<?= $product_id; ?>" onclick="return confirm('Are you sure you want to delete?')">
                                        <img src="./assets/images/icon-trash.svg" alt="trash" />
                                    </a>
                                <?php } else { ?> 
                                    <button class="remove-btn icon-btn delete_cart border-0 no-bg-color" value="<?php echo $product_id; ?>"  onclick="return confirm('Are you sure you want to delete?')">
                                        <img src="./assets/images/icon-trash.svg" alt="trash" />
                                    </button>
                                <?php } ?>    
                                
                            </div>
                        </div>


                    </div>
                <?php } ?>
            </div>
            <!-- <div class="order-action">
                <a href="" class="btn btn-primary btn-animation">
                    <span class="font-lg px-4">Send Mail</span>
                </a>
            </div> -->
            <div class="mail-shoping-btn-wrap d-flex justify-content-end mb-5">
                <a href="<?=$backtoshop;?>" class="continue-shop-btn btn btn-outline-primary btn-animation">Continue Shopping</a>
                <?php if (!empty($user_name)) { ?>
                    <a data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#send_mail_popup" class="btn btn-primary btn-animation ms-3">Send Mail</a>
                <?php } else { ?>  
                    <!-- <a data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#loginSignup" class="font-md semibold white-text">
                        SIGN IN
                    </a> -->
                    <a data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#loginSignup" class="btn btn-primary btn-animation ms-3">Send Mail</a>
                <?php } ?>
                
            </div>
        </div>
    <?php } else { ?>
        <p class="woocommerce-info">No Order found.</p></br>
        <div class="mail-shoping-btn-wrap d-flex justify-content-end mb-5">
            <a href="<?=$backtoshop;?>" class="continue-shop-btn btn btn-outline-primary btn-animation">Continue Shopping</a>
        </div>
    <?php } 
    echo $cartitems;
}

?>