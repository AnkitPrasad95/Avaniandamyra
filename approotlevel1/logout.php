<?php 
ob_start();
session_start();
include '../app/config.php'; 
unset($_SESSION['user']);
header("location: login.php"); 
?>