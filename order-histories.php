<?php
session_start();
require_once('app/autoload.php');

if (!isset($_SESSION['customer'])) {
    echo "<script>window.location.href='" . BASE_URL . "';</script>";
}

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
$OrderHistories = $query->getUserwiseOrderhistory($user_id);
// echo "<pre>";
// print_r($OrderHistories);
// echo "</pre>";    die;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once('inc/meta-head.php') ?>
    <style>
        .table-striped>tbody>tr:nth-of-type(odd)>* {
        --bs-table-accent-bg: rgb(255 255 255) !important;
    }

    .table>thead>tr>th {
    font-weight: 600;
    border: 1px solid #000000;
    }
    </style>
</head>

<body>
    <?php include_once('inc/header.php') ?>
    
    <section class="full-container py-4" id="Wish-List-Page">
        <div class="heading py-7 d-flex justify-content-between align-items-center">
            <p class="font-xxl2 headingFont bold font-mid-dark mb-0">
               Order Histories
            </p>

        </div>
        <?php if (!empty($OrderHistories)) { ?>
            <div class="order-table" id="example1">
                <div class="order-table-heading">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="product">
                                <p class="font-lg bold mid-dark-text mb-0">Order Id</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="comment">
                                <p class="font-lg bold mid-dark-text mb-0">Comment</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="comment">
                                <p class="font-lg bold mid-dark-text mb-0">Order Date</p>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex justify-content-between align-items-center">
                            
                            <div class="remove">
                                <p class="font-lg bold mid-dark-text mb-0">View</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="order-table-data" id="Wish-List-Data">
                    <?php foreach ($OrderHistories as $OrderHistoriesRow) { ?>
                        <div class="row border-bottom-gray">
                            <div class="col-md-3">
                                <div class="product">
                                    <div class="product-details">
                                        <p class="font-md mb-1 mid-dark-text"><?= $OrderHistoriesRow->order_id; ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="product">
                                    <div class="product-details">
                                        <p class="font-md mb-1 mid-dark-text"><?= $OrderHistoriesRow->message; ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="product">
                                    <div class="product-details">
                                        <p class="font-md mb-1 mid-dark-text"><?= date('d M, Y - h:i A', strtotime($OrderHistoriesRow->created_at)); ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3 align-self-center">
                                <div class="comment  product-discription-form">
                                    
                                    <div class="product-discription">
                                        <button value="<?php echo $OrderHistoriesRow->id; ?>" class="border-none bg-none myForm" title="view">
                                        <i class="fa fa-eye font-lg2" aria-hidden="true"></i>
                                    </button>
                                    </div>
                                </div>
                            </div>
                            
                            


                        </div>
                    <?php } ?>
                </div>
                
                <div class="mail-shoping-btn-wrap d-flex justify-content-end my-5">
                    <a href="<?=$backtoshop;?>" class="continue-shop-btn btn btn-outline-primary btn-animation">Continue Shopping</a>
                </div>
            </div>
        <?php } else { ?>
            <p class="woocommerce-info">No Order found.</p></br>
            <div class="mail-shoping-btn-wrap d-flex justify-content-end mb-5">
                <a href="<?=$backtoshop;?>" class="continue-shop-btn btn btn-outline-primary btn-animation">Continue Shopping</a>
            </div>
        <?php } ?>
    </section>
    <div class="modal fade" id="view-data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 800px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">View Order Details</h4>
                    <button type="button" class="close close-btn bg-none border-none font-lg" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body" id="recievedData" style="max-height:590px;  overflow-y: scroll;">

                </div>
                <div class="modal-footer">
                    <button type="button" class="close-btn btn btn-default btn btn-outline-primary btn-animation" data-dismiss="modal">Close</button>
                    
                </div>
            </div>
        </div>
    </div>
    

    <?php include_once('inc/footer.php') ?>
    <?php include_once('inc/footer-script.php') ?>
    <script>
    $("#example1").on("click", ".myForm", function() {
                var id = $(this).val();
                //alert(id);
                $('#view-data').modal('show'); 
                $.ajax({
                url: 'ajax/addToCart.php',
                type: 'post',
                data:{'action':'order_history',order_id:id},
                success: function(html)
                {
                    $('#recievedData').html(html);
                }
                }); 
            });
    $(".close-btn").click(function() {
        $('#view-data').modal('hide');
    }); 
    </script>
    

</body>


</html>