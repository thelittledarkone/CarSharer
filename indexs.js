//Ajax Call for sign up form
//Once form is submitted
$("#signupForm").submit(function(event){
//    Show Spinner
    $("#spinner").show();
//    Hide Results
    $("#signupMessage").hide();
//    prevent default php processing
    event.preventDefault();
//    collect user inputs
    var datatopost = $(this).serializeArray();
//    send them to signup.php using ajax
    $.ajax({
        url: "signup.php",
        type: "POST",
        data: datatopost,
//        Ajax call successful: show error or success message
        success: function(data){
            $("#spinner").hide();
            if(data){
                $("#signupMessage").html(data);
                $("#signupMessage").slideDown();
            }
        },
//        Ajax Call fails: show ajax call error
        error: function(){
            $("#spinner").hide();
            $("#signupMessage").html("<div class='alert alert-danger'>There was an error with the Ajax call. Please try again</div>");
            $("#signupMessage").slideDown();
        }
    });
});

//Ajax call for login form
//Once form is submitted
$("#loginForm").submit(function(event){
//    Show Spinner
    $("#spinner").show();
//    Hide Results
    $("#loginMessage").hide();
//    prevent default php processing
    event.preventDefault();
//    collect user inputs
    var datatopost = $(this).serializeArray();
//    send them to login.php using ajax
    $.ajax({
        url: "login.php",
        type: "POST",
        data: datatopost,
//        Ajax call successful
        success: function(data){
            $("#spinner").hide();
//            if php files returns a success: redirect user to notes page
            if(data == "success"){
                window.location = "mainpageloggedin.php";
//            otherwise show error message
            }else{
                $('#loginMessage').html(data);
                $("#loginMessage").slideDown();
            }
        },
//        Ajax Call fails: show ajax call error
        error: function(){
            $("#spinner").hide();
            $("#loginMessage").html("<div class='alert alert-danger'>There was an error with the Ajax call. Please try again</div>");
            $("#loginMessage").slideDown();
        }
    });
});

//Ajax call for forgot password form
//Once form is submitted
$("#passwordForm").submit(function(event){
//    Show Spinner
    $("#spinner").show();
//    Hide Results
    $("#passwordMessage").hide();
//    prevent default php processing
    event.preventDefault();
//    collect user inputs
    var datatopost = $(this).serializeArray();
//    send them to forgotpassword.php using ajax
    $.ajax({
        url: "forgotpassword.php",
        type: "POST",
        data: datatopost,
//        Ajax call successful
        success: function(data){
            $("#spinner").hide();
            $('#passwordMessage').html(data);
            $("#passwordMessage").slideDown();
        },
//        Ajax Call fails: show ajax call error
        error: function(){
            $("#spinner").hide();
            $("#passwordMessage").html("<div class='alert alert-danger'>There was an error with the Ajax call. Please try again</div>");
            $("#passwordMessage").slideDown();
        }
    });
});

//Create Geocoder
var geocoder = new google.maps.Geocoder();

var data;

//Search Form submit
$("#searchForm").submit(function(event){
//    Show Spinner
    $("#spinner").show();
//    Hide Results
    $("#searchResults").fadeOut();
//    prevent default php processing
    event.preventDefault();
//    collect user inputs
    data = $(this).serializeArray();
    
    getSearchDepartureCoordinates();
});

//Functions
function getSearchDepartureCoordinates(){
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
                getSearchDestinationCoordinates();
            }else{
                getSearchDestinationCoordinates();
            }
        }
    );
}

function getSearchDestinationCoordinates(){
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
                submitSearchRequest();
            }else{
                submitSearchRequest();
            }
        }
    );
}

function submitSearchRequest(){
    $.ajax({
        url: "search.php",
        data: data,
        type: "POST",
//        Ajax call successful: show error or success message
        success: function(data2){
        //    Hide Spinner
            $("#spinner").hide();
            $('#searchResults').html(data2);
            $('#tripResults').accordion({
                icons: false,
                active: false,
                collapsible: true,
                heightStyle: "content" 
            });
        //    Show Results
            $("#searchResults").fadeIn();
        },
//        Ajax Call fails: show ajax call error
        error: function(){
         //    Hide Spinner
            $("#spinner").hide();
            $("#searchResults").html("<div class='alert alert-danger'>There was an error with the searchTrips Ajax call. Please try again</div>");
        }
    });
}

























