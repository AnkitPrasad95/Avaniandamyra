<header id='navbar' class="header-section primary-header">
  <style>
    .loader {
      display: none;
      position: fixed;
      z-index: 1000000;
      top: 0;
      left: 0;
      height: 100%;
      width: 100%;
      background: rgba(255, 255, 255, .8) url('<?= BASE_URL; ?>/assets/images/loader.gif') 50% 50% no-repeat;
    }

    label.error {
        font-size: 15px;
    }

    span#reCaptchaError {
        color: red;
        font-size: 14px;
    }


  </style>

  <div class="loader" id="loading"></div>
  <?php
  $categories = $query->getCategory();
  $getCommonCollections = $query->getCommonCollections();

  // echo "<pre>";
  // print_r($getCollections['user_collentions']);
  // echo "</pre>";
  ?>
  <div class="header-link-wrapper-main">
    <div class="header-link-wrapper d-lg-flex">
      <a class="slideX d-lg-block d-none" href='<?= BASE_URL; ?>'>
        <img class="site-logo img-fluid dark-logo" src="<?= BASE_URL . 'assets/uploads/' . $logo; ?>" alt="<?= $application_name; ?>" />
      </a>
      <div class="header-links">

        <nav class="header-nav">
          <ul>
            <li>
              <a href="<?= BASE_URL; ?>">
                <span><img src="<?= BASE_URL.'assets/images/home.svg'; ?>"></span>
              </a>
            </li>
            <li class="dropdown">

              <a class="dropdown-toggle" id="dropdownMenuButton1" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true">
                PRODUCTS
              </a>
              <div class="dropdown-menu submenu" aria-labelledby="dropdownMenuButton1" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 24px);" data-popper-placement="bottom-start">
                <div class="submenuContent dark-text">
                  <ul id="tabs" class="nav nav-tabs">
                   <?php if(!empty($getCommonCollections)) { 
                    foreach($getCommonCollections as $key=> $getCommonCollection) { 
                        $existCategories = explode(',',$getCommonCollection->categories);
                        $gatColletionDetail = $query->gatColletionDetail($getCommonCollection->collection_id);
                        $ParentProductCount = 0;
                        foreach($existCategories as $existCategory){
                          
                          $Count =  $query->grandProductCount($existCategory);
                          $ParentProductCount = $ParentProductCount + $Count;
                        }
                    ?>
                  <li><a data-bs-toggle="tab" data-bs-target="#grand<?=$key;?>" role="tab" aria-selected="false" class="dark-text <?php if($key == 0) { echo 'active'; } ?> "><?=$gatColletionDetail->name;?> <span>(<?=$ParentProductCount;?>)</span></a></li> 
                  <?php  } } ?> 
                  <?php if(!empty($categories)) { 
                    foreach($categories as $key=> $category){  
                     
                      if($category->parent_category == 0) {
                      $productCount = $query->grandProductCount($category->id);
			
                    ?> 
		   <?php  if($category->id != 1) { ?> 	
                    <li><a data-bs-toggle="tab" data-bs-target="#parent<?=$key;?>" role="tab" aria-selected="false" class="dark-text <?php if(empty($getCommonCollections)) { if($key == 0) { echo 'active'; } }  ?> "><?=$category->name;?> <span>(<?=$productCount;?>)</span></a></li>
                  <?php } ?>
<?php } } }  ?>
                  

                  

                  </ul>
                  <div class="tab-content dark-text">
                    <!-- for grand parent wise accortion start -->
                    <?php if(!empty($getCommonCollections)) { 
                      foreach($getCommonCollections as $key=> $getCommonCollection) {
                        $existCategories = explode(',',$getCommonCollection->categories);
                        if($getCommonCollection->show_on_header == 1) {
                          $gatColletionDetail = $query->gatColletionDetail($getCommonCollection->collection_id);
                      ?>       
                      <div id="grand<?=$key;?>" class="tab-pane fade in <?php if($key == 0) { echo 'active'; } ?>  show">
                        <div class="category">
                      
                          <h4 class="category-heading"><?=$gatColletionDetail->name;?></h4>
                            <?php if(!empty($categories)) { 
                            foreach($categories as $key=> $category){  
                              if($category->name == 'Bags') { 
                              foreach($existCategories as $existCategory){
                              if($category->parent_category == 0 && $existCategory == $category->id) {
                              
                              $productCount = $query->grandProductCount($category->id);
                              $childCategories = $query->childCategories($category->id);
                            ?>
                            <div class="category-menu">
                                <a href=" <?=BASE_URL;?>product-category/<?=$gatColletionDetail->slug;?>/<?=$category->slug;?>" class="category-menu-item"><?=$category->name?><span>(<?=$productCount;?>)</span></a>
                            </div>
                          <?php } } } } } ?>      
                        </div>
                      </div> 
                    <?php } } } ?>        
                    <!-- for grand parent wise accortion end -->  
                    
                    
                    <!-- for parent wise accortion start  -->
                    <?php 
                    if(!empty($categories)) { 
                    foreach($categories as $key=> $category){  
                        if($category->parent_category == 0) { 
                          $productCount = $query->productCount($category->id);
                          $childCategories = $query->childCategories($category->id);
                        ?>

                      <div id="parent<?=$key;?>" class="tab-pane fade in show">
                        <div class="category">
                          <h4 class="category-heading"><?=$category->name;?></h4>
                          <div class="category-menu">
                          <?php if(!empty($childCategories)) { ?>  
                              <?php foreach($childCategories as $key=> $child) { ?>  
                                <a href=" <?=BASE_URL;?>product-category/<?=$category->slug;?>/<?=$child->slug;?> " class="category-menu-item"><?=$child->name;?></a>
                            <?php } }?>
                          </div>
                        </div>
                      </div>
                    <?php } } }  ?>  
                  <!-- for parent wise accortion end  -->   

                   


                  </div>
                  
                </div>
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true"></div>
              </div>
            </li>
            <li>
              <a href="<?= BASE_URL; ?>partners.php">
                <span>PARTNERS</span>
              </a>
            </li>
            <li>
              <a href="<?= BASE_URL; ?>contact-us.php">
                <span>CONTACT US</span>
              </a>
            </li>
            <?php if (!empty($user_name)) { ?>
            <li class="d-lg-none">
              <a href="<?= BASE_URL; ?>order-histories">
                <span>ORDER HISTORY</span>
              </a>
            </li>
            <?php } ?>
          </ul>
        </nav>
      </div>
      <div class="right-menu d-none d-lg-flex">
        <!-- <a class="btn-icon me-4 px-3">
          <img class="white-icon" src="<?= BASE_URL; ?>assets/images/i-search.svg" alt="search" />

        </a> -->
        <a class="btn-icon me-2 px-3">
          <!-- <img class="white-icon" src="http://127.0.0.1/avaniandamyra/wp-content/themes/avaniamyra/assets/images/i-outline.svg" alt="outline" /> -->
          <!-- GTranslate: https://gtranslate.io/ -->
          <select onchange="doGTranslate(this);" class="notranslate" id="gtranslate_selector" aria-label="Website Language Selector">
            <option value="">Select Language</option>
            <option value="en|nl">Dutch</option>
            <option value="en|en">English</option>
            <option value="en|fr">French</option>
            <option value="en|es">Spanish</option>
          </select>
          <div id="google_translate_element2"></div>
        </a>

        <a class="btn-icon me-4 px-3 position-relative"  href="<?php echo BASE_URL . 'wishlist'; ?>">
          <img class="white-icon" src="<?= BASE_URL; ?>assets/images/i-wishlist.svg" alt="wishlist" />
          <span class="wishlist-count" id="wishlist-value"><?php if (!empty($wishlistCount)) { echo $wishlistCount; } else { echo 0; } ?></span>                      
        </a>
        <a class="btn-icon me-4 px-3 position-relative" href="<?php 
                                              echo BASE_URL . 'cart';
                                             ?>">
          <img class="white-icon " src="<?= BASE_URL; ?>assets/images/icon-cart.svg" alt="cart" />
          <span class="cart-count" id="id-cart-value"></span>
          <!-- <?php if (!empty($orderCount)) { ?><span class="cart-count"> <?php echo $orderCount; ?></span> <?php } ?> -->
          
        </a>
        <!-- <div class="dropdown-container right">
          <div class="block block-minicart">
              <div class="minicart-content-wrapper">
                <div class="block-title">
                    <span>Recently added item(s)</span>
                </div>
                <a class="btn-minicart-close" title="Close">&#10060;</a>
                <div class="block-content" id="Cart_Header_Content">
                </div>
              </div>
          </div>
        </div> -->
        <div>
          <?php if (!empty($user_name)) { ?>
            <a data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#loginSignup" class="font-md semibold white-text">
              <?= $user_name; ?>
            </a>

            <span style="display: block;" class="font-exs"><a href="<?= BASE_URL; ?>order-histories">Order History</a></span>

          <?php } else { ?>
            <a data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#loginSignup" class="font-md semibold white-text">
              SIGN IN
            </a>
          <?php } ?>
        </div>

      </div>
    </div>

  </div>
  <div class="mobile-header  d-flex d-lg-none">
    <a href="<?= BASE_URL; ?>">
      <img class="site-logo img-fluid dark-logo" src="<?= BASE_URL . 'assets/uploads/' . $logo; ?>" alt="<?= $application_name; ?>" />
    </a>
  </div>
  <div class="mobile-right-menu d-flex d-lg-none align-items-center">
    <!--  <a class="btn-icon me-2 px-3">
      <img class="white-icon" src="<?= BASE_URL; ?>assets/images/i-search.svg" alt="search" />

    </a> -->
    <!-- <a class="btn-icon me-2 px-3">
      <img class="white-icon" src="<?= BASE_URL; ?>assets/images/i-outline.svg" alt="outline" />

    </a> -->
    <a class="btn-icon me-2 px-3">
      <!-- <img class="white-icon" src="http://127.0.0.1/avaniandamyra/wp-content/themes/avaniamyra/assets/images/i-outline.svg" alt="outline" /> -->
      <!-- GTranslate: https://gtranslate.io/ -->
      <select onchange="doGTranslate(this);" class="notranslate" id="gtranslate_selector" aria-label="Website Language Selector">
        <option value="">Select Language</option>
        <option value="en|nl">Dutch</option>
        <option value="en|en">English</option>
        <option value="en|fr">French</option>
        <option value="en|es">Spanish</option>
      </select>
      
      <div id="google_translate_element2"></div>
      
    </a>
    <a class="btn-icon me-2 px-3 position-relative" href="<?php echo BASE_URL . 'wishlist'; ?>">
      <img class="white-icon" src="<?= BASE_URL; ?>assets/images/i-wishlist.svg" alt="wishlist" />
      <span class="wishlist-count" id="wishlist-value2"><?php if (!empty($wishlistCount)) { echo $wishlistCount; } else { echo 0; } ?></span>  
    </a>
    <a class="btn-icon me-2 px-3 position-relative" href="<?php 
                                              echo BASE_URL . 'cart';
                                             ?>">
      <img class="white-icon" src="<?= BASE_URL; ?>assets/images/icon-cart.svg" alt="cart" />
      <span class="cart-count" id="id-cart-value2"></span>
      <!-- <?php //if (!empty($orderCount)) { ?><span class="cart-count"> <?php //echo $orderCount; ?></span> <?php //} ?> -->
    </a>
    <?php if (!empty($user_name)) { ?>
      <a data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#loginSignup" class="btn-icon me-3 ms-2 px-3 ">
        <img class="white-icon" src="<?= BASE_URL; ?>assets/images/user.svg" alt="user-icon" />
      </a>
    <?php } else { ?>
      <a data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#loginSignup" class="btn-icon me-3 ms-2 px-3">
        <img class="white-icon" src="<?= BASE_URL; ?>assets/images/user.svg" alt="user-icon" />

      </a>
    <?php } ?>
    <a class="menu">
      <div class="bar"></div>
      <div class="bar"></div>
      <div class="bar"> </div>
    </a>
  </div>
</header>