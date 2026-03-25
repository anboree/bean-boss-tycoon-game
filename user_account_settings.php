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
    <title>Account Settings</title>

    <!-- Font API -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Press+Start+2P&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/bean-boss-favicon.png">

    <!-- Link to CSS file -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <span class="back-btn"><a class="back-btn-link" href="user_account.php">&#8617;</a></span>

    <div class="account-settings-container">
        <!-- Added Decoration Ropes that visually holds up container -->
        <span class="rope-decor-left"></span>
        <span class="rope-decor-right"></span>

        <h2 class="account-settings-header">Account Settings</h2>
        <hr class="account-settings-hr">

        <div class="account-settings-flex-container">
            <p class="account-settings-option">Receive E-Mails about game updates and changes <span class="account-settings-button"><input type="checkbox" id="check1"><label for="check1" class="button"></label></span></p>

            <p class="account-settings-option">Show your profile on the leaderboard <span class="account-settings-button"><input type="checkbox" id="check2"><label for="check2" class="button"></label></span></p>

            <p class="account-settings-option">Enable background music <span class="account-settings-button"><input type="checkbox" id="check3"><label for="check3" class="button"></label></span></p>
        </div>
        <hr class="account-settings-hr">

        <input type="submit" name="delete_account" id="delete-account-button" value="Delete Account">
    </div>
</body>
</html>