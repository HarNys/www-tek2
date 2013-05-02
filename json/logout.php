<?php 

	session_start ();   

	if(isset($_SESSION['uid'])) 
	{
		echo "loogout";
		session_destroy();
	}
	else
	{
		echo "did not logout";

	} 
	echo "<br /> lol"

?>