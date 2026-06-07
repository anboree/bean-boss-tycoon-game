<?php
    session_start();

    include("../db/connection.php");

    if(!isset($_SESSION["id"])){
        header("Location: ../welcome.php");
    }

    include("../ban_check.php");
    include("admin_check.php");

    // Checks if ID exists
    if(!isset($_GET['id'])){
        header("Location: admin_dashboard.php");
        exit();
    }

    $userId = (int)$_GET['id'];

    if($userId <= 0){
        header("Location: admin_panel.php");
        exit();
    }

    // Start transaction
    $conn->begin_transaction();

    try{
        // Remove banned status
        $stmt = $conn->prepare("
            UPDATE user_account_details SET is_banned = 0
            WHERE user_id = ?
        ");

        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();

        // Add to leaderboard
        $stmt = $conn->prepare("
            UPDATE user_account_preferences SET show_on_leaderboard = 1
            WHERE user_id = ?
        ");

        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();

        // Commit changes
        $conn->commit();

        header("Location: admin_panel.php");
        exit();
    }
    catch (Exception $exception){
        // Rollback if something fails
        $conn->rollback();
        echo "Error banning user.";
    }
?>