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
        header("Location: admin_panel.php");
        exit();
    }

    $userId = (int)$_GET['id'];

    if($userId <= 0){
        header("Location: admin_panel.php");
        exit();
    }

    // Get user data
    $result = $conn->prepare("
        SELECT
            registered_users.username,
            registered_users.email,
            user_account_details.profile_picture,
            user_account_details.is_admin,
            user_game_progress.business_name
        FROM registered_users
        INNER JOIN user_account_details
        ON registered_users.id = user_account_details.user_id
        INNER JOIN user_game_progress
        ON user_account_details.user_id = user_game_progress.user_id
        WHERE registered_users.id = ?
    ");

    $result->bind_param("i", $userId);
    $result->execute();

    $get_result = $result->get_result();
    $user = $get_result->fetch_assoc();

    if(!$user){
        header("Location: admin_panel.php");
        exit();
    }

    // Array for storing errors
    $errors = [];

    // Remove profile picture
    if(isset($_POST["remove_pfp"])){
        if($user["profile_picture"] !== "default-pfp.jpg"){

            $file = "../profile_pictures/" . $user["profile_picture"];

            if(file_exists($file)){
                unlink($file);
            }
        }

        $stmt = $conn->prepare("
            UPDATE user_account_details
            SET profile_picture = 'default-pfp.jpg'
            WHERE user_id = ?
        ");

        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();

        header("Location: edit_user.php?id=" . $userId);
        exit();
    }

    if(isset($_POST["submit"])){
        $username = filter_var($_POST["username"], FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $business_name = filter_var($_POST["business-name"], FILTER_SANITIZE_SPECIAL_CHARS);

        // Username validation
        if(empty($username)){
            $errors["username"] = "Please choose a username!";
        }
        elseif(strlen($username) > 64){
            $errors["username"] = "Please don't enter more than 64 characters for the username!";
        }
        elseif(!preg_match('/^[\w ]+$/', $username)){
            $errors["username"] = "You can only use letters, numbers and underscore for your username!";
        }

        // Email validation
        if(empty($email)){
            $errors["email"] = "Please enter an email address!";
        }
        elseif(strlen($email) > 255){
            $errors["email"] = "Please don't enter more than 255 characters for the email address!";
        }
        elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors["email"] = "The email address you have entered is not valid!";
        }

        // Business name validation
        if(empty($business_name)){
            $errors["business-name"] = "Please enter a name for the coffee business!";
        }
        elseif(strlen($business_name) > 255){
            $errors["business-name"] = "Business name cannot be longer than 255 characters!";
        }
        elseif(!preg_match('/^[\w ]+$/', $business_name)){
            $errors["business-name"] = "You can only use letters, numbers and underscore for your business name!";
        }

        // Start transaction if no errors
        if(count($errors) == 0){
            $conn->begin_transaction();

            try{
                // Update registered_users
                $stmt = $conn->prepare("
                    UPDATE registered_users
                    SET username = ?, email = ?
                    WHERE id = ?
                ");

                $stmt->bind_param(
                    "ssi",
                    $username,
                    $email,
                    $userId
                );

                $stmt->execute();
                $stmt->close();

                // Update user_game_progress
                $stmt = $conn->prepare("
                    UPDATE user_game_progress
                    SET business_name = ?
                    WHERE user_id = ?
                ");

                $stmt->bind_param(
                    "si",
                    $business_name,
                    $userId
                );

                $stmt->execute();
                $stmt->close();

                // Make admin if checkbox is checked
                if(isset($_POST["make-admin"])){
                    $stmt = $conn->prepare("
                        UPDATE user_account_details
                        SET is_admin = 1
                        WHERE user_id = ?
                    ");

                    $stmt->bind_param("i", $userId);
                    $stmt->execute();
                    $stmt->close();
                }

                $conn->commit();

                header("Location: admin_panel.php");
                exit();
            }
            catch (Exception $exception){
                $conn->rollback();
                echo "Error updating data.";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>

    <!-- Font API -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Press+Start+2P&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/bean-boss-favicon.png">

    <!-- Link to CSS file -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body id="admin-body">
    <span class="back-btn"><a class="back-btn-link" href="admin_panel.php">&#8617;</a></span>
    <div class="container">
        <div class="form-container">
            <p class="register-login-heading">Edit User</h1>
            <form method="post" class="form">
                <label class="label" for="username">Change username</label>
                <input type="text" name="username" class="input admin-input" id="username" value="<?= $user["username"] ?>">

                <label class="label" for="email">Change email</label>
                <input type="text" name="email" class="input admin-input" id="email" value="<?= $user["email"] ?>">

                <label class="label" for="business-name">Change business name</label>
                <input type="text" name="business-name" class="input admin-input" id="business-name" value="<?= $user["business_name"] ?>">

                <label class="label" for="profile-picture">Profile Picture</label>
                <img src="../profile_pictures/<?= $user['profile_picture'] ?>" width="50" height="50">
                <button type="submit" name="remove_pfp" id="remove-pfp-btn">Remove</button>

                <?php if($user["is_admin"] == 0) : ?>
                    <label class="label" for="make-admin">Make admin</label>
                    <input type="checkbox" name="make-admin" id="make-admin">
                <?php endif; ?>

                <input type="submit" name="submit" class="btn" id="register-btn" value="Submit">
            </form>

            <!-- Form error output -->
            <?php
                if(count($errors) > 0){
                    echo "<ul class='error-msg'>";
                    foreach($errors as $error){
                        echo "<li>" . $error . "</li>";
                    }
                    echo "</ul>";
                }
            ?>

        </div>
    </div>
</body>
</html>