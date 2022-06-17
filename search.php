<?php
session_start();
include("connection.php");

//Error Messages
$missingDeparture = '<p><strong>Please enter your departure location!</strong></p>';
$invalidDeparture = '<p><strong>Please enter a valid departure location!</strong></p>';
$missingDestination = '<p><strong>Please enter your destination location!</strong></p>';
$invalidDestination = '<p><strong>Please enter a valid destination location!</strong></p>';

//Get Inputs
$departure = $_POST["departure"];
$destination = $_POST["destination"];

//Check Coordinates
if(!isset($_POST["departureLatitude"]) or !isset($_POST["departureLongitude"])){
    $errors .= $invalidDeparture;
}else{
    $departureLat = $_POST["departureLatitude"];
    $departureLon = $_POST["departureLongitude"];
}

if(!isset($_POST["destinationLatitude"]) or !isset($_POST["destinationLongitude"])){
    $errors .= $invalidDestination;
}else{
    $destinationLat = $_POST["destinationLatitude"];
    $destinationLon = $_POST["destinationLongitude"];
}

//Set Search Radius
$searchRadius = 10;

//Min & Max for Departure Longitude
$deltaLonDeparture = $searchRadius*360/(24901*cos(deg2rad($departureLon)));
$minLonDeparture = $departureLon - $deltaLonDeparture;
if($minLonDeparture < -180){
    $minLonDeparture += 360;
}
$maxLonDeparture = $departureLon + $deltaLonDeparture;
if($maxLonDeparture > 180){
    $maxLonDeparture -= 360;
}

//Min & Max for Destination Longitude
$deltaLonDestination = $searchRadius*360/(24901*cos(deg2rad($destinationLon)));
$minLonDestination = $destinationLon - $deltaLonDestination;
if($minLonDestination < -180){
    $minLonDestination += 360;
}
$maxLonDestination = $destinationLon + $deltaLonDestination;
if($maxLonDestination > 180){
    $maxLonDestination -= 360;
}

//Min & Max for Departure Latitude
$deltaLatDeparture = $searchRadius*180/12430;
$minLatDeparture = $departureLat - $deltaLatDeparture;
if($minLatDeparture < -90){
    $minLatDeparture = -90;
}
$maxLatDeparture = $departureLat + $deltaLatDeparture;
if($maxLatDeparture > 90){
    $maxLatDeparture = 90;
}

//Min & Max for Destination Latitude
$deltaLatDestination = $searchRadius*180/12430;
$minLatDestination = $destinationLat - $deltaLatDestination;
if($minLatDestination < -90){
    $minLatDestination = -90;
}
$maxLatDestination = $destinationLat + $deltaLatDestination;
if($maxLatDestination > 90){
    $maxLatDestination = 90;
}

//Check Departure
if(empty($departure)){
    $errors .= $missingDeparture;
}else{
    $departure = filter_var($departure, FILTER_SANITIZE_STRING);
}

//Check Destination
if(empty($destination)){
    $errors .= $missingDestination;
}else{
    $destination = filter_var($destination, FILTER_SANITIZE_STRING);
}

//Check for Errors
if($errors){
    $resultMessage = '<div class="alert alert-danger">' . $errors . '</div>';
    echo $resultMessage;
    exit;
}

//get all available trips in the carsharetrips table
$myArray = [$minLonDeparture < $maxLonDeparture, $minLatDeparture < $maxLatDeparture, $minLonDestination < $maxLonDestination, $minLatDestination < $maxLatDestination];

$queryChoice1 = [
    " (departureLon BETWEEN $minLonDeparture AND $maxLonDeparture)",
    " AND (departureLat BETWEEN $minLatDeparture AND $maxLatDeparture)",
    " AND (destinationLon BETWEEN $minLonDestination AND $maxLonDestination)",
    " AND (destinationLat BETWEEN $minLatDestination AND $maxLatDestination)"
];

$queryChoice2 = [
    " ((departureLon > $minLonDeparture) OR (departureLon < $maxLonDeparture))",
    " AND (departureLat BETWEEN $minLatDeparture AND $maxLatDeparture)",
    " AND ((destinationLon > $minLonDestination) OR (destinationLon < $maxLonDestination))",
    " AND (destinationLat BETWEEN $minLatDestination AND $maxLatDestination)"
];

$queryChoices = [$queryChoice2, $queryChoice1];

