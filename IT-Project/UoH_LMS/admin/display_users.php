<?php
require "../db_connect.php";
require "../display_msg.php";
// require "./verify_logged_in.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/tab_style.css">
    <link rel="stylesheet" type="text/css" href="css/pending_registrations_style.css">
    <title>Display All Users</title>
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
            <button class="headerBtn">Display All Users</button>

            <?php
            $query = $conn->prepare("SELECT ID, NAME, EMAIL, TYPE, BALANCE FROM user ORDER BY NAME");
            $query->execute();
            $result = $query->get_result();
            if (!$result)
                die("ERROR: Couldn't Fetch Users");
            $rows = mysqli_num_rows($result);
            if ($rows == 0)
                echo "<h2 align='center'>No User Available!</h2>";
            else {
                echo "<form class='cd-form'>";
                echo "<div class='error-message' id='error-message'>
						<p id='error'></p>
					</div>";
                echo "<table width='100%' cellpadding=5 cellspacing=5>";
                echo "<tr>
				
						<th>ID<hr></th>
						<th>Name<hr></th>
						<th>Email<hr></th>
						<th>Type<hr></th>
						<th>Balance<hr></th>
					</tr>";
                for ($i = 0; $i < $rows; $i++) {
                    $row = mysqli_fetch_array($result);
                    echo "<tr>";
                    for ($j = 0; $j < 5; $j++)
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