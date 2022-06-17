<?php
//<!--Start Session-->
session_start();
//<!--Connect to database-->
include('connection.php');

//Get user id
$id = $_SESSION['user_id'];

//Get email sent through ajax
$newemail = $_POST['email'];

//Check if new email exists
$sql = "SELECT * FROM users WHERE email = '$newemail'";
$result = mysqli_query($link, $sql);
if(!$result){
    echo '<div class="alert alert-danger">Error running the query</div>';
    exit;
}
$count = mysqli_num_rows($result);
if($count){
    echo '<div class="alert alert-danger">That email is already registered.</div>';
    exit;
}

//Get the current email of user
$sql = "SELECT * FROM users WHERE user_id='$user_id'";
$result = mysqli_query($link, $sql);
$count = mysqli_num_rows($result);
if($count == 1){
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $email = $row['email'];
}else{
    echo '<div class="alert alert-danger">There was an error retrieving the email from the database</div>';
}

//Create a unique activation code
$activationKey = bin2hex(openssl_random_pseudo_bytes(16));

//Insert activation code into users table
$sql = "UPDATE users SET activation2='$activationKey' WHERE user_id='$id'";
$result = mysqli_query($link, $sql);
if(!$result){
    echo '<div class="alert alert-danger">Error inserting new activation into database</div>';
}else{
    $message = "Please click on this link to update your email address:\n\n";
    $message .= "https://thelittledarkone.com/CarSharer/activatenewemail.php?email=" . urlencode($email) . "&newemail=" . urlencode($newemail) . "&key=$activationKey";
    if(mail($newemail, 'Email Update for your Online Notes App', $message, 'From:'.'cs@seraphimvirtualservices.com')){
           echo "<div class='alert alert-success'>A confirmation email has been sent to $newemail. Please click on the link to activate your new email address on your account.<br />If the email doesn't show in your inbox, don't forget to check your spam/junk folder.</div>";
}
}

//Send email with link to activatenewemail.php containing: current email, new email and activation code

//Run query and update username
$sql = "UPDATE users SET email='$email' WHERE user_id='$id'";
$result = mysqli_query($link, $sql);
if(!$result){
    echo '<div class="alert alert-danger">There was an error updating and storing the email in the database</div>';
}

?>