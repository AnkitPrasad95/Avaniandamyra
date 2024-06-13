<?php
session_start();
include('../app/autoload.php');

if($_POST['action'] == 'add_to_cart') {
	// echo "<pre>";
	// print_r($_POST); die();
    if(!empty($_POST['comment'])) {
        $comment = $_POST['comment'];
    } else {
        $comment = '';
    }

    if(!empty($_POST['quantity'])) {
        $quantity = $_POST['quantity'];
    } else {
        $quantity = 1;
    }
    
	$checkOrder = $query->checkOrder($_POST['product_id'], $_POST['user_id'], $comment, $quantity); 
    $checkCurrentOrderCount = $query->checkCurrentOrderCount($_POST['user_id']); 
    $_SESSION['backtoshop'] = $_POST['page_url'];
    echo $checkCurrentOrderCount;
   
}

if($_POST['action'] == 'add_comment') {
	// echo "<pre>";
	// print_r($_POST); die();
	$addComment = $query->addComment($_POST['comment'], $_POST['quantity'], $_POST['product_id'], $user_id); 
    echo $addComment;

}



if($_POST['action'] == 'send_order_email') {
    // echo "<pre>";
	// print_r($_POST);
    // echo "</pre>";
    // die; 
    $curr_user_email = $_POST['user_email'];
    $user_id = $_POST['user_id'];
    $comment_msg = $_POST['comment'];
    $orderList = $query->orderList($user_id);
    $uniqueOrder_id = 'ANA'.$user_id.date('dmYhis');  
 
    $message = "";
    $message = '<html><head><title>Product List</title></head><body><table style="border: 1px solid #ddd; border-collapse: collapse;width: 75%;"><tr><th style="border: 1px solid #ddd;">SN.</th><th style="border: 1px solid #ddd;">Product</th><th style="border: 1px solid #ddd;">Product Name</th><th style="border: 1px solid #ddd;">Comment</th><th style="border: 1px solid #ddd;">Quantity</th></tr>';
    $i = 1;
    foreach($orderList as $orderListRow){
        // echo "<pre>";
        // print_r($orderListRow['name']);
        // echo "</pre>"; 
        // die();
        if(!empty($orderListRow['thumbnail_image'])) {
            $image = BASE_URL.$orderListRow['file_path'].$orderListRow['thumbnail_image'];
        } else {
            $image = BASE_URL.'assets/uploads/placeholder.jpg';
        }
        if( $orderListRow['quantity'] > 1) {
            $quantity =  $orderListRow['quantity']; 

        } else {
            $quantity = 1;
        }
        $message .= '<tr><td style="padding: 15px;border: 1px solid #ddd;"> '. $i++ . '</td>';
        $message .= '<td style="padding: 15px;border: 1px solid #ddd;"><img src="'.$image.'" alt="pro-img" style="width:80px; height:85px;"></td>';
        $message .= '<td style="padding: 15px;border: 1px solid #ddd;"> '. $orderListRow['name'] . '</td>';
        $message .= '<td style="padding: 15px;border: 1px solid #ddd;"> '. $orderListRow['comment'] . '</td>';
        $message .= '<td style="padding: 15px;border: 1px solid #ddd;"> '. $quantity . '</td></tr>';
    }
    $message = $message . '</table>';
    $message .= '<div><h4>Order No : '.$uniqueOrder_id.'</h4>';
    $message .= '<div><h4>Name : '.$user_name.'</h4>';
    $message .= '<div><h4>Email: '.$curr_user_email.'</h4>';
    $message .= '<div><h4>Message:</h4>';
	$message .= '<p>'. $comment_msg .'</p></div>';
    $message = $message . '</body></html>';

    //echo $message; die;

    $mail->addAddress($receiver_email);

   //$mail->addCC('cc@example.com'); 
   //$mail->addBCC('bcc@example.com'); 

   // Set email format to HTML 
   $mail->isHTML(true);
   $mail->Subject = 'Product Order Details';
   $mail->Body    = $message;
    // Sending email for admin
    $mail->send();
    // if ( $mail->send()) {
    //     $msg =  'Your mail has been sent successfully.';
    //     echo json_encode( array("success" => true, "result" => $msg) );

    // } else{
    //     $msg = 'Unable to send email. Please try again.';
    //     echo json_encode( array("success" => false, "result" => $msg) );
    // }
	

	// Sending email for user
	if( $curr_user_email ){
        $mail2->addAddress($curr_user_email);

        //$mail->addCC('cc@example.com'); 
        //$mail->addBCC('bcc@example.com'); 

        // Set email format to HTML 
        $mail2->isHTML(true);
        $mail2->Subject = 'Product Order Details';
        $mail2->Body    = $message;
		
		if($mail2->send()){
		    //$msg =  'Your mail has been sent successfully.';
		    //echo json_encode( array("success" => true, "result" => $msg) );
            echo "mail_send";
		} else {
            echo "mail_not_send";
		    // $msg = 'Unable to send email. Please try again.';
		    // echo json_encode( array("failed" => false, "result" => $msg) );
		}
	} 
    $query->saveOrder($uniqueOrder_id, $user_id, $comment_msg);
    $updateOrderStatus = $query->updateOrderStatus($user_id);

}

