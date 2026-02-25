<?php
$con = mysqli_connect("[redacted]", "[redacted]", "[redacted]","[redacted]")
or die(json_encode(array("text"=>"connect-fail")));

if(file_get_contents("end_voting.txt") == 1 || true) {
    echo json_encode(array("text"=>"voting-locked"));
    exit;
}

$_POST = json_decode(file_get_contents('php://input'), true);

if(($_POST["choice1"] <= 6 && $_POST["choice1"] > 0) || ($_POST["choice2"] <= 12 && $_POST["choice2"] > 6)) {
    $date = date('Y-m-d H:i:s');

    // Prevent potential double votes by restricting each choice to a range of classes
    if($_POST["choice1"] != null && ($_POST["choice1"] <= 6 && $_POST["choice1"] > 0)) {
        $sql = "INSERT INTO majales_votes (class, date, agent, ip)
        VALUES ({$_POST["choice1"]}, '{$date}', '{$_SERVER['HTTP_USER_AGENT']}', '{$_SERVER['REMOTE_ADDR']}');";
        $result = mysqli_query($con, $sql);
        $lastId = mysqli_insert_id($con);
    } else {
        $lastId = "NULL";
    }

    if($_POST["choice2"] != null && ($_POST["choice2"] <= 12 && $_POST["choice2"] > 6)) {
        $sql = "INSERT INTO majales_votes (class, date, agent, ip, vote_id)
        VALUES ({$_POST["choice2"]}, '{$date}', '{$_SERVER['HTTP_USER_AGENT']}', '{$_SERVER['REMOTE_ADDR']}', {$lastId});";
        $result = mysqli_query($con, $sql);
    }



    echo json_encode(array("text"=>"success"));
} else {
    echo json_encode(array("text"=>"invalid-choices: " . implode($_POST)));
}