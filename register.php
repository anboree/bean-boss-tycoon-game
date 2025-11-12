<?php
    session_start();

    include("db/connection.php");

    // Empty array for storing form errors
    $errors = [];

    // CSRF Token creation if it doesn't exist yet
    if(empty($_SESSION["csrf_token"])){
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
    }

    if(isset($_POST["register"])){
        $username = filter_var($_POST["username"], FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm-password"];
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

        // CSRF Token verification
        if(!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]){
            die("CSRF Token error");
        }

    if(count($errors) == 0){
        // Checks if username already exists in DB
        $stmt = $conn->prepare("SELECT id FROM registered_users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows > 0){
            $errors["username"] = "Username is already registered!";
        }
        $stmt->close();

        // Checks if email already exists in DB
        $stmt = $conn->prepare("SELECT id FROM registered_users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows > 0){
            $errors["email"] = "Email is already registered!";
        }
        $stmt->close();

        // Sends data to DB with SQL Injection security/prevention if no errors
        if(count($errors) == 0){
            $stmt = $conn->prepare("INSERT INTO registered_users (username, email, password, registration_date) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $hashed_password, $registration_date);
            $stmt->execute();
            $stmt->close();
            $conn->close();

            // Unsetting the token after each successful registration
            unset($_SESSION["csrf_token"]);
        }
    }

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <span class="back-btn"><a class="back-btn-link" href="index.php">&#8617;</a></span>
    <div class="container">
        <div class="form-container">
            <p class="register-login-heading">Registration</h1>
            <form method="post" class="form">
                <label class="label" for="username">Username</label>
                <input type="text" name="username" class="input" id="username" placeholder="Choose a username" value="<?= $username ?? '' ?>">

                <label class="label" for="email">Email</label>
                <input type="text" name="email" class="input" id="email" placeholder="Enter your email address" value="<?= $email ?? '' ?>">

                <label class="label" for="password">Password</label>
                <input type="password" name="password" class="input" id="password" placeholder="Create a password">

                <label class="label" for="confirm-password">Confirm password</label>
                <input type="password" name="confirm-password" class="input" id="confirm-password" placeholder="Confirm your password">

                <!-- CSRF Token value -->
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"]; ?>">

                <input type="submit" name="register" class="btn" id="register-btn" value="Register">
            </form>

            <p class="subform-redirect">Already have an account?<a class="subform-redirect-link" href="login.php">Login</a></p>

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