<?php
    // Only start session if one isn't already active
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <nav class="navbar">
        <!-- Logo and title -->
        <div class="nav-logo-container">
            <a href="index.php"><img src="assets/bean-boss-logo.png" width="60" alt="Logo"></a>
            <span id="logo-text">Bean Boss</span>
        </div>

        <div class="nav-links">
            <?php if(isset($_SESSION["id"])): ?>
                <!-- Logged-in user -->
                <div class="leaderboard-pfp-div">
                    <img src="assets/leaderboard-icon.png" id="leaderboard-icon" width="30" alt="Leaderboard Icon">
                    <a href="leaderboard.php" class="nav-link leaderboard-link">Leaderboard</a>

                    <a href="user_account.php" class="nav-link">
                        <img id="profile-picture" src="assets/default-pfp.jpg" alt="Profile">
                    </a>
                </div>
            <?php else: ?>
                <!-- Not logged-in user -->
                <a href="login.php" class="nav-link">&#128273; Login</a>
                <a href="register.php" class="nav-link">&#128203; Register</a>
            <?php endif; ?>
        </div>
    </nav>
</body>
</html>