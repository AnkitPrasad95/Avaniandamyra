<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

	$path = $_FILES['photo_logo']['name'];
    $path_tmp = $_FILES['photo_logo']['tmp_name'];

    if($path!='') {
        $ext = pathinfo( $path, PATHINFO_EXTENSION );
        $file_name = basename( $path, '.' . $ext );
        if( $ext!='jpg' && $ext!='png' && $ext!='jpeg' && $ext!='gif' ) {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file for Featured Photo<br>';
        }
    }

    $path1 = $_FILES['footer_photo_logo']['name'];
    $path_tmp1 = $_FILES['footer_photo_logo']['tmp_name'];

    if($path1!='') {
        $ext1 = pathinfo( $path1, PATHINFO_EXTENSION );
        $file_name1 = basename( $path1, '.' . $ext1 );
        if( $ext1!='jpg' && $ext1!='png' && $ext1!='jpeg' && $ext1!='gif' ) {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file for Banner<br>';
        }
    }

    if($valid == 1) {

		if($path != '' && $path1 == '') {
			unlink('../assets/uploads/'.$_POST['current_logo']);
			// updating the data
			$final_name = 'logo-'.date('dmYhis').'.'.$ext;
			move_uploaded_file( $path_tmp, '../assets/uploads/'.$final_name);
			// updating the database
			$statement = $pdo->prepare("UPDATE tbl_settings SET logo=? WHERE id=1");
			$statement->execute(array($final_name));
			
			$success_message = 'Logo is updated successfully.';
			header("location:".$_SERVER['REQUEST_URI']);

		}

		if($path == '' && $path1 != '') {
			unlink('../assets/uploads/'.$_POST['current_footer_logo']);	
			$final_name1 = 'footer_logo-'.date('dmYhis').'.'.$ext1;
			move_uploaded_file( $path_tmp1, '../assets/uploads/'.$final_name1);	
			// updating the database
			$statement = $pdo->prepare("UPDATE tbl_settings SET footer_logo=? WHERE id=1");
			$statement->execute(array($final_name1));
			
			$success_message = 'Logo is updated successfully.';
			header("location:".$_SERVER['REQUEST_URI']);
		
		}

		if($path != '' && $path1 != '') {
			unlink('../assets/uploads/'.$_POST['current_logo']);	
			$final_name = 'logo-'.date('dmYhis').'.'.$ext;
			move_uploaded_file( $path_tmp, '../assets/uploads/'.$final_name);

			unlink('../assets/uploads/'.$_POST['current_footer_logo']);	
			$final_name1 = 'footer_logo-'.date('dmYhis').'.'.$ext1;
			move_uploaded_file( $path_tmp1, '../assets/uploads/'.$final_name1);	
			// updating the database
			$statement = $pdo->prepare("UPDATE tbl_settings SET logo=?, footer_logo=? WHERE id=1");
			$statement->execute(array($final_name, $final_name1));
			$success_message = 'Logo is updated successfully.';
			header("location:".$_SERVER['REQUEST_URI']);
		}

        
    	
    }
}

if(isset($_POST['form2'])) {
	$valid = 1;

	$path = $_FILES['photo_favicon']['name'];
    $path_tmp = $_FILES['photo_favicon']['tmp_name'];

    if($path == '') {
    	$valid = 0;
        $error_message .= 'You must have to select a photo<br>';
    } else {
        $ext = pathinfo( $path, PATHINFO_EXTENSION );
        $file_name = basename( $path, '.' . $ext );
        if( $ext!='jpg' && $ext!='png' && $ext!='jpeg' && $ext!='gif' ) {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file<br>';
        }
    }

    if($valid == 1) {
    	// removing the existing photo
    	$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
    	$statement->execute();
    	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
    	foreach ($result as $row) {
    		$favicon = $row['favicon'];
    		unlink('../assets/uploads/'.$favicon);
    	}

    	// updating the data
    	$final_name = 'favicon-'.date('dmYhis').'.'.$ext;
        move_uploaded_file( $path_tmp, '../assets/uploads/'.$final_name );

        // updating the database
		$statement = $pdo->prepare("UPDATE tbl_settings SET favicon=? WHERE id=1");
		$statement->execute(array($final_name));

        $success_message = 'Favicon is updated successfully.';
    	
    }
}

