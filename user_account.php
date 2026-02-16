<?php
    session_start();

    if(!isset($_SESSION["id"])){
        header("Location: welcome.php");
    }

    include("db/connection.php");
    include("navbar.php");

    $stmt = $conn->prepare("
    SELECT 
        registered_users.username,
        user_account_details.level,
        user_account_details.xp
    FROM registered_users
    INNER JOIN user_account_details
        ON registered_users.id = user_account_details.user_id
    WHERE registered_users.id = ?
    ");
    $stmt->bind_param("i", $_SESSION["id"]);
    $stmt->execute();
    $result = $stmt->get_result();

    $user = $result->fetch_assoc();

    $stmt->close();
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Account</title>

    <!-- Font API -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Press+Start+2P&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/bean-boss-favicon.png">

    <!-- Link to CSS file -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <span class="back-btn"><a class="back-btn-link" href="index.php">&#8617;</a></span>
    <div class="profile-container">
        <div class="profile-info">
            <img src="assets/default-pfp.jpg" width="220px" style="border-radius: 200px; margin-bottom: 10px;" alt="Profile Picture">

            <hr class="user-account-hr">

            <p class="user-account-info" id="user-account-username"><?= htmlspecialchars($user["username"]) ?></p>

            <hr class="user-account-hr">

            <p class="user-account-info">Level: <?= htmlspecialchars($user["level"]) ?></p>
            <p class="user-account-info">XP: <?= htmlspecialchars($user["xp"]) ?></p>

            <hr class="user-account-hr">

            <a class="user-account-info user-account-info-link" href="edit_profile.php">Edit Profile</a>
            <a class="user-account-info user-account-info-link" href="user_account_settings.php">Account Settings</a>
            <a class="user-account-info user-account-info-link" href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>