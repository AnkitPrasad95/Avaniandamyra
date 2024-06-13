<?php
session_start();
require_once('app/autoload.php');
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
// if (!isset($_SESSION['customer'])) {
//     echo "<script>window.location.href='" . BASE_URL . "';</script>";
// }

if(isset($_SESSION['backtoshop'])){
    $backtoshop = BASE_URL.$_SESSION['backtoshop'];
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once('inc/meta-head.php') ?>
</head>

<body>
    <?php include_once('inc/header.php') ?>
    <?php
    $orderList = $query->orderList($user_id);
   
    ?>
    <section class="full-container py-4">
        <div class="heading py-7 d-flex justify-content-between align-items-center">
            <p class="font-xxl2 headingFont bold font-mid-dark mb-0">
                Order List
            </p>

        </div>
        <?php if (!empty($orderList)) { ?>
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
                    <?php foreach ($orderList as $orderListRow) { ?>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="product">
                                    <div class="product-image">
                                        <img src="<?= BASE_URL . $orderListRow->file_path . $orderListRow->thumbnail_image; ?>" alt="product1" />
                                    </div>
                                    <div class="product-details">
                                        <p class="font-md mb-1 mid-dark-text"><?= $orderListRow->name; ?></p>

                                    </div>
                                </div>
                            </div>
                            <?php if ($orderListRow->comment != '') { ?>
                                <div class="col-md-5">
                                    <div class="comment">
                                        <div class="product-discription">
                                            <p class="font-sm bold mid-dark-text d-md-none d-sm-block mb-1">Comment for Customization</p>
                                            <p class="font-lg mid-dark-text mb-0"><?= $orderListRow->comment; ?></p>

                                        </div>
                                    </div>
                                </div>

                            <?php } else { ?>
                                <div class="col-md-5 ">
                                    <div class="comment  product-discription-form">
                                        <div class="product-form">

                                            <div class="form-group">
                                                <p class="font-sm bold mid-dark-text d-md-none d-sm-block mb-1">Comment for Customization</p>
                                                <textarea rows="3" class="form-control font-md" placeholder="How can we help you?" name="details-<?= $orderListRow->order_id; ?>" id="comment-<?= $orderListRow->order_id; ?>"></textarea>
                                            </div>

                                            <div class="d-flex align-items-center mt-4">
                                                <a class="save btn btn-primary btn-animation me-4" onclick="add_comment(<?= $orderListRow->order_id; ?>)">
                                                    <span class="font-md px-4">Save</span>
                                                </a>
                                                <a class="cancel-comment btn btn-outline-primary btn-animation ms-3 ">
                                                    <span class="font-md  px-4">Cancel</span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="product-discription">
                                            <a class="customization btn btn-outline-primary mt-5 ps-4">
                                                <img class=" icon-32 me-3" src="./assets/images/icon-comment.svg" alt="comment" /> Add Comment
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="col-md-3 d-flex justify-content-between align-items-center">
                                <div class="quantity">
                                    <p class="font-sm bold mid-dark-text d-md-none d-sm-block mb-1">Quantity</p>
                                    <input class="form-control" type="number"  id="quantity-<?= $orderListRow->order_id; ?>" name="quantity-<?= $orderListRow->order_id; ?>" value="<?= $orderListRow->quantity; ?>" <?php if ($orderListRow->quantity > 1) {
                                                                                                                                                                                                                        echo 'readonly';
                                                                                                                                                                                                                    } ?> />

                                </div>
                                </form>
                                <div class="remove">
                                    <a class="remove-btn icon-btn" href="?remove_order=<?= $orderListRow->order_id; ?>" onclick="return confirm('Are you sure you want to delete?')">
                                        <img src="./assets/images/icon-trash.svg" alt="trash" />
                                    </a>
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
                    <a data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#send_mail_popup" class="btn btn-primary btn-animation ms-3">Send Mail</a>
                </div>
            </div>
        <?php } else { ?>
            <p class="woocommerce-info">No Order found.</p></br>
            <div class="mail-shoping-btn-wrap d-flex justify-content-end mb-5">
                <a href="<?=$backtoshop;?>" class="continue-shop-btn btn btn-outline-primary btn-animation">Continue Shopping</a>
            </div>
        <?php } ?>
    </section>

    <div class="modal fade" id="send_mail_popup" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" data-dismiss="modal" data-toggle="modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4">
                <a data-bs-dismiss="modal" class="modal-close d-none">
                    <img src="<?php echo BASE_URL; ?>/assets/images/i-cross.svg" alt="cross" />
                </a>

                <div class="modal-product-details p-3">
                    <div class="product-details-card">
                        <div class="product-discription-form">
                            <div class="row">
                                <div class="col-12 mb-5">
                                    <div class="form-group">
                                        <label class="font-sm mb-2" for="details">Message *</label>
                                        <textarea id="pro-cart-msg" rows="3" class="form-control font-md" placeholder="How can we help you?"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="card-btns">
                                <a id="send-mail-btn" onclick="send_email(<?= rand(); ?>)" class="btn btn-primary btn-animation w-100">
                                    <span class="font-lg">Submit</span>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once('inc/footer.php') ?>
    <?php include_once('inc/footer-script.php') ?>
    <script>
        $(document).ready(function() {
            var retrievedObject = null;
            if (localStorage) {
                retrievedObject = localStorage.getItem('myArray');
            } else {
                alert("Error: This browser is still not supported; Please use google chrome!");
            }
            var parsedArray = null;
            if (retrievedObject) {
                parsedArray = JSON.parse(retrievedObject);
            }
            if (parsedArray == null) {
                $("#id-cart-value").html(0);
            } else {
                $("#id-cart-value").html(parsedArray.length);
            }

            $.ajax({
                type: "POST",
                url: '<?=BASE_URL;?>view_cart_details.php',
                data: {
                    myData: localStorage.getItem('myArray')
                },
                cache: false,
                //dataType: "JSON",
                success: function(data) {

                    $("#carttable").html(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('error');
                }
            });





        });
    </script>
    <script type="text/javascript">
        function add_comment(order_id) {
            //alert(order_id);
            var comment = $('#comment-' + order_id).val();
            var quantity = $('#quantity-' + order_id).val();
            //alert(comment + quantity);
            if (comment == '') {
                alert('please enter message');
                return false;
            }
            if (quantity == '') {
                alert('please enter your quantity');
                return false;
            }

            //alert(rand + comment +  quantity);

            $.ajax({
                type: "POST",
                url: "<?= BASE_URL ?>ajax/addToCart.php",
                data: {
                    action: "add_comment",
                    comment: comment,
                    order_id: order_id,
                    quantity: quantity
                },

                beforeSend: function() {
                    document.getElementById("loading").style.display = "block";
                },
                success: function(response) {
                    document.getElementById("loading").style.display = "none";
                    if (response == 'comment_added') {
                        console.log(response);
                        setTimeout(function() {
                            location.reload();
                            //window.location.href='<?= BASE_URL; ?>cart';
                        }, 100)

                    }

                },

            });
        }

        //email sender 
        function send_email(rand) {
            //alert(rand);
            var email_comment = $('#pro-cart-msg').val();
            if (email_comment == '') {
                alert('please enter message');
                return false;
            }

            $.ajax({
                type: "POST",
                url: "<?= BASE_URL ?>ajax/addToCart.php",
                data: {
                    action: "send_order_email",
                    comment: email_comment,
                    user_id: <?=$user_id;?>,
                    user_email: "<?=$user_email;?>"
                },

                beforeSend: function() {
                    document.getElementById("loading").style.display = "block";
                    //debugger;
                },
                success: function(response) {
                    document.getElementById("loading").style.display = "none";
                    console.log(response);
                    if (response == 'mail_send') {
                        $('#send-mail-btn').text('your order has been send successfully.').css('color', 'green');
                        //alert('your order has been send successfully.');
                        setTimeout(function() {
                            window.location.href='<?= BASE_URL; ?>thank-you.php';
                        }, 100)

                    } else {
                        alert('Errrpr. Email not send.');
                    }

                },

            });

        }
    </script>

</body>


</html>