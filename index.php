<?php
    session_start();

    include("db/connection.php");

    if(!isset($_SESSION["id"])){
        header("Location: welcome.php");
    }

    // Checks if start_game has been completed for registered users
    $stmt = $conn->prepare("
        SELECT id FROM user_game_progress WHERE user_id = ?
    ");
    $stmt->bind_param("i", $_SESSION["id"]);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 0){
        header("Location: start_game.php");
        exit();
    }

    include("navbar.php");

    // Fetching player business name from the DB
    $stmt = $conn->prepare("
        SELECT business_name 
        FROM user_game_progress 
        WHERE user_id = ?
    ");
    $stmt->bind_param("i", $_SESSION["id"]);
    $stmt->execute();
    $result = $stmt->get_result();

    $gameData = $result->fetch_assoc();
    $business_name = $gameData["business_name"];

    $stmt->close();
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
<body id="index-body">
    <!-- Game Container -->
    <div class="gameContainer">

        <!-- Added Decoration Ropes that visually holds up container -->
        <span class="rope-decor-left"></span>
        <span class="rope-decor-right"></span>

        <!-- Business Name -->
        <p class="business-name"><span id="businessName"><b><?= htmlspecialchars($business_name) ?></b></span></p>

        <!-- Day & Time -->
        <div class="day-time">
            <p>Day: <span id="currentDay">1</span></p>
            <p>Time: <span id="currentTime">7:00 AM</span></p>
        </div>

        <div class="game-grid-container">
            <!-- Main game box -->
            <div class="main-box">
                <p id="moneyText">Money: <span id="money-color">$<span id="money">0</span></span></p>
                <p id="beansText">Beans: <span id="beans">250</span></p>
                <button id="brewBtn">Brew Coffee</button>
                <img id="pauseBtn" src="assets/resume-icon.png" alt="Resume/Pause Game">
            </div>

            <!-- Upgrades box -->
            <div class="upgrades-box" id="upgradesContainer"></div>

            <!-- Activity box -->
            <div id="activity-box">
                <hr class="activity-box-hr">
                    <p class="activity-text">Welcome back to Bean Boss! <br> This is where your game activity is shown.</p>
                <hr class="activity-box-hr">
            </div>

            <!-- Save and Load buttons -->
            <div id="save-load-box">
                <input type="submit" name="save" id="save-game-btn" value="Save Game">
                <input type="submit" name="load" id="load-game-btn" value="Load Game">
            </div>

            <!-- Store box -->
            <div id="store-box">
                <span id="store-box-header">Bean Store</span>
            </div>
        </div>
    </div>

    <script src="js/app.js"></script>
</body>
</html>