<?php
//<!--Start Session-->
session_start();
//<!--Connect to database-->
include("connection.php");

//<!--Check user inputs-->
//<!--    Define error messages-->
$missingEmail = '<p><strong>Please enter your email address.</strong></p>';
$missingPassword = '<p><strong>Please enter your password.</strong></p>';

//<!--    Get email and password-->
//Email
if(empty($_POST["loginemail"])){
    $errors .= $missingEmail;
}else{
    $email = filter_var($_POST["loginemail"], FILTER_SANITIZE_EMAIL);
}
//Password
if(empty($_POST["loginpassword"])){
    $errors .= $missingPassword;
}else{
    $password = filter_var($_POST["loginpassword"], FILTER_SANITIZE_STRING);
}

//<!--    If there are any error, print error message-->
if($errors){
    $resultMessage = '<div class="alert alert-danger">' . $errors . '</div>';
    echo $resultMessage;
    //<!--No errors-->
}else{
//<!--    Prepare variables for the query-->
    $email = mysqli_real_escape_string($link, $email);

    $password = mysqli_real_escape_string($link, $password);
    $password = hash('sha256', $password);

//<!--    Run query: Check combination of email and password exists-->
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password' AND activation='activated'";
    $result = mysqli_query($link, $sql);
    if(!$result){
        echo '<div class="alert alert-danger">Error running the query</div>';
        echo '<div class="alert alert-danger">' . mysqli_error($link) . '</div>';
        exit;
    }

//<!--    If email or password don't match, print error-->
    $count = mysqli_num_rows($result);
    if($count !== 1){
        echo '<div class="alert alert-danger">Wrong Username or Password</div>';
    }else{
//<!--        Log user in: Set session variables-->
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['email'] = $row['email'];

//<!--        If remember me is not checked, print success-->
        if(empty($_POST["rememberme"])){
            echo "success";
        }else{
            //Create two variables $authentificator1 and $authentificator2
            $authenticator1 = bin2hex(openssl_random_pseudo_bytes(10));
            //2*2*...*2
            $authenticator2 = openssl_random_pseudo_bytes(20);
            //Store them in a cookie
            function f1($a, $b){
                $c = $a . "," . bin2hex($b);
                return $c;
            }
            $cookieValue = f1($authenticator1, $authenticator2);
            setcookie(
                "rememberme",
                $cookieValue,
                time() + 1296000
            );

            //Run query to store them in rememberme table
            function f2($a){
                $b = hash('sha256', $a); 
                return $b;
            }
            $f2authenticator2 = f2($authenticator2);
            $user_id = $_SESSION['user_id'];
            $expiration = date('Y-m-d H:i:s', time() + 1296000);

            $sql = "INSERT INTO rememberme
            (`authenticator1`, `f2authenticator2`, `user_id`, `expires`)
            VALUES
            ('$authenticator1', '$f2authenticator2', '$user_id', '$expiration')";
            $result = mysqli_query($link, $sql);
            if(!$result){
                echo  '<div class="alert alert-danger">There was an error storing data to remember you next time.</div>';  
            }else{
                echo "success";   
            }
        }
    }
}
?>