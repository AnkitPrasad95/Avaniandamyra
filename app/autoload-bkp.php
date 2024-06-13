<?php
ob_start();
require_once 'config.php';
require_once __DIR__.'/vendors/autoload.php';
require_once 'query.class.php';
require_once 'mail.php';
$query = new query();
$statement = $pdo->prepare("SELECT * FROM tbl_settings");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach ($result as $row) {
	$application_name				 = $row['application_name'];
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
	$receiver_email         = $row['receive_email_subject'];
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
}



if (isset($_SESSION['customer'])) {
  $customerDtails = $query->getCustomerDetailsByid($_SESSION['customer']);
  $user_id = $customerDtails->id;
  $user_email = trim($customerDtails->email);
//   echo "<pre>";
//   print_r($customerDtails);
//   echo "</pre>";
  $user_name = $customerDtails->first_name . ' ' . $customerDtails->last_name;

  $getCollections = $query->getCollectionByUser($customerDtails->colletion_ids);
  $orderCount = $query->orderCount($user_id);
} else {
  $getCollections = $query->getCollectionByUser();
  $user_id = '';
  $user_email = '';
  $user_name = '';
}
    
?>