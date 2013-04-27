<?php
try {

	$temp = file_get_contents("pw.txt");
	$pw = explode(" ",$temp);
	$db = new PDO('mysql:host=95.34.97.5;dbname='."$pw[0]", "$pw[0]","$pw[1]");
	} catch (PDOException $e) 
	{
    	die ('Kunne ikke koble til serveren : ' . $e->getMessage());
	}
?>
