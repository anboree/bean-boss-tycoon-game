<?php
    session_start();

    include("../db/connection.php");

    if(!isset($_SESSION["admin_id"])){
        header("Location: admin_login.php");
    }

    // Pagination
    $usersPerPage = 6;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;

    if($page < 1){
        $page = 1;
    }

    $offset = ($page - 1) * $usersPerPage;

    // Getting total users from database
    $totalUsersResult = $conn->query("
        SELECT COUNT(*) AS total
        FROM registered_users
    ");

    $totalUsers = $totalUsersResult->fetch_assoc()['total'];
    $totalPages = ceil($totalUsers / $usersPerPage);

    // Query to get necessary user data
    $result = $conn->query("
        SELECT 
            registered_users.id,
            registered_users.username,
            registered_users.email,
            registered_users.registration_date,
            user_account_details.profile_picture,
            user_account_details.last_active
        FROM registered_users
        INNER JOIN user_account_details
        ON registered_users.id = user_account_details.user_id
        ORDER BY registered_users.id DESC
        LIMIT $usersPerPage
        OFFSET $offset
    ");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    
    <!-- Font API -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Press+Start+2P&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/bean-boss-favicon.png">

    <!-- Link to CSS file -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="admin-flex-container">
        <h1 id="admin-panel-heading">Dashboard</h1>
        <div class="admin-panel-container">
            <table id="admin-panel-table">
                <tr>
                    <th class="admin-panel-header">ID</th>
                    <th class="admin-panel-header">Username</th>
                    <th class="admin-panel-header">Email</th>
                    <th class="admin-panel-header">Profile Picture</th>
                    <th class="admin-panel-header">Registration Date</th>
                    <th class="admin-panel-header">Last Active</th>
                    <th class="admin-panel-header">Actions</th>
                </tr>

                <?php while($user = $result->fetch_assoc()) : ?>

                <tr>
                    <td class="admin-panel-data"><?= $user['id'] ?></td>
                    <td class="admin-panel-data"><?= htmlspecialchars($user['username']) ?></td>
                    <td class="admin-panel-data"><?= htmlspecialchars($user['email']) ?></td>
                    <td class="admin-panel-data"><img src="../profile_pictures/<?= $user['profile_picture'] ?>" width="50" height="50"></td>
                    <td class="admin-panel-data"><?= $user['registration_date'] ?></td>
                    <td class="admin-panel-data"><?= $user['last_active'] ?></td>
                    <td class="admin-panel-data"><button id="admin-view-btn"><a id="admin-view-link" href="view_user.php">View</a></button>
                                                 <button id="admin-edit-btn"><a id="admin-edit-link" href="edit_user.php">Edit</a></button>
                                                 <button id="admin-delete-btn"><a id="admin-delete-link" href="delete_user.php?id=<?= $user['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a></button>
                    </td>
                </tr>

                <?php endwhile; ?>

            </table>

            <!-- Pagination output -->
            <div id="pagination-container">
                <?php for($i = 1; $i <= $totalPages; $i++) : ?>
                    <a class="pagination-btn" href="?page=<?= $i ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>

        </div>

        <button id="add-user-btn" style="border: 2px solid black; margin-top: 10px;"><a style="text-decoration: none; color: black;" href="add_user.php">Add User</a></button>
        <button id="admin-logout-btn" style="border: 2px solid black;"><a style="text-decoration: none; color: black;" href="admin_logout.php">Logout</a></button>

    </div>
</body>
</html>