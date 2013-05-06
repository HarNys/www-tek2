<script type="text/javascript">
	$("#createGroupForm .create").click(function(e) {
		e.preventDefault();
		$.post("pages/createStudentGroup.php", $("#createGroupForm").serialize(), function(result) {
			if (result == "group name taken") {
				window.alert("Gruppe navnet er opptatt.");
			} else if (result == "pass") {
				$("#createGroup").remove();
				$('body > section').load ('pages/showStudentGroup.php');
			}
		}, "json");
	});

	// $("#addGroupMemberForm .create").click(function(e) {
	// 	e.preventDefault();
	// 	$.post("pages/addGroupMember.php", $("#addGroupMemberForm").serialize(), function(result) {
	// 		if(result == "in a group") {
	// 			window.alert("Studenten er allerede i en gruppe.");
	// 		} else {
	// 			$('body > section').load ('pages/showStudentGroup.php');
	// 		}
	// 	}, "json");
	// });

	$('#studentSearchForm .submit').click(function(e) {
		e.preventDefault();
		window.alert("search");
		var search = this.form.search.value;
		$.ajax ({
			url : 'https://tvil.hig.no/json_services/searchUser.php',
			data : { 'search': '%'+search+'%' },
			crossDomain : true,
			type : 'POST',
			dataType : 'json',
			success : function (data) {
				$('#studentlist').empty();
					for (student in data) {
						$('#studentlist').append ('<li><a id="'+data[student]["uid"]+'" class="addStudent" href="">Legg til </a>'
													+data[student]["uid"]
													+': '+data[student]['surename']
													+', '+data[student]['givenname']
													+'</li>');
						$('#studentlist li').last().data ('uid', data[student]['uid']);
					}
				$('#students').slideDown();
			}
		});
	});

	$('#students .addStudent').die();
	$('#students .addStudent').live('click', function(e) {
		e.preventDefault();
		window.alert("add");
		var uid = this.id;
		$.post("pages/addGroupMember.php", { 'group' : group, 'uid' : uid }, function(result){
			if(result == "in a group") {
				window.alert("Studenten er allerede i en gruppe.");
			} else {
				$('body > section').load ('pages/showStudentGroup.php');
			}
		}, "json");
		return false;
	});
</script>

<?php
	require_once("../db.php");

	session_start();

	if (!isset($_SESSION['uid']) || ($_SESSION['type'] != 'student')) {
		die ( 'Not logged in as student');
	}

	// Looks to see if the student is already a memeber of a group
	$sql = "SELECT *
			FROM groupparticipants
			INNER JOIN projectgroups ON projectgroups.id = groupparticipants.groupid
			WHERE groupparticipants.participantid = '".$_SESSION['uid']."'
			";
	$sth = $db->prepare($sql);
	$sth->execute();

	if(!($sth->rowCount())) {
		// If the student is not a memmber of a group, display a from to create a new group
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
		$group = $sth->fetch();

		// Get all the members of the group.
		$sql = "SELECT *
				FROM groupparticipants
				INNER JOIN projectgroups ON projectgroups.id = groupparticipants.groupid
				WHERE groupparticipants.groupid = '".$group['id']."'
				";
		$sth = $db->prepare($sql);
		$sth->execute();

		echo"<div id='showGroup'>";
		echo"<h2>Gruppeoversikt</h2>";
		echo"<h3>".$group['name']."</h3>";
		echo"<p>";
		foreach ($sth->fetchAll() as $member) {
			echo"".$member['participantid']."</br>";
		}
		echo"</p>";
		echo"Søk etter nytt medlem:</br>";
		echo"<form id='studentSearchForm'>";
		echo"<input type='text' name='search' value='Søk etter student'></br>";
		echo"<input type='button' class='submit' value='Søk'></br>";
		echo"</form>";
		echo"</br>";

		echo"<div id='students'>";
		echo"<ul id='studentlist'></ul>";
		echo"</div>";


		echo"</br>";
		echo"<h2>Prosjektoversikt</h2>";

		// Checks to see if the group has been asigned a project.
		$sql="	SELECT projects.*
				FROM projectrequest
				INNER JOIN projects ON projects.id = projectrequest.projectid
				WHERE projectrequest.groupid = ".$group['id']." AND projectrequest.priority = 'taken'
			";
		$sth = $db->prepare($sql);
		$sth->execute();
		if($sth->rowCount()) {
			$project= $sth->fetch();
			echo"<h3>Dere har fått tildelt dette prosjektet:</h3>";
			echo"Tittel: '".$project['title']."'<br>";
			echo"Alternativ tittel: '".$project['altTitle']."'<br>";
			echo"Kort tittel: '".$project['shortTitle']."'<br>";
			echo"</div>";
		} else {
			$sql="	SELECT *
					FROM projectrequest
					INNER JOIN projects ON projects.id = projectrequest.projectid
					WHERE projectrequest.groupid = '".$group['id']."'
			";
			$sth = $db->prepare($sql);
			$sth->execute();

			echo"Dere har ikke fått tildelt et prosjekt enda.</br>";
			echo"<h3>Dere har prioritert dise prosjektene:</h3>";
			foreach ($sth->fetchAll() as $project) {
				echo"Tittel: '".$project['title']."'</br>";
				echo"Prioritet: ";
				if($project['priority']=='high'){
					echo"Høy";
				} else if ($project['priority']=='medium'){
					echo"Medium";
				} else if ($project['priority']=='low'){
					echo"Lav";
				}
				echo"</br></br>";
			}
		}
	}
?>