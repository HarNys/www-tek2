<?php

	/**
	 * 
	 */
	// Start the session handling system
	session_start ();
	

	// Connect to the database
	require_once ("../db.php");

	$projectID = $_GET['id'];

	$sql = " SELECT projects.* , externalusers.companyname
			 FROM projects, externalusers
			 WHERE projects.id = '$projectID' AND
			 externalusers.id = '$projectID' ";

	$sth = $db->prepare ($sql);
	$sth->execute();

	$project = $sth->fetch();

	echo "<h1>" . $project['title'] . "</h1>";
	echo "<br>";
	echo "Bedrift/Eier: " . $project['companyname'];
	echo "<br>";
	echo $project['description'];

?>