<?php
    session_start();

    include("db/connection.php");

    if(!isset($_SESSION["id"])){
        header("Location: welcome.php");
    }

    include("ban_check.php");
    include("start_game_check.php");

    // Empty array for storing form errors
    $errors = [];

    // CSRF Token creation if it doesn't exist yet
    if(!isset($_SESSION["csrf_token"])){
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
    }

    if(isset($_POST["confirm"])){
        $old_password = $_POST["old_password"];
        $new_password = $_POST["new_password"];
        $confirm_new_password = $_POST["confirm_new_password"];

        // Get current password hash
        $stmt = $conn->prepare("
            SELECT password
            FROM registered_users
            WHERE id = ?
        ");

        $stmt->bind_param("i", $_SESSION["id"]);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        $stmt->close();

        // Verify old password
        if(empty($old_password)){
            $errors["old_password"] = "Please enter your current password!";
        }
        elseif(!password_verify($old_password, $user["password"])){
            $errors["old_password"] = "Current password is incorrect!";
        }

        // New password validation
        if(empty($new_password)){
            $errors["new_password"] = "Please enter a new password!";
        }
        elseif(strlen($new_password) < 8){
            $errors["new_password"] = "Password must contain at least 8 characters!";
        }
        elseif(strlen($new_password) > 64){
            $errors["new_password"] = "Password cannot exceed 64 characters!";
        }
        elseif(!preg_match("#[0-9]+#", $new_password)){
            $errors["new_password"] = "Password must contain at least 1 number!";
        }
        elseif(!preg_match("#[A-Z]+#", $new_password)){
            $errors["new_password"] = "Password must contain at least 1 uppercase letter!";
        }
        elseif(!preg_match("#[a-z]+#", $new_password)){
            $errors["new_password"] = "Password must contain at least 1 lowercase letter!";
        }

        // Confirm password validation
        if(empty($confirm_new_password)){
            $errors["confirm_new_password"] = "Please confirm your new password!";
        }
        elseif($new_password !== $confirm_new_password){
            $errors["confirm_new_password"] = "Passwords do not match!";
        }

        // Prevent using same password
        if(password_verify($new_password, $user["password"])){
            $errors["new_password"] = "Your new password must be different from the current password!";
        }

        // CSRF Token verification
        if(!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]){
            die("CSRF Token error");
        }

        // Update password
        if(count($errors) === 0){
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            $conn->begin_transaction();

            try{
                $stmt = $conn->prepare("
                    UPDATE registered_users
                    SET password = ?
                    WHERE id = ?
                ");
                $stmt->bind_param(
                "si",
                $hashed_password,
                $_SESSION["id"]
                );
                $stmt->execute();
                $stmt->close();

                $conn->commit();

                // Remove CSRF token after success
                unset($_SESSION["csrf_token"]);

                header("Location: user_account_settings.php");
                exit;
            }
            catch(Exception $exception){
                    // If anything fails, undo everything
                    $conn->rollback();
                    $errors["database"] = "Change failed. Please try again.";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>

    <!-- Font API -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Press+Start+2P&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/bean-boss-favicon.png">

    <!-- Link to CSS file -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <span class="back-btn"><a class="back-btn-link" href="user_account_settings.php">&#8617;</a></span>
    <div class="container">
        <div class="form-container">
            <p class="register-login-heading">Change Password</h1>
            <form method="post" class="form">
                <label class="label" for="old-password">Old Password</label>
                <input type="password" name="old_password" class="input account-settings-input" id="password" placeholder="Enter your current password">

                <label class="label" for="new-password">New Password</label>
                <input type="password" name="new_password" class="input account-settings-input" id="new-password" placeholder="Enter new password">

                <label class="label" for="confirm-new-password">Confirm New Password</label>
                <input type="password" name="confirm_new_password" class="input account-settings-input" id="confirm-new-password" placeholder="Confirm new password">

                <label id="show-password-label" for="show-password-toggle">Show Password:</label>
                <input type="checkbox" id="show-password-toggle" onclick="showPassword()">

                <!-- Show password function -->
                <script>
                    function showPassword(){
                        let password = document.getElementById("password");
                        let newPassword = document.getElementById("new-password");
                        let confirmNewPassword = document.getElementById("confirm-new-password");
                        if(password.type === "password" && newPassword.type === "password" && confirmNewPassword.type === "password"){
                            password.type = "text";
                            newPassword.type = "text";
                            confirmNewPassword.type = "text";
                        } 
                        else{
                            password.type = "password";
                            newPassword.type = "password";
                            confirmNewPassword.type = "password";
                        }
                    }
                </script>

                <!-- CSRF Token value -->
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"]; ?>">

                <input type="submit" name="confirm" class="btn" id="confirm-btn" value="Submit">
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