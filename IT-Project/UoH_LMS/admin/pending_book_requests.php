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
    <link rel="stylesheet" type="text/css" href="../admin/css/pending_registrations_style.css">
    <title>Manage Pending Book Requests</title>
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
            <button class="headerBtn">Manage Pending Book Requests</button>

            <?php
            $query = $conn->prepare("SELECT * FROM pending_book_requests;");
            $query->execute();
            $result = $query->get_result();;
            $rows = mysqli_num_rows($result);
            if ($rows == 0)
                echo "<h2 align='center' style='color: green; font-size:20px;'>No Requests Pending :)</h2>";
            else {
                echo "<form class='cd-form' method='POST' action=''>";
                echo "<div class='error-message' id='error-message'>
						<p id='error'></p>
					</div>";
                echo "<table width='100%' cellpadding=5 cellspacing=5>
						<tr>
							<th></th>
							<th>User ID<hr></th>
							<th>Book<hr></th>
							<th>Time<hr></th>
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
                    for ($j = 1; $j < 4; $j++)
                        echo "<td>" . $row[$j] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                echo "<br /><br /><div style='float: right;'>";
                echo "<input type='submit' value='Reject Request' name='r_reject' />&nbsp;&nbsp;&nbsp;&nbsp;";
                echo "<input type='submit' value='Allow' name='r_allow'/>";
                echo "</div>";
                echo "</form>";
            }

            if (isset($_POST['r_allow'])) {
                $requests = 0;
                for ($i = 0; $i < $rows; $i++) {
                    if (isset($_POST['cb_' . $i])) {
                        $request_id =  $_POST['cb_' . $i];
                        $query = $conn->prepare("SELECT user_id, book_isbn FROM pending_book_requests WHERE request_id = ?;");
                        $query->bind_param("d", $request_id);
                        $query->execute();
                        $resultRow = mysqli_fetch_array($query->get_result());
                        $user_id = $resultRow[0];
                        $book_isbn = $resultRow[1];

                        $query = $conn->prepare("INSERT INTO book_issue_log(user_id, book_isbn) VALUES(?, ?);");
                        $query->bind_param("ss", $user_id, $book_isbn);
                        if (!$query->execute())
                            die(show_success_msg("ERROR: Couldn\'t Issue Book :("));
                        $requests++;

                        $query = $conn->prepare("SELECT due_date FROM book_issue_log WHERE user_id = ? AND book_isbn = ?;");
                        $query->bind_param("ss", $user_id, $book_isbn);
                        $query->execute();
                        $due_date = mysqli_fetch_array($query->get_result())[0];
                    }
                }
                if ($requests > 0)
                    echo show_success_msg("Granted Successfully!" . $requests . " Requests. Due Date: ". $due_date);
                else
                    echo show_error_msg("No Request Selected!");
            }

            if (isset($_POST['r_reject'])) {
                $requests = 0;
                for ($i = 0; $i < $rows; $i++) {
                    if (isset($_POST['cb_' . $i])) {
                        $requests++;
                        $request_id =  $_POST['cb_' . $i];

                        $query = $conn->prepare("DELETE FROM pending_book_requests WHERE request_id = ?");
                        $query->bind_param("d", $request_id);
                        if (!$query->execute())
                            die(show_error_msg("ERROR: Couldn\'t Delete Request :("));
                    }
                }
                if ($requests > 0)
                    echo show_success_msg("Successfully Deleted " . $requests . " Requests :)");
                else
                    echo show_error_msg("No Request Selected!");
            }
            ?>

        </div>
    </div>
</body>

</html>