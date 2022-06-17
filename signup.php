<?php
//<!--Start Session-->
session_start();
//<!--Connect to database-->
include('connection.php');

//<!--Check user inputs-->

//<!--    Define error messages-->
$missingUsername = '<p><strong>Please enter a Username!</strong></p>';
$missingEmail = '<p><strong>Please enter your Email Address!</strong></p>';
$invalidEmail = '<p><strong>Please enter a valid Email Address!</strong></p>';
$missingPassword = '<p><strong>Please enter a Password!</strong></p>';
$invalidPassword = '<p><strong>Your password should be at least 6 characters long and include at least one capital letter and one number!</strong></p>';
$differentPassword = '<p><strong>Passwords don\'t match!</strong></p>';
$missingPassword2 = '<p><strong>Please confirm your Password!</strong></p>';
$missingFirstname = '<p><strong>Please enter your first name!</strong></p>';
$missingLastname = '<p><strong>Please enter your last name!</strong></p>';
$missingPhone = '<p><strong>Please enter your phone number!</strong></p>';
$invalidPhone = '<p><strong>Please enter a valid phone number!</strong></p>';

//<!--    Get username, email, password, password2-->
//<!--    Store errors in error variable-->
//Username
if(empty($_POST["username"])){
    $errors .= $missingUsername;
}else{
    $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
}
//Firstname
if(empty($_POST["firstname"])){
    $errors .= $missingFirstname;
}else{
    $firstname = filter_var($_POST["firstname"], FILTER_SANITIZE_STRING);
}
//Lastname
if(empty($_POST["lastname"])){
    $errors .= $missingLastname;
}else{
    $lastname = filter_var($_POST["lastname"], FILTER_SANITIZE_STRING);
}
//Email
if(empty($_POST["email"])){
    $errors .= $missingEmail;
}else{
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors .= $invalidEmail;
    }
}
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
//Phone Number
if(empty($_POST["phone"])){
    $errors .= $missingPhone;
}elseif(preg_match('/\D/', $_POST["phone"])){
    $errors .= $invalidPhone;
}else{
    $phone = filter_var($_POST["phone"], FILTER_SANITIZE_STRING);
}
//Gender
if(!empty($_POST["gender"])){
    $gender = $_POST["gender"];
}else{
    $gender = NULL;
}
//More Information
if(!empty($_POST["moreinfo"])){
    $moreinfo = filter_var($_POST["moreinfo"], FILTER_SANITIZE_STRING);
}else{
    $moreinfo = NULL;
}

//<!--    If there are any error, print error message-->
if($errors){
    $resultMessage = '<div class="alert alert-danger">' . $errors . '</div>';
    echo $resultMessage;
    exit;
}

//<!--No errors-->
//<!--    Prepare variables for the queries-->
$username = mysqli_real_escape_string($link, $username);
$firstname = mysqli_real_escape_string($link, $firstname);
$lastname = mysqli_real_escape_string($link, $lastname);
$email = mysqli_real_escape_string($link, $email);
$phone = mysqli_real_escape_string($link, $phone);
$gender = mysqli_real_escape_string($link, $gender);
$moreinfo = mysqli_real_escape_string($link, $moreinfo);

$password = mysqli_real_escape_string($link, $password);
//$password = md5($password);
//128 bits -> 32 characters
$password = hash('sha256', $password);
//256 bits -> 64 characters


//<!--    If username exists in the user table, print error-->
$sql = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($link, $sql);
if(!$result){
    echo '<div class="alert alert-danger">Error running the query</div>';
    echo '<div class="alert alert-danger">' . mysqli_error($link) . '</div>';
    exit;
}
$results = mysqli_num_rows($result);
if($results){
    echo '<div class="alert alert-danger">That username is already registered</div>';
    exit;
}
//<!--    else-->
//<!--        If email exists in the users table, print error-->
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($link, $sql);
if(!$result){
    echo '<div class="alert alert-danger">Error running the query</div>';
    exit;
}
$results = mysqli_num_rows($result);
if($results){
    echo '<div class="alert alert-danger">That email is already registered. Do you want to log in?</div>';
    exit;
}
//<!--        else-->
//<!--            Create a unique activation code-->
$activationKey = bin2hex(openssl_random_pseudo_bytes(16));
    //byte: unit of data = 8 bits
    //bit: 0 or 1
    //16 bytes = 16*8 = 128 bits
    //(2*2*2*2)*2*2*2*2*...*2
    //16*16*...*16
    //32 characters

//<!--            Insert user details and activation code in the users table-->
$sql = "INSERT INTO users (`first_name`, `last_name`, `username`, `email`, `password`, `activation`, `gender`, `phonenumber`, `moreinformation`) VALUES ('$firstname', '$lastname', '$username', '$email', '$password', '$activationKey', '$gender', '$phone', '$moreinfo')";
$result = mysqli_query($link, $sql);
if(!$result){
    echo '<div class="alert alert-danger">Error inserting user details into database</div>';
    exit;
}

//<!--            Send the user an email with a link to activate.php with their email                   and activation code-->
$message = "Please click on this link to activate your account:\n\n";
$message .= "https://thelittledarkone.com/CarSharer/activate.php?email=" . urlencode($email) . "&key=$activationKey";
if(mail($email, 'Confirm your Registration', $message, 'From:'.'cs@seraphimvirtualservices.com')){
       echo "<div class='alert alert-success'>Thank for your registering! A confirmation email has been sent to $email. Please click on the activation link to activate your account.<br />If the email doesn't show in your inbox, don't forget to check your spam/junk folder.</div>";
}

?>