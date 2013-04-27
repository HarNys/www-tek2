<?php

	/**
	 * 
	 */
	// Start the session handling system
	session_start ();
	

	// Connect to the database
	require_once ("../db.php");

	$projectID = $_GET['id'];

	$sql = " SELECT * FROM projects WHERE id = '$projectID'  ";
	$sth = $db->prepare ($sql);
	$sth->execute();

	$project = $sth->fetch();

	echo "<h1>" . $project['title'] . "</h1>";
	echo "<br>";
	echo "Eier: " . $project['owner'];
	echo "<br>";
	echo $project['description'];

?>