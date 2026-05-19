<?php
    session_start();

    include("../db/connection.php");

    if(isset($_SESSION["admin_id"])){
        header("Location: admin_panel.php");
    }

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
            $stmt = $conn->prepare("SELECT id, username, password FROM admin_users WHERE username = ?");
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
                    $_SESSION["admin_id"] = $id;
                    $stmt->close();
                    $conn->close();

                    header("Location: admin_panel.php");
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
    <title>Admin Login</title>
    
    <!-- Font API -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Press+Start+2P&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/bean-boss-favicon.png">

    <!-- Link to CSS file -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <p class="register-login-heading">Admin Login</h1>
            <form method="post" class="form">
                <label class="label" for="username">Username</label>
                <input type="text" name="username" class="input" id="username" placeholder="Enter your username" value="<?= $username ?? '' ?>">

                <label class="label" for="username">Password</label>
                <input type="password" name="password" class="input" id="password" placeholder="Enter your password">

                <label id="show-password-label" for="show-password-toggle">Show Password:</label>
                <input type="checkbox" id="show-password-toggle" onclick="showPassword()">

                <!-- Show password function -->
                <script>
                    function showPassword(){
                        var password = document.getElementById("password");
                        if(password.type === "password"){
                            password.type = "text";
                        } 
                        else{
                            password.type = "password";
                        }
                    }
                </script>

                <!-- CSRF Token value -->
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"]; ?>">

                <input type="submit" name="login" class="btn" id="login-btn" value="Login">
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