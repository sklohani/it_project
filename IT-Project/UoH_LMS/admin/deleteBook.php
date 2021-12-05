<?php
require "../db_connect.php";
require "./verify_logged_in.php";

if(isset($_GET['isbn'])){
    $id=$_GET['isbn'];

    $qry="DELETE from book where isbn=$id";
    $result=mysqli_query($conn,$qry);
            
	if($result)
        header('Location:delete_book.php');
    else
        echo "ERROR!!";

}
?>
