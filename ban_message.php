<?php
    session_start();

    include("db/connection.php");

    if(!isset($_SESSION["id"])){
        header("Location: welcome.php");
    }

    // Checks if user is actually banned
    $ban_check = $conn->prepare("
        SELECT is_banned FROM user_account_details
        WHERE user_id = ?
    ");
    $ban_check->bind_param("i", $_SESSION["id"]);
    $ban_check->execute();

    $ban_check_result = $ban_check->get_result();

    if($ban_check_result->num_rows > 0){
        $row = $ban_check_result->fetch_assoc();

        if($row['is_banned'] !== 1){
            header("Location: welcome.php");
        }
    }
    $ban_check->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your account is banned</title>

    <!-- Font API -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Press+Start+2P&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/bean-boss-favicon.png">

    <!-- Link to CSS file -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <span class="back-btn"><a class="back-btn-link" href="logout.php">&#8617;</a></span>
    <div class="ban-message-main-container">
        <div class="ban-message-sub-container">
            <h1 id="ban-message-header">Your account is banned!</h1>
            <p id="ban-message-text">If you think that this is a mistake, send an email to info@beanboss.online for further information and resolution.</p>
        </div>
    </div>
</body>
</html>