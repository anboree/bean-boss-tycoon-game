<?php
    session_start();

    include("db/connection.php");

    $data = [
        "progress" => [],
        "upgrades" => []
    ];

    $stmt = $conn->prepare("
        SELECT day, hour, minute, money, beans, upgrade_level
        FROM user_game_progress
        WHERE user_id = ?
    ");

    $stmt->bind_param("i", $_SESSION["id"]);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $data["progress"] = [
        "day" => $row["day"],
        "hour" => $row["hour"],
        "minute" => $row["minute"],
        "money" => $row["money"],
        "beans" => $row["beans"],
        "upgradeLevel" => $row["upgrade_level"]
    ];

    $stmt->close();

    $stmt = $conn->prepare("
        SELECT upgrade_key, owned
        FROM user_upgrades
        WHERE user_id = ?
    ");

    $stmt->bind_param("i", $_SESSION["id"]);
    $stmt->execute();

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {

        $data["upgrades"][] = [
            "key" => $row["upgrade_key"],
            "owned" => (bool)$row["owned"]
        ];
    }

    $stmt->close();

    header('Content-Type: application/json');

    echo json_encode($data);
?>