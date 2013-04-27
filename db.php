<?php
try {

	// $temp = file_get_contents("./pw.txt");
	// $pw = explode(" ",$temp);
	// $db = new PDO('mysql:host=harnys.net; dbname='."$pw[0]", "$pw[0]","$pw[1]");
	$db = new PDO('mysql:host=localhost; dbname=blog', "blog", "jeghaddetrobbel");
	} catch (PDOException $e) 
	{
    	die ('Kunne ikke koble til serveren : ' . $e->getMessage());
	}
?>

