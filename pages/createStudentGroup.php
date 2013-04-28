<?php
	require_once("../db.php");

	session_start();

	if (!isset($_SESSION['uid']) || ($_SESSION['type'] != 'student')) {
		die ( 'Not logged in as student');
	}

	$sql = "INSERT INTO projectgroups 
			SET name = '".$_POST['groupName']."'
			";

	$sth = $db->prepare($sql);
	$sth->execute();


	if($sth->rowCount() != 0) {
		$id = $db->lastInsertID();

		$sql = "INSERT INTO groupparticipants
				SET groupid = '".$id."', participantid = '".$_SESSION['uid']."'
				";
		$sth = $db->prepare($sql);
		$sth->execute();

		if($sth->rowCount() != 0) {
			die(json_encode("pass"));
		} else {
			die(json_encode("fail member"));
		}
	} else {
		die(json_encode("fail group"));
	}
?>