<?php
include("../app/config.php");
$now = gmdate("D, d M Y H:i:s");
header('Content-Type: text/csv; charset=utf-8');  
header('Content-Disposition: attachment; filename=RequestLeads_list.csv');  
$output = fopen("php://output", "w");  
fputcsv($output, array('SL', 'Organization name', 'Person', 'Email', 'Phone', 'Address', 'Buyer Type', 'Message', 'Date'));  
$statement = $pdo->prepare("SELECT * FROM tbl_user_request order by id desc");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
	fputcsv($output, array($row['id'],$row['organization_name'],$row['person'],$row['email'],$row['phone'], $row['address'],$row['type_of_buyer'],$row['message'],date('d-m-Y h : i A', strtotime($row['created_at']))));
} 
fclose($output);
?>