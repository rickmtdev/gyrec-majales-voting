<?php
$con = mysqli_connect("[redacted]", "[redacted]", "[redacted]","[redacted]")
or die(json_encode(array("text"=>"connect-fail")));

$sql = "select count(*) as total,
count(if(class = 1 ,1,null)) as class_1,
count(if(class = 2 ,1,null)) as class_2,
count(if(class = 3 ,1,null)) as class_3,
count(if(class = 4 ,1,null)) as class_4,
count(if(class = 5 ,1,null)) as class_5,
count(if(class = 6 ,1,null)) as class_6,
count(if(class = 7 ,1,null)) as class_7,
count(if(class = 8 ,1,null)) as class_8,
count(if(class = 9 ,1,null)) as class_9,
count(if(class = 10 ,1,null)) as class_10,
count(if(class = 11 ,1,null)) as class_11,
count(if(class = 12 ,1,null)) as class_12
from majales_votes;";
$result = mysqli_query($con, $sql);
$array = mysqli_fetch_assoc($result);
$display = file_get_contents("counter.txt");
$timer = file_get_contents("timer.txt");
$array["display_counter"] = $display;
$array["timer"] = $timer;

echo json_encode($array);