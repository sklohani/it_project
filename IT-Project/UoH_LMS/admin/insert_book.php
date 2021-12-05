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
    <link rel="stylesheet" type="text/css" href="css/insert_book_style.css">
    <title>Insert New Book Record</title>
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
            <button class="headerBtn">Insert New Book Record</button>

            <form class="book-form" method="POST" action="">
                <div class="error-message" id="error-message">
                    <p id="error" style="font-size: 16px;"></p>
                </div>
                <div class="form-group">
                    <input class="form-input" id="b_isbn" type="number" name="b_isbn" placeholder="ISBN *" required />
                </div>
                <div class="form-group">
                    <input class="form-input" type="text" name="b_title" placeholder="Book Title *" required />
                </div>
                <div class="form-group">
                    <select class="form-input" name="b_category">
                        <option value="" selected disabled>Category *</option>
                        <option>Technology</option>
                        <option>Mathematics</option>
                        <option>Physics</option>
                        <option>Chemistry</option>
                        <option>Economics</option>
                        <option>History</option>
                        <option>Politics</option>
                        <option>Law</option>
                        <option>Psychology</option>
                        <option>Literature</option>
                        <option>Medical</option>
                        <option>Education</option>
                        <option>Sports</option>
                        <option>Fantasy</option>
                        <option>Biography</option>
                        <option>Comics</option>
                        <option>Fiction</option>
                        <option>Non-Fiction</option>
                    </select>
                </div>
                <div class="form-group">
                    <input class="form-input" type="text" name="b_author" placeholder="Author Name *" required />
                </div>
                <div class="form-group">
                    <input class="form-input" type="number" name="b_price" placeholder="Price *" required />
                </div>

                <div class="form-group">
                    <input class="form-input" type="number" name="b_copies" placeholder="Number of Copies *" required />
                </div>

                <br />
                <input class="btnSubmit" type="submit" name="b_add" value="Add Book" />
            </form>
        </div>
    </div>
</body>

<?php
if (isset($_POST['b_add'])) {
    $query = $conn->prepare("SELECT isbn FROM book WHERE isbn = ?;");
    $query->bind_param("s", $_POST['b_isbn']);
    $query->execute();

    if (mysqli_num_rows($query->get_result()) != 0)
        echo show_error_msg("A book with entered ISBN already exists!");
    else {
        $query = $conn->prepare("INSERT INTO book VALUES(?, ?, ?, ?, ?, ?);");
        $query->bind_param("ssssdd", $_POST['b_isbn'], $_POST['b_title'], $_POST['b_author'], $_POST['b_category'], $_POST['b_price'], $_POST['b_copies']);

        if (!$query->execute())
            die(show_error_msg("ERROR: Couldn't add book"));
        echo show_success_msg("New book record has been added :)");
    }
}
?>

</html>