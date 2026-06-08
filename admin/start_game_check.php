<?php
    // Checks if start_game has been completed for registered users
    $stmt = $conn->prepare("
        SELECT id FROM user_game_progress WHERE user_id = ?
    ");
    $stmt->bind_param("i", $_SESSION["id"]);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 0){
        header("Location: ../start_game.php");
        exit();
    }
?>