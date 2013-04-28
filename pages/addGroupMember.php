<?php
	require_once("../db.php");

	session_start();

	if (!isset($_SESSION['uid']) || ($_SESSION['type'] != 'student')) {
		die ( 'Not logged in as student');
	}

	$newGroupMember = $db->query("	INSERT INTO 'groupparticipants'
									SET 'groupid'='".$_POST['groupId']."', 'participantid'='".$_POST['memberId']."'
								");

	if($newGroupMemeber) {
			die("pass");
	} else {
			die("fail");
	}
?>