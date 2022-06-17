<?php
session_start();
include('connection.php');

//logout
include('logout.php');
//remember me
include('rememberme.php');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Car Sharer - Home</title>
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
            width: 100%;
            height: 30vh;
            margin-top: 10px;
        }

        </style>
    </head>
    <body>
<!--        Navigation-->
        <?php
        if(isset($_SESSION["user_id"])){
            include("navbarconnected.php");
        }else{
            include("navbarnotconnected.php");
        }
        
        ?>
        
<!--        Main Content-->
        <div class="jumbotron" id="myContainer">
            <h1>Plan Your Next Trip Now!</h1>
            <p>Save Money! Save the Environment!</p>
            <p>You can save up to $3,000 per year using car sharing!</p>
<!--            Search Form-->
            <form class="form-inline" id="searchForm">
                <div class="form-group">
                    <label for="departure" class="sr-only">Departure</label>
                    <input type="text" placeholder="Departure" name="departure" id="departure" class="searchInput">
                    <label for="destination" class="sr-only">Destination</label>
                    <input type="text" placeholder="Destination" name="destination" id="destination" class="searchInput">
                    <input type="submit" value="Search" class="btn btn-lg btnDone">
                </div>
            </form>
            <div class="container-fluid">
                <div id="googleMap"></div>
                <div id="searchResults"></div>
            </div>
            
            <?php
            if(!isset($_SESSION["user_id"])){
                echo "<button type='button' class='btn btn-lg btnColor btnSignup' data-target='#signupModal' data-toggle='modal'>Sign Up For Free</button>";
            }
            ?>
            
        </div>
<!--        Login-->
        <form method="post" id="loginForm">
            <div class="modal" id="loginModal" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <a class="close" data-dismiss="modal">&times;</a>
                            <h4 id="myModalLabel">Login:</h4>
                        </div>
                        <div class="modal-body">
<!--                            login message for php file-->
                            <div id="loginMessage"></div>
<!--                            Sign up form-->
                            <div class="form-group">
                                <label for="loginemail" class="sr-only">Email</label>
                                <input class="form-control" type="email" id="loginemail" name="loginemail" placeholder="Email" maxlength="50">
                                <label for="loginpassword" class="sr-only">Password</label>
                                <input class="form-control" type="password" id="loginpassword" name="loginpassword" placeholder="Password" maxlength="30">        
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="rememberme" id="rememberme">
                                    Remember Me
                                </label>
                                <a class="pull-right" style="cursor: pointer" data-dismiss="modal" data-target="#passwordModal" data-toggle="modal">Forgot Password?</a>
                            </div>                            
                        </div>
                        <div class="modal-footer">
                            <input class="btn btnColor" name="login" type="submit" value="Login">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-target="#signupModal" data-toggle="modal">Register</button>
                        </div>
                    </div>
                </div>
            </div>        
        </form>
<!--        Sign Up-->
        <form method="post" id="signupForm">
            <div class="modal" id="signupModal" role="dialog" aria-labelledby="signupModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <a class="close" data-dismiss="modal">&times;</a>
                            <h4 id="myModalLabel">Sign Up today and Start using our Car Sharer App</h4>
                        </div>
                        <div class="modal-body">
<!--                            Signup message for php file-->
                            <div id="signupMessage"></div>
<!--                            Sign up form-->
                            <div class="form-group">
                                <label for="username" class="sr-only">Username</label>
                                <input class="form-control" type="text" id="username" name="username" placeholder="Username" maxlength="30">
                                <label for="firstname" class="sr-only">First Name</label>
                                <input class="form-control" type="text" id="firstname" name="firstname" placeholder="First Name" maxlength="30">
                                <label for="lastname" class="sr-only">Last Name</label>
                                <input class="form-control" type="text" id="lastname" name="lastname" placeholder="Last Name" maxlength="30">
                                <label for="email" class="sr-only">Email</label>
                                <input class="form-control" type="email" id="email" name="email" placeholder="Email" maxlength="50">
                                <label for="password" class="sr-only">Choose a Password</label>
                                <input class="form-control" type="password" id="password" name="password" placeholder="Choose a Password" maxlength="30">
                                <label for="password2" class="sr-only">Confirm Password</label>
                                <input class="form-control" type="password" id="password2" name="password2" placeholder="Confirm Password" maxlength="30">
                                <label for="phone" class="sr-only">Username</label>
                                <input class="form-control" type="text" id="phone" name="phone" placeholder="Phone Number" maxlength="30">
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="radio" name="gender" id="male" value="male">
                                    Male                                
                                </label>
                                <label>
                                    <input type="radio" name="gender" id="female" value="female">
                                    Female                                
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="moreinfo">Comments: </label>
                                <textarea name="moreinfo" class="form-control" id="moreinfo" rows="5" maxlength="300"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input class="btn btnColor" name="signup" type="submit" value="Sign Up">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>        
        </form>
<!--        Forgot password form-->
        <form method="post" id="passwordForm">
            <div class="modal" id="passwordModal" role="dialog" aria-labelledby="passwordModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <a class="close" data-dismiss="modal">&times;</a>
                            <h4 id="myModalLabel">Enter your email address below to recover your account and set a new password:</h4>
                        </div>
                        <div class="modal-body">
<!--                            forgot password message for php file-->
                            <div id="passwordMessage"></div>
<!--                            Forgot Password form-->
                            <div class="form-group">
                                <label for="passwordemail" class="sr-only">Email</label>
                                <input class="form-control" type="email" id="passwordemail" name="passwordemail" placeholder="Email" maxlength="50">  
                            </div>                       
                        </div>
                        <div class="modal-footer">
                            <input class="btn btnColor" name="forgotpassword" type="submit" value="Submit">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-target="#signupModal" data-toggle="modal">Register</button>
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
        
        
        
        <script src="script.js"></script>
        <script src="indexs.js"></script>
        <script>
            $(function(){
                $("#search").addClass('active');
            });
        </script>
    </body>