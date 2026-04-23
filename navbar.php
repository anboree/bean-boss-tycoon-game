<?php
    // Only start session if one isn't already active
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }

    include("db/connection.php");

    $stmt = $conn->prepare("
        SELECT user_account_details.profile_picture
        FROM user_account_details
        WHERE user_account_details.user_id = ?
    ");

    $stmt->bind_param("i", $_SESSION["id"]);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font API -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Press+Start+2P&display=swap" rel="stylesheet">

    <!-- Link to CSS file -->
    <link rel="stylesheet" href="css/style.css">
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
                    <img src="assets/leaderboard-icon.png" id="leaderboard-icon" width="30px" alt="Leaderboard Icon">
                    <a href="leaderboard.php" class="nav-link leaderboard-link">Leaderboard</a>

                    <a href="user_account.php" class="nav-link">
                        <img id="profile-picture" src="profile_pictures/<?php echo htmlspecialchars($user['profile_picture'] ?? 'default-pfp.jpg'); ?>" alt="Profile">
                    </a>
                </div>
            <?php else: ?>
                <!-- Not logged-in user -->
                <div class="register-login-nav-div">
                    <img src="assets/login-icon.png" width="20px" style="margin-top: -3px; margin-right: 3px;" alt="login-icon"><a href="login.php" class="nav-link"> Login</a>
                    <img src="assets/register-icon.png" width="20px" style="margin-top: -5px; margin-right: 2px;" alt="register-icon"><a href="register.php" class="nav-link"> Register</a>
                </div>
            <?php endif; ?>
        </div>
    </nav>
    
    <!-- Mobile Navbar -->
    <nav class="navbar-mobile">
        <div class="nav-mobile-links">
            <?php if(isset($_SESSION["id"])): ?>
                <!-- Logged-in user -->
                <a href="leaderboard.php" class="nav-link">
                    <img src="assets/leaderboard-icon.png" width="40px" style="margin-bottom: 20px; margin-left: 22px; margin-right: 20px;" alt="Leaderboard Icon">
                </a>

                <a href="index.php" class="nav-link">
                    <img src="assets/bean-boss-logo.png" width="80px" style="margin-right: 20px;" alt="Logo">
                </a>

                <a href="user_account.php" class="nav-link">
                    <img id="profile-picture" src="profile_pictures/<?php echo htmlspecialchars($user['profile_picture'] ?? 'default-pfp.jpg'); ?>" width="60px" style="margin-bottom: 10px; border: 4px solid black; border-radius: 50%; object-fit: cover;" alt="Profile">
                </a>
            <?php else: ?>
                <!-- Not logged-in user -->
                <div class="register-login-nav-div">
                    <a href="login.php" class="nav-link">
                        <img src="assets/login-icon.png" width="40px" style="margin-left: 11px; margin-right: 22px;" alt="login-icon">
                    </a>

                    <a href="welcome.php" class="nav-link">
                        <img src="assets/bean-boss-logo.png" width="80px" style="margin-right: 22px;" alt="Logo">
                    </a>

                    <a href="register.php" class="nav-link">
                        <img src="assets/register-icon.png" width="40px" alt="register-icon">
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </nav>
</body>
</html>