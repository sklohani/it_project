<?php
    // Connecting to database
    $conn = mysqli_connect('localhost', 'root', '', 'hmsdb');

    // If connection fails to establish then exit.
    if(!$conn)
    {
        die('Connection Failed!'.mysqli_error($conn));
    }
?>