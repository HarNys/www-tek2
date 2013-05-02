<?php
	header ('Content-type: application/json');
	require_once '../db.php';
	
	$sql = 'UPDATE `projects` SET status = ? WHERE id LIKE ?';
	$sth = $db->prepare ($sql);
	$sth->execute (array ($_POST['status'], $_POST['project']));

	die (json_encode (array ('ok'=>'OK')));
?>