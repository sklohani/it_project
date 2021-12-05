<?php
// Logging out - destroying the session variables and redirecting to index.php.
    session_start();
    if(isset($_SESSION["type"]))
    {
        session_unset();
        session_destroy();
        $msg = "Successfully Logged Out :)";
        $msgEncoded = base64_encode($msg);
        header('location:../index.php?msg='.$msgEncoded);
    }
?>