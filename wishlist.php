<?php
session_start();
require_once('app/autoload.php');

// if (!isset($_SESSION['customer'])) {
//     echo "<script>window.location.href='" . BASE_URL . "';</script>";
// }

if(isset($_SESSION['backtoshop'])){
    $backtoshop = BASE_URL.$_SESSION['backtoshop'];
} else {
    $backtoshop = BASE_URL;
}

if (isset($_GET['remove_product'])) {
    //echo $_GET['remove_order'];
    $remove = $query->removeWishlist($_GET['remove_product']);
    if ($remove == 'deleted') {
        //echo "<script>alert('Order deleted.');</script>";
        echo "<script>window.location.href='wishlist';</script>";
    } else {
        //echo "<script>alert('Order not deleted.');</script>";
        echo "<script>window.location.href='wishlist';</script>";
    }
}
$wishList = $query->getWishList($user_id);
// echo "<pre>";
// print_r($wishList);
// echo "</pre>";    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once('inc/meta-head.php') ?>
</head>

<body>
    <?php include_once('inc/header.php') ?>
    
    <section class="full-container py-4" id="Wish-List-Page">
        <div class="heading py-7 d-flex justify-content-between align-items-center">
            <p class="font-xxl2 headingFont bold font-mid-dark mb-0">
               Wish List
            </p>

        </div>
        <?php if (!empty($wishList)) { ?>
            <div class="order-table" id="example1">
                <div class="order-table-heading">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="product">
                                <p class="font-lg bold mid-dark-text mb-0">Product</p>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="comment">
                                <p class="font-lg bold mid-dark-text mb-0">Add To Cart</p>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex justify-content-between align-items-center">
                            
                            <div class="remove">
                                <p class="font-lg bold mid-dark-text mb-0">Remove</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="order-table-data" id="Wish-List-Data">
                    <?php foreach ($wishList as $wishListRow) { ?>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="product">
                                    <div class="product-image">
                                        <img src="<?= BASE_URL . $wishListRow->file_path . $wishListRow->thumbnail_image; ?>" alt="product1" />
                                    </div>
                                    <div class="product-details">
                                        <p class="font-md mb-1 mid-dark-text"><?= $wishListRow->name; ?></p>

                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-5 align-self-center">
                                <div class="comment  product-discription-form">
                                    
                                    <div class="product-discription">
                                        <button class="customization btn btn-outline-primary ps-4 add_to_cart" id="cartadd_<?=$wishListRow->product_id;?>" value="<?=$wishListRow->product_id;?>">
                                            <img class=" icon-32 me-3" src="./assets/images/icon-cart.svg" alt="comment" /> Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3 d-flex justify-content-between align-items-center">
                                <div class="remove">
                                    <a class="remove-btn icon-btn" href="?remove_product=<?= $wishListRow->id; ?>" onclick="return confirm('Are you sure you want to delete?')">
                                        <img src="./assets/images/icon-trash.svg" alt="trash" />
                                    </a>
                                </div>
                            </div>


                        </div>
                    <?php } ?>
                </div>
                
                <div class="mail-shoping-btn-wrap d-flex justify-content-end mb-5">
                    <a href="<?=$backtoshop;?>" class="continue-shop-btn btn btn-outline-primary btn-animation">Continue Shopping</a>
                    
                </div>
            </div>
        <?php } else { ?>
            <p class="woocommerce-info">No Product found.</p></br>
            <div class="mail-shoping-btn-wrap d-flex justify-content-end mb-5">
                <a href="<?=$backtoshop;?>" class="continue-shop-btn btn btn-outline-primary btn-animation">Continue Shopping</a>
            </div>
        <?php } ?>
    </section>

    

    <?php include_once('inc/footer.php') ?>
    <?php include_once('inc/footer-script.php') ?>
    <script>
       $("#example1").on("click", ".add_to_cart", function() {
            var id = $(this).val();
            //alert(id);
            $.ajax({
                type: "POST",
                url: "<?= BASE_URL ?>ajax/addToCart.php",
                data: {
                    action: "add_to_cart_from_wishlist",product_id: id},
                    success: function(response) {
                        data = JSON.parse(response);
                        console.log(data);
                        if(data.message == 'product_already_exist') {
                            $("#cartadd_" + id).text("Product already in cart");
                        } else {
                            $("#cartadd_" + id).text("Product added to cart");
                        }
                        $("#id-cart-value").html(data.product_count);
                        $("#id-cart-value2").html(data.product_count);
                        
                    }
            });
        });
      
    </script>

</body>


</html>