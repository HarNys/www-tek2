<?php
	require_once("../db.php");

	session_start();

	if (!isset($_SESSION['uid']) || ($_SESSION['type'] != 'student')) {
		die ( 'Not logged in as student');
	}

	// See if student is already in a group
	$sql = "SELECT *
			FROM groupparticipants
			WHERE groupparticipants.participantid = '".$_POST['memberId']."'
			";
	$sth = $db->prepare($sql);
	$sth->execute();

	if($sth->rowCount()) {
		die(json_encode("in a group"));
	}

	// Insert new member
	$sql = "INSERT INTO groupparticipants
			SET groupid = '".$_POST['groupId']."', participantid = '".$_POST['memberId']."'
			";
	$sth = $db->prepare($sql);
	$sth->execute();

	if($sth->rowCount()) {
			die(json_encode("pass"));
	} else {
			die(json_encode("fail"));
	}
?>