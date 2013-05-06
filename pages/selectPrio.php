<?php
	require_once("../db.php");

	session_start();

	if (!isset($_SESSION['uid']) || ($_SESSION['type'] != 'student')) {
		die ( 'Not logged in as student');
	}

	$sql = "SELECT *
			FROM projectrequest
			WHERE groupid = '".$_POST['groupId']."' AND priority= '".$_POST['prio']."'
			";
	$sth = $db->prepare($sql);
	$sth->execute();

	if($sth->rowCount()) {
		$sql = "UPDATE projectrequest 
				SET projectid = '".$_POST['projectId']."'
				WHERE groupid = '".$_POST['groupId']."' AND priority= '".$_POST['prio']."'
				";
		$sth = $db->prepare($sql);
		$sth->execute();
	} else {
		$sql = "INSERT INTO projectrequest
			SET projectid = '".$_POST['projectId']."', groupid= '".$_POST['groupId']."', priority= '".$_POST['prio']."', date = NOW()
			";
		$sth = $db->prepare($sql);
		$sth->execute();
	}

	$sql = "SELECT *
			FROM projectrequest
			WHERE groupid = '".$_POST['groupId']."'
			";
	$sth = $db->prepare($sql);
	$sth->execute();

	if($sth->rowCount()) {
		die(json_encode("".$sth->rowCount().""));
	} else {
		die(json_encode("fail"));
	}
?>