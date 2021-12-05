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
    <title>Display All Books</title>
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
            <button class="headerBtn">Display All Books</button>

            <?php
            $query = $conn->prepare("SELECT * FROM book ORDER BY title");
            $query->execute();
            $result = $query->get_result();
            if (!$result)
                die("ERROR: Couldn't Fetch Books");
            $rows = mysqli_num_rows($result);
            if ($rows == 0)
                echo "<h2 align='center'>No Books Available!</h2>";
            else {
                echo "<form class='cd-form'>";
                echo "<div class='error-message' id='error-message'>
						<p id='error'></p>
					</div>";
                echo "<table width='100%' cellpadding=5 cellspacing=5>";
                echo "<tr>
				
						<th>ISBN<hr></th>
						<th>Book Title<hr></th>
						<th>Author<hr></th>
						<th>Category<hr></th>
						<th>Price<hr></th>
                        <th>Copies<hr></th>
					</tr>";
                for ($i = 0; $i < $rows; $i++) {
                    $row = mysqli_fetch_array($result);
                    echo "<tr>";
                    for ($j = 0; $j < 6; $j++)
                        if ($j == 4)
                            echo "<td>Rs." . $row[$j] . "</td>";
                        else
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