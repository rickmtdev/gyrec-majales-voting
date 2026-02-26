<?php
// Main PHP script component
// This script is included in all PHP scripts.

// Uncomment to show PHP errors for debugging if disabled
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

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
    $con = mysqli_connect(SQL_HOST, SQL_USERNAME, SQL_PASSWORD,SQL_DBNAME)
    or die(json_encode(array("text"=>"connect-fail")));
    $sql = "
    CREATE TABLE IF NOT EXISTS `". SQL_TABLE ."` (
        `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique vote id',
        `class` INT(11) NOT NULL COMMENT 'ID of selected class (1-12)',
        `date` DATETIME NOT NULL COMMENT 'Date submitted',
        `agent` TEXT NOT NULL COMMENT 'Device agent of the voting PC',
        `ip` TEXT NOT NULL COMMENT 'IP of the voting PC',
        `vote_id` INT(11) NULL COMMENT 'First vote ID (not null if the vote has been submitted as a second choice by one voter); useful when determining the voter count',
        `removed` INT(11) NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $result = mysqli_query($con, $sql);
}