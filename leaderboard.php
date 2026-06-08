<?php
    session_start();

    include("db/connection.php");

    if(!isset($_SESSION["id"])){
        header("Location: welcome.php");
    }

    include("ban_check.php");
    include("start_game_check.php");

    include("navbar.php");

    // Pagination
    $usersPerPage = 5;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;

    if($page < 1){
        $page = 1;
    }

    $offset = ($page - 1) * $usersPerPage;

    // Getting total users from database
    $totalUsersResult = $conn->query("
        SELECT COUNT(*) AS total
        FROM user_game_progress
    ");

    $totalUsers = $totalUsersResult->fetch_assoc()['total'];
    $totalPages = ceil($totalUsers / $usersPerPage);

    // Query to get necessary user data
    $result = $conn->query("
        SELECT 
            registered_users.username,
            user_account_details.profile_picture,
            user_game_progress.business_name,
            user_game_progress.day,
            user_game_progress.money,
            user_game_progress.beans,
            user_game_progress.upgrade_level,
            user_account_preferences.show_on_leaderboard
        FROM registered_users
        INNER JOIN user_account_details
        ON registered_users.id = user_account_details.user_id
        INNER JOIN user_game_progress
        ON registered_users.id = user_game_progress.user_id
        INNER JOIN user_account_preferences
        ON registered_users.id = user_account_preferences.user_id
        WHERE user_account_preferences.show_on_leaderboard = 1
        ORDER BY user_game_progress.money DESC
        LIMIT $usersPerPage
        OFFSET $offset
    ");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>

    <!-- Font API -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Press+Start+2P&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/bean-boss-favicon.png">

    <!-- Link to CSS file -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <span class="back-btn"><a class="back-btn-link" href="index.php">&#8617;</a></span>
    <div class="leaderboard-flex-container">
        <div class="leaderboard-container">
            <!-- Added Decoration Ropes that visually holds up container -->
            <span class="rope-decor-left"></span>
            <span class="rope-decor-right"></span>

            <h1 id="leaderboard-heading">Leaderboard</h1>

            <hr id="leaderboard-hr">

            <table id="leaderboard-table">
                <tr>
                    <th class="leaderboard-table-header">#</th>
                    <th class="leaderboard-table-header">PFP</th>
                    <th class="leaderboard-table-header">Username</th>
                    <th class="leaderboard-table-header">Business Name</th>
                    <th class="leaderboard-table-header">Day</th>
                    <th class="leaderboard-table-header">Money</th>
                    <th class="leaderboard-table-header">Beans</th>
                    <th class="leaderboard-table-header">Level</th>
                </tr>

                <?php $i = $offset + 1; ?>

                <?php while($user = $result->fetch_assoc()) : ?>

                <tr>
                    <td class="leaderboard-table-data">
                        <?php
                            echo $i;
                            $i++;
                        ?>
                    </td>
                    <td class="leaderboard-table-data"><img src="profile_pictures/<?= $user['profile_picture'] ?>" id="profile-picture"></td>
                    <td class="leaderboard-table-data"><?= htmlspecialchars($user["username"]) ?></td>
                    <td class="leaderboard-table-data"><?= htmlspecialchars($user["business_name"]) ?></td>
                    <td class="leaderboard-table-data"><?= htmlspecialchars($user["day"]) ?></td>
                    <td class="leaderboard-table-data">$<?= htmlspecialchars($user["money"]) ?></td>
                    <td class="leaderboard-table-data"><?= htmlspecialchars($user["beans"]) ?></td>
                    <td class="leaderboard-table-data"><?= htmlspecialchars($user["upgrade_level"]) ?></td>
                </tr>

                <?php endwhile; ?>

            </table>

            <!-- Pagination output -->
            <div id="pagination-container">
                <?php for($i = 1; $i <= $totalPages; $i++) : ?>
                    <a class="pagination-btn <?= isset($_GET['page']) && $_GET['page'] == $i ? 'active-pagination-btn' : '' ?>" href="?page=<?= $i ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>

        </div>
    </div>
</body>
</html>