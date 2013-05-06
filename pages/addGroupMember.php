<?php
	require_once("../db.php");

	session_start();

	if (!isset($_SESSION['uid']) || ($_SESSION['type'] != 'student')) {
		die ( 'Not logged in as student');
	}

	// Get the groupId
	$sql = "SELECT *
			FROM groupparticipants
			INNER JOIN projectgroups ON projectgroups.id = groupparticipants.groupid
			WHERE groupparticipants.participantid = '".$_SESSION['uid']."'
			";
	$sth = $db->prepare($sql);
	$sth->execute();
	$group = $sth->fetch();

	// Insert new member
	$sql = "INSERT INTO groupparticipants
			SET groupid = '".$group['groupid']."', participantid = '".$_POST['memberId']."'
			";
	$sth = $db->prepare($sql);
	$sth->execute();

	if($sth->rowCount()) {
			die(json_encode("pass"));
	} else {
			die(json_encode("fail"));
	}
?>