<?php
	require "db_connect.php";
	session_start();

	if(isset($_GET['msg']))
        $msg = base64_decode($_GET['msg']);
    else
        $msg = '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script> -->
	<link rel="stylesheet" href="style.css">
	<title>UoH Hostel Management System</title>
</head>

<body>

	<h2>UoH Hostel Management System</h2>
	<div id="box">
		<div class="message" id="message">
			<p id="msg" style="color:green; font-size:14px;"><?php echo $msg ?></p>
		</div>
		<div id="user">
			<a href="user">
				<img src="./pics/visitor.svg" width="185px" height="auto" /><br /><br/>
				&nbsp;User Login/Boarding
			</a>
		</div>
		<div id="verticalLine">
			<div id="admin">
				<a id="admin-link" href="admin">
					<img src="./pics/admin.svg" width="300px" height="220" /><br />
					&nbsp;&nbsp;&nbsp;Admin Login
				</a>
			</div>
		</div>
	</div>

</body>

</html>