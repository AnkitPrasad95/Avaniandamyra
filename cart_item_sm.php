
<?php
session_start();
require_once('app/autoload.php');
$check = BASE_URL.'order-list.php';

if (isset($_REQUEST['myData'])) {
	$data = $_REQUEST['myData'];

	$data_cart = json_decode($data, true);
    
	$codCount = 0;
	$cartitems = '';
	$total_amount = 0;
	$cartitems .= '<div class="minicart-items-wrapper overflowed">
		<ol class="minicart-items">';
	if ($data_cart != null) {

		foreach ($data_cart as $value) {

			$product_id = $value['id'];

			$get_product = $query->getProducts($product_id);
            // echo "<pre>";
            // print_r($get_product);
            // echo "</pre>"; 
			$product_name  = $get_product->name;
            if(!empty($get_product->thumbnail_image)) {
                $image = BASE_URL.$get_product->file_path.$get_product->thumbnail_image;
            } else {
                $image = BASE_URL.'assets/uploads/placeholder.jpg';
            }
            
			$quantity = $value['quantity'];



			$cartitems .= '<li class="item product product-item">
														
			<div class="product">
				<a class="product-item-photo" href="#" title="Long sleeve overall">
					<span class="product-image-container">
					<span class="product-image-wrapper">
					<img class="product-image-photo" src="' . $image . '" style="width:60px;height:60px;" alt="Long sleeve overall">
					</span>
					</span>
				</a>
				<div class="product-item-details">
					<div class="product-item-name">
						<a>' . $product_name . ' </a>
					</div>
					<div class="product-item-qty">
						<label class="label">Qty</label>
						<input class="item-qty cart-item-qty" maxlength="12" value=' . $quantity . '>
						<button class="update-cart-item" style="display: none" title="Update">
							<span>Update</span>
						</button>
					</div>
					<div class="product-item-pricing">
						<div class="product actions">
							<div class="secondary">
								<a onclick="del_sm_Function(' . $value['id'] . ')" class="action delete" title="Remove item">
									<span>Delete</span>
								</a>
							</div>
							<div class="primary">
								<a class="action edit" href="' . $check . '" title="Edit item">
									<span>Edit</span>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</li>
			';
		}
	}

	$cartitems .= '</ol>
	</div>';
	$cartitems .= '
        <div class="actions">
            <div class="secondary">
                <a href="cart.php" class="btn btn-alt">
                    <i class="icon icon-cart"></i><span>View Cart</span>
                </a>
            </div>
            <div class="primary">
                <a class="btn" href="' . $check . '">
                    <i class="icon icon-external-link"></i><span>Go to Checkout</span>
                </a>
            </div>
        </div>';
}





echo $cartitems;

?>