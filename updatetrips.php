<?php
session_start();
include("connection.php");

//Error Messages
$missingDeparture = '<p><strong>Please enter your departure location!</strong></p>';
$invalidDeparture = '<p><strong>Please enter a valid departure location!</strong></p>';
$missingDestination = '<p><strong>Please enter your destination location!</strong></p>';
$invalidDestination = '<p><strong>Please enter a valid destination location!</strong></p>';
$missingPrice = '<p><strong>Please choose a price per seat!</strong></p>';
$invalidPrice = '<p><strong>Please choose a valid price per seat, using numbers only!</strong></p>';
$missingSeats = '<p><strong>Please select number of available seats!</strong></p>';
$invalidSeats = '<p><strong>The number of available seats should contain digits only!</strong></p>';
$missingFrequency = '<p><strong>Please select a frequency!</strong></p>';
$missingDays = '<p><strong>Please select at least one day!</strong></p>';
$missingDate = '<p><strong>Please choose a date for your trip!</strong></p>';
$missingTime = '<p><strong>Please choose a time for your trip!</strong></p>';

//Get Inputs
$trip_id = $_POST["trip_id"];
$departure = $_POST["editDeparture"];
$destination = $_POST["editDestination"];
$price = $_POST["editPrice"];
$seats = $_POST["editSeats"];
$regular = $_POST["editRegular"];
$date = $_POST["editDate"];
$time = $_POST["editTime"];
$monday = $_POST["editMonday"];
$tuesday = $_POST["editTuesday"];
$wednesday = $_POST["editWednesday"];
$thursday = $_POST["editThursday"];
$friday = $_POST["editFriday"];
$saturday = $_POST["editSaturday"];
$sunday = $_POST["editSunday"];

//Check Departure
if(empty($departure)){
    $errors .= $missingDeparture;
}else{
    //Check Coordinates
    if(!isset($_POST["departureLatitude"]) or !isset($_POST["departureLongitude"])){
        $errors .= $invalidDeparture;
    }else{
        $departureLat = $_POST["departureLatitude"];
        $departureLon = $_POST["departureLongitude"];
        $departure = filter_var($departure, FILTER_SANITIZE_STRING);
    }
}

//Check Destination
if(empty($destination)){
    $errors .= $missingDestination;
}else{
    //Check Coordinates
    if(!isset($_POST["destinationLatitude"]) or !isset($_POST["destinationLongitude"])){
        $errors .= $invalidDestination;
    }else{
        $destinationLat = $_POST["destinationLatitude"];
        $destinationLon = $_POST["destinationLongitude"];
        $destination = filter_var($destination, FILTER_SANITIZE_STRING);
    }
}

//Check Price
if(empty($price)){
    $errors .= $missingPrice;
}elseif(preg_match('/\D/', $price)){
    $errors .= $invalidPrice;
}else{
    $price = filter_var($price, FILTER_SANITIZE_STRING);
}

//Check Seats
if(empty($seats)){
    $errors .= $missingSeats;
}elseif(preg_match('/\D/', $seats)){
    $errors .= $invalidSeats;
}else{
    $seats = filter_var($seats, FILTER_SANITIZE_STRING);
}

//Check Frequency
if(empty($regular)){
    $errors .= $missingFrequency;
}elseif($regular == "Y"){
    if(empty($monday) && empty($tuesday) && empty($wednesday) && empty($thursday) && empty($friday) && empty($saturday) && empty($sunday)){
        $errors .= $missingDays;
    }
    if(empty($time)){
        $errors .= $missingTime;
    }
}else{
    if(empty($date)){
        $errors .= $missingDate;
    }
    if(empty($time)){
        $errors .= $missingTime;
    }
}

//Check for Errors
if($errors){
    $resultMessage = "<div class='alert alert-danger'>$errors</div>";
    echo $resultMessage;
}else{
    //No errors, prepare variables for query
    $departure = mysqli_real_escape_string($link, $departure);
    $destination = mysqli_real_escape_string($link, $destination);
    $tblName = 'carsharetrips';
    $user_id = $_SESSION['user_id'];
    if($regular == "Y"){
        //Query for Regular trip
        $sql = "UPDATE $tblName SET `departure`='$departure',`departureLon`='$departureLon',`departureLat`='$departureLat',`destination`='$destination',`destinationLon`='$destinationLon',`destinationLat`='$destinationLat',`price`='$price',`seatsavailable`='$seats',`regular`='$regular',`time`='$time',`monday`='$monday',`tuesday`='$tuesday',`wednesday`='$wednesday',`thursday`='$thursday',`friday`='$friday',`saturday`='$saturday',`sunday`='$sunday' WHERE `trip_id`='$trip_id' LIMIT 1";
    }else{
        //Query for One-off trip
        $sql = "UPDATE $tblName SET `departure`='$departure',`departureLon`='$departureLon',`departureLat`='$departureLat',`destination`='$destination',`destinationLon`='$destinationLon',`destinationLat`='$destinationLat',`price`='$price',`seatsavailable`='$seats',`regular`='$regular',`date`='$date',`time`='$time' WHERE `trip_id`='$trip_id' LIMIT 1";
    }
    
    $result = mysqli_query($link, $sql);
    //Check Query success
    if(!$result){
        echo "<div class='alert alert-danger'>There was an error! The trip could not be updated!</div>";
    }
}



       
       
       
       
       
       
       





?>