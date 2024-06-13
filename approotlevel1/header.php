<?php
ob_start();
session_start();
include("../app/config.php");
include("../app/autoload.php");
$error_message = '';
$success_message = '';
$error_message1 = '';
$success_message1 = '';

// Check if the user is logged in or not
if (!isset($_SESSION['user']) && !isset($_SESSION['login_url'])) {
	header('location: login.php');
	exit;
} else if(isset($_SESSION['user']) && isset($_SESSION['login_url']) && $_SESSION['login_url'] != BASE_URL){
	header('location: logout.php');
	exit;
}

// Getting data from the website settings table
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
	$receive_email = $row['receive_email'];
}

// Current Page Access Level check for all pages
$cur_page = substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Avaniamyra- Admin Panel</title>

	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/ionicons.min.css">
	<link rel="stylesheet" href="css/datepicker3.css">
	<link rel="stylesheet" href="css/all.css">
	<link rel="stylesheet" href="css/select2.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.css">
	<link rel="stylesheet" href="css/jquery.fancybox.css">
	<link rel="stylesheet" href="css/AdminLTE.min.css">
	<link rel="stylesheet" href="css/on-off-switch.css">
	<link rel="stylesheet" href="css/_all-skins.min.css">
	<link rel="stylesheet" href="css/tagify.css">
	<link rel="stylesheet" href="css/summernote.css">
	<link rel="stylesheet" href="style.css">
	<link rel="shortcut icon" href="<?= BASE_URL . 'assets/uploads/' . $favicon; ?>">
	<style>
		.loader {
		display: none;
		position: fixed;
		z-index: 1000000;
		top: 0;
		left: 0;
		height: 100%;
		width: 100%;
		background: rgba(255, 255, 255, .8) url('<?= BASE_URL; ?>assets/images/loader.gif') 50% 50% no-repeat;
		}


	</style>

  	<div class="loader" id="loading"></div>

</head>

