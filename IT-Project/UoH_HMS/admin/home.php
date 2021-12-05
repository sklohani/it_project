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
    <link rel="stylesheet" type="text/css" href="./css/home_style.css">
    <title>Admin Home</title>
</head>
<body>

    <h2><a href="../index.php">UoH Hostel Management System</a></h2>
    <div class="container">
        <div class="leftdiv">
            <a href="./home.php"><Button class="btnPart">ADMIN</Button></a>
            <a href="./available_rooms.php"><Button class="btnPart">Display Available Rooms</Button></a>
            <a href="./display_users.php"><Button class="btnPart">Display All Boarders</Button></a>
            <a href="./pending_boarding.php"><Button class="btnPart">Pending Boarding Requests</Button></a>
            <a href="../logout.php"><Button class="btnPart">LOGOUT</Button></a>
        </div>
        <div class="rightdiv">
            <button class="headerBtn">Admin Profile</button>

            <?php
            $query = "SELECT ID, EMAIL FROM admin";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_array($result);
            ?>

            <h3>ID: <?php echo $row[0] ?></h3>
            <h3>Email: <?php echo $row[1] ?></h3>

        </div>
    </div>
</body>
</html>