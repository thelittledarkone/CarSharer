<?php
//<!--Start Session-->
session_start();
//<!--Connect to database-->
include('connection.php');

//<!--    Define error messages-->
$missingCurrentPassword = '<p><strong>Please enter Current Password!</strong></p>';
$incorrectCurrentPassword = '<p><strong>Please enter Correct Current Password!</strong></p>';
$missingPassword = '<p><strong>Please enter a Password!</strong></p>';
$invalidPassword = '<p><strong>Your password should be at least 6 characters long and include at least one capital letter and one number!</strong></p>';
$differentPassword = '<p><strong>Passwords don\'t match!</strong></p>';
$missingPassword2 = '<p><strong>Please confirm your Password!</strong></p>';

//Passwords
if(empty($_POST["passwordold"])){
    $errors .= $missingCurrentPassword;
}else{
    $currentpassword = filter_var($_POST["passwordold"], FILTER_SANITIZE_STRING);
    $currentpassword = mysqli_real_escape_string($link, $currentpassword);
    $currentpassword = hash('sha256', $currentpassword);
    //Check if password is correct
    $id = $_SESSION['user_id'];
    $sql = "SELECT password FROM users WHERE user_id='$id'";
    $result = mysqli_query($link, $sql);    
    $count = mysqli_num_rows($result);
    if($count !== 1){
        echo '<div class="alert alert-danger">There was an error running the query</div>';
    }else{
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        if($currentpassword != $row['password']){
            $errors .= $incorrectCurrentPassword;
        }
    }
}
if(empty($_POST["passwordnew"])){
    $errors .= $missingPassword;
}elseif(!(strlen($_POST["passwordnew"])>6 
        and preg_match('/[A-Z]/', $_POST["passwordnew"])
        and preg_match('/[A-Z]/', $_POST["passwordnew"])
          )
       ){
    $errors .= $invalidPassword;
}else{
    $password = filter_var($_POST["passwordnew"], FILTER_SANITIZE_STRING);
    if(empty($_POST["passwordconfirm"])){
        $errors .= $missingPassword2;
    }else{
        $password2 = filter_var($_POST["passwordconfirm"], FILTER_SANITIZE_STRING);
        if($password !== $password2){
            $errors .= $differentPassword;
        }
    }
}

//<!--    If there are any error, print error message-->
if($errors){
    $resultMessage = '<div class="alert alert-danger">' . $errors . '</div>';
    echo $resultMessage;
}else{
    $password = mysqli_real_escape_string($link, $password);
    $password = hash('sha256', $password);
    //run query
    $sql = "UPDATE users SET password='$password' WHERE user_id='$id'";
    $result = mysqli_query($link, $sql);
    if(!$result){
        echo '<div class="alert alert-danger">There was an error resetting the password in the database</div>';
    }else{
        echo '<div class="alert alert-success">Password successfully updated.</div>';
    }
}

?>