   <?php
$user_id = $_SESSION['user_id'];

//get username
$sql = "SELECT * FROM users WHERE user_id='$user_id'";
$result = mysqli_query($link, $sql);
$count = mysqli_num_rows($result);
    
if($count == 1){
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $username = $row["username"];
    $picture = $row['profilepicture'];
}else{
    echo "<div class='alert alert-danger'>There was an error retrieving the username from the database.</div>";
}
?>     




        <nav role="navigation" class="navbar navbar-custom navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand">Car Sharer App</a>
                    <button type="button" class="navbar-toggle" data-target="#navbarCollapse" data-toggle="collapse">
                        <span class="sr-only"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="navbar-collapse collapse" id="navbarCollapse">
                    <ul class="nav navbar-nav">
                        <li id='search'><a href="index.php">Search</a></li>
                        <li id='profile'><a href="profile.php">Profile</a></li>
                        <li id='help'><a href="#">Help</a></li>
                        <li id='contactTab'><a href="#">Contact</a></li>
                        <li id='trips'><a href="mainpageloggedin.php">My Trips</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="profilePicList"><a><div data-toggle="modal" data-target="#updatePicture">
                            <?php
                            if(empty($picture)){
                                echo "<img src='css/profilepics/angel.png' class='navProfilePic'>";
                            }else{
                                echo "<img src='$picture' class='navProfilePic'>";
                            }
                            ?>
                            </div></a></li>
                        <li><a href="#" class="navbarUser"><?php echo $username; ?></a></li>
                        <li><a href="index.php?logout=1">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>        
