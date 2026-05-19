<?php
    session_start();

    include("../db/connection.php");

    if(!isset($_SESSION["admin_id"])){
        header("Location: admin_login.php");
    }

    // Array for storing errors
    $errors = [];

    if(isset($_POST["add_user"])){
        $username = filter_var($_POST["username"], FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm-password"];
        $business_name = filter_var($_POST["business-name"], FILTER_SANITIZE_SPECIAL_CHARS);
        $profilePicture = "default-pfp.jpg";
        $registration_date = date("Y-m-d H:i:s");

        // Username validation
        if(empty($username)){
            $errors["username"] = "Please choose a username!";
        }
        elseif(strlen($username) > 64){
            $errors["username"] = "Please don't enter more than 64 characters for your username!";
        }

        // Email validation
        if(empty($email)){
            $errors["email"] = "Please enter an email address!";
        }
        elseif(strlen($email) > 255){
            $errors["email"] = "Please don't enter more than 255 characters for your email address!";
        }
        elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors["email"] = "The email address you have entered is not valid!";
        }

        // Password validation
        if(empty($password)){
            $errors["password"] = "Please create a password!";
        }
        elseif(strlen($password) < 8){
            $errors["password"] = "Please use at least 8 characters for your password!";
        }
        elseif(strlen($password) > 64){
            $errors["password"] = "Please don't enter more than 64 characters for your password!";
        }
        elseif(!preg_match("#[0-9]+#", $password)){
            $errors["password"] = "Your password must use at least 1 number!";
        }
        elseif(!preg_match("#[A-Z]+#", $password)){
            $errors["password"] = "Your password must use at least 1 uppercase letter!";
        }
        elseif(!preg_match("#[a-z]+#", $password)){
            $errors["password"] = "Your password must use at least 1 lowercase letter!";
        }

        // Confirmed password validation and password hashing
        if(empty($confirm_password)){
            $errors["confirm-password"] = "Please confirm your password!";
        }
        elseif($confirm_password !== $password){
            $errors["confirm-password"] = "Passwords don't match!";
        }
        else{
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        }

        // Business name validation
        if(empty($business_name)){
            $errors["business-name"] = "Please enter a name for your coffee business!";
        }
        elseif(strlen($business_name) > 255){
            $errors["business-name"] = "Business name cannot be longer than 255 characters!";
        }

        // Image upload
        if(isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0){
            $fileName = $_FILES["profile_picture"]["name"];
            $tmpName = $_FILES["profile_picture"]["tmp_name"];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $allowed = ["jpg","jpeg","png"];

            if(in_array($fileExt, $allowed)){
                $newFileName = "pfp_" . time() . "." . $fileExt;
                move_uploaded_file($tmpName, "../profile_pictures/" . $newFileName);

                $profilePicture = $newFileName;
            }
        }

        // Start transaction if no errors
        if(count($errors) == 0){
            $conn->begin_transaction();

            try{
                // Insert into registered_users
                $stmt = $conn->prepare("
                    INSERT INTO registered_users
                    (username, email, password, registration_date)
                    VALUES (?, ?, ?, ?)
                ");

                $stmt->bind_param(
                    "ssss",
                    $username,
                    $email,
                    $hashed_password,
                    $registration_date
                );

                $stmt->execute();

                $userId = $stmt->insert_id;

                $stmt->close();

                // Insert into user_account_details
                $stmt = $conn->prepare("
                    INSERT INTO user_account_details
                    (user_id, profile_picture, last_active)
                    VALUES (?, ?, ?)
                ");

                $stmt->bind_param(
                    "iss",
                    $userId,
                    $profilePicture,
                    $registration_date
                );

                $stmt->execute();
                $stmt->close();

                // Insert into user_account_preferences
                $stmt = $conn->prepare("
                    INSERT INTO user_account_preferences (user_id)
                    VALUES (?)
                ");
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $stmt->close();

                // Insert default game progress
                $stmt = $conn->prepare("
                    INSERT INTO user_game_progress
                    (user_id, business_name, day, hour, minute, money, beans, upgrade_level)
                    VALUES (?, ?, 1, 7, 0, 0, 250, 1)
                ");

                $stmt->bind_param("is",
                                  $userId,
                                  $business_name);

                $stmt->execute();
                $stmt->close();

                $conn->commit();

                header("Location: admin_panel.php");
                exit();
            }
            catch (Exception $e){
                $conn->rollback();
                echo "Error adding user.";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>

    <!-- Font API -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Press+Start+2P&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/bean-boss-favicon.png">

    <!-- Link to CSS file -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <span class="back-btn"><a class="back-btn-link" href="admin_panel.php">&#8617;</a></span>
    <div class="container">
        <div class="form-container">
            <p class="register-login-heading">Add User</h1>
            <form method="post" class="form" enctype="multipart/form-data">
                <label class="label" for="username">Username</label>
                <input type="text" name="username" class="input" id="username" placeholder="Choose a username" value="<?= $username ?? '' ?>">

                <label class="label" for="email">Email</label>
                <input type="text" name="email" class="input" id="email" placeholder="Enter your email address" value="<?= $email ?? '' ?>">

                <label class="label" for="password">Password</label>
                <input type="password" name="password" class="input" id="password" placeholder="Create a password">

                <label class="label" for="confirm-password">Confirm password</label>
                <input type="password" name="confirm-password" class="input" id="confirm-password" placeholder="Confirm your password">

                <label id="show-password-label" for="show-password-toggle">Show Password:</label>
                <input type="checkbox" id="show-password-toggle" onclick="showPassword()">

                <!-- Show password function -->
                <script>
                    function showPassword(){
                        var password = document.getElementById("password");
                        var confirmPassword = document.getElementById("confirm-password");
                        if(password.type === "password" && confirmPassword.type === "password"){
                            password.type = "text";
                            confirmPassword.type = "text";
                        } 
                        else{
                            password.type = "password";
                            confirmPassword.type = "password";
                        }
                    }
                </script>

                <label class="label" for="business-name">Business Name</label>
                <input type="text" name="business-name" class="input" id="business-name" placeholder="Choose business name" value="<?= $business_name ?? '' ?>">

                <label class="label" for="profile-picture">Profile Picture</label>
                <input type="file" name="profile_picture" style="text-align: right;" accept="image/*">

                <input type="submit" name="add_user" class="btn" id="register-btn" value="Add User">
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