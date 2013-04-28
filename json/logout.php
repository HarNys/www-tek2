<?php 

	session_start ();  

	error_log("logging out if logedin"); 

	if(isset($_SESSION['uid'])) 
	{
		session_destroy();
	} 

?>