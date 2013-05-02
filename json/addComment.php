<?php
/**
 * Script is called when a new external user is created
 */

// Start the session handling system
session_start ();

// Return correct content type
header ('Content-type: application/json');

// Connect to the database
require_once '../db.php';



$sql = "SELECT * FROM staffcomments WHERE uid LIKE ?";
$sth = $db->prepare ($sql);
$sth->execute (array ($_POST['user']));


//Vi har her gått ut i fra at en ansatt bare skal ha en kommentar per prosjekt
// på grunn av primary keys i databasen vi har fått utdelt

//om ansatt har komentar oppdater en eksisterende
if($sth->rowCount()!= 0)
{
	$sth->closeCursor();
	$sql = "UPDATE staffcomments SET uid = ?, projectid = ?, comment = ?";
	$sth = $db->prepare ($sql);
	$sth->execute (array ($_POST['user'], $_POST['project'] ,$_POST['comment']));
}

//eller sett inn en komentar
else
{
	$sth->closeCursor();
	$sql = "INSERT INTO staffcomments (uid, projectid, comment) VALUES (?,?,?)";
	$sth = $db->prepare ($sql);
	$sth->execute (array ($_POST['user'], $_POST['project'] ,$_POST['comment']));
}


die (json_encode (array ('ok'=>'OK')));



//error_log($_POST['user']. " - ". $_POST['project']. " - ". $_POST['comment']); 
?>