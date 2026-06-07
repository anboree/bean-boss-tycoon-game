<?php
    session_start();

    include("db/connection.php");

    if(!isset($_SESSION["id"])){
        header("Location: welcome.php");
    }

    include("ban_check.php");

    // Query to get necessary user data
    $result = $conn->prepare("
        SELECT email FROM registered_users WHERE id = ?
    ");
    $result->bind_param("i", $_SESSION["id"]);
    $result->execute();

    $get_result = $result->get_result();
    $user = $get_result->fetch_assoc();

    // Empty array for storing form errors
    $errors = [];

    // CSRF Token creation if it doesn't exist yet
    if(!isset($_SESSION["csrf_token"])){
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
    }

    if(isset($_POST["confirm"])){
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

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

        // CSRF Token verification
        if(!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]){
            die("CSRF Token error");
        }

        if(count($errors) == 0){
            // Checks if email already exists in DB
            $stmt = $conn->prepare("SELECT id FROM registered_users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if($stmt->num_rows > 0){
                $errors["email"] = "Email is already registered!";
            }
            $stmt->close();

            if(count($errors) === 0){

            $conn->begin_transaction();

                try{
                    // Update email
                    $stmt = $conn->prepare("
                        UPDATE registered_users SET email = ?
                        WHERE id = ?
                    ");
                    $stmt->bind_param("si", $email, $_SESSION["id"]);
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
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Email</title>

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
            <p class="register-login-heading">Change Email</h1>
            <form method="post" class="form">
                <label class="label" for="email">New Email</label>
                <input type="text" name="email" class="input account-settings-input" id="email" placeholder="Enter your new email address" value="<?= $user["email"] ?>">

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