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
    <link rel="stylesheet" type="text/css" href="./css/pending_boarding_style.css">
    <title>Display All Boarders</title>
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
            <button class="headerBtn">Display All Boarders</button>

            <?php
            $query = $conn->prepare("SELECT ID, NAME, from_date, to_date, HOSTEL, ROOM FROM user ORDER BY NAME");
            $query->execute();
            $result = $query->get_result();
            if (!$result)
                die("ERROR: Couldn't Fetch Users");
            $rows = mysqli_num_rows($result);
            if ($rows == 0)
                echo "<h2 align='center'>No Boarder Available!</h2>";
            else {
                echo "<form class='cd-form'>";
                echo "<div class='error-message' id='error-message'>
						<p id='error'></p>
					</div>";
                echo "<table width='100%' cellpadding=5 cellspacing=5>";
                echo "<tr>
						<th>ID<hr></th>
						<th>Name<hr></th>
						<th>Staying From<hr></th>
						<th>Staying Till<hr></th>
						<th>Hostel<hr></th>
						<th>Room<hr></th>
					</tr>";
                for ($i = 0; $i < $rows; $i++) {
                    $row = mysqli_fetch_array($result);
                    echo "<tr>";
                    for ($j = 0; $j < 6; $j++)
                            echo "<td>" . $row[$j] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";

                echo "</form>";
            }
            ?>
        </div>
    </div>
</body>

</html>