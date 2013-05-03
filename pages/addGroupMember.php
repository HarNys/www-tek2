<?php
	require_once("../db.php");

	session_start();

	if (!isset($_SESSION['uid']) || ($_SESSION['type'] != 'student')) {
		die ( 'Not logged in as student');
	}

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