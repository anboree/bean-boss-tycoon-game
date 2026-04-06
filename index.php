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

    <!-- Imported fonts from Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Press+Start+2P&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/bean-boss-favicon.png">

    <!-- Link to CSS file -->
    <link rel="stylesheet" href="css/style.css">
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

        <div class="game-grid-container">
            <!-- Main game box -->
            <div class="main-box">
                <p id="moneyText">Money: $<span id="money">0</span></p>
                <button id="brewBtn">Brew Coffee</button>
                <img id="pauseBtn" src="assets/pause-icon.png" alt="Pause Game">
            </div>

            <!-- Upgrades box -->
            <div class="upgrades-box">
                <p id="upgradesLevelText">Level <span id="upgrades-level">1</span> Upgrades</p>
                <button class="upgradesBtn" onclick="buyUpgrade('coffeeMachine')">Buy Coffee Machine - $50 (Used, Earns $1 - $3 per click) <img src="assets/used-coffee-machine-upgrade.png" alt="Used Coffee Machine" width="50px" height="50px" style="display: block; margin: 0 auto 0 auto"></button>
                <button class="upgradesBtn" onclick="buyUpgrade('businessSign')">Buy Business Sign - $120 (+10% more money) <img src="assets/coffee-business-sign-upgrade.png" alt="Business Sign" width="50px" height="50px" style="display: block; margin: 0 auto 0 auto"></button>
                <button class="upgradesBtn" onclick="buyUpgrade('hireBarista')">Hire Barista - $100 per day (Earns $1 per 2 seconds) <img src="assets/hire-part-time-barista-upgrade.png" alt="Part-Time Barista" width="50px" height="50px" style="display: block; margin: 0 auto 0 auto"></button>
                <button class="upgradesBtn" onclick="buyUpgrade('premiumBeans')">Buy Premium Beans - $400 (Earns more money) <img src="assets/premium-coffee-beans-upgrade.png" alt="Premium Coffee Beans" width="50px" height="50px" style="display: block; margin: 0 auto 0 auto"></button>
                <button class="upgradesBtn" onclick="buyUpgrade('biggerStand')"><span id="levelFinalUpgrade">Buy Bigger Coffee Stand - $800 (Unlocks Level 2, Increased rent price)</span></button>
            </div>

            <!-- Activity box -->
            <div id="activity-box">
                <p class="activity-text">Welcome back to Bean Boss! <br> This is where your game activity is shown.</p>
            </div>
        </div>
    </div>

    <script src="js/app.js"></script>
</body>
</html>