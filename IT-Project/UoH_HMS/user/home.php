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
    <title>User Home</title>
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
            <button class="headerBtn"><?php echo $name ?> Profile</button>

            <?php
            $query = "SELECT ID, NAME, EMAIL, TYPE, from_date, to_date, hostel, room, BALANCE FROM user WHERE ID = '$id'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_array($result);
            ?>

            <h3>ID: <?php echo $row[0] ?></h3>
            <h3>Name: <?php echo $row[1] ?></h3>
            <h3>Email: <?php echo $row[2] ?></h3>
            <h3>Role: <?php echo $row[3] ?></h3>
            <h3>Staying From: <?php echo $row[4] ?></h3>
            <h3>Staying Till: <?php echo $row[5] ?></h3>
            <h3>Hostel Alloted: Hostel-<?php echo $row[6] ?></h3>
            <h3>Room Alloted: <?php echo $row[7] ?></h3>
            <h3>Balance: Rs. <?php echo $row[8] ?></h3>
        </div>
    </div>
</body>
</html>