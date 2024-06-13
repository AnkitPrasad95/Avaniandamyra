<footer class="primary">
  <div class="dark-overlay py-6">
    <div class="full-container">
      <div class="subscribe-content">
        <div class="row ">
          <div class="col-lg-5 col-md-6 ">
            <div class="d-flex align-items-center">
              <img class="icon-56 me-4" src="<?= BASE_URL; ?>assets/images/icon-letter.svg" alt="subscribe" />
              <div class="content">
                <p class="font-lg2 mb-0">Subscribe To Our Newsletter</p>
                <p class="font-sm mb-0">Stay in touch with us to get latest news and discount
                  coupons</p>
              </div>
            </div>
          </div>
          <?php
          if (isset($_POST['news_form'])) {
            if (empty($_POST['news_email'])) {
              $news_email = "Email field can't empty.";
            } else {
              $userIP = $_SERVER['REMOTE_ADDR'];


              $data = array($_POST['news_email'], date('Y-m-d H:i:s'), 1, $userIP);
              $res = $query->newsletterSave($data);
              // print_r($res);
              // die();
              if($res == 'email_exist') {
                echo "<script> alert('You have already subscribed our newsletter.'); </script>";
              } else {
                $mail->addAddress($receiver_email);

                //$mail->addCC('cc@example.com'); 
                //$mail->addBCC('bcc@example.com'); 

                // Set email format to HTML 
                $mail->isHTML(true);
                $mail->Subject = 'Newsletter Subscriber';
                $message = "
                  <html>
                  <head>
                  <title>HTML email</title>
                  </head>
                  <body>
                  
                  <table>";
                $message .= "<tr style='background-color:#dcdbdb'>
                  <th style='text-align:left;width: 27%;padding-left: 10px;padding-top: 6px;padding-bottom: 6px;'> " . $_POST['news_email'] . "  has been subscribed our newsletter.</th>
                  </tr>";
                $message .= "</table>
                  </body>
                  </html>
                  ";
                  //echo $message; die;
                $mail->Body    = $message;

                if ($mail->send()) {
                  echo "<script> alert('Thank you for subscribed our newsletter.'); </script>";
                } else {
                    echo "<script> alert('Error in Successfully'); </script>";
                }
              }
              echo "<script> window.location.href='" . $_SERVER['REQUEST_URI'] . "'; </script>";
            }
          }
          ?>
          <div class="col-lg-7 col-md-6 d-flex align-items-center pt-4 pb-4">

            <div class="position-relative withbtn w-100">
              <div class="required_fields" style="color:red;"><?php if (!empty($news_email)) {
                                                                echo $news_email;
                                                              } ?></div>
              <form method="POST" id="news_form">
                <input type="email" class="form-control" placeholder="" name="news_email" required />
                <button type="submit" name="news_form" class="btn btn-animation btn-primary">
                  <span class="font-md regular">Subscribe Now </span>
                </button>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="full-container pt-5">

    <div class="row  pt-3">
      <div class="col-lg-4 col-md-6 py-4">
        <a class="footer-logo mb-4 d-block" href='<?= BASE_URL; ?>'>
          <img src="<?= BASE_URL; ?>assets/uploads/<?=$footer_logo;?>" alt="avaniAmyra" />
        </a>
        <p class="font-sm white-text col-lg-8 col-md-10 px-0"><?= $footer_about; ?></p>
      </div>
      <div class="col-lg-5 col-md-6  py-4">
        <div class="footer-links row">
          <div class="col-6 pe-4">
            <p class="mb-4 font-lg bold white-text">Usefull Links</p>
            <a class="btn-link-white font-sm d-flex mb-4">
              About Us
            </a>
            <a href="<?= BASE_URL . 'partners.php'; ?>" class="btn-link-white font-sm d-flex mb-4">
              Partners
            </a>
            <a href="<?= BASE_URL . 'contact-us.php'; ?>" class="btn-link-white font-sm d-flex mb-4">
              Contact Us
            </a>
          </div>
          <div class="col-6 ps-5">
            <p class="mb-4 font-lg bold white-text">Products</p>
            <a href="<?=BASE_URL.'product-category/bags';?>" class="btn-link-white font-sm d-flex mb-4">
              Bags
            </a>
            <a href="<?=BASE_URL.'product-category/scarves';?>" class="btn-link-white font-sm d-flex mb-4">
              Scalves
            </a>
            <a href="<?=BASE_URL.'product-category/kaftans';?>" class="btn-link-white font-sm d-flex mb-4">
              Kaftans
            </a>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-12 py-4">
        <div class="footer-contact">
          <div class="d-flex justify-content-md-end mb-5">
            <a class="call-us d-flex btn-link-white">
              <img class="icon-47" src="<?= BASE_URL; ?>assets/images/icon-customerService.svg" alt="customerService" />
              <div class="content ms-4">
                <h4 class="font-lg mb-0 white-text">Call Us On</h4>
                <p class="font-xs mb-0 "><?= $contact_phone; ?><br><?= $contact_phone2; ?></p>
              </div>
            </a>
          </div>
          <div class="d-flex justify-content-md-end mb-5">
            <a class="call-us d-flex btn-link-white">
              <img class="icon-47" src="<?= BASE_URL; ?>assets/images/icon-email.svg" alt="email" />
              <div class="content ms-4">
                <h4 class="font-lg mb-0 white-text">Mail Us</h4>
                <p class="font-xs mb-0 "><?= $contact_email; ?></p>
              </div>
            </a>
          </div>
        </div>

        <div class="social-media">
          <a href="<?= $facebook; ?>" class="btn-icon">
            <img src="<?= BASE_URL; ?>assets/images/facebook.svg" alt="facebook" />
          </a>
          <a href="<?= $twitter; ?>" class="btn-icon">
            <img src="<?= BASE_URL; ?>assets/images/twitter.svg" alt="twitter" />
          </a>
          <a href="<?= $linkedin; ?>" class="btn-icon">
            <img src="<?= BASE_URL; ?>assets/images/linkedin.svg" alt="linkedin" />
          </a>
          <a href="<?= $youtube; ?>" class="btn-icon">
            <img src="<?= BASE_URL; ?>assets/images/instagram.svg" alt="instagram" />
          </a>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="copyright">
        <p class="font-sm white-text mb-0"><?= $footer_copyright; ?></p>
      </div>
      <div class="tnc white-text d-flex align-items-center">
        <a class="btn-link-white font-sm">Privacy Policy </a><span class="px-3 font-md"> | </span><a class="btn-link-white font-sm">Terms & Conditions</a>
      </div>
    </div>
  </div>
