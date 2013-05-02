


<?php
	/**
	 * 
	 */
	// Start the session handling system
	session_start ();
	

	// Connect to the database
	require_once ("../db.php");

	$projectID = $_GET['id'];
	$userUID = $_SESSION['uid'];
	echo $projectID;

	/*$sql = " SELECT projects.* , externalusers.companyname
			 FROM projects, externalusers
			 WHERE projects.id = '$projectID' AND
			 externalusers.id = '$projectID' ";*/
	$sql = "SELECT title, projects.description, externalusers.companyname
 			FROM projects
 			LEFT JOIN externalusers ON externalusers.id = projects.owner
 			WHERE projects.id LIKE ?";

	$sth = $db->prepare ($sql);
	$sth->execute(array($projectID));

	$project = $sth->fetch();

	echo "<h1>" . $project['title'] . "</h1>";
	echo "<br>";
	echo "Bedrift/Eier: " . $project['companyname'];
	echo "<br>";
	echo $project['description'];
	
	
	$sql ="SELECT comment FROM staffcomments WHERE uid LIKE '$userUID' AND projectid LIKE '$projectID'";
	$sth->closeCursor();
	$sth = $db->prepare ($sql);
	$sth->execute();

		echo "<form class='commentBox' onsubmit='return false;'>
		<textarea class='tinymce' name='myComment'></textarea><br />
		<input type ='hidden' name='project' value='$projectID'>
		<input type ='hidden' name='user' value='$userUID'>
		<input type='button' value='Kommenter' onclick='javascript:addComments(this.form)'/>
		</form>";
	
	
	if($sth->rowcount())
	{
		echo"<h2>Mine kommentarer</h2>";
		foreach($sth->fetchAll() as $row)
		{
			echo "".$row['comment']. "<br /><br />";

		}
	}
	else
	{
		echo "<br />Du har ingen kommentarer på dette projektet";
	}



//comment
?>

<script>

	function addComments(form)
	{
		var data ={
					project : form.project.value,
					user : form.user.value,
					comment : form.myComment.value}; 

		$.ajax ({
			url : 'json/addComment.php',
			type : 'POST',
			data : data,
			success : function (data) {
				if (data.ok) 
				{
					alert ("Kommentar er lagret");
					$('body > section').load("pages/showProjectInfo.php?id=<?php echo $projectID;?>");
				}
				else
				{
					alert (data.error);
				}
					
			}
		});

	}

	$('.showProjectInfo').click (function() 
	{
		 alert($(this).attr('id'));
		$('body > section').load('pages/showProjectInfo.php?id=' + this.id);
		return false;
	});

</script>