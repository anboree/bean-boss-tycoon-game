<?php
    session_start();

    include("db/connection.php");

    $conn->begin_transaction();

    try{
        // Delete game progress
        $stmt = $conn->prepare("
            DELETE FROM user_game_progress
            WHERE user_id = ?
        ");

        $stmt->bind_param("i", $_SESSION["id"]);
        $stmt->execute();
        $stmt->close();

        // Delete all owned upgrades
        $stmt = $conn->prepare("
            DELETE FROM user_upgrades
            WHERE user_id = ?
        ");

        $stmt->bind_param("i", $_SESSION["id"]);
        $stmt->execute();
        $stmt->close();

        $conn->commit();

        echo "success";
    }
    catch(Exception $exception){
        $conn->rollback();

        echo "Error";
    }
?>