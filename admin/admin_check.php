<?php
    // Checks if user is an admin
    $admin_check = $conn->prepare("
        SELECT
            user_id,
            is_admin
        FROM user_account_details
        WHERE user_id = ?
    ");
    $admin_check->bind_param("i", $_SESSION["id"]);
    $admin_check->execute();

    $admin_check_result = $admin_check->get_result();

    if($admin_check_result->num_rows > 0){
        $row = $admin_check_result->fetch_assoc();

        if($row['is_admin'] !== 1){
            header("Location: ../index.php");
        }
    }
    $admin_check->close();
?>