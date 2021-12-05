<?php
	session_start();
	
	if(empty($_SESSION['type']))
		header("Location: ../index.php");
	else if(strcmp($_SESSION['type'], "user") == 0)
		header("Location: ../user/home.php");
?>