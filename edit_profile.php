<?php
    session_start();

    if(!isset($_SESSION["id"])){
        header("Location: welcome.php");
    }

    include("db/connection.php");
    include("navbar.php");

    if(isset($_POST["save-changes"])){
        $user_id = $_SESSION["id"];

        // Username update
        if(!empty($_POST["new-username"])){
            $newUsername = trim($_POST["new-username"]);
            $stmt = $conn->prepare("UPDATE registered_users SET username = ? WHERE id = ?");
            $stmt->bind_param("si", $newUsername, $user_id);
            $stmt->execute();
            $stmt->close();
        }

        // Profile picture update
        if(isset($_FILES["profile_picture"]) && $_FILES["profile_picture"]["error"] === 0){
            $fileName = $_FILES["profile_picture"]["name"];
            $tmpName = $_FILES["profile_picture"]["tmp_name"];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $allowed = ["jpg","jpeg","png"];

            if(in_array($fileExt, $allowed)){

                $newFileName = "pfp_" . $user_id . "_" . time() . "." . $fileExt;

                move_uploaded_file($tmpName, "profile_pictures/" . $newFileName);

                $stmt = $conn->prepare("
                    UPDATE user_account_details 
                    SET profile_picture = ?
                    WHERE user_id = ?
                ");

                $stmt->bind_param("si", $newFileName, $user_id);
                $stmt->execute();
                $stmt->close();
            }
        }
        header("Location: edit_profile.php");
        exit();
    }

    $stmt = $conn->prepare("
    SELECT 
        registered_users.username,
        user_account_details.profile_picture,
        user_account_details.level,
        user_account_details.xp
    FROM registered_users
    INNER JOIN user_account_details
        ON registered_users.id = user_account_details.user_id
    WHERE registered_users.id = ?
    ");
    $stmt->bind_param("i", $_SESSION["id"]);
    $stmt->execute();
    $result = $stmt->get_result();

    $user = $result->fetch_assoc();

    $stmt->close();
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>

    <!-- Font API -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Press+Start+2P&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/bean-boss-favicon.png">
</head>
<body>
    <span class="back-btn"><a class="back-btn-link" href="user_account.php">&#8617;</a></span>
    
    <div class="account-settings-container">
        <!-- Added Decoration Ropes that visually holds up container -->
        <span class="rope-decor-left"></span>
        <span class="rope-decor-right"></span>

        <h2 class="account-settings-header">Edit Profile</h2>
        <hr class="account-settings-hr">

        <form class="profile-info" method="POST" enctype="multipart/form-data">
            <img src="profile_pictures/<?php echo htmlspecialchars($user['profile_picture'] ?? 'default-pfp.jpg'); ?>" width="160px" height="160px" style="margin-bottom: 10px; border: 4px solid black; border-radius: 50%; object-fit: cover;
            object-position: center;" alt="Profile Picture">
            <p class="account-settings-text">Change Profile Picture:</p>
            <input type="file" name="profile_picture" id="change-pfp-btn">

            <hr class="account-settings-hr">

            <p class="user-account-info" style="font-size: 20px; margin-bottom: 4px;"><?= htmlspecialchars($user["username"]) ?></p>
            <p class="account-settings-text">Change Username:</p>
            <input type="text" name="new-username" id="change-username-input" placeholder="Enter new username">

            <hr class="account-settings-hr">

            <input type="submit" name="save-changes" class="save-changes-btn" value="Save Changes">
        </form>
    </div>
</body>
</html>