<?php
session_start();
require_once('app/autoload.php');
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
// if (!isset($_SESSION['customer'])) {
//     echo "<script>window.location.href='" . BASE_URL . "';</script>";
// }



if (isset($_GET['remove_order'])) {
    //echo $_GET['remove_order'];
    $remove = $query->orderRemove($_GET['remove_order'], $user_id);
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
<style>
    .disable-click{
        pointer-events:none;
    }
</style>
<body>
    <?php include_once('inc/header.php') ?>
    <section class="full-container py-4">
        <div class="heading py-7 d-flex justify-content-between align-items-center">
            <p class="font-xxl2 headingFont bold font-mid-dark mb-0">
                Order List
            </p>

        </div>
        <div id="carttable"></div>

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
                                        <span id="email_msg"></span>  
                                    </div>
                                </div>
                            </div>
                            <div class="card-btns">
                                <a id="send-mail-btn" onclick="send_email(<?= rand(); ?>); return false;" class="btn btn-primary btn-animation w-100">
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
            $.ajax({
                type: "POST",
                url: '<?= BASE_URL; ?>view_cart_details.php',
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
        $("#carttable").on("click", ".customization", function() {
            $(this).closest('.product-discription-form').addClass('form-active');
        });
        $("#carttable").on("click", ".cancel-comment", function() {
            $(this).closest('.product-discription-form').removeClass('form-active');
        });
        /* -------Add comment start-------- */
        $("#carttable").on("click", ".add_comment", function() {
            var product_id = $(this).val();
            //alert(product_id);
            var comment = $('#comment-' + product_id).val();
            var quantity = $('#quantity-' + product_id).val();
            //alert(comment + quantity);
            if (comment == '') {
                //alert('please enter message');
                $('#comment_msg_'+product_id).html('please enter message').css('color', 'red');
                return false;
            }
            if (quantity == '') {
                $('#qty_msg_'+product_id).html('please enter your quantity').css('color', 'red');
                //alert('please enter your quantity');
                return false;
            }
            var product_quantity = quantity;
            var product_message = comment;
            <?php if (isset($_SESSION['customer'])) { ?>
                $.ajax({
                    type: "POST",
                    url: "<?= BASE_URL ?>ajax/addToCart.php",
                    data: {
                        action: "add_comment",
                        comment: product_message,
                        product_id: product_id,
                        quantity: product_quantity
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
            <?php }  else { ?> 
            var cartObject = new Object();
            cartObject.id = product_id;
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
                parsedArray = [];
            }
            var found = false;
            if (parsedArray.length == 0) {
                found = true;
            } else {
                for (var i = 0; i < parsedArray.length; i++) {

                    if (parsedArray[i].id == cartObject.id) {
                        //             alert("Product is already added to cart"); 
                        parsedArray[i].quantity = product_quantity.toString();
                        parsedArray[i].msg = product_message.toString();

                    } else {
                        found = true;
                    }
                }
            }
            var localData = localStorage.setItem('myArray', JSON.stringify(parsedArray));
            //alert(localStorage.getItem('myArray'));
            $.ajax({
                type: "POST",
                url: '<?= BASE_URL; ?>view_cart_details.php',
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
            <?php } ?> 
        });
        /* -------Add comment end-------- */
        /* -------delete cart product fron local storage-------- */
        $("#carttable").on("click", ".delete_cart", function() {
            var pid = $(this).val();
            var parsedArray = JSON.parse(localStorage["myArray"]);
            for (i = 0; i < parsedArray.length; i++)
                if (parsedArray[i].id == pid) parsedArray.splice(i, 1);
            localStorage["myArray"] = JSON.stringify(parsedArray);
            if (parsedArray == null) {
                $("#id-cart-value").html(0);
                $("#id-cart-value2").html(0);

            } else {
                var cart = parsedArray.length;
                $("#id-cart-value").html(cart);
                $("#id-cart-value2").html(cart);

            }


            $.ajax({
                type: "POST",
                url: 'view_cart_details.php',
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
         /* -------delete cart product fron local storage end-------- */
        <?php if (isset($_SESSION['customer'])) { ?>
            //email sender 
            function send_email(rand) {
                //alert(rand);
                var email_comment = $('#pro-cart-msg').val();
                if (email_comment == '') {
                    //alert('please enter message');
                    $('#email_msg').html('please enter message').css('color', 'red');
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: "<?= BASE_URL ?>ajax/addToCart.php",
                    data: {
                        action: "send_order_email",
                        comment: email_comment,
                        user_id: <?= $user_id; ?>,
                        user_email: "<?= $user_email; ?>",
                    },

                    beforeSend: function() {
                        document.getElementById("loading").style.display = "block";
                        $('#send-mail-btn').text('sending...').addClass("disable-click");
                        //debugger;
                    },
                    success: function(response) {
                        document.getElementById("loading").style.display = "none";
                        console.log(response);
                        if (response == 'mail_send') {
                            $('#send-mail-btn').text('your order has been send successfully.').css('color', 'green');
                            //alert('your order has been send successfully.');
                            setTimeout(function() {
                                localStorage.clear()
                                window.location.href = '<?= BASE_URL; ?>thank-you.php';
                            }, 100)

                        } else {
                            alert('Errrpr. Email not send.');
                        }

                    },

                });

            }

            
        <?php } ?>

            //for update quantity
            function update_quantity(qty, prdct_id) {
                if (qty.value !== "") {
                    var qty = qty.value;
                    //console.log(qty + 'prd='+prdct_id);
                    <?php if (isset($_SESSION['customer'])) { ?>
                    $.ajax({
                        type: "POST",
                        url: "<?= BASE_URL ?>ajax/addToCart.php",
                        data: {
                            action: "update_quantity",product_id: prdct_id, qty},
                            beforeSend: function() {
                                document.getElementById("loading").style.display = "block";
                            },
                            success: function(response) {
                                document.getElementById("loading").style.display = "none";
                                data = JSON.parse(response);
                                console.log(data);
                                if(data.message == 'product_qty_updated') {
                                    setTimeout(function() {
                                        location.reload();
                                    }, 100)
                                } else {
                                    alert('Something went wrong.');
                                    setTimeout(function() {
                                        location.reload();
                                    }, 100)
                                }
                                
                                
                            }
                    });
                    <?php } else { ?>
                        var product_quantity = qty;
                        var cartObject = new Object();
                        cartObject.id = prdct_id;
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
                            parsedArray = [];
                        }
                        var found = false;
                        if (parsedArray.length == 0) {
                            found = true;
                        } else {
                            for (var i = 0; i < parsedArray.length; i++) {

                                if (parsedArray[i].id == cartObject.id) {
                                    //alert("Product is already added to cart"); 
                                    parsedArray[i].quantity = product_quantity.toString();

                                } else {
                                    found = true;
                                }
                            }
                        }
                        var localData = localStorage.setItem('myArray', JSON.stringify(parsedArray));
                        <?php } ?>    
                    }
                }
        
    </script>

</body>


</html>