if($_POST['action'] == 'add_to_wishlist'){
    $add_to_wishlist = $query->add_to_wishlist($_POST['product_id'], $_POST['user_id']);
    echo $add_to_wishlist;
    // echo "<pre>";
	// print_r($_POST); die();

}

if($_POST['action'] == 'show_product_detail_modal' ) {

$product = $query->getProductDetails($_POST['product_id']);
//print_r($product); die();
$productImages = $query->get_images($product->id);
 ?>

    <a data-bs-dismiss="modal" class="modal-close">
        <img src="<?= BASE_URL; ?>assets/images/i-cross.svg" alt="cross" />
    </a>
    <div class="product-image-slider">
        <div class="productSlider">
            <?php if(!empty($productImages)) { 
                foreach($productImages as $productImagesRow) {
            ?> 
            <div class="product-slider-item">
                <img src="<?= BASE_URL . $productImagesRow->file_path . $productImagesRow->photo_name; ?>" alt="<?= $product->name; ?>" />
            </div>
            <?php } } else { ?> 
            <div class="product-slider-item">
                <img src="<?= BASE_URL . $product->file_path . $product->thumbnail_image; ?>" alt="<?= $product->name; ?>" />
            </div>
            <?php } ?>
        </div>
    </div>
    <div class="modal-product-details">
        <div class="product-details-card">
            <a class="wishlist-icon active">
                <svg width="34" height="31" viewBox="0 0 34 31" fill="#9F9987">
                    <path d="M27.4644 0.265504C22.6752 -0.974341 18.5546 2.42916 16.7677 4.26461C14.9687 2.44131 10.8359 -0.974341 6.04672 0.27766C-1.64761 2.28329 -0.0309528 9.66159 0.540348 12.1291C2.97142 22.9474 15.6373 29.9853 16.1843 30.2771C16.3635 30.3738 16.5641 30.4239 16.7677 30.4229C16.9714 30.4239 17.172 30.3738 17.3512 30.2771C17.886 29.9853 30.5762 22.9838 32.9951 12.044C33.5786 9.3577 35.1588 2.25898 27.4644 0.265504Z" />
                </svg>
            </a>
            <div class="heading">
                <h4 class="font-lg dark-text mb-2"><?= $_POST['cat_title']; ?></h4>
                <h3 class="headingFont bold font-xxl2 dark-text mb-4"><?= $product->name; ?></h3>
                <?php 
                if(!empty($product->tags)) { ?>
                <div class="tags">
                    <?php
                    foreach (json_decode($product->tags) as $key => $details){ ?>
                        <div class="tag-item font-sm"><?=$details->value;?></div>
                    <?php } ?>
                    
                    
                </div>
                <?php } ?>
            </div>
            <div class="product-discription-form">
                <div class="product-discription">
                    <p class="discription-text mid-dark-text font-lg  mb-3 custom_scrollbar"> <!-- line-6 -->
                        <?=$product->short_description;?></br>
                        <?=$product->remarks?>
                    </p>
                    <!-- <a class="link-dark font-lg d-none">Read More</a> -->

                    <div class="card-btns">
                        <a class="customization btn btn-outline-primary btn-animation">
                            <span class="font-lg">Customization</span>
                        </a>
                        <?php if(isset($_SESSION['customer'])) { ?> 
                            <a href="order-list.php" class="btn btn-primary btn-animation ">
                                <span class="font-lg">Add to Order</span>
                            </a>
                        <?php } else { ?> 
                            <a data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#loginSignup" class="btn btn-primary btn-animation">
                              Add to Order
                            </a>
                        <?php } ?>
                        
                    </div>
                </div>
                <div class="product-form">
                    <form method="post" name="productDetails" autocomplete="off" class="modal-form mb-0" novalidate="novalidate">
                        <div class="row">
                            <div class="col-12 mb-5">
                                <div class="form-group">
                                    <label class="font-sm mb-2" for="details">Message</label>
                                    <textarea rows="3" class="form-control font-md" placeholder="How can we help you?" name="details" id="details"></textarea>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label class="font-sm mb-2" for="Quantity">Quantity</label>
                                    <input type="text" class="form-control font-md" name="Quantity" id="Quantity" />
                                </div>
                            </div>
                        </div>
                        <div class="card-btns">
                            <?php if(isset($_SESSION['customer'])) { ?> 
                            <a href="<?=BASE_URL;?>order-list.php" class="btn btn-primary btn-animation w-100 ">
                                <span class="font-lg">Add to Order</span>
                            </a>
                            <?php } else { ?> 
                                <a data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#loginSignup" class="btn btn-primary btn-animation">
                                  Add to Order
                                </a>
                            <?php } ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php } ?>