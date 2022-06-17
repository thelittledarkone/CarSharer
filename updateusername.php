<?php
//<!--Start Session-->
session_start();
//<!--Connect to database-->
include('connection.php');

//Get user id
$id = $_SESSION['user_id'];

//Get username sent through ajax
$username = $_POST['username'];

//Run quesry and update username
$sql = "UPDATE users SET username='$username' WHERE user_id='$id'";
$result = mysqli_query($link, $sql);
if(!$result){
    echo '<div class="alert alert-danger">There was an error updating and storing the username in the database</div>';
}

?>