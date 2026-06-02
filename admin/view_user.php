<?php
    session_start();

    include("../db/connection.php");

    if(!isset($_SESSION["admin_id"])){
        header("Location: admin_login.php");
    }

    // Checks if ID exists
    if(!isset($_GET['id'])){
        header("Location: admin_panel.php");
        exit();
    }

    $userId = (int)$_GET['id'];

    if($userId <= 0){
        header("Location: admin_panel.php");
        exit();
    }

    $result = $conn->prepare("
        SELECT
            registered_users.id,
            registered_users.username,
            user_game_progress.user_id,
            user_game_progress.business_name,
            user_game_progress.day,
            user_game_progress.hour,
            user_game_progress.minute,
            user_game_progress.money,
            user_game_progress.beans,
            user_game_progress.upgrade_level
        FROM registered_users
        INNER JOIN user_game_progress
        ON registered_users.id = user_game_progress.user_id
        WHERE registered_users.id = ?
        LIMIT 1
    ");

    $result->bind_param("i", $userId);
    $result->execute();

    $get_result = $result->get_result();
    $user = $get_result->fetch_assoc();

    if(!$user){
        header("Location: admin_panel.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User</title>

    <!-- Font API -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Press+Start+2P&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/bean-boss-favicon.png">

    <!-- Link to CSS file -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body id="admin-body">
    <span class="back-btn"><a class="back-btn-link" href="admin_panel.php">&#8617;</a></span>
    <div class="admin-flex-container">
        <h1 id="admin-panel-heading"><?= htmlspecialchars($user["username"]); ?> Game Stats</h1>
        <div class="admin-panel-container">
            <table id="view-user-table">
                <tr>
                    <th class="admin-panel-header">Business Name</th>
                    <th class="admin-panel-header">Day</th>
                    <th class="admin-panel-header">Hour</th>
                    <th class="admin-panel-header">Minute</th>
                    <th class="admin-panel-header">Money</th>
                    <th class="admin-panel-header">Beans</th>
                    <th class="admin-panel-header">Upgrade Level</th>
                </tr>

                <tr>
                    <td class="admin-panel-data"><?= htmlspecialchars($user["business_name"]) ?></td>
                    <td class="admin-panel-data"><?= $user["day"] ?></td>
                    <td class="admin-panel-data"><?= $user["hour"] ?></td>
                    <td class="admin-panel-data"><?= $user["minute"] ?></td>
                    <td class="admin-panel-data"><?= $user["money"] ?></td>
                    <td class="admin-panel-data"><?= $user["beans"] ?></td>
                    <td class="admin-panel-data"><?= $user["upgrade_level"] ?></td>
                </tr>

            </table>
        </div>
    </div>
</body>
</html>