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
    <link rel="stylesheet" type="text/css" href="./css/issue_book_style.css">
    <title>Request for Book Issue</title>
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
            <button class="headerBtn">Request for Book Issue</button>

            <?php
            $query = $conn->prepare("SELECT * FROM book ORDER BY title");
            $query->execute();
            $result = $query->get_result();
            if (!$result)
                die("ERROR: Couldn't Fetch Books");
            $rows = mysqli_num_rows($result);
            if ($rows == 0)
                echo "<h2 align='center'>No Books are Available!</h2>";
            else {
                echo "<form class='cd-form' method='POST' action='#'>";
                echo "<h2>List of Available Books</h2>";
                echo "<div class='error-message' id='error-message'>
						<p id='error'></p>
					</div>";
                echo "<table width='100%' cellpadding=5 cellspacing=5>";
                echo "<tr>
						<th></th>
						<th>ISBN<hr></th>
						<th>Book Title<hr></th>
						<th>Author<hr></th>
						<th>Category<hr></th>
						<th>Price<hr></th>
						<th>Copies<hr></th>
					</tr>";
                for ($i = 0; $i < $rows; $i++) {
                    $row = mysqli_fetch_array($result);
                    echo "<tr>
							<td>
								<label class='control control--radio'>
									<input type='radio' name='isbn_book' value=" . $row[0] . " />
								<div class='control__indicator'></div>
							</td>";
                    for ($j = 0; $j < 6; $j++)
                        if ($j == 4)
                            echo "<td>Rs." . $row[$j] . "</td>";
                        else
                            echo "<td>" . $row[$j] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                echo "<br /><br /><input type='submit' name='u_request' value='Request Book' />";
                echo "</form>";
            }

            if (isset($_POST['u_request'])) {
                if (empty($_POST['isbn_book']))
                    echo show_error_msg("Please Select a Book to Issue!");
                else {
                    $query = $conn->prepare("SELECT copies FROM book WHERE isbn = ?;");
                    $query->bind_param("s", $_POST['isbn_book']);
                    $query->execute();
                    $copies = mysqli_fetch_array($query->get_result())[0];
                    if ($copies == 0)
                        echo show_error_msg("No Copies of Selected Book are Available :(");
                    else {
                        $query = $conn->prepare("SELECT request_id FROM pending_book_requests WHERE user_id = ?;");
                        $query->bind_param("s", $id);
                        $query->execute();
                        if (mysqli_num_rows($query->get_result()) == 1)
                            echo show_error_msg("You can only request one book at a time!");
                        else {
                            $query = $conn->prepare("SELECT book_isbn FROM book_issue_log WHERE user_id = ?;");
                            $query->bind_param("s", $id);
                            $query->execute();
                            $result = $query->get_result();
                            if (mysqli_num_rows($result) >= 2)
                                echo show_error_msg("Cannot issue more than 2 books at a time!");
                            else {
                                $rows = mysqli_num_rows($result);
                                for ($i = 0; $i < $rows; $i++)
                                {
                                    if (strcmp(mysqli_fetch_array($result)[0], $_POST['isbn_book']) == 0)
                                        break;
                                }
                                if ($i < $rows)
                                    echo show_error_msg("You have already issued a copy of this book!");
                                else {
                                    $query = $conn->prepare("SELECT balance FROM user WHERE id = ?;");
                                    $query->bind_param("s", $id);
                                    $query->execute();
                                    $balance = mysqli_fetch_array($query->get_result())[0];

                                    $query = $conn->prepare("SELECT price FROM book WHERE isbn = ?;");
                                    $query->bind_param("s", $_POST['isbn_book']);
                                    $query->execute();
                                    $bookPrice = mysqli_fetch_array($query->get_result())[0];
                                    if ($balance < $bookPrice)
                                        echo show_error_msg("Not have sufficient balance to issue selected book!");
                                    else {
                                        $query = $conn->prepare("INSERT INTO pending_book_requests(user_id, book_isbn) VALUES(?, ?);");
                                        $query->bind_param("ss", $id, $_POST['isbn_book']);
                                        if (!$query->execute())
                                            echo show_error_msg("ERROR: Couldn\'t Request Book :(");
                                        else
                                            echo show_success_msg("Selected book has been requested :)");
                                    }
                                }
                            }
                        }
                    }
                }
            }
            ?>
        </div>
    </div>
</body>

</html>