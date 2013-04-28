<?php
	/**
	 * Dialog used to create a new project.
	 */
	// Start the session handling system
	session_start ();
	

	// Connect to the database
	require_once ("../db.php");

	$sql = ' SELECT title, owner, shortTitle, projects.id , companyname 
			FROM projects  LEFT JOIN externalusers ON projects.owner = externalusers.id ';
	$sth = $db->prepare ($sql);
	$sth->execute();
	
	if ( $sth->rowCount() == 0 )
	{
		echo "Nope";
	}
	else
	{
		$result = $sth->fetchAll();

		foreach( $result as $row )
		{
			
			$sql = ' SELECT comment FROM  staffcomments LEFT JOIN projects ON staffcomments.projectid = projects.id WHERE uid LIKE ?';
		
			$stmt = $db->prepare ($sql);
			$stmt->execute(array($_SESSION['uid']));
			$comments = $stmt->fetch();
			$stmt -> closeCursor();

			echo " hahaha  ". $comments['comment'];

			echo "Oppdragsgiver: " . $row['companyname'];
			echo "<br />";
			echo "<a class='showProjectInfo' id='" . $row['id'] . "' href = ''>" . $row['title'] . "</a>";
			
			if($comments['comment'] != "")
			{
				echo "<br /> <font color = 'green'><b>Du komenterte: </font></b>".$comments['comment']." <br />";
			}
			else
			{
				echo "<br /><font color = 'red'><b> Du har ikke komentert her! </font> </b> <br />";
			}
		}
	}

?>

<script> 

 	$('.showProjectInfo').click (function() 
 	{
 		// alert($(this).attr('id'));
 		$('body > section').load('pages/showProjectInfo.php?id=' + this.id);
 		return false;
 	});
 
</script>