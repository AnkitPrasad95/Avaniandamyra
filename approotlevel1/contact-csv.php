<?php
include("../app/config.php");
$now = gmdate("D, d M Y H:i:s");
header('Content-Type: text/csv; charset=utf-8');  
header('Content-Disposition: attachment; filename=contact_list-'.date('d-m-Y-h-i').'.csv');  
$output = fopen("php://output", "w");  
fputcsv($output, array('SL', 'Theme', 'Email', 'Attachment', 'Comment', 'Date'));  
$statement = $pdo->prepare("SELECT * FROM tbl_contact order by id desc");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
	fputcsv($output, array($row['id'],$row['theme'],$row['email'],BASE_URL.$row['file_path'].$row['attachment'],$row['comment'],$row['created_at']));
} 
fclose($output);
?>