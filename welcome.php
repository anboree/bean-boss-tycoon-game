<?php
    session_start();

    // If user already has a set session, then take them to index.php
    if(isset($_SESSION["id"])){
        header("Location: index.php");
    }

    include("navbar-logged-out.html");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Bean Boss</title>

    <!-- Font API -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Press+Start+2P&display=swap" rel="stylesheet">

    <!-- Link to CSS file -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="welcome-main-container">
        <div class="welcome-sub-container">
            <h1 id="welcome-header">Welcome to Bean Boss!</h1>
            <p id="welcome-tagline">Build your coffee empire, one bean at a time</p>

            <ul class="welcome-features">
                <li>📈 Grow your coffee business</li>
                <li>☕ Brew, upgrade, and automate</li>
                <li>🏪 From a stand to a coffee empire</li>
            </ul>

            <div class="welcome-actions">
                <button class="welcome-btn"><a href="register.php" id="welcome-register-link">Start Playing</a></button>
                <a href="login.php" id="welcome-login-link">I already have an account</a>
            </div>
        </div>
    </div>
</body>
</html>