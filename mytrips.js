//Variables
var data; var departureLongitude; var departureLatitude; var destinationLongitude; var destinationLatitude; var trip;

//Get Trips on page load
getTrips();

//Create Geocoder
var geocoder = new google.maps.Geocoder();

//Hide Date, Time and Checkbox from modal
$('.regular').hide(); $('.oneOff').hide();
$('.regularEdit').hide(); $('.oneOffEdit').hide();

//Select radio and show relevent elements
var myRadio = $('input[name="regular"]');

myRadio.click(function(){
    if($(this).is(':checked')){
       if($(this).val() == "Y"){
           $('.oneOff').hide(); $('.regular').show();
       }else{
           $('.regular').hide(); $('.oneOff').show();
       }
    }
});

//Select radio and show relevent elements for Edit Modal
var myRadio = $('input[name="editRegular"]');

myRadio.click(function(){
    if($(this).is(':checked')){
       if($(this).val() == "Y"){
           $('.oneOffEdit').hide(); $('.regularEdit').show();
       }else{
           $('.regularEdit').hide(); $('.oneOffEdit').show();
       }
    }
});

//Calendar
$('input[name="date"], input[name="editDate"]').datepicker({
    numberOfMonths: 1,
    showAnim: "fadeIn",
    dateFormat: "D d M, yy",
//    showWeek: true, For adding the week number to the calendar
    minDate: +1,
    maxDate: "+12M"
});

//Ajax
//Click on Create Trip Button
$("#addTripForm").submit(function(event){
//    Show Spinner
    $("#spinner").show();
//    Hide Results
    $("#addTripMessage").hide();
    event.preventDefault();
    data = $(this).serializeArray();
    getAddTripDepartureCoordinates();
});


//Functions
function getAddTripDepartureCoordinates(){
    geocoder.geocode(
        {
        'address': document.getElementById("departure").value
        },
        function(results, status){
            if(status == google.maps.GeocoderStatus.OK){
                departureLongitude = results[0].geometry.location.lng();
                departureLatitude = results[0].geometry.location.lat();
                data.push({name:'departureLongitude', value:departureLongitude});
                data.push({name:'departureLatitude', value:departureLatitude});
                getAddTripDestinationCoordinates();
            }else{
                getAddTripDestinationCoordinates();
            }
        }
    );
}

function getAddTripDestinationCoordinates(){
    geocoder.geocode(
        {
        'address': document.getElementById("destination").value
        },
        function(results, status){
            if(status == google.maps.GeocoderStatus.OK){
                destinationLongitude = results[0].geometry.location.lng();
                destinationLatitude = results[0].geometry.location.lat();
                data.push({name:'destinationLongitude', value:destinationLongitude});
                data.push({name:'destinationLatitude', value:destinationLatitude});
                submitAddTripRequest();
            }else{
                submitAddTripRequest();
            }
        }
    );
}

function submitAddTripRequest(){
    $.ajax({
        url: "addtrips.php",
        type: "POST",
        data: data,
//        Ajax call successful: show error or success message
        success: function(returnedData){
            $("#spinner").hide();
            if(returnedData){
                $("#addTripMessage").html(returnedData);
                $("#addTripMessage").slideDown();
            }else{
                //Hide Modal
                $("#addTripModal").modal('hide');
                //Reset Form
                $("#addTripForm")[0].reset();
                //Hide Regular and One off elements
                $('.regular').hide(); $('.oneOff').hide();
                //Load Trips
                getTrips();
            }
        },
//        Ajax Call fails: show ajax call error
        error: function(){
            $("#spinner").hide();
            $("#addTripMessage").html("<div class='alert alert-danger'>There was an error with the addTrips Ajax call. Please try again</div>");
            $("#addTripMessage").slideDown();
        }
    });
}

function getTrips(){
//    Show Spinner
    $("#spinner").show();
//    Hide Results
    $("#myTrips").fadeOut();
    $.ajax({
        url: "gettrips.php",
//        Ajax call successful: show error or success message
        success: function(returnedData){
            $("#spinner").hide();
            $("#myTrips").html(returnedData);
            $("#myTrips").fadeIn();
        },
//        Ajax Call fails: show ajax call error
        error: function(){
            $("#spinner").hide();
            $("#myTrips").html("<div class='alert alert-danger'>There was an error with the getTrips Ajax call. Please try again</div>");
            $("#myTrips").fadeIn();
        }
    });
}

