<?php
	include "../db.php";

	session_start();

	if (!isset($_SESSION['uid']) || ($_SESSION['type'] != 'student')) {
		die ( 'Not logged in as student');
	}

	// Looks to see if the student is already a memeber of a group
	$getGroup = $db->query("	SELECT 'projectgroups.*'
								FROM 'groupparticipants'
								INNER JOIN 'projectgroups' ON 'projectgroups.id' = 'groupparticipants.groupid'
								WHERE 'groupparticipants.participantid' = '".$_SESSION['uid']."'
							");
	if($getGroup->rowCount != 1) {
		// If the student is not a memmer of a group, display a from to create a new group
		echo"<div id='createGroup'>";
		echo"<h1>Opprett ny studentgruppe</h1>";
		echo"<p>Du er ikke medlem av en gruppe. Opprett en ny gryppe:</p>";
		echo"<form id='createGroupForm' method='post'>";
		echo"<label for='groupName'> Gruppenavn </label>";
		echo"<input type='text' name='groupName' /></br>";
		echo"<input type='submit' name='groupSubmit' class='create' value='Opprett' />";
		echo"</form>";
		echo"</div>";
	} else {
		// If the student is a memeber of a group, display the group name.
		$group = $getGroup->fetch();
		$getProject = $db->query("	SELECT 'projects.*' 
									FROM 'projectrequest'
									INNER JOIN 'projects' ON 'projects.id' = 'projectrequest.projectid'
									WHERE 'projectrequest.groupid' = '".$group['id']."' AND 'projectrequest.priority' = 'taken'
								");

		echo "<h1>".$group['name']."</h1>";

		if($getProject->rowCount()) {
			$project= $getProject->fetch();
		}
	}
?>