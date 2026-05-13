<?php
    session_start();

    include("db/connection.php");

    $progress = json_decode($_POST['progress'], true);
    $upgrades = json_decode($_POST['upgrades'], true);

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("
            UPDATE user_game_progress SET day = ?, hour = ?, minute = ?, money = ?, beans = ?, upgrade_level = ? WHERE user_id = ?
        ");

        $stmt->bind_param(
            "iiiiiii",
            $progress['day'],
            $progress['hour'],
            $progress['minute'],
            $progress['money'],
            $progress['beans'],
            $progress['upgradeLevel'],
            $_SESSION["id"]
        );

        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("
            UPDATE user_upgrades
            SET owned = ?
            WHERE user_id = ? AND upgrade_key = ?
        ");

        foreach ($upgrades as $upgrade) {
            $owned = $upgrade['owned'] ? 1 : 0;

            $stmt->bind_param(
                "iis",
                $owned,
                $_SESSION["id"],
                $upgrade['key']
            );

            $stmt->execute();
        }

        $stmt->close();
        $conn->commit();
        echo "success";
    } catch (Exception $e) {
        $conn->rollback();
        echo "error";
    }
?>