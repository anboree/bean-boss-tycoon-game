<?php
    include("navbar-logged-out.html");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bean Boss</title>
    
    <!-- Imported fonts from Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Press+Start+2P&display=swap" rel="stylesheet">

    <!-- Link to CSS file -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Game Container -->
    <div class="gameContainer">

        <!-- Business Name -->
        <p class="business-name"><span id="businessName"><b>My Coffee Stand</b></span></p>

        <!-- Day & Time -->
        <div class="day-time">
            <p>Day: <span id="currentDay">1</span></p>
            <p>Time: <span id="currentTime">7:00 AM</span></p>
        </div>

        <!-- Main game box -->
        <div class="main-box">
            <p>Money: $<span id="money">0</span></p>
            <button id="brewBtn">Brew Coffee</button>
        </div>

        <!-- Upgrades box -->
        <p id="upgradesHeaderText">Upgrades</p>
        <div class="upgrades-box">
        </div>

    </div>

    <script src="js/app.js"></script>
</body>
</html>