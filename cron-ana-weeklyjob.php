<?php 
session_start();
require_once('app/autoload.php');
$date = strtotime("-7 day");
$cronDate = date('Y-m-d', $date);
$res = $query->manage_user_cron($cronDate);
echo "<pre>";
print_r($res);
echo "</pre>";


?>


