<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("location: index.php");
}
//<!--Connect to database-->
include('connection.php');
//Get user id
$user_id = $_SESSION['user_id'];

//Get username and email
$sql = "SELECT * FROM users WHERE user_id='$user_id'";
$result = mysqli_query($link, $sql);
$count = mysqli_num_rows($result);
if($count == 1){
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $username = $row['username'];
    $email = $row['email'];
    $moreinfo = $row['moreinformation'];
    $picture = $row['profilepicture'];
}else{
    echo '<div class="alert alert-danger">There was an error retrieving the username and email from the database</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Profile</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
        <meta name="description" content="#">
        <link rel="icon" href="#">
        <!-- Bootstrap & Fonts CSS -->
        <link rel="stylesheet" href="boot/bootstrap.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Akaya+Telivigala&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
<!--        Custom Styling-->
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
<!--        Navigation-->
        <?php
        include("navbarconnected.php");
        ?>
        
<!--        Main Content-->
        <div class="container" id="profileContainer">
            <div class="row">
                <div class="col-md-offset-3 col-md-6">
                    <h2>General Account Settings</h2>
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <tr data-toggle="modal" data-target="#updatePicture">
                                <td>Profile Picture</td>
                                <td><?php
                                    if(empty($picture)){
                                        echo "<img src='css/profilepics/angel.png' class='tableProfilePic'>";
                                    }else{
                                        echo "<img src='$picture' class='tableProfilePic'>";
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr data-target="#updateusername" data-toggle="modal">
                                <td>Username</td>
                                <td><?php echo $username;?></td>
                            </tr>
                            <tr data-target="#updateemail" data-toggle="modal">
                                <td>Email</td>
                                <td><?php echo $email;?></td>
                            </tr>
                            <tr data-target="#updatepassword" data-toggle="modal">
                                <td>Password</td>
                                <td>hidden</td>
                            </tr>
                            <tr data-target="#updateabout" data-toggle="modal">
                                <td>About Me</td>
                                <td><?php echo $moreinfo;?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
<!--        Update Username-->
        <form method="post" id="updateUsernameForm">
            <div class="modal" id="updateusername" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button class="close" data-dismiss="modal">&times;</button>
                            <h4 id="myModalLabel">Update Username:</h4>
                        </div>
                        <div class="modal-body">
<!--                            update username message for php file-->
                            <div id="updateUsernameMessage"></div>
<!--                            Update form-->
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input class="form-control" type="text" id="username" name="username" maxlength="30" value="<?php echo $username;?>">
                            </div>                   
                        </div>
                        <div class="modal-footer">
                            <input class="btn btnColor" name="updateusername" type="submit" value="Submit">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>        
        </form>
<!--        Update Email-->
        <form method="post" id="updateEmailForm">
            <div class="modal" id="updateemail" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button class="close" data-dismiss="modal">&times;</button>
                            <h4 id="myModalLabel">Update Email:</h4>
                        </div>
                        <div class="modal-body">
<!--                            login message for php file-->
                            <div id="updateEmailMessage"></div>
<!--                            Update form-->
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input class="form-control" type="email" id="email" name="email" maxlength="50" value="<?php echo $email;?>">
                            </div>                   
                        </div>
                        <div class="modal-footer">
                            <input class="btn btnColor" name="updateemail" type="submit" value="Submit">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>        
        </form>
<!--        Forgot password form-->
        <form method="post" id="updatePasswordForm">
            <div class="modal" id="updatepassword" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button class="close" data-dismiss="modal">&times;</button>
                            <h4 id="myModalLabel">Update Password:</h4>
                        </div>
                        <div class="modal-body">
<!--                            update password message for php file-->
                            <div id="updatePasswordMessage"></div>
<!--                            Sign up form-->
                            <div class="form-group">
                                <label for="passwordold" class="sr-only">Current Password</label>
                                <input class="form-control" type="password" id="passwordold" name="passwordold" placeholder="Current Password" maxlength="30">  
                            </div>    
                            <div class="form-group">
                                <label for="passwordnew" class="sr-only">New Password</label>
                                <input class="form-control" type="password" id="passwordnew" name="passwordnew" placeholder="New Password" maxlength="30">  
                            </div>    
                            <div class="form-group">
                                <label for="passwordconfirm" class="sr-only">Confirm Password</label>
                                <input class="form-control" type="password" id="passwordconfirm" name="passwordconfirm" placeholder="Confirm New Password" maxlength="30">  
                            </div>                       
                        </div>
                        <div class="modal-footer">
                            <input class="btn btnColor" name="updatepassword" type="submit" value="Submit">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>        
        </form>
        <!--        Update Picture Modal-->
        <form method="post" id="updatePictureForm" enctype="multipart/form-data">
            <div class="modal" id="updatePicture" role="dialog" aria-labelledby="pictureModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <a class="close" data-dismiss="modal">&times;</a>
                            <h4 id="myModalLabel">Upload Picture:</h4>
                        </div>
                        <div class="modal-body">
<!--                            picture message for php file-->
                            <div id="updatePictureMessage"></div>
<!--                            Current Profile Pic-->
                            <div class="profilePicContainer">
                                <?php
                                if(empty($picture)){
                                    echo "<img src='css/profilepics/angel.png' class='profilePic' id='profilePic'>";
                                }else{
                                    echo "<img src='$picture' class='profilePic' id='profilePic'>";
                                }
                                ?>
                            </div>
<!--                            Upload Form-->
                            <div class="form-group">
                                <label for="picture" class="sr-only">Select a Picture</label>
                                <input type="file" name="picture" id="picture">
                            </div>                           
                        </div>
                        <div class="modal-footer">
                            <input class="btn btnColor" name="submit" type="submit" value="Submit">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>        
        </form>
        
<!--        Footer-->
        <div class="footer">
            <div class="container-fluid">
                <p id="brand">Seraphim Virtual Services, Copyright &copy; <?php $today = date("Y"); echo $today?></p>
                <p id="credits"><a href="credits.html">Credits</a></p>
            </div>
        
        </div>
        
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <!--Include all compiled plugins (below), or include individual files as needed -->
        <script src="boot/bootstrap.min.js"></script>
        <script src="profile.js"></script>
        <script>
            $(function(){
                $("#profile").addClass('active');
            });
        </script>
    </body>
</html>