if(isset($_POST['form3'])) {
	
	// updating the database
	// $statement = $pdo->prepare("UPDATE tbl_settings SET footer_about=?, footer_copyright=?, contact_address=?, contact_email=?, contact_phone=?, contact_fax=?, contact_map_iframe=? WHERE id=1");
	// $statement->execute(array($_POST['footer_about'],$_POST['footer_copyright'],$_POST['contact_address'],$_POST['contact_email'],$_POST['contact_phone'],$_POST['contact_fax'],$_POST['contact_map_iframe']));
	$statement = $pdo->prepare("UPDATE tbl_settings SET application_name=?, footer_about=?, footer_copyright=?, contact_address=?, contact_address2=?, contact_email=?, receive_email_subject=?, contact_phone=?, contact_phone2=?, contact_phone3=?, contact_map_iframe=?, facebook=?, twitter=?, linkedin=?, youtube=?, google_site_key=?, google_secret_key=? WHERE id=1");
	$statement->execute(array($_POST['application_name'], $_POST['footer_about'], $_POST['footer_copyright'],$_POST['contact_address'],$_POST['contact_address2'],$_POST['contact_email'],$_POST['receive_email_subject'],$_POST['contact_phone'], $_POST['contact_phone2'], $_POST['contact_phone3'], $_POST['contact_map_iframe'], $_POST['facebook'], $_POST['twitter'], $_POST['linkedin'], $_POST['youtube'], $_POST['google_site_key'], $_POST['google_secret_key']));
	$success_message = 'General content settings is updated successfully.';
    
}

if(isset($_POST['form4'])) {
	$valid = 1;
 //print_r($_FILES); die();
	$path = $_FILES['header_banner']['name'];
    $path_tmp = $_FILES['header_banner']['tmp_name'];

    if($path!='') {
        $ext = pathinfo( $path, PATHINFO_EXTENSION );
        $file_name = basename( $path, '.' . $ext );
        if( $ext!='jpg' && $ext!='png' && $ext!='jpeg' && $ext!='gif' ) {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file for Featured Photo<br>';
        }
    }

    $path1 = $_FILES['footer_banner']['name'];
    $path_tmp1 = $_FILES['footer_banner']['tmp_name'];

    if($path1!='') {
        $ext1 = pathinfo( $path1, PATHINFO_EXTENSION );
        $file_name1 = basename( $path1, '.' . $ext1 );
        if( $ext1!='jpg' && $ext1!='png' && $ext1!='jpeg' && $ext1!='gif' ) {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file for Banner<br>';
        }
    }

    if($valid == 1) {

		if($path != '' && $path1 == '') {
		    if(!empty($_POST['current_header_banner'])){
			unlink('../assets/uploads/'.$_POST['current_header_banner']);
		    }
			// updating the data
			$final_name = 'header-banner-'.date('dmyhis').'.'.$ext;
			move_uploaded_file( $path_tmp, '../assets/uploads/'.$final_name);
			// updating the database
			$statement = $pdo->prepare("UPDATE tbl_settings SET header_banner=? WHERE id=1");
			$statement->execute(array($final_name));
			
			$success_message = 'Banner is updated successfully.';
			header("location:".$_SERVER['REQUEST_URI']);

		}

		if($path == '' && $path1 != '') {
		    if(!empty($_POST['current_footer_banner'])){
			unlink('../assets/uploads/'.$_POST['current_footer_banner']);
		    }
			$final_name1 = 'footer_banner'.date('dmyhis').'.'.$ext1;
			move_uploaded_file( $path_tmp1, '../assets/uploads/'.$final_name1);	
			// updating the database
			$statement = $pdo->prepare("UPDATE tbl_settings SET footer_banner=? WHERE id=1");
			$statement->execute(array($final_name1));
			
			$success_message = 'Banner is updated successfully.';
			header("location:".$_SERVER['REQUEST_URI']);
		
		}

		if($path != '' && $path1 != '') {
		    
		    if(!empty($_POST['current_header_banner'])){
			unlink('../assets/uploads/'.$_POST['current_header_banner']);
		    }
		    
			$final_name = 'header-banner-'.date('dmyhis').'.'.$ext;
			move_uploaded_file( $path_tmp, '../assets/uploads/'.$final_name);
			
            if(!empty($_POST['current_footer_banner'])){
			unlink('../assets/uploads/'.$_POST['current_footer_banner']);
            }
            
			$final_name1 = 'footer_banner'.date('dmyhis').'.'.$ext1;
			move_uploaded_file( $path_tmp1, '../assets/uploads/'.$final_name1);	
			// updating the database
			$statement = $pdo->prepare("UPDATE tbl_settings SET header_banner=?, footer_banner=? WHERE id=1");
			$statement->execute(array($final_name, $final_name1));
			$success_message = 'Banner is updated successfully.';
			header("location:".$_SERVER['REQUEST_URI']);
		}

        
    	
    }
}


