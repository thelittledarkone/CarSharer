<?php
ob_start();
//<!--The user is re-directed to this file after clicking the recover account link-->
//<!--Recover link contains 2 get perameters: user_id and key-->

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
        <title>Password Reset</title>
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
                    <h1 style='color:aliceblue;'>Reset Password:</h1>
                    <div id="resultMessage" style='color:aliceblue;'></div>
<?php

//<!--If user_id or recover key is missing, show error-->
if(!isset($_GET['user_id']) || !isset($_GET['key'])){
    echo '<div class="alert alert-danger">Error: Please click the link in email</div>';
    exit;
}
//<!--    Store them in 2 variables-->
$user_id = $_GET['user_id'];
$key = $_GET['key'];
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
    echo '<div class="alert alert-danger">Recovery link may be old, Please try again.</div>';
    exit;
}
                    
//Print reset password form with hidden user_id and key fields                    
echo "
    <form method=post id='passwordreset' style='color:aliceblue;'>
        <input type='hidden' name='key' value='$key'>
        <input type='hidden' name='user_id' value='$user_id'>
        <div class='form-group'>
            <label for='password'>Enter your new Password:</label>
            <input type='password' name='password' id='password' placeholder='Enter Password' class='form-control'>
        </div>
        <div class='form-group'>
            <label for='password2'>Re-enter Password::</label>
            <input type='password' name='password2' id='password2' placeholder='Re-enter Password' class='form-control'>
        </div>
        <input type='submit' name='resetpassword' class='btn btn-success btn-lg' value='Reset Password'>
    </form>
";                    
    
?>            
                </div>
            </div>
        </div>
<!--        Footer-->
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="boot/js/bootstrap.min.js"></script>
        <script>
            //Once the form is submitted
            $("#passwordreset").submit(function(event){ 
                //prevent default php processing
                event.preventDefault();
                //collect user inputs
                var datatopost = $(this).serializeArray();
            //    console.log(datatopost);
                //send them to signup.php using AJAX
                $.ajax({
                    url: "storeresetpassword.php",
                    type: "POST",
                    data: datatopost,
                    success: function(data){

                        $('#resultMessage').html(data);
                    },
                    error: function(){
                        $("#resultMessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");

                    }

                });

            });           
        </script>
                
    </body>
</html>
<?php ob_flush(); ?>