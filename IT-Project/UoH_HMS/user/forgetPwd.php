<?php
require "../db_connect.php";
require "../display_msg.php";
require "../verify_logged_out.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/forgot_pwd_style.css">
    <title>Forgot Password</title>
</head>

<body>

    <h2><a href="../index.php">UoH Hostel Management System</a></h2>
    <div class="forgotPwd-form">
        <h3>Forgot Password</h3>
        <div class="error-message" id="error-message">
            <p id="error" style="font-size: 16px;"></p>
        </div>
        <form action="" method="post">
            <div class="form-group">
                <input type="text" class="form-input" name="m_id" placeholder="Your ID *" required />
            </div>
            <div class="form-group">
                <input type="text" class="form-input" name="m_email" placeholder="Your Email *" required />
            </div>
            <div class="form-group">
                <input type="password" class="form-input" name="m_password" placeholder="New Password *" required />
            </div>
            <div class="form-group">
                <input type="submit" class="btnSubmit" name="p_change" value="Change Password" />
            </div>
        </form>
    </div>
</body>

<?php
if (isset($_POST['p_change'])) {
    // Getting the username and phone number from $_POST global variable.
    $id = mysqli_real_escape_string($conn, $_POST['m_id']);
    $email = mysqli_real_escape_string($conn, $_POST['m_email']);

    // Querying the database to check if user exist
    $sql = "SELECT id, email FROM user WHERE id='$id' and email='$email'";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);

    // If user exist then set the new password,
    // Otherwise show error message
    if ($count == 1) {
        $pwd = mysqli_real_escape_string($conn, $_POST['m_password']);
        $pwdEncoded = md5($pwd);

        // Updating the database with new password and show succesful message,
        // or else, show error message.
        $query = "UPDATE user SET PASSWORD ='$pwdEncoded' WHERE id='$id'";
        if (mysqli_query($conn, $query)) {
            $msg = "Congratulations You have successfully changed your password :)";
            $msgEncoded = base64_encode($msg);
            header('location:./index.php?msg='.$msgEncoded);
        } else {
            echo show_error_msg(mysqli_error($conn));
        }
    } else {
        echo show_error_msg("Invalid ID or Email :(");
    }
}
?>

</html>