
<?php
	
	//session_start ();
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
		echo "<h1>Oppgaver:</h1>";
		
		//for hver oppgave
		foreach($stmt->fetchAll() as $row)
		{
			
			//skriver ut på siden all informasjonen
			echo "<b>Oppgave#: ". $row['id']. ".</b><br/> <b>Oppdragsgiver:</b><br />". $row['companyname']. 
			"<br/> <b>Oppgave navn:</b> <br />". $row['title']." - ".$row['shortTitle'] .
			"<br /><b>Beskrivelse:</b>". $row['description'];
		}
	}
	//om det ikke er noen godkjente oppgaver
	else
	{
		echo "<h1>Ingen godkjente oppgaver</h1>Ingen godkjente oppgaver for øyeblikket. Kom heller tilbake senere for å se om noe har dukket opp da!";
	}
?>
