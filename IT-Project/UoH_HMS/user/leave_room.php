<?php
require "../db_connect.php";
require "../display_msg.php";
require "./verify_logged_in.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/tab_style.css">
    <link rel="stylesheet" type="text/css" href="./css/leave_room_style.css">
    <title>Leave Room</title>
</head>

<body>
    <?php
    $id = $_SESSION["id"];
    $query = "SELECT name FROM user WHERE id='$id'";
    $query_result = mysqli_query($conn, $query);
    $fetch_result = mysqli_fetch_assoc($query_result);
    $name = $fetch_result['name'];
    ?>
    <h2><a href="../index.php">UoH Hostel Management System</a></h2>
    <div class="container">
        <div class="leftdiv">
            <a href="./home.php"><Button class="btnPart"><?php echo $name ?></Button></a>
            <a href="./leave_room.php"><Button class="btnPart">Leave Room</Button></a>
            <a href="../logout.php"><Button class="btnPart">LOGOUT</Button></a>
        </div>
        <div class="rightdiv">
            <button class="headerBtn">Leave Your Room</button>
            <div class='error-message' id='error-message'>
                <p id='error'></p>
            </div>
            <div class="leave-form">
                <p>Type "Leave" without double-quote to Confirm.</p>
                <form action="" method="post">
                    <input type="text" class="form-input" name="confirm" id="confirm" placeholder="Your Input *" required>
                    <input type="submit" class="btnSubmit" name="u_confirm" value="Confirm">
                </form>
            </div>
        </div>
    </div>
</body>

<?php

if (isset($_POST['u_confirm'])) {
    $text = $_POST['confirm'];
    if (strcmp($text, "Leave") != 0)
        echo show_error_msg("Invalid Input!");
    else {
        $query = $conn->prepare("DELETE FROM user WHERE id = ?;");
        $query->bind_param("s", $id);
        if (!$query->execute()) {
            die(show_error_msg("ERROR! Couldn\'t Leave Room"));
        }
        else
        {
            session_unset();
            session_destroy();
            $msg = "Good Bye! See You Soon :)";
            $msgEncoded = base64_encode($msg);
            header('location:../index.php?msg=' . $msgEncoded);
        }
    }
}
?>

</html>