?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Settings</h1>
	</div>
</section>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
	$application_name                = $row['application_name'];
	$logo                            = $row['logo'];
	$footer_logo					 = $row['footer_logo'];
	$favicon                         = $row['favicon'];
	$header_banner                   = $row['header_banner'];
	$footer_banner                   = $row['footer_banner'];
	$cse_result_year                 = $row['cse_result_year'];
	$footer_about                    = $row['footer_about'];
	$footer_copyright                = $row['footer_copyright'];
	$contact_address                 = $row['contact_address'];
	$contact_address2                 = $row['contact_address2'];
	$contact_email                   = $row['contact_email'];
	$contact_phone                   = $row['contact_phone'];
	$contact_phone2                  = $row['contact_phone2'];
	$contact_phone3 				 = $row['contact_phone3'];
	$contact_fax                     = $row['contact_fax'];
	$contact_map_iframe              = $row['contact_map_iframe'];

	$facebook                   	= $row['facebook'];
	$twitter                   		= $row['twitter'];
	$linkedin                   	= $row['linkedin'];
	$youtube                   		= $row['youtube'];


	$receive_email                   = $row['receive_email'];
	$receive_email_subject           = $row['receive_email_subject'];
	$receive_email_thank_you_message = $row['receive_email_thank_you_message'];
	$total_recent_news_footer        = $row['total_recent_news_footer'];
	$total_popular_news_footer       = $row['total_popular_news_footer'];
	$total_recent_news_sidebar       = $row['total_recent_news_sidebar'];
	$total_popular_news_sidebar      = $row['total_popular_news_sidebar'];
	$total_recent_news_home_page     = $row['total_recent_news_home_page'];
	$meta_title_home                 = $row['meta_title_home'];
	$meta_keyword_home               = $row['meta_keyword_home'];
	$meta_description_home           = $row['meta_description_home'];
	$home_title_service              = $row['home_title_service'];
	$home_subtitle_service           = $row['home_subtitle_service'];
	$home_status_service             = $row['home_status_service'];
	$home_title_team_member          = $row['home_title_team_member'];
	$home_subtitle_team_member       = $row['home_subtitle_team_member'];
	$home_status_team_member         = $row['home_status_team_member'];
	$home_title_testimonial          = $row['home_title_testimonial'];
	$home_subtitle_testimonial       = $row['home_subtitle_testimonial'];
	$home_photo_testimonial          = $row['home_photo_testimonial'];
	$home_status_testimonial         = $row['home_status_testimonial'];
	$home_title_news                 = $row['home_title_news'];
	$home_subtitle_news              = $row['home_subtitle_news'];
	$home_status_news                = $row['home_status_news'];
	$home_title_partner              = $row['home_title_partner'];
	$home_subtitle_partner           = $row['home_subtitle_partner'];
	$home_status_partner             = $row['home_status_partner'];
	$mod_rewrite                     = $row['mod_rewrite'];
	$newsletter_title                = $row['newsletter_title'];
    $newsletter_text                 = $row['newsletter_text'];
    $newsletter_photo                = $row['newsletter_photo'];
    $newsletter_status               = $row['newsletter_status'];
    $banner_search                   = $row['banner_search'];
    $banner_category                 = $row['banner_category'];
    $counter_1_title                 = $row['counter_1_title'];
    $counter_1_value                 = $row['counter_1_value'];
    $counter_2_title                 = $row['counter_2_title'];
    $counter_2_value                 = $row['counter_2_value'];
    $counter_3_title                 = $row['counter_3_title'];
    $counter_3_value                 = $row['counter_3_value'];
    $counter_4_title                 = $row['counter_4_title'];
    $counter_4_value                 = $row['counter_4_value'];
    $counter_photo                   = $row['counter_photo'];
    $counter_status                  = $row['counter_status'];
    $color                           = $row['color'];
	$google_site_key				= $row['google_site_key'];
	$google_secret_key				= $row['google_secret_key'];
}
?>


