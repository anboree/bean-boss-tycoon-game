<?php
    $db_servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $db_name = "bean_boss_tycoon_db";

    $conn = mysqli_connect($db_servername, $db_username, $db_password, $db_name);

    $timezone = date_default_timezone_set("Europe/Riga");

    if(!$conn){
        echo "Error";
        die;
    }
?>