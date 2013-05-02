<?php

	require_once ("../db.php");

	$sql ="SELECT title FROM projects WHERE status LIKE 'submitted' OR status LIKE 'reworked'";
	$sth = $db->prepare ($sql);
	$sth -> execute();


	$sql = "SELECT title, owner, shortTitle, status, projects.id , companyname, projects.description 
			FROM projects  LEFT JOIN externalusers ON projects.owner = externalusers.id 
			WHERE status LIKE 'submitted' OR status LIKE 'reworked' LIMIT 0, 1";
	$stmt = $db->prepare ($sql);
	$stmt->execute();

	if($stmt->rowCount() != 0)
	{
		echo "<b>Det er ". $sth->rowCount(). " prosjekt(er) igjen å gå igjennom.</b><br /><br />"; 
		$result = $stmt->fetchAll();
		foreach( $result as $row )
		{
			$projectID = $row['id'];
			echo "Oppdragsgiver: " . $row['companyname'];
			echo "<br />";
			echo "<a class='showProjectInfo' id='" . $projectID . "' href = ''>" . $row['title'] . "</a><br /><br />";
			echo "<b>Beskrivelse:</b>";
			echo $row['description'];
			echo "<b>Status:</b> ".$row['status'] ."<br />";

			
			echo"<form>
				Set status: 
				<select id='showNextProject' name = 'status'> 
				<option value='cleared'>Godkjent</option>
				<option id='showNextProject' value='denied'>Ikke godkjent</option>
				</select><br />
				<input type ='hidden' name='projectid' value='$projectID'>

				<input type='button' id='nextButton' value='Neste' onclick='javascript:NextProject(this.form)'/>
				</form>";
		}
	}
	else
	{
		echo "ingen prosjekt forslag som ikke har blit gjennomgått";
	}
?>

<script type="text/javascript">

	$(document).ready(function() {  
		setup ();		
	});

	function setup ()
	{
		$('#nextButton').hide();
	}


 	$('#showNextProject').click (function() 
 	{
 		$('#nextButton').show();
 		return false;
 	});

 	$('.showProjectInfo').click (function() 
 	{
 		// alert($(this).attr('id'));
 		$('body > section').load('pages/showProjectInfo.php?id=' + this.id);
 		return false;
 	});


 	function NextProject(form) 
 	{
 		var data ={
				status : form.status.value,
				project : form.projectid.value}; 

		$.ajax ({
			url : 'json/updateProjectStatus.php',
			type : 'POST',
			data : data,
			success : function (data) {
				if (data.ok) 
				{
					$('body > section').load('pages/godkjeningMote.php');
				}
				else
				{
					alert (data.error);
				}
					
			}
		});
 	}




</script>