<body class="hold-transition fixed skin-blue sidebar-mini">

	<div class="wrapper">

		<header class="main-header">

			<a href="index.php" class="logo">
				<span class="logo-lg">Admin</span>
			</a>

			<nav class="navbar navbar-static-top">

				<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
					<span class="sr-only">Toggle navigation</span>
				</a>

				<span style="float:left;line-height:50px;color:#000;padding-left:15px;font-size:18px;">Admin Panel</span>

				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav">
						<li class="dropdown user user-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<img src="../assets/uploads/<?php echo $_SESSION['user']['photo']; ?>" class="user-image" alt="User Image">
								<span class="hidden-xs"><?php echo 'Admin'; ?></span>
							</a>
							<ul class="dropdown-menu">
								<li class="user-footer">
									<div>
										<a href="profile-edit.php" class="btn btn-default btn-flat">Edit Profile</a>
									</div>
									<div>
										<a href="logout.php" class="btn btn-default btn-flat">Log out</a>
									</div>
								</li>
							</ul>
						</li>
					</ul>
				</div>

			</nav>
		</header>

		<?php $cur_page = substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1); ?>

		<aside class="main-sidebar">
			<section class="sidebar">

				<ul class="sidebar-menu">

					<li class="treeview <?php if ($cur_page == 'index.php') {
											echo 'active';
										} ?>">
						<a href="index.php">
							<i class="fa fa-tachometer"></i> <span>Dashboard</span>
						</a>
					</li>

					<li class="treeview <?php if ($cur_page == BASE_URL) {
											echo 'active';
										} ?>">
						<a href="<?= BASE_URL; ?>" target="_blank">
							<i class="fa fa-hand-o-right"></i> <span>Visit Website</span>
						</a>
					</li>

					<li class="treeview <?php if ($cur_page == 'settings.php') {
											echo 'active';
										} ?>">
						<a href="settings.php">
							<i class="fa fa-cog"></i> <span>General Settings</span>
						</a>
					</li>

					<!-- <li class="treeview <?php if (($cur_page == 'slider-add.php') || ($cur_page == 'slider.php') || ($cur_page == 'slider-edit.php')) {
													echo 'active';
												} ?>">
			  			<a href="slider.php">
			          <i class="fa fa-product-hunt" aria-hidden="true"></i> <span>Slider</span>
			         </a>
			  		</li> -->

					<!-- <li class="treeview <?php if (($cur_page == 'blog-add.php') || ($cur_page == 'blog.php') || ($cur_page == 'blog-edit.php')) {
													echo 'active';
												} ?>">
			  			<a href="blog.php">
			           <i class="fa fa-file-text-o" aria-hidden="true"></i> <span>Blogs</span>
			         </a>
			  		</li>   -->
					<li class="treeview <?php if (($cur_page == 'user-add.php') || ($cur_page == 'users.php') || ($cur_page == 'user-edit.php')) {
											echo 'active';
										} ?>">
						<a href="users.php">
							<i class="fa fa-users" aria-hidden="true"></i> <span>Manage Users</span>
						</a>
					</li>

					<!-- <li class="treeview <?php if (($cur_page == 'event-add.php') || ($cur_page == 'event.php') || ($cur_page == 'event-edit.php')) {
													echo 'active';
												} ?>">
			  			<a href="event.php">
			           <i class="fa fa-file-text-o" aria-hidden="true"></i> <span>Events</span>
			         </a>
			  		</li>   -->
					<!-- <li class="treeview <?php if (($cur_page == 'media-photo.php') || ($cur_page == 'media-photo.php') || ($cur_page == 'media-photo.php')) {
													echo 'active';
												} ?>">
						<a href="media-photo.php">
						<i class="fa fa-newspaper-o" aria-hidden="true"></i> <span>Media</span>
						</a>
					 </li> -->
					<!-- <li class="treeview <?php if (($cur_page == 'vendorLeads-add.php') || ($cur_page == 'vendorLeads.php') || ($cur_page == 'vendorLeads-edit.php')) {
													echo 'active';
												} ?>">
			          <a href="vendorLeads.php">
			            <i class="fa fa-users"></i> <span>Vendor Leads</span>
			          </a>
			        </li>-->

					<!-- <li class="treeview <?php if (($cur_page == 'service-add.php') || ($cur_page == 'service.php') || ($cur_page == 'service-edit.php')) {
													echo 'active';
												} ?>">
			  			<a href="service.php">
			          <i class="fa fa-product-hunt" aria-hidden="true"></i> <span>Service</span>
			         </a>
			  		</li> -->

					<li class="treeview <?php if (($cur_page == 'productList.php') || ($cur_page == 'productCat-edit.php')
											|| ($cur_page == 'productList-edit.php') || ($cur_page == 'collection.php') || ($cur_page == 'collection-add.php')
											|| ($cur_page == 'collection-edit.php') || ($cur_page == 'productSubCat.php') || ($cur_page == 'productList-add.php')
											|| ($cur_page == 'productSubCat-edit.php') || ($cur_page == 'size.php') || ($cur_page == 'material.php')
											|| ($cur_page == 'manageCollection-edit.php') || ($cur_page == 'manageCollection.php') || ($cur_page == 'manageCollection-add.php')
											|| ($cur_page == 'productCat.php')
										) {
											echo 'active';
										} ?>">
						<a href="#">
							<i class="fa fa-hand-o-right"></i>
							<span>Manage Product</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li><a href="collection.php"><i class="fa fa-circle-o"></i> Master Collection</a></li>
							<li><a href="productCat.php"><i class="fa fa-circle-o"></i> Master Category</a></li>
							<!-- <li><a href="productSubCat.php"><i class="fa fa-circle-o"></i> Manage Sub Category</a></li> -->
							<li><a href="size.php"><i class="fa fa-circle-o"></i> Master Size</a></li>
							<li><a href="material.php"><i class="fa fa-circle-o"></i> Master Material</a></li>
							<li><a href="productList.php"><i class="fa fa-circle-o"></i>Master Product</a></li>
							<li><a href="manageCollection.php"><i class="fa fa-circle-o"></i>Manage Collection</a></li>

						</ul>
					</li>

					<li class="treeview <?php if ($cur_page == 'wishlist.php') {
											echo 'active';
										} ?>">
						<a href="wishlist.php">
							<i class="fa fa-bars"></i> <span>Wishlist Reports</span>
						</a>
					</li>
					<li class="treeview <?php if ($cur_page == 'orders.php') {
											echo 'active';
										} ?>">
						<a href="orders.php">
							<i class="fa fa-bars"></i> <span>Order Reports</span>
						</a>
					</li>
					<!-- <li class="treeview <?php if (($cur_page == 'newsletter.php') || ($cur_page == 'newsletter-add.php') || ($cur_page == 'newsletter-edit.php')) {
													echo 'active';
												} ?>">
			          <a href="newsletter.php">
			            <i class="fa fa-commenting-o" aria-hidden="true"></i> <span>Newsletters</span>
			          </a>
			        </li>-->
					<!-- <li class="treeview <?php if (($cur_page == 'manageProject.php') || ($cur_page == 'galleryCat-add.php') || ($cur_page == 'galleryCat-edit.php')) {
													echo 'active';
												} ?>">
			          <a href="manageProject.php">
			            <i class="fa fa-hand-o-right" aria-hidden="true"></i> <span>Project</span>
			          </a>
			        </li>  -->


					<!-- <li class="treeview <?php if (($cur_page == 'marketArea-add.php') || ($cur_page == 'marketArea.php') || ($cur_page == 'marketArea-edit.php')) {
													echo 'active';
												} ?>">
			  			<a href="marketArea.php">
			           <i class="fa fa-file-text-o" aria-hidden="true"></i> <span>Market Area</span>
			         </a>
			  		</li> -->
					<li class="treeview <?php if (($cur_page == 'partner-add.php') || ($cur_page == 'partner.php') || ($cur_page == 'partner-edit.php')) {
													echo 'active';
												} ?>">
			         <a href="partner.php">
			          <i class="fa fa-user-secret" aria-hidden="true"></i> <span>Partner Logo</span>
				 	 </a>
			  		</li>

					<!-- <li class="treeview <?php if (($cur_page == 'testimonials.php') || ($cur_page == 'testimonials-add.php') || ($cur_page == 'testimonials-edit.php')) {
													echo 'active';
												} ?>">
			          <a href="testimonials.php">
			            <i class="fa fa-commenting-o" aria-hidden="true"></i> <span>Testimonials</span>
			          </a>
			        </li>  -->

					<!-- <li class="treeview <?php if (($cur_page == 'static-content.php') || ($cur_page == 'static-content-add.php') || ($cur_page == 'static-content-edit.php')) {
													echo 'active';
												} ?>">
			          <a href="static-content.php">
			            <i class="fa fa-commenting-o" aria-hidden="true"></i> <span>Static Pages</span>
			          </a>
			        </li> -->

					<!-- <li class="treeview <?php if (($cur_page == 'team-add.php') || ($cur_page == 'team.php') || ($cur_page == 'team-edit.php')) {
													echo 'active';
												} ?>">
			          <a href="team.php">
			           <i class="fa fa-user-secret" aria-hidden="true"></i> <span>Manage Team</span>
			          </a>
			        </li> -->


					<!--<li class="treeview <?php if (($cur_page == 'careerCat.php') || ($cur_page == 'careerleads.php')) {
												echo 'active';
											} ?>">
						<a href="#">
							<i class="fa fa-hand-o-right"></i>
							<span>Career</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li><a href="careerCat.php"><i class="fa fa-circle-o"></i> Career Category</a></li>
							<li><a href="careerleads.php"><i class="fa fa-circle-o"></i> Career Enquiry</a></li>
							
						</ul>
					</li> -->


					<li class="treeview <?php if (($cur_page == 'contact-leads.php') || ($cur_page == 'requestLeads.php') || ($cur_page == 'career-leads.php') || ($cur_page == 'subscriber.php')) {
											echo 'active';
										} ?>">
						<a href="#">
							<i class="fa fa-question-circle"></i>
							<span>Report</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li><a href="contact-leads.php"><i class="fa fa-circle-o"></i> Contact</a></li>
							<li><a href="subscriber.php"><i class="fa fa-circle-o"></i> Subscriber</a></li>
							<li><a href="requestLeads.php"><i class="fa fa-circle-o"></i> Request</a></li>

						</ul>
					</li>

				</ul>
			</section>
		</aside>

		<div class="content-wrapper">