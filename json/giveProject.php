<?php
require_once ("../db.php");

header ('Content-type: application/json');

	//henter ut prosjektet til gruppen om de har noen
	$sql = "SELECT * FROM projectrequest WHERE groupid LIKE ? AND priority LIKE 'taken'";
	$stmt = $db->prepare ($sql);
	$stmt->execute(array($_POST['groupe']));

	//om de ikke har noe project fra før av
	if($stmt->rowCount() == 0)
	{
		$stmt -> closeCursor();
		$sql = "UPDATE projectrequest SET priority = 'taken' WHERE projectid LIKE ? AND groupid LIKE ? ";
		$stmt = $db->prepare ($sql);
		$stmt->execute(array($_POST['project'], $_POST['groupe']));


		//når en gruppe har fått et project trenger de ikke de gamle prioriteringene sine
		$stmt -> closeCursor();
		$sql = "DELETE FROM projectrequest WHERE projectid LIKE ? AND groupid LIKE ? AND priority NOT LIKE 'taken'";
		$stmt = $db->prepare ($sql);
		$stmt->execute(array($_POST['project'], $_POST['groupe']));

		$stmt -> closeCursor();
		$sql = "UPDATE projects SET status ='given' WHERE id LIKE ?";
		$stmt = $db->prepare ($sql);
		$stmt->execute(array($_POST['project']));
	}

	//oppdaer informasjon
	else
	{
		$projectRequest = $stmt->fetch();

		//frigjør tidligere prosjekt
		$stmt -> closeCursor();
		$sql = "UPDATE projects SET status ='cleared' WHERE id LIKE ?";
		$stmt = $db->prepare ($sql);
		$stmt->execute(array($projectRequest['projectid']));

		//ta det nye
		$stmt -> closeCursor();
		$sql = "UPDATE projects SET status ='given' WHERE id LIKE ?";
		$stmt = $db->prepare ($sql);
		$stmt->execute(array($_POST['project']));

		//oppdater til å jobbe på det nye
		$sql = "UPDATE projectrequest SET projectid = ? WHERE groupid LIKE ?";
		$stmt = $db->prepare ($sql);
		$stmt->execute(array($_POST['project'], $_POST['groupe']));


	}


	die (json_encode (array ('ok'=>'OK')));

?>