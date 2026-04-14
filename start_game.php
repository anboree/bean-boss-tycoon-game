<?php
    session_start();

    include("db/connection.php");

    if(!isset($_SESSION["id"])){
        header("Location: welcome.php");
    }

    $errors = [];

    // Prevents access to this file if already completed
    $stmt = $conn->prepare("
        SELECT id FROM user_game_progress WHERE user_id = ?
    ");
    $stmt->bind_param("i", $_SESSION["id"]);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        header("Location: index.php");
        exit();
    }

    if(isset($_POST["start-game"])){
        $business_name = filter_var($_POST["business-name"], FILTER_SANITIZE_SPECIAL_CHARS);
        $user_id = $_SESSION["id"];

        if(empty($business_name)){
            $errors["business-name"] = "Please enter a name for your coffee business!";
        }
        elseif(strlen($business_name) > 255){
            $errors["business-name"] = "Business name cannot be longer than 255 characters!";
        }

        if(count($errors) == 0){
            // Default values
            $day = 1;
            $hour = 7;
            $minute = 0;
            $money = 0;
            $beans = 0;
            $upgrade_level = 1;

            $conn->begin_transaction();

            try{
                // Insert game progress
                $stmt = $conn->prepare("
                    INSERT INTO user_game_progress 
                    (user_id, business_name, day, hour, minute, money, beans, upgrade_level)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");

                $stmt->bind_param(
                    "isiiiiii",
                    $user_id,
                    $business_name,
                    $day,
                    $hour,
                    $minute,
                    $money,
                    $beans,
                    $upgrade_level
                );

                $stmt->execute();
                $stmt->close();

                // Insert default upgrades (all locked)
                $defaultUpgrades = [
                    "coffeeMachine",
                    "businessSign",
                    "hireBarista",
                    "premiumBeans",
                    "biggerCoffeeStand"
                ];

                foreach($defaultUpgrades as $upgradeKey){
                    $stmt = $conn->prepare("
                        INSERT INTO user_upgrades (user_id, upgrade_key, owned)
                        VALUES (?, ?, 0)
                    ");

                    $stmt->bind_param("is", $user_id, $upgradeKey);
                    $stmt->execute();
                    $stmt->close();
                }

                $conn->commit();

                header("Location: index.php");
                exit();

            } 
            catch(Exception $e){
                $conn->rollback();
                $error = "Something went wrong. Please try again.";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Start Game</title>
    
    <!-- Font API -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Press+Start+2P&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/bean-boss-favicon.png">

    <!-- Link to CSS file -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="start-game-flex-container">
        <div class="start-game-container">
            <form method="POST">
                <label class="label" id="start-game-label" for="business-name">Enter your coffee business name</label>
                <input type="text" name="business-name" class="input" id="start-game-input" placeholder="e.g. My Coffee Stand">
                <input type="submit" name="start-game" id="start-game-btn" value="Start Playing!">
            </form>
        </div>
        <!-- Error output -->
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
</body>
</html>