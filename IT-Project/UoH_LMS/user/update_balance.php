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
    <link rel="stylesheet" type="text/css" href="./css/update_balance_style.css">
    <title>Update Balance</title>
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
            <button class="headerBtn">Update Available Balance</button>

            <?php
            $query = "SELECT BALANCE FROM user WHERE ID = '$id'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_array($result);
            ?>

            <div class="error-message" id="error-message">
                <p id="error"></p>
            </div>
            <h3>Available Balance: Rs. <?php echo $row[0] ?></h3>
            <form class="balance-form" action="" method="post">
                <input class="form-input" type="number" name="u_balance" placeholder="Balance to Add *" required />
                <input class="btnSubmit" type="submit" name="u_add" value="Update Balance" />
            </form>
        </div>
    </div>
</body>

<?php
if (isset($_POST['u_add'])) {

    $query = $conn->prepare("UPDATE user SET balance = balance + ? WHERE id = ?;");
    $query->bind_param("ds", $_POST['u_balance'], $id);
    if (!$query->execute())
        die(show_error_msg("ERROR: Couldn\'t Add Balance :("));
    echo show_success_msg("Balance Added Successfully :)");
}
?>

</html>