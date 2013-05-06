<?php
	session_start ();
	require ("../db.php");
	 
	 //sql spørring som finner all nødvendig info om godkjente oppgaver
	$sql = "SELECT externalusers.id, projects.owner,projects.id, projects.title, projects.shortTitle, projects.description,externalusers.companyname
			FROM projects
			LEFT JOIN externalusers ON projects.owner = externalusers.id 
			WHERE projects.status LIKE 'cleared'";
			
	$stmt = $db->prepare($sql);
	$stmt->execute();
	
	//om det er noen godkjente oppgaver
	if ($stmt->rowCount()!=0)
	{
		if ($_SESSION['type'] == 'student') {
			$sql = "SELECT id
					FROM projectgroups
					INNER JOIN groupparticipants ON groupparticipants.groupid = projectgroups.id
					WHERE groupparticipants.participantid = '".$_SESSION['uid']."'
					";
			$sth = $db->prepare($sql);
			$sth->execute();
			$group = $sth->fetch();
		}
		echo "<h1>Oppgaver:</h1>";
		
		//for hver oppgave
		$project = 0;
		foreach($stmt->fetchAll() as $row)
		{
			//skriver ut på siden all informasjonen
			echo "<b>Oppgave#: ". $row['id']. ".</b><br/> <b>Oppdragsgiver:</b><br />". $row['companyname']. 
			"<br/> <b>Oppgave navn:</b> <br />". $row['title']." - ".$row['shortTitle'] .
			"<br /><b>Beskrivelse:</b>". $row['description'];

			if ($_SESSION['type'] == 'student') {
				if($sth->rowCount()) {
					echo"<form id='priorityForm".$project."' method='post'>";
					echo"<input type='hidden' name='groupId' value='".$group['id']."' />";
					echo"<input type='hidden' name='projectId' value='".$row['id']."' />";
					echo"<select name='prio'>";
					echo"<option value='high'>Høy</option>";
					echo"<option value='medium'>Middels</option>";
					echo"<option value='low'>Lav</option>";
					echo"</select>";
					echo"<input type='submit' name='prioritySubmit' class='create' value='Send' />";
					echo"</form>";
?>
					<script type="text/javascript">
						$("#priorityForm"+<?php echo $project;?>+" .create").click(function(e) {
							e.preventDefault();
							$.post("pages/selectPrio.php", $("#priorityForm"+<?php echo $project;?>+"").serialize(), function(result) {
							}, "json");
						});
					</script>
<?php
					$project += 1;
				}
			}
		}
	}
	//om det ikke er noen godkjente oppgaver
	else
	{
		echo "<h1>Ingen godkjente oppgaver</h1>Ingen godkjente oppgaver for øyeblikket. Kom heller tilbake senere for å se om noe har dukket opp da!";
	}
?>