function formatModal(){
    $('#editDeparture').val(trip['departure']);
    $('#editDestination').val(trip['destination']);
    $('#editPrice').val(trip['price']);
    $('#editSeats').val(trip['seatsavailable']);
    
    if(trip['regular'] == "Y"){
        $('#editYes').prop('checked', true);
        $('#editMonday').prop('checked', trip['monday'] == "1"? true:false);
        $('#editTuesday').prop('checked', trip['tuesday'] == "1"? true:false);
        $('#editWednesday').prop('checked', trip['wednesday'] == "1"? true:false);
        $('#editThursday').prop('checked', trip['thursday'] == "1"? true:false);
        $('#editFriday').prop('checked', trip['friday'] == "1"? true:false);
        $('#editSaturday').prop('checked', trip['saturday'] == "1"? true:false);
        $('#editSunday').prop('checked', trip['sunday'] == "1"? true:false);
        $('input[name="editTime"]').val(trip['time']);
        $('.oneOffEdit').hide(); $('.regularEdit').show();
    }else{
        $('#editNo').prop('checked', true);
        $('input[name="editDate"]').val(trip['date']);
        $('input[name="editTime"]').val(trip['time']);
        $('.regularEdit').hide(); $('.oneOffEdit').show();
    }
}

function getEditTripDepartureCoordinates(){
    geocoder.geocode(
        {
        'address': document.getElementById("editDeparture").value
        },
        function(results, status){
            if(status == google.maps.GeocoderStatus.OK){
                departureLongitude = results[0].geometry.location.lng();
                departureLatitude = results[0].geometry.location.lat();
                data.push({name:'departureLongitude', value:departureLongitude});
                data.push({name:'departureLatitude', value:departureLatitude});
                getEditTripDestinationCoordinates();
            }else{
                getEditTripDestinationCoordinates();
            }
        }
    );
}

function getEditTripDestinationCoordinates(){
    geocoder.geocode(
        {
        'address': document.getElementById("editDestination").value
        },
        function(results, status){
            if(status == google.maps.GeocoderStatus.OK){
                destinationLongitude = results[0].geometry.location.lng();
                destinationLatitude = results[0].geometry.location.lat();
                data.push({name:'destinationLongitude', value:destinationLongitude});
                data.push({name:'destinationLatitude', value:destinationLatitude});
                submitEditTripRequest();
            }else{
                submitEditTripRequest();
            }
        }
    );
}

function submitEditTripRequest(){
    $.ajax({
        url: "updatetrips.php",
        type: "POST",
        data: data,
//        Ajax call successful: show error or success message
        success: function(returnedData){
            $("#spinner").hide();
            if(returnedData){
                $("#editTripMessage").html(returnedData);
                $("#editTripMessage").slideDown();
            }else{
                //Hide Modal
                $("#editTripModal").modal('hide');
                //Reset Form
                $("#editTripsForm")[0].reset();
                //Load Trips
                getTrips();
            }
        },
//        Ajax Call fails: show ajax call error
        error: function(){
            $("#spinner").hide();
            $("#editTripMessage").html("<div class='alert alert-danger'>There was an error with the editTrips Ajax call. Please try again</div>");
            $("#editTripMessage").slideDown();
        }
    });
}

//Click on Edit Button
$("#editTripModal").on('show.bs.modal', function(event){
    $("#editTripMessage").empty();
    var invoker = $(event.relatedTarget);

    //Ajax Call to get Trip details
    $.ajax({
        url: "tripdetails.php",
        method: "POST",
        data: {trip_id:invoker.data('trip_id')},
//        Ajax call successful: show error or success message
        success: function(returnedData){
            $("#spinner").hide();
            if(returnedData){
                if(returnedData == "error"){
                    $("#editTripMessage").html("<div class='alert alert-danger'>There was an error with the editTrips Ajax call. Please try again</div>");
                    $("#editTripMessage").slideDown();
                }else{
                    trip = JSON.parse(returnedData);
                    //Fill the edit trip form with the JSON data
                    formatModal();
                }
            }
        },
//        Ajax Call fails: show ajax call error
        error: function(){
            $("#spinner").hide();
            $("#editTripMessage").html("<div class='alert alert-danger'>There was an error with the editTrips Ajax call. Please try again</div>");
            $("#editTripMessage").slideDown();
        }
    });
    
    $('#editTripsForm').submit(function(event){
    //    Show Spinner
        $("#spinner").show();
    //    Hide Results
        $("#editTripMessage").hide();
//        $("#editTripMessage").empty();
        event.preventDefault();
        data = $(this).serializeArray();
        data.push({name:'trip_id', value:invoker.data('trip_id')});
        getEditTripDepartureCoordinates();
    });
    
//    Delete Trip
    $('#deleteTrip').click(function(){
        $.ajax({
            url: "deletetrips.php",
            method: "POST",
            data: {trip_id:invoker.data('trip_id')},
//        Ajax call successful: show error or success message
            success: function(returnedData){
                $("#spinner").hide();
                if(returnedData){
                    $("#editTripMessage").html("<div class='alert alert-danger'>There was an error with the deleteTrips Ajax call. Please try again</div>");
                    $("#editTripMessage").slideDown();
                }else{
                    $("#editTripModal").modal('hide');
                    getTrips();
                }
            },
//        Ajax Call fails: show ajax call error
            error: function(){
                $("#spinner").hide();
                $("#editTripMessage").html("<div class='alert alert-danger'>There was an error with the editTrips Ajax call. Please try again</div>");
                $("#editTripMessage").slideDown();
            }
        });
    });
});





