<!--This file receives: user_id, generated key to reset password, password1 and password2-->
<!--This file then resets password for user_id if all checks are correct-->

<?php
//<!--The user is re-directed to this file after clicking the recover account link-->
//<!--Recover link contains 2 get perameters: user_id and key-->

//<!--Start Session-->
session_start();
//<!--Connect to database-->
include('connection.php');

//<!--If user_id or recover key is missing, show error-->
if(!isset($_POST['user_id']) || !isset($_POST['key'])){
    echo '<div class="alert alert-danger">Error: Please click the link in email</div>';
    exit;
}
//<!--    Store them in 2 variables-->
$user_id = $_POST['user_id'];
$key = $_POST['key'];
$time = time() - 86400;
//<!--    Prepare variables for query-->
$user_id = mysqli_real_escape_string($link, $user_id);
$key = mysqli_real_escape_string($link, $key);

//<!--    Run query to check combination of user_id & key exists and is less than 24h old-->
$sql = "SELECT user_id FROM forgotpassword WHERE fp_key='$key' AND user_id='$user_id' AND time>'$time' AND status='pending'";
$result = mysqli_query($link, $sql);
if(!$result){
    echo '<div class="alert alert-danger">Error inserting user details into the database</div>';
    exit;
}
//<!--    If combination does not exist->
$count = mysqli_num_rows($result);
if($count !== 1){
    echo '<div class="alert alert-danger">Please try again.</div>';
    exit;
}

//Errors
$missingPassword = '<p><strong>Please enter a Password!</strong></p>';
$invalidPassword = '<p><strong>Your password should be at least 6 characters long and include at least one capital letter and one number!</strong></p>';
$differentPassword = '<p><strong>Passwords don\'t match!</strong></p>';
$missingPassword2 = '<p><strong>Please confirm your Password!</strong></p>';

//Passwords
if(empty($_POST["password"])){
    $errors .= $missingPassword;
}elseif(!(strlen($_POST["password"])>6 
        and preg_match('/[A-Z]/', $_POST["password"])
        and preg_match('/[A-Z]/', $_POST["password"])
          )
       ){
    $errors .= $invalidPassword;
}else{
    $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);
    if(empty($_POST["password2"])){
        $errors .= $missingPassword2;
    }else{
        $password2 = filter_var($_POST["password2"], FILTER_SANITIZE_STRING);
        if($password !== $password2){
            $errors .= $differentPassword;
        }
    }
}

//If errors, print error message
if($errors){
    $resultMessage = '<div class="alert alert-danger">' . $errors . '</div>';
    echo $resultMessage;
    exit;
}

//<!--    Prepare variables for the queries-->
$user_id = mysqli_real_escape_string($link, $user_id);

$password = mysqli_real_escape_string($link, $password);
$password = hash('sha256', $password);

//Update users password in users table
$sql = "UPDATE users SET password='$password' WHERE user_id='$user_id'";
$result = mysqli_query($link, $sql);
if(!$result){
    echo '<div class="alert alert-danger">Error updating user details into database</div>';
    exit;
}

//set key status to used so as to avoid link multi usage.
$sql = "UPDATE forgotpassword SET status='used' WHERE fp_key='$key' AND user_id='$user_id'";
$result = mysqli_query($link, $sql);
if(!$result){
    echo '<div class="alert alert-danger">Error updating status details into database</div>';
}else{
    echo '<div class="alert alert-success">Password updated successfully.<a href="index.php">Login</a></div>';
}
?>