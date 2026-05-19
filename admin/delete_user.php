<?php
    session_start();

    include("../db/connection.php");

    if(!isset($_SESSION["admin_id"])){
        header("Location: admin_login.php");
    }

    // Checks if ID exists
    if(!isset($_GET['id'])){
        header("Location: admin_dashboard.php");
        exit();
    }

    $userId = (int)$_GET['id'];

    if($userId <= 0){
        header("Location: admin_dashboard.php");
        exit();
    }

    // Start transaction
    $conn->begin_transaction();

    try{
        // Delete user upgrades
        $stmt = $conn->prepare("
            DELETE FROM user_upgrades
            WHERE user_id = ?
        ");

        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();

        // Delete game progress
        $stmt = $conn->prepare("
            DELETE FROM user_game_progress
            WHERE user_id = ?
        ");

        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();

        // Delete account preferences
        $stmt = $conn->prepare("
            DELETE FROM user_account_preferences
            WHERE user_id = ?
        ");

        // Delete account details
        $stmt = $conn->prepare("
            DELETE FROM user_account_details
            WHERE user_id = ?
        ");

        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();

        // Delete registered user
        $stmt = $conn->prepare("
            DELETE FROM registered_users
            WHERE id = ?
        ");

        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();

        // Commit changes
        $conn->commit();

        header("Location: admin_panel.php");
        exit();
    }
    catch (Exception $e){
        // Rollback if something fails
        $conn->rollback();
        echo "Error deleting user.";
    }
?>