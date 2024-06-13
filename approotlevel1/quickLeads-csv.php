<?php
include("../app/config.php");
$now = gmdate("D, d M Y H:i:s");
header('Content-Type: text/csv; charset=utf-8');  
header('Content-Disposition: attachment; filename=HomeEnquiryLeads_list.csv');  
$output = fopen("php://output", "w");  
fputcsv($output, array('SL', 'Name', 'Email', 'Phone', 'Subject', 'Message',  'Date'));  
$statement = $pdo->prepare("SELECT * FROM tbl_quick_enquiries order by id desc");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
	fputcsv($output, array($row['id'],$row['name'],$row['email'],$row['phone'],$row['subject'],$row['message'],date('d-m-Y h : i A', strtotime($row['created_at']))));
} 
fclose($output);
?>