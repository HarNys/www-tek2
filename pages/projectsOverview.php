<?php
	/**
	 * Dialog used to create a new project.
	 */
	// Start the session handling system
	session_start ();
	

	// Connect to the database
	require_once ("../db.php");

	$sql = ' SELECT title, owner, shortTitle, projects.id , companyname  
			FROM projects  LEFT JOIN externalusers ON projects.owner = externalusers.id';
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
			echo "Oppdragsgiver: " . $row['companyname'];
			echo "<br />";
			echo "<a class='showProjectInfo' id='" . $row['id'] . "' href = ''>" . $row['title'] . "</a>";
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