</footer>
<!-- login modal -->
<div class="modal fade" id="loginSignup" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" data-dismiss="modal" data-toggle="modal">
  <div class="modal-dialog modal-dialog-centered loginModal">
    <div class="modal-content">
      <div class="login-bg"></div>
      <div class="login-content">
        <a data-bs-dismiss="modal" class="modal-close">
          <img src="<?= BASE_URL;  ?>assets/images/i-cross.svg" alt="cross" />
        </a>

        <a class="modal-logo" href='<?php echo BASE_URL; ?>'>
          <img class="modal-logo-img" src="<?php echo BASE_URL . 'assets/uploads/' . $logo; ?>" alt="<?= $application_name; ?>" />
        </a>
        <?php if (!isset($_SESSION['customer'])) { ?>
          <div class="login-card" id="login-1">
            <div class="login-heading mb-5 pb-4">
              <p class="font-md dark-text">Welcome to <span class="primary-text">A & A ACCESSORIES</span></p>
              <h4 class="font-xxxl dark-text ">Sign in</h4>
            </div>

            <form class="woocommerce-form woocommerce-form-login login modal-form mb-0" onsubmit="return false;">
              <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide form-group mb-5">
                <label class="font-sm mb-2" for="username"><span class="required">Enter your username or email address *</span></label>
                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text form-control font-md" name="username" id="username" autocomplete="username" value="" placeholder="Username or email address" required />
                <span class="username_error" style="display:none;"></span>
              </p>
              <!-- <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="password" class="font-sm mb-2"><span class="required">Enter your Password *</span></label>
                <input class="woocommerce-Input woocommerce-Input--text input-text form-control font-md" type="password" name="password" id="password" autocomplete="current-password" placeholder="Password" required />
                <span class="userpassword_error" style="display:none;"></span>
              </p> -->
              <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="password" class="font-sm mb-2"><span class="required">Enter your Password *</span></label>
                <div class="position-relative">
                <input class="woocommerce-Input woocommerce-Input--text input-text form-control font-md" type="password" name="password" id="password" autocomplete="current-password" placeholder="Password" required />
                <input type="checkbox" id="toggle" value="0" onchange="togglePassword(this);"  class="fa fa-eye">
                <span class="userpassword_error" style="display:none;"></span>
                </div>
              </p>

              <p class="form-row mt-5">

                <button id="login_btn" type="submit" class="woocommerce-button button woocommerce-form-login__submit btn btn-primary btn-animation px-7 py-3 font-lg bold ms-3" name="send-request">Log In</button>

                <!-- <button type="submit" class="woocommerce-button button woocommerce-form-login__submit custom-login-btn" name="login" value="">Log in</button>  -->
                <button type="submit" class="woocommerce-button button woocommerce-form-login__submit btn btn-primary btn-animation px-7 py-3 font-lg bold ms-3" name="New User" id="new-user-btn" value="New User">New User</button>
              </p>
            </form>
          </div>

          <!-- ====== start new ueser request form ====== -->
          <div class="login-card d-none" id="new-user">
            <div class="login-heading mb-5 pb-4">
              <p class="font-md dark-text">Welcome to <span class="primary-text">A & A ACCESSORIES</span></p>
              <h4 class="font-xxxl dark-text "> Request</h4>
            </div>

            <form class="send-request-frm modal-form mb-0" method="post" onsubmit="return false;">

              <p class="form-row form-row-wide form-group mb-5">
                <label class="font-sm mb-2" for="username">&nbsp;<span class="required">Enter your email address *</span></label>
                <input type="email" class="input-text form-control font-md" name="send-user" id="send_request_email" placeholder="Email address" required />
              </p>

              <div class="form-group col-md-12 mb-5">
                <label class="font-sm mb-2 dark-text regular">Message</label>
                <div class="position-relative">
                  <textarea id="send_request_msg" rows="3" class="form-control" placeholder="How can we help you?" name="request-message"></textarea>
                </div>
              </div>

              <p class="form-row">
                <button id="send_reqst_btn" type="submit" class="btn btn-primary btn-animation px-7 py-3 font-lg bold" name="send-request">Send Request</button>
              </p>

            </form>
          </div>
          <!-- ====== end new ueser request form ====== -->
        <?php } else { ?>
          <div class="login-card">
            <div class="login-heading mb-5 pb-4">
              <p class="font-md dark-text">Welcome to <span class="primary-text">A & A ACCESSORIES</span></p>
              <h4 class="font-xxxl dark-text ">Sign Out</h4>
            </div>
            <form method="post" name="logoutForm" autocomplete="off" class="modal-form mb-0" novalidate="novalidate">
              <p class="custom-logout">

                <a href="<?= BASE_URL . 'logout.php'; ?>" title="Logout" class="btn btn-primary btn-animation px-7 py-3 font-lg bold ms-3">Logout</a>
              </p>

            </form>
          </div>
        <?php } ?>
      </div>

    </div>
  </div>
</div>
<!-- login modal end -->