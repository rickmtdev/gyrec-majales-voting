<?php
require __DIR__ . '/main.php';

$array = getVotes($pdo);

$config = getConfig($pdo);
$array['display_counter'] = $config['counter'];
$array['timer'] = $config['timer'];

echo json_encode($array);