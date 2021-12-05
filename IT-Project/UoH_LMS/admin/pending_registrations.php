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
    <link rel="stylesheet" type="text/css" href="css/pending_registrations_style.css">
    <title>Manage Pending Membership Registrations</title>
</head>

<body>

    <h2><a href="../index.php">UoH Library Management System</a></h2>
    <div class="container">
        <div class="leftdiv">
            <a href="./home.php"><Button class="btnPart">ADMIN</Button></a>
            <a href="./insert_book.php"><Button class="btnPart">Insert Book</Button></a>
            <a href="./delete_book.php"><Button class="btnPart">Delete Book</Button></a>
            <a href="./display_books.php"><Button class="btnPart">Display Books</Button></a>
            <a href="./display_users.php"><Button class="btnPart">Display Users</Button></a>
            <a href="./pending_book_requests.php"><Button class="btnPart">Pending Book Requests</Button></a>
            <a href="./pending_registrations.php"><Button class="btnPart">Pending Registrations</Button></a>
            <a href="../logout.php"><Button class="btnPart">LOGOUT</Button></a>
        </div>
        <div class="rightdiv">
            <button class="headerBtn">Manage Pending Membership Registrations</button>

            <?php
            $query = $conn->prepare("SELECT id, name, email, type, balance FROM pending_registrations");
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
                    for ($j = 0; $j < 4; $j++)
                        echo "<td>" . $row[$j] . "</td>";
                    echo "<td>Rs." . $row[$j] . "</td>";
                    echo "</tr>";
                }
                echo "</table><br /><br />";
                echo "<div style='float: right;'>";

                echo "<input type='submit' value='Confirm Verification' name='l_confirm' />&nbsp;&nbsp;&nbsp;";
                echo "<input type='submit' value='Reject' name='l_delete' />";
                echo "</div>";
                echo "</form>";
            }

            if (isset($_POST['l_confirm'])) {
                $members = 0;
                for ($i = 0; $i < $rows; $i++) {
                    if (isset($_POST['cb_' . $i])) {
                        $id =  $_POST['cb_' . $i];
                        $query = $conn->prepare("SELECT * FROM pending_registrations WHERE id = ?;");
                        $query->bind_param("s", $id);
                        $query->execute();
                        $row = mysqli_fetch_array($query->get_result());

                        $query = $conn->prepare("INSERT INTO user(id, name, email, password, type, balance) VALUES(?, ?, ?, ?, ?, ?);");
                        $query->bind_param("sssssd", $id, $row[1], $row[2], $row[3], $row[4], $row[5]);
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

                        $query = $conn->prepare("DELETE FROM pending_registrations WHERE id = ?;");
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