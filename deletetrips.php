<?php
session_start();
include("connection.php");

$trip_id = $_POST['trip_id'];

$sql = "DELETE FROM carsharetrips WHERE trip_id='$trip_id'";
$result = mysqli_query($link, $sql);

if(!$result){
    echo "<div class='alert alert-warning'>You have not deleted the trip, please try again later!</div>";
}

?>