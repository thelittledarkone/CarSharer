<?php
//<!--Start Session-->
session_start();
//<!--Connect to database-->
include("connection.php");

//<!--Check user inputs-->
//<!--    Define error messages-->
$missingEmail = '<p><strong>Please enter your email address.</strong></p>';
$invalidEmail = '<p><strong>Please enter a valid Email Address!</strong></p>';

//Email
if(empty($_POST["passwordemail"])){
//<!--    Store errors in error variable-->
    $errors .= $missingEmail;
}else{
//<!--    Get email-->
    $email = filter_var($_POST["passwordemail"], FILTER_SANITIZE_EMAIL);
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
//<!--    Store errors in error variable-->
        $errors .= $invalidEmail;
    }
}

//<!--    If there are any error, print error message-->
if($errors){
    $resultMessage = '<div class="alert alert-danger">' . $errors . '</div>';
    echo $resultMessage;
    exit;
}

//<!--No errors-->
//<!--    Prepare variables for the query-->
$email = mysqli_real_escape_string($link, $email);

//<!--    Run query: Check if email exists in user table-->
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($link, $sql);
if(!$result){
    echo '<div class="alert alert-danger">Error running the query</div>';
    exit;
}
//<!--    If email doesn't exist in user table, print error-->
$count = mysqli_num_rows($result);
if(!$count){
    echo '<div class="alert alert-danger">That email does not exist on our database. Do you want to sign up?</div>';
    exit;
}

//<!--    else-->
//<!--        Get the user_id-->
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$user_id = $row['user_id'];

//<!--        Create a unique activation code-->
$key = bin2hex(openssl_random_pseudo_bytes(16));

//<!--        Insert user details and activation code in the forgotpassword table-->
$time = time();
$status = 'pending';
$sql = "INSERT INTO forgotpassword (`user_id`, `fp_key`, `time`, `status`) VALUES ('$user_id', '$key', '$time', '$status')";
$result = mysqli_query($link, $sql);
if(!$result){
    echo '<div class="alert alert-danger">Error inserting user details into the database</div>';
    exit;
}

//<!--        Send email with link to resetpassword.php with user_id and activation                   code-->
$message = "Please click on this link to recover your account:\n\n";
$message .= "https://thelittledarkone.com/CarSharer/resetpassword.php?user_id=$user_id&key=$key";
//<!--        If email sent successfully-->
if(mail($email, 'Reset your Password', $message, 'From:'.'cs@seraphimvirtualservices.com')){
//<!--            Print success-->
       echo "<div class='alert alert-success'>An email has been sent to $email. Please click on the link to reset your password.<br />If the email doesn't show in your inbox, don't forget to check your spam/junk folder.</div>";
}

?>