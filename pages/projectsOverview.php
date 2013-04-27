<?php
	/**
	 * Dialog used to create a new project.
	 */
	// Start the session handling system
	session_start ();
	

	// Connect to the database
	require_once ("../db.php");

	$sql = ' SELECT * FROM projects ';
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
			echo "Oppdragsgiver: " . $row['owner'];
			echo "<br />";
			echo "<a class='showProjectInfo' id='" . $row['id'] . "' href = ''>" . $row['shortTitle'] . "</a>";
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