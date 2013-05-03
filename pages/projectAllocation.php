<?php
	
	/**
	 * Dialog used to create a new project.
	 */
	// Start the session handling system
	session_start ();
	

	// Connect to the database
	require_once ("../db.php");

	$sql = "SELECT * FROM  projectgroups";
	$sth = $db->prepare ($sql);
	$sth->execute();
	
	if ( $sth->rowCount() == 0 )
	{
		echo "Fant ingen grupper";
	}

	else
	{

		$result = $sth->fetchAll();

		foreach( $result as $row )
		{	

			$groupid = $row['id'];
			//ser om gruppen allerede har fått en
			$sql = "SELECT title FROM projectrequest LEFT JOIN projects ON projects.id = projectrequest.projectid
				 	WHERE priority LIKE 'taken' AND groupid LIKE ?"; 
			$stmt = $db->prepare ($sql);
			$stmt->execute(array($row['id']));
			
			if($stmt->rowCount()!=0)
			{
				$givenProject = $stmt->fetch();
				echo "<b><font color = 'green'>Denne gruppen har fått tildelt:</b></font> ". $givenProject['title']. "<br />";
			}

			$stmt -> closeCursor();

			//henter ut project ønsker som ikke er gitt bort
			$sql = 'SELECT projects.id, projects.title, priority FROM projectrequest 
					LEFT JOIN projects ON projectrequest.projectid = projects.id 
					WHERE groupid LIKE ? AND projects.status NOT LIKE "given"';
			
			$stmt = $db->prepare ($sql);
			$stmt->execute(array($row['id']));
			
			

			echo "<b>Gruppe: </b>". $row['name']. "<br />";

			//velg mellom ønskene til gruppen
			if($stmt->rowCount()!=0)
			{
				$requests = $stmt->fetchAll();
				echo "<form>
						<select name='giveproject' onclick='javascript:giveProject(this.form)'>";
				foreach ($requests as $request)
				{
					$projectid =  $request['id'];
					echo "<option value = '$projectid'>". $request['title']." - ".$request['priority']."</option>";
				}
				echo "</select>";
				echo "<input type='hidden' name='group' value='$groupid'>";
				echo "</form>";
			}
			//Om gruppen ikke har noen ønsker eller alle ønskene er tatt vi resten av prosjektene som er ledige:
			else
			{
				$stmt -> closeCursor();
				$sql = "SELECT projects.id, projects.title FROM projects  
					WHERE projects.status LIKE 'cleared'";
				$stmt = $db->prepare ($sql);
				$stmt->execute();
				$requests = $stmt->fetchAll();
				
				//velg om det er flere igjen
				if($stmt->rowCount()!=0)
				{
					echo "<font color = 'red'><b> Denne gruppen har ingen ønsker, eller ønskene er allerede git bort! </font> </b> <br />";
					echo "<form><select name='giveproject' onclick='javascript:giveProject(this.form)'>";
					
					foreach ($requests as $request) 
					{
						$projectid =  $request['id'];
						echo "<option value = '$projectid'>". $request['title']. "</option>";
					}
					echo "</select>";

					echo "<input type='hidden' name='group' value='$groupid'>";

					echo "</form>";
				}
				//det er ingen projecter å velge mellom
				else
				{
					echo "<font color = 'red'><b> Det er ikke fler prosjekter å dele ut </font> </b> <br />";
				}
				

			}
			echo "<br /><br /><br /><br />";
			$stmt -> closeCursor();
		}
	}

?>

<script> 

function giveProject(form)
{
	alert(form.giveproject.value +" - "+form.group.value);
	var data ={
				project : form.giveproject.value,
				groupe : form.group.value}; 

		$.ajax ({
			url : 'json/giveProject.php',
			type : 'POST',
			data : data,
			success : function (data) {
				if (data.ok) 
				{
					$('body > section').load('pages/projectAllocation.php');
				}
				else
				{
					alert (data.error);
				}
					
			}
		});
}
 
</script>