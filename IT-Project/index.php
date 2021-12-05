<?php
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
	<title>Management System Dashboard</title>
</head>

<body>

	<h2>Management System Dashboard</h2>
	<div id="box">
		<div class="message" id="message">
			<p id="msg" style="color:green; font-size:14px;"><?php echo $msg ?></p>
		</div>
		<div id="library">
			<a href="./UoH_LMS/index.php">
				<img src="./pics/library.svg" width="200px" height="auto" /><br /><br/>
				&nbsp;Library Management System
			</a>
		</div>
		<div id="verticalLine">
			<div id="hostel">
				<a id="hostel-link" href="./UoH_HMS/index.php">
					<img src="./pics/hostel.svg" width="200px" height="220" /><br />
					&nbsp;&nbsp;&nbsp;Hostel Management System
				</a>
			</div>
		</div>
	</div>

</body>

</html>