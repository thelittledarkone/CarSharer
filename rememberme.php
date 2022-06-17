<?php
//<!--If user is not logged in and rememberme cookie exists-->
// Alternative function to if statement -> array_key_exists('user_id', $_SESSION)
if(!isset($_SESSION['user_id']) && !empty($_COOKIE['rememberme'])){
//    f1: Cookie: $a . "," . bin2hex($b);
//    f2: Store in Table: hash('sha256', $a);
    
    //<!--    Extract $authenticator 1&2 from the cookie-->
    list($authenticator1, $authenticator2) = explode(',', $_COOKIE['rememberme']);
    $authenticator2 = hex2bin($authenticator2);
    $f2authenticator2 = hash('sha256', $authenticator2);
    
    //<!--    Look for authenticator 1 in remember me table-->
    $sql = "SELECT * FROM rememberme WHERE authenticator1='$authenticator1'";
    $result = mysqli_query($link, $sql);
    if(!$result){
        echo  '<div class="alert alert-danger">There was an error running query.</div>';
        exit;
    }
    $count = mysqli_num_rows($result);
    if($count !== 1){
        echo '<div class="alert alert-danger">Remember Me process failed</div>';
        exit;
    }
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    
    //<!--    If authenticator 2 does not match, print error-->
    if(!hash_equals($row['f2authenticator2'], $f2authenticator2)){
        echo '<div class="alert alert-danger">Hash not equal</div>';
    }else{
        //<!--        Generate new authenticators-->
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
        }
        
        //<!--        Log user in and redirect to notes page-->
        
        $_SESSION['user_id'] = $row['user_id'];
        header("location:mainpageloggedin.php");
    }
    
}

?>