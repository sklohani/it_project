<?php
	require "../db_connect.php";
	require "../verify_logged_out.php";

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
    <link rel="stylesheet" href="./css/index_style.css">
    <title>LMS Admin Login</title>
</head>

<body>
    <h2><a href="../index.php">UoH Library Management System</a></h2>
    <div class="login-form">
        <h3>Admin Login</h3>
        <div class="error-message" id="error-message">
			<p id="error" style="color: red; font-size:14px;"><?php echo $msg ?></p>
		</div>
        <form action="login.php" method="post">
            <div class="form-group">
                <input type="email" class="form-input" name="login_email" placeholder="Admin Email *" value="" required/>
            </div>
            <Label style="color:red">*<php echo $emailmsg ?></label>
            <div class="form-group">
                <input type="password" class="form-input" name="login_password" placeholder="Admin Password *" value="" required/>
            </div>
            <Label style="color:red">*<php echo $pasdmsg ?></label>
            <div class="form-group">
                <input type="submit" class="btnSubmit" value="Login" />
            </div>
            <div class="form-group">
                <a href="forgetPwd.php" class="ForgetPwd">Forgot Password?</a>
            </div>
        </form>
    </div>
</body>

</html>