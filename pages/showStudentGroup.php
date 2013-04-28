<script type="text/javascript">
	$("#createGroupForm .create").click(function(e) {
		e.preventDefault();

		var groupName = this.form.groupName.value;

		$.post("pages/createStudentGroup.php", $("#createGroupForm").serialize(), function(result) {
			if (result == "pass") {
				$("#createGroup").remove();
				//$("#showGroup").show();
			}
		}, "json");

	});
</script>

<?php
	require_once("../db.php");

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
		echo"<p>Du er ikke medlem av en gruppe. Opprett en ny gryppe eller be om å bli lagt til i en eksisterende gruppe.</p>";
		echo"<form id='createGroupForm' method='post'>";
		echo"<label for='groupName'>Gruppenavn</label>";
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

		echo"<div id='showGroup'>";
		echo"<h1>Gruppeoversikt</h1></br>";
		echo"<h2>".$group['name']."</h2></br>";
		// Print out names here!
		echo"<form id='addGroupMemberForm' method='post'>";
		echo"<input type='hidden' name='groupId' value='".$group['id']."' /></br>";
		echo"<label for='memberId'>Studentnummer</label>";
		echo"<input type='text' name='memberId' /></br>";
		echo"<input type='submit' name='groupSubmit' class='create' value='Opprett' />";
		echo"</form>";
		echo"</br>";
		echo"<h2>Prosjektoversikt</h2></br>";

		if($getProject->rowCount()) {
			$project= $getProject->fetch();

			echo"Tittel: '".$project_info['title']."'<br>";
			echo"Alternativ tittel: '".$project_info['altTitle']."'<br>";
			echo"Kort tittel: '".$project_info['shortTitle']."'<br>";
			echo"</div>";
		} else {
			echo"Dere har ikke fått tildelt et prosjekt";
		}	
	}
?>