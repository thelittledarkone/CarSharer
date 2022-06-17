<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("location: index.php");
}
//<!--Connect to database-->
include('connection.php');

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>My Trips</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
        <meta name="description" content="#">
        <link rel="icon" href="#">
        <!--        Google Maps API-->
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAphl-IELg3mtynk1CFNOH42b3USA1z4VU&libraries=places"></script>
        <!-- Bootstrap & Fonts CSS -->
        <link rel="stylesheet" href="boot/bootstrap.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Akaya+Telivigala&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
<!--        JQuery & Bootstrap JS Scripts-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="boot/bootstrap.min.js"></script>
<!--        JQuery UI-->
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/eggplant/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<!--        Custom Styling-->
        <link rel="stylesheet" href="css/style.css">
        <style>
        #googleMap{
            width: 300px;
            height: 200px;
            margin: 30px auto;
        }
 

        </style>
    </head>
    <body>
<!--        Navigation-->
        <?php
        include("navbarconnected.php");
        
        ?>
    
        
<!--        Main Content-->
        <div class="container" id="myTripContainer">
<!--            Collapsable div for error messages-->
            <div id="alert" class="alert alert-danger collapse">
                <a class="close" data-dismiss="alert">&times;</a>
                <p id="alertContent"></p>
            </div>
            <div class="row">
                <div class="col-sm-offset-2 col-sm-8">
                    <div class="buttons">
                        <button type="button" id="addTrip" class="btn btn-lg btnDone" data-toggle="modal" data-target="#addTripModal">Add Trip</button>
                    </div>
                    <div id="myTrips" class="trips">
<!--                    Use Ajax to call a php file that retrieves the notes from                       database-->
                    </div>
                </div>
            </div>
        </div>
<!--        Add Trips Modal-->
        <form method="post" id="addTripForm">
            <div class="modal" id="addTripModal" role="dialog" aria-labelledby="addTripModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button class="close" data-dismiss="modal">&times;</button>
                            <h4 id="addTripModalLabel">New Trip:</h4>
                        </div>
                        <div class="modal-body">
<!--                            Add Trip message for php file-->
                            <div id="addTripMessage"></div>
                            
<!--                            Map-->
                            <div id="googleMap" class="map"></div>
<!--                            Create Trip form-->
                            <div class="form-group">
                                <label for="departure" class="sr-only">Departure</label>
                                <input class="form-control" type="text" id="departure" name="departure" placeholder="Departure">  
                            </div>    
                            <div class="form-group">
                                <label for="destination" class="sr-only">Destination</label>
                                <input class="form-control" type="text" id="destination" name="destination" placeholder="Destination">  
                            </div>    
                            <div class="form-group">
                                <label for="price" class="sr-only">Price</label>
                                <input class="form-control" type="number" id="price" name="price" placeholder="Price">  
                            </div>  
                            <div class="form-group">
                                <label for="seats" class="sr-only">Price</label>
                                <input class="form-control" type="number" id="seats" name="seats" placeholder="Seats Available">  
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="radio" name="regular" id="yes" value="Y">
                                    Regular                                
                                </label>
                                <label>
                                    <input type="radio" name="regular" id="no" value="N">
                                    One-off                                
                                </label>
                            </div>
                            <div class="checkbox checkbox-inline regular">
                                <label>
                                    <input type="checkbox" name="monday" id="monday" value="1">
                                    Monday                               
                                </label>
                                <label>
                                    <input type="checkbox" name="tuesday" id="tuesday" value="1">
                                    Tuesday                               
                                </label>
                                <label>
                                    <input type="checkbox" name="wednesday" id="wednesday" value="1">
                                    Wednesday                              
                                </label>
                                <label>
                                    <input type="checkbox" name="thursday" id="thursday" value="1">
                                    Thursday                               
                                </label>
                                     <label>
                                    <input type="checkbox" name="friday" id="friday" value="1">
                                    Friday                               
                                </label>
                                <label>
                                    <input type="checkbox" name="saturday" id="saturday" value="1">
                                    Saturday                              
                                </label>
                                <label>
                                    <input type="checkbox" name="sunday" id="sunday" value="1">
                                    Sunday                               
                                </label>
                            </div>
                            <div class="form-group oneOff">
                                <label for="date" class="sr-only">Date</label>
                                <input class="form-control" readonly="readonly" id="date" name="date">  
                            </div>
                            <div class="form-group regular oneOff">
                                <label for="time" class="sr-only">Time</label>
                                <input class="form-control" type="time" id="time" name="time">  
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input class="btn btnColor" name="createTrip" type="submit" value="Create Trip">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>        
        </form>
        <!--        Edit Trips Modal-->
        <form method="post" id="editTripsForm">
            <div class="modal" id="editTripModal" role="dialog" aria-labelledby="editTripModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button class="close" data-dismiss="modal">&times;</button>
                            <h4 id="editTripModalLabel">Edit Trip:</h4>
                        </div>
                        <div class="modal-body">
