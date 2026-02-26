<?php
require __DIR__ . '/main.php';

if(getConfig($pdo)['end_voting'] == true) {
    echo json_encode(array("text"=>"voting-locked"));
    exit;
}

$_POST = json_decode(file_get_contents('php://input'), true);

if(VOTING_RATE_LIMIT_SECONDS != null) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $since = date('Y-m-d H:i:s', time() - VOTING_RATE_LIMIT_SECONDS);

    $sqlRateLimit = "
    SELECT COUNT(*) AS recent_votes
    FROM `" . SQL_TABLE . "`
    WHERE ip = :ip
    AND removed IS NULL
    AND date >= :since
    ";

    $stmt = $pdo->prepare($sqlRateLimit);
    $stmt->execute([
        ':ip'    => $ip,
        ':since' => $since,
    ]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['recent_votes'] > 0) {
        echo json_encode(["text" => "rate-limit"]);
        exit;
    }
}

if(isset($_POST["choice1"]) && isset($_POST["choice2"]) && is_int($_POST["choice1"]) && is_int($_POST["choice2"]) &&
    (($_POST["choice1"] <= 6 && $_POST["choice1"] > 0) || ($_POST["choice2"] <= 12 && $_POST["choice2"] > 6))) {
    $date = date('Y-m-d H:i:s');

    // Prevent potential double votes by restricting each choice to a range of classes
    if($_POST["choice1"] != null && ($_POST["choice1"] <= 6 && $_POST["choice1"] > 0)) {
        $_POST["choice1"] = intval($_POST["choice1"]); // Prevent SQL injection
        
        $sql = "
        INSERT INTO `" . SQL_TABLE . "` 
        (class, date, agent, ip)
        VALUES (:class, :date, :agent, :ip)
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':class' => (int) $_POST['choice1'],
            ':date'  => $date,
            ':agent' => $_SERVER['HTTP_USER_AGENT'],
            ':ip'    => $_SERVER['REMOTE_ADDR'],
        ]);

        $lastId = $pdo->lastInsertId();
    } else {
        $lastId = "NULL";
    }

    if($_POST["choice2"] != null && ($_POST["choice2"] <= 12 && $_POST["choice2"] > 6)) {
        $_POST["choice2"] = intval($_POST["choice2"]); // Prevent SQL injection

        $sql = "
        INSERT INTO `" . SQL_TABLE . "` 
        (class, date, agent, ip, vote_id)
        VALUES (:class, :date, :agent, :ip, :vote_id)
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':class'   => (int) $_POST['choice2'],
            ':date'    => $date,
            ':agent'   => $_SERVER['HTTP_USER_AGENT'],
            ':ip'      => $_SERVER['REMOTE_ADDR'],
            ':vote_id' => (int) $lastId,
        ]);
    }



    echo json_encode(array("text"=>"success"));
} else {
    echo json_encode(array("text"=>"invalid-choices: " . implode($_POST)));
}