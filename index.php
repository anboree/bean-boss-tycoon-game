<?php
    session_start();

    if(!isset($_SESSION["id"])){
        header("Location: welcome.php");
    }

    include("db/connection.php");
    include("navbar.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bean Boss</title>

    <!-- Font API -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Press+Start+2P&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/bean-boss-favicon.png">
</head>
<body>
    <!-- Game Container -->
    <div class="gameContainer">

        <!-- Added Decoration Ropes that visually holds up container -->
        <span class="rope-decor-left"></span>
        <span class="rope-decor-right"></span>

        <!-- Business Name -->
        <p class="business-name"><span id="businessName"><b>My Coffee Stand</b></span></p>

        <!-- Day & Time -->
        <div class="day-time">
            <p>Day: <span id="currentDay">1</span></p>
            <p>Time: <span id="currentTime">7:00 AM</span></p>
        </div>

        <!-- Main game box -->
        <div class="main-box">
            <p id="moneyText">Money: $<span id="money">0</span></p>
            <button id="brewBtn">Brew Coffee</button>
            <img id="pauseBtn" src="assets/pause-icon.png" alt="Pause Game">
        </div>

        <!-- Upgrades box -->
        <p id="upgradesHeaderText">Upgrades</p>
        <div class="upgrades-box">
            <p id="upgradesLevelText">Level <span id="upgrades-level">1</span></p>
            <button class="upgradesBtn"><span id="">Buy Coffee Machine - $400 (Used, Earns $1 - $3 per click)</span></button>
            <button class="upgradesBtn"><span id="">Buy Bigger Coffee Stand - $1,000 (Unlocks barista)</span></button>
            <button class="upgradesBtn"><span id="">Hire Barista - $120 per day (Earns $1 per second)</span></button>
            <button class="upgradesBtn"><span id="">Buy Small Coffee Shop - $5,000 (Unlocks Level 2)</span></button>
        </div>

        <!-- Activity box -->
        <p id="activityHeaderText">Activity</p>
        <div class="activity-box">
            <p class="activity-text">Welcome back to Bean Boss!</p>
        </div>

    </div>

    <script src="js/app.js"></script>
</body>
</html>