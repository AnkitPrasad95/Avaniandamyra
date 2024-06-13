<?php 
ob_start();
session_start();
include 'app/config.php'; 
unset($_SESSION['customer']);
unset($_SESSION['backtoshop']);
header("location:".BASE_URL); 
?>