<section class="content" style="min-height:auto;margin-bottom: -30px;">
	<div class="row">
		<div class="col-md-12">
			<?php if($error_message): ?>
			<div class="callout callout-danger">
			
			<p>
			<?php echo $error_message; ?>
			</p>
			</div>
			<?php endif; ?>

			<?php if($success_message): ?>
			<div class="callout callout-success">
			
			<p><?php echo $success_message; ?></p>
			</div>
			<?php endif; ?>
		</div>
	</div>
</section>

<section class="content">

	<div class="row">
		<div class="col-md-12">
							
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_1" data-toggle="tab">Logo</a></li>
						<li><a href="#tab_2" data-toggle="tab">Favicon</a></li>
						<li><a href="#tab_3" data-toggle="tab">General Content</a></li>
						<!-- <li><a href="#tab_4" data-toggle="tab">Promotion Banner</a></li> -->
					</ul>
					<div class="tab-content">
          				<div class="tab-pane active" id="tab_1">


          					<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
							  <input type="hidden" name ="current_logo" value="<?=$logo;?>">
							  <input type="hidden" name ="current_footer_logo" value="<?=$footer_logo;?>">
								<div class="box box-info">
									<div class="box-body">
									    <?php if(!empty($logo)) { ?>
										<div class="form-group">
											<label for="" class="col-sm-2 control-label">Existing Logo</label>
											<div class="col-sm-6" style="padding-top:6px;">
												<img src="../assets/uploads/<?php echo $logo; ?>" class="existing-photo" style="height:80px;background: aquamarine;">
											</div>
										</div>
										<?php } ?>
										<div class="form-group">
											<label for="" class="col-sm-2 control-label">New Logo</label>
											<div class="col-sm-6" style="padding-top:6px;">
												<input type="file" name="photo_logo">
											</div>
										</div>
                                        <?php if(!empty($footer_logo)) { ?>
										<div class="form-group">
											<label for="" class="col-sm-2 control-label">Existing Footer Logo</label>
											<div class="col-sm-6" style="padding-top:6px;">
												<img src="../assets/uploads/<?php echo $footer_logo; ?>" class="existing-photo" style="height:80px;background: aquamarine;">
											</div>
										</div>
										<?php } ?>
										<div class="form-group">
											<label for="" class="col-sm-2 control-label">New Footer Logo</label>
											<div class="col-sm-6" style="padding-top:6px;">
												<input type="file" name="footer_photo_logo">
											</div>
										</div>
										<div class="form-group">
											<label for="" class="col-sm-2 control-label"></label>
											<div class="col-sm-6">
												<button type="submit" class="btn btn-success pull-left" name="form1">Update Logo</button>
											</div>
										</div>
									</div>
								</div>
							</form>

							


          				</div>
          				<div class="tab-pane" id="tab_2">

          					<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
							<div class="box box-info">
								<div class="box-body">
									<div class="form-group">
							            <label for="" class="col-sm-2 control-label">Existing Photo</label>
							            <div class="col-sm-6" style="padding-top:6px;">
							                <img src="../assets/uploads/<?php echo $favicon; ?>" class="existing-photo" style="height:40px;">
							            </div>
							        </div>
									<div class="form-group">
							            <label for="" class="col-sm-2 control-label">New Photo</label>
							            <div class="col-sm-6" style="padding-top:6px;">
							                <input type="file" name="photo_favicon">
							            </div>
							        </div>
							        <div class="form-group">
										<label for="" class="col-sm-2 control-label"></label>
										<div class="col-sm-6">
											<button type="submit" class="btn btn-success pull-left" name="form2">Update Favicon</button>
										</div>
									</div>
								</div>
							</div>
							</form>


          				</div>
          				<div class="tab-pane" id="tab_3">

							<form class="form-horizontal" action="" method="post">
							<div class="box box-info">
								<div class="box-body">
								
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Application name </label>
										<div class="col-sm-9">
											<input class="form-control" type="text" name="application_name" value="<?php echo $application_name; ?>">
										</div>
									</div>		
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Footer - About Us </label>
										<div class="col-sm-9">
											<textarea class="form-control" name="footer_about" id="" rows="8"><?php echo $footer_about; ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Footer - Copyright </label>
										<div class="col-sm-9">
											<input class="form-control" type="text" name="footer_copyright" value="<?php echo $footer_copyright; ?>">
										</div>
									</div>								
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Contact Address 1 </label>
										<div class="col-sm-6">
											<textarea class="form-control" name="contact_address" style="height:50px;"><?php echo $contact_address; ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Contact Address 2 </label>
										<div class="col-sm-6">
											<textarea class="form-control" name="contact_address2" style="height:50px;"><?php echo $contact_address2; ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Contact Email </label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="contact_email" value="<?php echo $contact_email; ?>">
										</div>
									</div>
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Mail Reciever Email </label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="receive_email_subject" value="<?php echo $receive_email_subject; ?>">
										</div>
									</div>
									
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Contact Phone Number </label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="contact_phone" value="<?php echo $contact_phone; ?>">
										</div>
									</div>
									
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Contact Phone Number 2 </label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="contact_phone2" value="<?php echo $contact_phone2; ?>">
										</div>
									</div>

									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Contact Phone Number 3 </label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="contact_phone3" value="<?php echo $contact_phone3; ?>">
										</div>
									</div>
								
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Contact Map iFrame </label>
										<div class="col-sm-9">
											<textarea class="form-control" name="contact_map_iframe" style="height:200px;"><?php echo $contact_map_iframe; ?></textarea>
										</div>
									</div>

									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Facebook </label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="facebook" value="<?php echo $facebook; ?>">
										</div>
									</div>

									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Twitter </label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="twitter" value="<?php echo $twitter; ?>">
										</div>
									</div>

									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Instagram </label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="linkedin" value="<?php echo $linkedin; ?>">
										</div>
									</div>

									<div class="form-group">
										<label for="" class="col-sm-2 control-label">LinkedIn </label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="youtube" value="<?php echo $youtube; ?>">
										</div>
									</div>

									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Google Site Key </label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="google_site_key" value="<?php echo $google_site_key; ?>">
										</div>
									</div>
									<div class="form-group">
										<label for="" class="col-sm-2 control-label">Google Secret Key </label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="google_secret_key" value="<?php echo $google_secret_key; ?>">
										</div>
									</div>

									<div class="form-group">
										<label for="" class="col-sm-2 control-label">LinkedIn </label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="youtube" value="<?php echo $youtube; ?>">
										</div>
									</div>
									
									<div class="form-group">
										<label for="" class="col-sm-2 control-label"></label>
										<div class="col-sm-6">
											<button type="submit" class="btn btn-success pull-left" name="form3">Update</button>
										</div>
									</div>
								</div>
							</div>
							</form>


          				</div>
          				
          				<div class="tab-pane" id="tab_4">


          					<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
							  <input type="hidden" name ="current_header_banner" value="<?=$header_banner;?>">
							  <input type="hidden" name ="current_footer_banner" value="<?=$footer_banner;?>">
								<div class="box box-info">
									<div class="box-body">
									    <?php if(!empty($header_banner)) { ?> 
										<div class="form-group">
											<label for="" class="col-sm-2 control-label">Existing Hader Banner</label>
											<div class="col-sm-6" style="padding-top:6px;">
												<img src="../assets/uploads/<?php echo $header_banner; ?>" class="existing-photo" style="height:80px;">
											</div>
										</div>
										<?php } ?>
										<div class="form-group">
											<label for="" class="col-sm-2 control-label">New Header Banner</label>
											<div class="col-sm-6" style="padding-top:6px;">
												<input type="file" name="header_banner">
											</div>
										</div>
                                        <?php if(!empty($footer_banner)) { ?> 
										<div class="form-group">
											<label for="" class="col-sm-2 control-label">Existing Footer Banner</label>
											<div class="col-sm-6" style="padding-top:6px;">
												<img src="../assets/uploads/<?php echo $footer_banner; ?>" class="existing-photo" style="height:80px;">
											</div>
										</div>
										<?php } ?>
										<div class="form-group">
											<label for="" class="col-sm-2 control-label">New Footer Banner</label>
											<div class="col-sm-6" style="padding-top:6px;">
												<input type="file" name="footer_banner">
											</div>
										</div>
										<div class="form-group">
											<label for="" class="col-sm-2 control-label"></label>
											<div class="col-sm-6">
												<button type="submit" class="btn btn-success pull-left" name="form4">Update Banner</button>
											</div>
										</div>
									</div>
								</div>
							</form>

							


          				</div>

          			





          			</div>
				</div>

			
		</div>
	</div>

</section>

<?php require_once('footer.php'); ?>