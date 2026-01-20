<?php
    session_start();

    if(isset($_SESSION["id"])){
        header("Location: index.php");
    }

    include("db/connection.php");

    // Empty array for storing form errors
    $errors = [];

    // CSRF Token creation if it doesn't exist yet
    if(empty($_SESSION["csrf_token"])){
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
    }

    if(isset($_POST["login"])){
        $username = filter_var($_POST["username"], FILTER_SANITIZE_SPECIAL_CHARS);
        $password = $_POST["password"];

        // Username validation
        if(empty($username)){
            $errors["username"] = "Enter your username!";
        }

        // Password validation
        if(empty($password)){
            $errors["password"] = "Enter your password!";
        }

        // CSRF Token verification
        if(!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]){
            die("CSRF Token error");
        }

        if(count($errors) == 0){
            // Prepared statement to fetch user by username
            $stmt = $conn->prepare("SELECT id, username, password FROM registered_users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if($stmt->num_rows > 0){
                $stmt->bind_result($id, $username, $hashed_password);
                $stmt->fetch();

                // Verifying password
                if(password_verify($password, $hashed_password)){
                    // Unsetting the token after each successful login
                    unset($_SESSION["csrf_token"]);
                    $_SESSION["id"] = $id;
                    $stmt->close();
                    $conn->close();

                    header("Location: index.php");
                    exit;
                }
                else{
                    $errors["login"] = "Invalid username or password!";
                }
            }
            else{
                $errors["login"] = "Invalid username or password!";
            }

            $stmt->close();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <span class="back-btn"><a class="back-btn-link" href="index.php">&#8617;</a></span>
    <div class="container">
        <div class="form-container">
            <p class="register-login-heading">Login</h1>
            <form method="post" class="form">
                <label class="label" for="username">Username</label>
                <input type="text" name="username" class="input" id="username" placeholder="Enter your username" value="<?= $username ?? '' ?>">

                <label class="label" for="username">Password</label>
                <input type="password" name="password" class="input" id="password" placeholder="Enter your password">

                <!-- CSRF Token value -->
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"]; ?>">

                <input type="submit" name="login" class="btn" id="login-btn" value="Login">
            </form>

            <p class="subform-redirect">Don't have an account?<a class="subform-redirect-link" href="register.php">Register</a></p>

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