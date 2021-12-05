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
    <title>Display My Books</title>
</head>

<body>
    <?php
    $id = $_SESSION['id'];
    $query = "SELECT name FROM user WHERE id='$id'";
    $query_result = mysqli_query($conn, $query);
    $fetch_result = mysqli_fetch_assoc($query_result);
    $name = $fetch_result['name'];
    ?>
    <h2><a href="../index.php">UoH Library Management System</a></h2>
    <div class="container">
        <div class="leftdiv">
            <a href="./home.php"><Button class="btnPart"><?php echo $name ?></Button></a>
            <a href="./display_books.php"><Button class="btnPart">Display All Books</Button></a>
            <a href="./display_my_books.php"><Button class="btnPart">Display My Books</Button></a>
            <a href="./issue_book.php"><Button class="btnPart">Issue Book</Button></a>
            <a href="./update_balance.php"><Button class="btnPart">Update Balance</Button></a>
            <a href="../logout.php"><Button class="btnPart">LOGOUT</Button></a>
        </div>
        <div class="rightdiv">
            <button class="headerBtn">Display My Books</button>

            <?php
            $query = $conn->prepare("SELECT book_isbn FROM book_issue_log WHERE user_id = ?;");
            $query->bind_param("s", $id);
            $query->execute();
            $result = $query->get_result();
            $rows = mysqli_num_rows($result);
            if ($rows == 0)
                echo "<h2 align='center' style='color: green; font-size:20px;'>No Issued Book Found!</h2>";
            else {
                echo "<form class='cd-form' method='POST' action=''>";
                echo "<div class='message' id='message'>
						<p id='msg'></p>
					</div>";
                echo "<div class='error-message' id='error-message'>
						<p id='error'></p>
					</div>";
                echo "<table width='100%' cellpadding='5' cellspacing='5'>
						<tr>
							<th></th>
							<th>ISBN<hr></th>
							<th>Title<hr></th>
							<th>Due Date<hr></th>
						</tr>";
                for ($i = 0; $i < $rows; $i++) {
                    $isbn = mysqli_fetch_array($result)[0];
                    if ($isbn != NULL) {
                        $query = $conn->prepare("SELECT title FROM book WHERE isbn = ?;");
                        $query->bind_param("s", $isbn);
                        $query->execute();
                        $title = mysqli_fetch_array($query->get_result())[0];
                        echo "<tr>
								<td>
									<label class='control control--checkbox'>
										<input type='checkbox' name='cb_book" . $i . "' value='" . $isbn . "'>
										<div class='control__indicator'></div>
									</label>
								</td>";
                        echo "<td>" . $isbn . "</td>";
                        echo "<td>" . $title . "</td>";

                        $query = $conn->prepare("SELECT due_date FROM book_issue_log WHERE user_id = ? AND book_isbn = ?;");
                        $query->bind_param("ss", $id, $isbn);
                        $query->execute();
                        echo "<td>" . mysqli_fetch_array($query->get_result())[0] . "</td>";
                        echo "</tr>";
                    }
                }
                echo "</table><br />";
                echo "<input type='submit' name='b_return' value='Return Selected Books' />";
                echo "</form>";
            }

            if (isset($_POST['b_return'])) {
                $books = 0;
                for ($i = 0; $i < $rows; $i++)
                    if (isset($_POST['cb_book' . $i])) {
                        $query = $conn->prepare("SELECT due_date FROM book_issue_log WHERE user_id = ? AND book_isbn = ?;");
                        $query->bind_param("ss", $id, $_POST['cb_book' . $i]);
                        $query->execute();
                        $due_date = mysqli_fetch_array($query->get_result())[0];

                        $query = $conn->prepare("SELECT DATEDIFF(CURRENT_DATE, ?);");
                        $query->bind_param("s", $due_date);
                        $query->execute();
                        $late_days = (int)mysqli_fetch_array($query->get_result())[0];

                        $query = $conn->prepare("DELETE FROM book_issue_log WHERE user_id = ? AND book_isbn = ?;");
                        $query->bind_param("ss", $id, $_POST['cb_book' . $i]);
                        if (!$query->execute())
                            die(show_error_msg("ERROR: Couldn\'t Return the Books :("));

                        if ($late_days > 0) {
                            $penalty = 10 * $late_days;
                            $query = $conn->prepare("UPDATE user SET balance = balance - ? WHERE id = ?;");
                            $query->bind_param("ds", $penalty, $id);
                            $query->execute();
                            echo '<script>
									document.getElementById("msg").innerHTML += "Penalty of Rs. ' . $penalty . ' was charged for keeping book ' . $_POST['cb_book' . $i] . ' for ' . $days . ' days late.<br />";
									document.getElementById("message").style.display = "block";
								</script>';
                        }
                        $books++;
                    }
                if ($books > 0) {
                    echo show_success_msg("Successfully Returned ' . $books . ' Book :)");
                } else
                    echo show_error_msg("Please Select a Book to Return!");
            }
            ?>

        </div>
    </div>
</body>

</html>