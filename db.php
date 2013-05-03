<?php
try {
	
	/* Hva johannes bruker
	$db = new PDO('mysql:host=localhost; dbname=www-tek2', "root", "D&Dftw");
	} catch (PDOException $e) 
	{
    	die ('Kunne ikke koble til serveren : ' . $e->getMessage());
	}*/


	$db = new PDO('mysql:host=localhost; dbname=blog', "blog", "jeghaddetrobbel");
	} catch (PDOException $e) 
	{
    	die ('Kunne ikke koble til serveren : ' . $e->getMessage());
	}
?>

