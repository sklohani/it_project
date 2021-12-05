<?php
	require "../db_connect.php";
	require "../verify_logged_out.php";

    if(isset($_GET['msg']))
        $msg = base64_decode($_GET['msg']);
    else
        $msg = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if ($_POST['u_balance'] < 10000)
            $msg = "Initial balance must be minimum 10000 for boarding!";
        else
        {
            $query = $conn->prepare("(SELECT ID FROM USER WHERE ID = ?) UNION (SELECT ID FROM pending_boarding WHERE ID = ?);");
            $query->bind_param("ss", $_POST['u_id'], $_POST['u_id']);
            $query->execute();
            if(mysqli_num_rows($query->get_result()) != 0)
                $msg = "User ID entered is already taken :(";
            else
            {
                $query = $conn->prepare("(SELECT EMAIL FROM USER WHERE EMAIL = ?) UNION (SELECT EMAIL FROM pending_boarding WHERE EMAIL = ?);");
                $query->bind_param("ss", $_POST['u_email'], $_POST['u_email']);
                $query->execute();
                if(mysqli_num_rows($query->get_result()) != 0)
                    $msg = "Account already registered with entered email :(";
                else
                {
                    $query = $conn->prepare("SELECT room_no FROM hostel_room WHERE hostel_name  = ? AND available = 0");
                    $query->bind_param('s', $_POST['u_hostel']);
                    $query->execute();
                    if(mysqli_num_rows($query->get_result()) == 0)
                        $msg = "No Rooms Available in selected Hostel :(";
                    else
                    {
                        $query = $conn->prepare("INSERT INTO pending_boarding(ID, NAME, EMAIL, PASSWORD, TYPE, to_date, hostel, BALANCE) VALUES(?, ?, ?, ?, ?, ?, ?, ?);");
                        $query->bind_param("sssssssd", $_POST['u_id'], $_POST['u_name'], $_POST['u_email'], md5($_POST['u_password']), $_POST['u_type'], $_POST['u_toDate'], $_POST['u_hostel'], $_POST['u_balance']);
                        if($query->execute())
                        {
                            $msg = "Details submitted, soon you'll will be notified after verifications :)";
                            $msgEncoded = base64_encode($msg);
                            header('location:../index.php?msg='.$msgEncoded);
                        }
                        else
                            $msg = "Registration Unsuccessful! Please try again later :(";
                    }
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/board_style.css">
    <title>HMS Boarder Boarding</title>
</head>

<body>
    <h2><a href="../index.php">UoH Hostel Management System</a></h2>
    <div class="reg-form">
        <h3>Boarder Boarding</h3>
        <div class="error-message" id="error-message">
			<p id="error" style="color: red; font-size:14px;"><?php echo $msg ?></p>
		</div>
        <form action="" method="POST">
            <div class="form-group">
                <input type="text" class="form-input" id="u_name" name="u_name" placeholder="Your Full Name *" required/>
            </div>
            <div class="form-group">
                <input type="text" class="form-input" id="u_id" name="u_id" placeholder="Your User ID *" required/>
            </div>
            <div class="form-group">
                <input type="email" class="form-input" id="u_email" name="u_email" placeholder="Your Email *" required/>
            </div>
            <div class="form-group">
                <select class="form-input" name="u_type" id="u_type" required>
                    <option value="" selected disabled>User Type *</option>
                    <option value="Student">Student</option>
                    <option value="Employee">Employee</option>
                </select>
            </div>
            <div class="form-group">
                <select class="form-input" name="u_hostel" id="u_hostel" required>
                    <option value="" selected disabled>Apply for Hostel *</option>
                    <option value="A">Hostel-A</option>
                    <option value="B">Hostel-B</option>
                </select>
            </div>
            <div class="form-group">
                <input type="text" class="form-input" id="u_toDate" name="u_toDate" placeholder="Boarding Till *" onfocus="(this.type='date')" onblur="if(this.value==''){this.type='text'}" required/>
            </div>
            <div class="form-group">
                <input type="number" class="form-input" id="u_balance" name="u_balance" placeholder="Initial Balance * (Minimum: 10000)" required/>
            </div>
            <div class="form-group">
                <input type="password" class="form-input" id="u_password" name="u_password" placeholder="Your Password *" required/>
            </div>
            <div class="form-group">
                <input type="submit" class="btnSubmit" name="u_board" value="Board Now" />
            </div>
            <div class="form-group">
                <a href="index.php" class="login">Already Boarded? Login Here</a>
            </div>
        </form>
    </div>
</body>
</html>