<!--                            Edit Trip message for php file-->
                            <div id="editTripMessage"></div>
                            
<!--                            Edit Trip form-->
                            <div class="form-group">
                                <label for="editDeparture" class="sr-only">Departure</label>
                                <input class="form-control" type="text" id="editDeparture" name="editDeparture" placeholder="Departure">  
                            </div>    
                            <div class="form-group">
                                <label for="editDestination" class="sr-only">Destination</label>
                                <input class="form-control" type="text" id="editDestination" name="editDestination" placeholder="Destination">  
                            </div>    
                            <div class="form-group">
                                <label for="editPrice" class="sr-only">Price</label>
                                <input class="form-control" type="number" id="editPrice" name="editPrice" placeholder="Price">  
                            </div>  
                            <div class="form-group">
                                <label for="editSeats" class="sr-only">Price</label>
                                <input class="form-control" type="number" id="editSeats" name="editSeats" placeholder="Seats Available">  
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="radio" name="editRegular" id="editYes" value="Y">
                                    Regular                                
                                </label>
                                <label>
                                    <input type="radio" name="editRegular" id="editNo" value="N">
                                    One-off                                
                                </label>
                            </div>
                            <div class="checkbox checkbox-inline regularEdit">
                                <label>
                                    <input type="checkbox" name="editMonday" id="editMonday" value="1">
                                    Monday                               
                                </label>
                                <label>
                                    <input type="checkbox" name="editTuesday" id="editTuesday" value="2">
                                    Tuesday                               
                                </label>
                                <label>
                                    <input type="checkbox" name="editWednesday" id="editWednesday" value="3">
                                    Wednesday                              
                                </label>
                                <label>
                                    <input type="checkbox" name="editThursday" id="editThursday" value="4">
                                    Thursday                               
                                </label>
                                     <label>
                                    <input type="checkbox" name="editFriday" id="editFriday" value="5">
                                    Friday                               
                                </label>
                                <label>
                                    <input type="checkbox" name="editSaturday" id="editSaturday" value="6">
                                    Saturday                              
                                </label>
                                <label>
                                    <input type="checkbox" name="editSunday" id="editSunday" value="7">
                                    Sunday                               
                                </label>
                            </div>
                            <div class="form-group oneOffEdit">
                                <label for="editDate" class="sr-only">Date</label>
                                <input class="form-control" readonly="readonly" id="editDate" name="editDate">  
                            </div>
                            <div class="form-group regularEdit oneOffEdit">
                                <label for="editTime" class="sr-only">Time</label>
                                <input class="form-control" type="time" id="editTime" name="editTime">  
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input class="btn btnColor" name="editTrip" type="submit" value="Edit Trip">
                            <input class="btn btn-danger" name="deleteTrip" id="deleteTrip" value="Delete" type="button">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>        
        </form>
<!--        Footer-->
        <div class="footer">
            <div class="container-fluid">
                <p id="brand">Seraphim Virtual Services,<br /> Copyright &copy; 2020 - <?php $today = date("Y"); echo $today?></p>
                <p id="credits"><a href="credits.html">Credits</a></p>
            </div>
        
        </div>
        
<!--        Spinner-->
        <div id="spinner">
            <img src="css/images/ajax-loader.gif" width="64" height="64"/>
            <br /> Loading ...
        </div>  
        
        
        
        <script src="mytrips.js"></script>
        <script src="script.js"></script>
        <script>
            $(function(){
                $("#trips").addClass('active');
            });
        </script>
    </body>
</html>