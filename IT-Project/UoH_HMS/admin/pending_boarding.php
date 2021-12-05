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
    <link rel="stylesheet" type="text/css" href="css/pending_boarding_style.css">
    <title>Manage Pending Boarding Request</title>
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
            <button class="headerBtn">Manage Pending Boarding Request</button>

            <?php
            $query = $conn->prepare("SELECT id, name, email, type, to_date, hostel, balance FROM pending_boarding");
            $query->execute();
            $result = $query->get_result();
            $rows = mysqli_num_rows($result);
            if ($rows == 0)
                echo "<h2 align='center' style='color: green; font-size:20px;'>None at the moment :)</h2>";
            else {
                echo "<form class='cd-form' method='POST' action=''>";
                echo "<div class='error-message' id='error-message'>
                                <p id='error' style='font-size: 16px;'></p>
                            </div>";
                echo "<table width='100%' cellpadding=5 cellspacing=5>
                                <tr>
                                    <th></th>
                                    <th>Id<hr></th>
                                    <th>Name<hr></th>
                                    <th>Email<hr></th>
                                    <th>Type<hr></th>
                                    <th>Till Date<hr></th>
                                    <th>Hostel<hr></th>
                                    <th>Balance<hr></th>
                                </tr>";
                for ($i = 0; $i < $rows; $i++) {
                    $row = mysqli_fetch_array($result);
                    echo "<tr>";
                    echo "<td>
                                <label class='control control--checkbox'>
                                    <input type='checkbox' name='cb_" . $i . "' value='" . $row[0] . "' />
                                    <div class='control__indicator'></div>
                                </label>
                            </td>";
                    $j;
                    for ($j = 0; $j < 6; $j++)
                        echo "<td>" . $row[$j] . "</td>";
                    echo "<td>Rs." . $row[$j] . "</td>";
                    echo "</tr>";
                }
                echo "</table><br /><br />";
                echo "<div style='float: right;'>";

                echo "<input type='submit' value='Confirm Board' name='l_confirm' />&nbsp;&nbsp;&nbsp;";
                echo "<input type='submit' value='Reject' name='l_delete' />";
                echo "</div>";
                echo "</form>";
            }

            if (isset($_POST['l_confirm'])) {
                $members = 0;
                for ($i = 0; $i < $rows; $i++) {
                    if (isset($_POST['cb_' . $i])) {
                        $id =  $_POST['cb_' . $i];
                        $query = $conn->prepare("SELECT * FROM pending_boarding WHERE id = ?;");
                        $query->bind_param("s", $id);
                        $query->execute();
                        $row = mysqli_fetch_array($query->get_result());

                        $query = $conn->prepare("SELECT room_no FROM hostel_room WHERE hostel_name  = ? AND available = 0");
                        $query->bind_param("s", $row[6]);
                        $query->execute();
                        $room = mysqli_fetch_array($query->get_result())[0];

                        $query = $conn->prepare("INSERT INTO user(id, name, email, password, type, to_date, hostel, room, balance) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?);");
                        $query->bind_param("ssssssssd", $id, $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $room, $row[7]);
                        if (!$query->execute()) {
                            die(show_error_msg("ERROR: Unable to Insert :("));
                        }
                        $members++;
                    }
                }
                if ($members > 0)
                    echo show_success_msg("Successfully Added " . $members . " Users");
                else
                    echo show_error_msg("No Registration Selected");
            }

            if (isset($_POST['l_delete'])) {
                $requests = 0;
                for ($i = 0; $i < $rows; $i++) {
                    if (isset($_POST['cb_' . $i])) {
                        $id =  $_POST['cb_' . $i];

                        $query = $conn->prepare("DELETE FROM pending_boarding WHERE id = ?;");
                        $query->bind_param("s", $id);
                        if (!$query->execute()) {
                            die(show_error_msg("ERROR: Couldn\'t Delete "));
                        }
                        $requests++;
                    }
                }
                if ($requests > 0)
                    show_success_msg("Successfully Deleted " . $requests . " Requests");
                else
                    show_error_msg("No Registration Selected");
            }
            ?>
        </div>
    </div>
</body>

</html>