<?php
    // Checks if user is banned
    $ban_check = $conn->prepare("
        SELECT is_banned FROM user_account_details
        WHERE user_id = ?
    ");
    $ban_check->bind_param("i", $_SESSION["id"]);
    $ban_check->execute();

    $ban_check_result = $ban_check->get_result();

    if($ban_check_result->num_rows > 0){
        $row = $ban_check_result->fetch_assoc();

        if($row['is_banned'] !== 0){
            header("Location: ../ban_message.php");
        }
    }
    $ban_check->close();
?>