//Query
$sql = "SELECT * FROM carsharetrips WHERE ";
for ($value=0; $value<4; $value++) {
    $index = $myArray[$value];
    $sql .= $queryChoices[$index][$value];
}

////Departure Longitude
//if($departureLonOutOfRange){
//    $sql .= "((departureLon > $minLonDeparture) OR (departureLon < $maxLonDeparture))";
//}else{
//    $sql .= "(departureLon BETWEEN $minLonDeparture AND $maxLonDeparture)";
//}
//
////Departure Latitude
//sql .= " AND (departureLat BETWEEN $minLatDeparture AND $maxLatDeparture)";
//
////Destination Longitude
//if($destinationLonOutOfRange){
//    $sql .= " AND ((destinationLon > $minLonDestination) OR (destinationLon < $maxLonDestination))";
//}else{
//    $sql .= " AND (destinationLon BETWEEN $minLonDestination AND $maxLonDestination)";
//}
//
////Destination Latitude
//sql .= " AND (destinationLat BETWEEN $minLatDestination AND $maxLatDestination)";

//Run Query
$result = mysqli_query($link, $sql);

if(!$result){
    echo "ERROR: Unable to execute: $sql. " . mysqli_error($link); exit;
}

if(mysqli_num_rows($result) == 0){
    echo "<div class='alert alert-info noResults'>There are no journeys matching your search!</div>"; exit;
}

echo "<div class='alert alert-info journeySummary'>From $departure to $destination.<br />Closest Journeys:</div>";

echo "<div id='tripResults'>";

//Cycle through trips
while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
    //Get Trips
    //Check Frequency
        if($row['regular'] == "N"){
            $frequency = "One-Off Journey.";
            $time = $row['date'] . " at " . $row['time'] . ".";
            
            //check if date is in past
            $source = $row['date'];
            $tripDate = DateTime::createFromFormat('D d M, yy', $source);
            $today = date('D d M, yy');
            $todayDate = DateTime::createFromFormat('D d M, yy', $today);
            if($tripDate < $todayDate){
                continue;
            }
            
        }else{
            $frequency = "Regular Journey.";
            $weekdayArray = [];
            $weekdays = ['monday'=>'Mon', 'tuesday'=>'Tue', 'wednesday'=>'Wed', 'thursday'=>'Thu', 'friday'=>'Fri', 'saturday'=>'Sat', 'sunday'=>'Sun'];
            foreach($weekdays as $key => $value){
                if($row[$key] == 1){array_push($weekdayArray, $value);}
            }
            $time = implode("-", $weekdayArray) . " at " . $row['time'] . ".";
        }
        $tripDeparture = $row['departure'];
        $tripDestination = $row['destination'];
        $price = $row['price'];
        $seats = $row['seatsavailable'];
    
    //Get user_id
    $person_id = $row['user_id'];
    
    //Run query to get user details
    $sql2 = "SELECT * FROM users WHERE user_id='$person_id' LIMIT 1";
    $result2 = mysqli_query($link, $sql2);
    if(!$result2){
        echo "ERROR: Unable to execute: $sql. " . mysqli_error($link); exit;
    }
    $row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
    $firstname = $row2['first_name'];
    $gender = $row2['gender'];
    $moreinfo = $row2['moreinformation'];
    $picture = $row2['profilepicture'];
    if($_SESSION['user_id']){
        $phone = $row2['phonenumber'];
    }else{
        $phone = "Please sign up! Only members have access to contact information.";
    }
    
    //Print Trip Card
    echo "
    <h4 class='row'>
        <div class='col-sm-2'>
            <div class='driver'>$firstname</div>
            <div><img class='driverPic' src='$picture'/></div>
        </div>
        <div class='col-sm-8 searchJourneyContainer'>
            <div><span>Departure:</span> $tripDeparture.</div>
            <div><span>Destination:</span> $tripDestination.</div>
            <div class='timeDetails'>$time</div>
            <div>$frequency</div>
        </div>
        <div class='col-sm-2 searchPriceContainer'>
            <div class='priceDetails'>£$price</div>
            <div class='searchPerSeat'>Per Seat</div>
            <div class='seatDetails'>$seats left!</div>
        </div>
    
    </h4>
    <div class='driverInfo'>
        <div>
            <div>Gender: $gender</div>
            <div>&#9742: $phone</div>
        </div>
        <div>
            <div class='aboutDriver'>About Me: $moreinfo</div>
        </div>
    </div>
    ";
}

echo "</div>";












?>