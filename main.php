<?php
// Main PHP script component
// This script is included in all PHP scripts.

// // Uncomment to show PHP errors for debugging if disabled
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

// Import setup constants
if (file_exists(__DIR__ . '/env.local.php')) {
    require __DIR__ . '/env.local.php';
} else {
    require __DIR__ . '/env.php';
}

// Compare login credentials
function isLogin($string, $admin = false, $cookie = false) {
    if($cookie) return hash('sha256', $admin ? ADMIN_PASSWORD . ADMIN_SALT : USER_PASSWORD . USER_SALT) == $string;
    return hash('sha256', $admin ? ADMIN_PASSWORD . ADMIN_SALT : USER_PASSWORD . USER_SALT) == hash('sha256', $admin ? $string . ADMIN_SALT : $string . USER_SALT);
}

function setLogin($admin = false) {
    $hash = hash('sha256', $admin ? ADMIN_PASSWORD . ADMIN_SALT : USER_PASSWORD . USER_SALT);

    if($admin) {
        setcookie(ADMIN_COOKIE_NAME, $hash, time() + (10 * 365 * 24 * 3600), "/");
    } else {
        setcookie(USER_COOKIE_NAME, $hash, time() + (10 * 365 * 24 * 3600), "/");
    }
    
}

function getVotes($pdo) {
    $sql = "
    SELECT 
        COUNT(*) AS total,
        COUNT(IF(class = 1 ,1,NULL)) AS class_1,
        COUNT(IF(class = 2 ,1,NULL)) AS class_2,
        COUNT(IF(class = 3 ,1,NULL)) AS class_3,
        COUNT(IF(class = 4 ,1,NULL)) AS class_4,
        COUNT(IF(class = 5 ,1,NULL)) AS class_5,
        COUNT(IF(class = 6 ,1,NULL)) AS class_6,
        COUNT(IF(class = 7 ,1,NULL)) AS class_7,
        COUNT(IF(class = 8 ,1,NULL)) AS class_8,
        COUNT(IF(class = 9 ,1,NULL)) AS class_9,
        COUNT(IF(class = 10 ,1,NULL)) AS class_10,
        COUNT(IF(class = 11 ,1,NULL)) AS class_11,
        COUNT(IF(class = 12 ,1,NULL)) AS class_12
    FROM `" . SQL_TABLE . "`
    WHERE removed IS NULL
    ";

    try {
        $stmt = $pdo->query($sql);
        return $stmt->fetch();
    } catch (PDOException $e) {
        die(json_encode(["text" => "query-fail"]));
    }
}

function isValidDateTime($value) {
    $dt = DateTime::createFromFormat('Y-m-d H:i:s', $value);
    return $dt && $dt->format('Y-m-d H:i:s') === $value;
}

function getConfig($pdo) {
    $sql = "SELECT counter, end_voting, timer 
            FROM `" . SQL_CONFIG_TABLE . "` 
            WHERE id = 1 
            LIMIT 1";

    $stmt = $pdo->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return [
            'counter'    => true,
            'end_voting' => false,
            'timer'      => ''
        ];
    }

    return [
        'counter'    => (bool)$row['counter'],
        'end_voting' => (bool)$row['end_voting'],
        'timer'      => $row['timer'] !== null
                        ? date('Y-m-d H:i:s', strtotime($row['timer']))
                        : ''
    ];
}

function setConfig($pdo, $config) {
    if (empty($config)) {
        return;
    }

    $fields = [];
    $params = [];

    if (array_key_exists('counter', $config)) {
        $fields[] = "counter = :counter";
        $params[':counter'] = $config['counter'] ? 1 : 0;
    }

    if (array_key_exists('end_voting', $config)) {
        $fields[] = "end_voting = :end_voting";
        $params[':end_voting'] = $config['end_voting'] ? 1 : 0;
    }

    if (array_key_exists('timer', $config)) {
        if (
            $config['timer'] === '' ||
            !is_string($config['timer']) ||
            !isValidDateTime($config['timer'])
        ) {
            $fields[] = "timer = NULL";
        } else {
            $fields[] = "timer = :timer";
            $params[':timer'] = $config['timer'];
        }
    }

    if (empty($fields)) {
        return;
    }

    $pdo->beginTransaction();

    try {
        // Ensure single row exists
        $pdo->exec("
            INSERT INTO `" . SQL_CONFIG_TABLE . "` (id)
            VALUES (1)
            ON DUPLICATE KEY UPDATE id = id
        ");

        $sql = "UPDATE `" . SQL_CONFIG_TABLE . "` 
                SET " . implode(', ', $fields) . " 
                WHERE id = 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $pdo->commit();

    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
}

// Redirect to login page if user not logged in
// Use .htaccess rules to prevent access to non-PHP assets as well
if(!isset($nonauth)) {
    if(!isset($_COOKIE[USER_COOKIE_NAME]) || !isLogin($_COOKIE[USER_COOKIE_NAME], false, true)) {
        header("location: login.php");
        exit;
    }
}

// Connect to DB
if(!isset($static)) {
    try {
        $dsn = "mysql:host=" . SQL_HOST . ";dbname=" . SQL_DBNAME . ";charset=utf8mb4";

        $pdo = new PDO($dsn, SQL_USERNAME, SQL_PASSWORD, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

    } catch (PDOException $e) {
        die(json_encode(["text" => "connect-fail"]));
    }

    $sql = "
    CREATE TABLE IF NOT EXISTS `" . SQL_TABLE . "` (
        `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique vote id',
        `class` INT(11) NOT NULL COMMENT 'ID of selected class (1-12)',
        `date` DATETIME NOT NULL COMMENT 'Date submitted',
        `agent` TEXT NOT NULL COMMENT 'Device agent of the voting PC',
        `ip` TEXT NOT NULL COMMENT 'IP of the voting PC',
        `vote_id` INT(11) NULL COMMENT 'First vote ID (not null if the vote has been submitted as a second choice by one voter); useful when determining the voter count',
        `removed` INT(11) NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";

    try {
        $pdo->exec($sql);
    } catch (PDOException $e) {
        die(json_encode(["text" => "query-fail"]));
    }

    $sql = "
    CREATE TABLE IF NOT EXISTS `" . SQL_CONFIG_TABLE . "` (
        `id` TINYINT NOT NULL PRIMARY KEY DEFAULT 1,
        `counter` TINYINT(1) NOT NULL DEFAULT 1,
        `end_voting` TINYINT(1) NOT NULL DEFAULT 0,
        `timer` DATETIME NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";

    try {
        $pdo->exec($sql);
    } catch (PDOException $e) {
        die(json_encode(["text" => "query-fail"]));
    }
}