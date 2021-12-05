<?php
    // Starting the session.
    session_start();

    // Connecting to database
    require "../db_connect.php";

    // Getting the username and password from $_POST global variable.
    $email = mysqli_real_escape_string($conn, $_POST['login_email']);
    $password = mysqli_real_escape_string($conn, $_POST['login_password']);

    // Encoding the password
    $passwordEncoded = md5($password);

    // Querying the database to check if user exist
    $sql = "SELECT EMAIL, PASSWORD FROM admin WHERE EMAIL='$email' and PASSWORD='$passwordEncoded'";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);

    // If the user exits then set the session variable and,
    // redirect to home.php
    // Otherwise, redirect to index.php with error message.
    if($count == 1)
    {
        $_SESSION["type"] = "admin";
        header('location:home.php');
    }
    else
    {
        $msg = "Invalid Username or Password :(";
        $msgEncoded = base64_encode($msg);
        header('location:index.php?msg='.$msgEncoded);
    }

?>