<?php

$db = "project";
$dbuser = "root";
$dbpass = "";
$dbhost = "localhost";


$allowed = array('secured');
$table = "social";

session_start();
$con = mysqli_connect($dbhost, $dbuser, $dbpass, $db);

$stop = false;

if (!$con) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

$ip = $_SERVER['REMOTE_ADDR'];
$r = mysqli_query($con, "SELECT 1 FROM $table WHERE ip='$ip' AND blocked=0");
$exist = mysqli_fetch_assoc($r) ?? 0;

if(!get('code', false) && !in_array(get('code'), $allowed) && !isset($_SESSION['secured']) && !$exist) {
    $stop = true;
} else {
    if(!isset($_SESSION['secured'])) {
        $_SESSION['secured'] = true;
        header("Location: ./");
        die();
    } else if(get('code', false)) {
        header("Location: ./");
        die();
    }
    if(!$exist) {
        $good = mysqli_query($con, "INSERT INTO $table (ip, created_date)
                            VALUES ('$ip', now())");
        if(!$good) {
            echo "I had a problem securing you, you will be fine for now.";
        }
    }
}


function get($key, $default=NULL) {
    return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <title>Private Social</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.12.0/css/all.css">
    <link id="favicon" rel="shortcut icon" href="awake.ico" type="image/x-icon">
</head>
<body>
    <div class="container">
        <?php
            if($stop) {
                echo "<span>You are not allowed <i class=\"fad fa-frown\"></i></span>";
                die();
            }
        ?>
        <a target="_blank" href="instagram" class="icon fab fa-instagram"></a>
        <a target="_blank" href="spotify" class="icon fab fa-spotify"></a>
        <a target="_blank" href="snapchat" class="icon fab fa-snapchat-ghost"></a>
        <a target="_blank" href="whatsapp" class="icon fab fa-whatsapp"></a>
        <a target="_blank" href="telephone number" rel="nofollow" class="icon fad fa-phone"></a>
    </div>
</body>
</html>