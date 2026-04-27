<?php
    session_start();

    include("db/connection.php");

    if(!isset($_SESSION["id"])){
        header("Location: welcome.php");
    }

    // Checks if start_game has been completed for registered users
    $stmt = $conn->prepare("
        SELECT id FROM user_game_progress WHERE user_id = ?
    ");
    $stmt->bind_param("i", $_SESSION["id"]);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 0){
        header("Location: start_game.php");
        exit();
    }

    include("navbar.php");

    // SAVING CHANGES
    if(isset($_POST["save_changes"])){
        $user_id = $_SESSION["id"];
        $email_updates = isset($_POST["email_updates"]) ? 1 : 0;
        $show_on_leaderboard = isset($_POST["show_on_leaderboard"]) ? 1 : 0;
        $background_music = isset($_POST["background_music"]) ? 1 : 0;

        // UPDATE QUERY
        $stmt = $conn->prepare("
        UPDATE user_account_preferences
        SET email_updates = ?, show_on_leaderboard = ?, background_music = ?
        WHERE user_id = ?
    ");

        $stmt->bind_param("iiii", 
        $email_updates, 
        $show_on_leaderboard, 
        $sound_effects,
        $background_music, 
        $user_id
    );

        $stmt->execute();
        $stmt->close();

        header("Location: user_account_settings.php");
        exit();
    }

    // GAME RESET
    if(isset($_POST["reset_game"])){
        $user_id = $_SESSION["id"];

        // Start transaction (very important)
        $conn->begin_transaction();

        try{
            // Delete upgrades progress
            $stmt = $conn->prepare("DELETE FROM user_upgrades WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();

            // Delete game progress
            $stmt = $conn->prepare("DELETE FROM user_game_progress WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();

            // Commit
            $conn->commit();

            // Redirect
            header("Location: start_game.php");
            exit();
        }
        catch(Exception $e){
            $conn->rollback();
            echo "Error resetting game progress.";
        }
    }

    // ACCOUNT DELETION
    if(isset($_POST["delete_account"])){
        $user_id = $_SESSION["id"];

        // Start transaction (very important)
        $conn->begin_transaction();

        try{
            // Delete upgrades progress
            $stmt = $conn->prepare("DELETE FROM user_upgrades WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();

            // Delete game progress
            $stmt = $conn->prepare("DELETE FROM user_game_progress WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();

            // Delete preferences
            $stmt = $conn->prepare("DELETE FROM user_account_preferences WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();

            // Delete account details
            $stmt = $conn->prepare("DELETE FROM user_account_details WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();

            // Delete main user
            $stmt = $conn->prepare("DELETE FROM registered_users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();

            // Commit
            $conn->commit();

            // Destroy session
            session_destroy();

            // Redirect
            header("Location: welcome.php");
            exit();

        }
        catch(Exception $e){
            $conn->rollback();
            echo "Error deleting account.";
        }
}

    // SELECT QUERY
    $stmt = $conn->prepare("
        SELECT email_updates, show_on_leaderboard, background_music
        FROM user_account_preferences
        WHERE user_id = ?
    ");
    $stmt->bind_param("i", $_SESSION["id"]);
    $stmt->execute();
    $result = $stmt->get_result();

    $preferences = $result->fetch_assoc();

    $stmt->close();
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>

    <!-- Font API -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Press+Start+2P&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/bean-boss-favicon.png">

    <!-- Link to CSS file -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <span class="back-btn"><a class="back-btn-link" href="user_account.php">&#8617;</a></span>
    <div class="profile-flex-container">
        <div class="account-settings-container">
            <!-- Added Decoration Ropes that visually holds up container -->
            <span class="rope-decor-left"></span>
            <span class="rope-decor-right"></span>

            <h2 class="account-settings-header">Account Settings</h2>
            <hr class="account-settings-hr">

            <form class="account-settings-flex-container" method="POST">
                    <p class="account-settings-option">Receive E-Mails about game updates and changes <span class="account-settings-button"><input type="checkbox" name="email_updates" id="check1" <?= ($preferences["email_updates"] == 1) ? "checked" : "" ?>><label for="check1" class="button"></label></span></p>

                    <p class="account-settings-option">Show your profile on the leaderboard <span class="account-settings-button"><input type="checkbox" name="show_on_leaderboard" id="check2" <?= ($preferences["show_on_leaderboard"] == 1) ? "checked" : "" ?>><label for="check2" class="button"></label></span></p>

                    <p class="account-settings-option">Enable background music <span class="account-settings-button"><input type="checkbox" name="background_music" id="check4" <?= ($preferences["background_music"] == 1) ? "checked" : "" ?>><label for="check3" class="button"></label></span></p>
                
                    <input type="submit" name="save_changes" id="account-settings-save-btn" class="save-changes-btn" value="Save Changes">
            </form>
            <hr class="account-settings-hr">

            <h2 class="account-settings-header">Other Settings</h2>
            <hr class="account-settings-hr">

            <!-- Reset Game option -->
            <form method="POST" onsubmit="return confirmReset()">
                <input type="submit" name="reset_game" id="reset-game-button" value="Reset Game">
            </form>

            <!-- Delete Account option -->
            <form method="POST" onsubmit="return confirmDelete()">
                <input type="submit" name="delete_account" id="delete-account-button" value="Delete Account">
            </form>
        </div>
    </div>

    <script>
        function confirmReset(){
            return confirm("Are you sure you want to reset your game progress? This action cannot be undone.");
        }

        function confirmDelete(){
            return confirm("Are you sure you want to delete your account? This action cannot be undone.");
        }
    </script>
</body>
</html>