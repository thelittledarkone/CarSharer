<?php
//<!--The user is re-directed to this file after clicking the activation link-->
//<!--Signup link contains 2 get perameters: email and activation key-->

//<!--Start Session-->
session_start();
//<!--Connect to database-->
include('connection.php');
    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible"content="IE=edge">
        <meta name="viewport"content="width=device-width, initial-scale=1">
        <title>Account Activation</title>
<!--        CSS-->
        <link href="boot/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/style.css">
<!--        Icons and Fonts-->
        <link rel="icon" href="css/MBK.ico">
        <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Black+Ops+One&display=swap" rel="stylesheet">
    </head>
    <body>
        <!--        Nav Bar-->
        
        <!--   Message Body-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-offset-1 col-sm-10">
                    <h1>Account Activation</h1>
        
<?php

//<!--If email or activation key is missing, show error-->
if(!isset($_GET['email']) || !isset($_GET['key'])){
    echo '<div class="alert alert-danger">Error: Please click the activation link in email</div>';
    exit;
}
//<!--    Store them in 2 variables-->
$email = $_GET['email'];
$key = $_GET['key'];
//<!--    Prepare variables for query-->
$email = mysqli_real_escape_string($link, $email);
$key = mysqli_real_escape_string($link, $key);

//<!--    Run query set activation field to activated for the provided email-->
$sql = "UPDATE users SET activation='activated' WHERE (email='$email' AND activation='$key') LIMIT 1";
$result = mysqli_query($link, $sql);

//<!--    If query is successful, show success message and invite user to login-->
if(mysqli_affected_rows($link) == 1){
    echo "<div class='alert alert-success'>Your account has been activated!</div>";
    echo '<a href="index.php" type="button" class="btn-lg btn-success">Log In</a>';
}else{
//<!--        Show error message-->
    echo '<div class="alert alert-danger">Your account could not be activated. Please try again later.</div>';
}            
?>            
                </div>
            </div>
        </div>
<!--        Footer-->
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="boot/js/bootstrap.min.js"></script>
                
    </body>
</html>
