<?php
session_start();
include("connection.php");

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM carsharetrips WHERE user_id='$user_id'";
$result = mysqli_query($link, $sql);

if($result){
    if(mysqli_num_rows($result)>0){
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            //Check Frequency
            if($row['regular'] == "N"){
                $frequency = "One-Off Journey.";
                $time = $row['date'] . " at " . $row['time'] . ".";
            }else{
                $frequency = "Regular Journey.";
                $weekdayArray = [];
                if($row['monday'] == 1){array_push($weekdayArray, "Mon");}
                if($row['tuesday'] == 1){array_push($weekdayArray, "Tue");}
                if($row['wednesday'] == 1){array_push($weekdayArray, "Wed");}
                if($row['thursday'] == 1){array_push($weekdayArray, "Thu");}
                if($row['friday'] == 1){array_push($weekdayArray, "Fri");}
                if($row['saturday'] == 1){array_push($weekdayArray, "Sat");}
                if($row['sunday'] == 1){array_push($weekdayArray, "Sun");}
                $time = implode("-", $weekdayArray) . " at " . $row['time'] . ".";
            }
            $departure = $row['departure'];
            $destination = $row['destination'];
            $price = $row['price'];
            $seats = $row['seatsavailable'];
            $trip_id = $row['trip_id'];
            echo "
            <div class='row tripContainer'>
                <div class='col-sm-8 journeyContainer'>
                    <div><span>Departure:</span> $departure.</div>
                    <div><span>Destination:</span> $destination.</div>
                    <div class='timeDetails'>$time</div>
                    <div>$frequency</div>
                </div>
                <div class='col-sm-2 priceContainer'>
                    <div class='priceDetails'>£$price</div>
                    <div class='perSeat'>Per Seat</div>
                    <div class='seatDetails'>$seats left!</div>
                </div>
                <div class='col-sm-2 editButtonContainer'>
                    <button type='button' id='edit' class='btn btn-lg btnColor' data-toggle='modal' data-target='#editTripModal' data-trip_id='$trip_id'>Edit</button>
                </div>
            </div>
            ";
        }
    }else{
        echo "<div class='alert alert-warning'>You have not created any trips yet!</div>";
    }
}

?>