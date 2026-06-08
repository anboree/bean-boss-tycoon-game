<?php
    session_start();

    include("../db/connection.php");

    if(!isset($_SESSION["id"])){
        header("Location: ../welcome.php");
    }

    include("ban_check.php");
    include("admin_check.php");
    include("start_game_check.php");

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require '../PHPMailer-7.1.1/src/Exception.php';
    require '../PHPMailer-7.1.1/src/PHPMailer.php';
    require '../PHPMailer-7.1.1/src/SMTP.php';

    $mail = new PHPMailer(true);

    $message_sent = false;

    $errors = [];

    if(isset($_POST["send"])){

        $subject = trim($_POST["subject"]);
        $message = trim($_POST["message"]);

        if(!empty($subject) && !empty($message)){

            $result = $conn->query("
                SELECT registered_users.email
                FROM registered_users
                INNER JOIN user_account_preferences
                ON registered_users.id = user_account_preferences.user_id
                WHERE user_account_preferences.email_updates = 1
            ");

            while($user = $result->fetch_assoc()){

                $mail = new PHPMailer(true);

                try{
                    $mail->isSMTP();
                    $mail->Host = 'smtp.hostinger.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'info@beanboss.online';
                    $mail->Password = '=1NiHFJZGd';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('info@beanboss.online', 'Bean Boss');
                    $mail->addAddress($user["email"]);

                    $mail->isHTML(false);
                    $mail->Subject = $subject;
                    $mail->Body = $message;

                    $mail->send();
                }
                catch(Exception $exception){
                }
            }

            $message_sent = true;
        }
        else{
            $errors["email"] = "Please fill out all fields!";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Email</title>

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
        <div class="form-container email-form">
            <p class="register-login-heading">Send Email</h1>
            <form method="post" class="form">
                <label for="subject" class="label">Subject</label>
                <input type="text" name="subject" id="subject" class="input">

                <label for="message" class="label">Message</label>
                <textarea name="message" id="message" rows="10" cols="60" style="resize: none;"></textarea>

                <button type="submit" name="send" class="btn" id="register-btn">Send Email</button>

                <?php if($message_sent): ?>
                    <p style="color: white; margin-top: 10px;">Email successfully sent!</p>
                <?php endif; ?>
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