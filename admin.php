<?php
ob_start();
if($_COOKIE["admin_auth"] == "[redacted]") {
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
    from majales23_votes where removed IS NULL;";
    $result = mysqli_query($con, $sql);
    $array = mysqli_fetch_assoc($result);

    $sql = "select
    count(*) from majales23_votes where vote_id IS NULL and removed IS NULL;";
    $result = mysqli_query($con, $sql);
    $arrayUnique = mysqli_fetch_assoc($result);
    //$display = file_get_contents("counter.txt");
    //$array["display_counter"] = $display;

    //echo json_encode($array);

    $display = file_get_contents("counter.txt");
    $endVoting = file_get_contents("end_voting.txt");
    $date = file_get_contents("timer.txt");
    if(isset($_POST["counts"])) {
        if($display == 0) {
            $value = 1;
        } else {
            $value = 0;
        }
        $fp = fopen('counter.txt', 'w');
        fwrite($fp, $value);
        fclose($fp);
        header("location: admin.php#input");
    } elseif(isset($_POST["votes"])) {
        if($endVoting == 0) {
            $value = 1;
        } else {
            $value = 0;
        }
        $fp = fopen('end_voting.txt', 'w');
        fwrite($fp, $value);
        fclose($fp);
        header("location: admin.php#input");
    } elseif(isset($_POST["date"])) {
        $pieces = explode("-", $_POST["date"]);
        $restPiece = explode(" ", $pieces[2]);
        $timePiece = explode(":", $restPiece[1]);
        // echo $pieces[0] . "a" . $pieces[1] . "a" . $restPiece[0] . "a" . $timePiece[0] . "a" . $timePiece[1] . "a" . $timePiece[2] . "a";
        if(is_numeric($pieces[0]) && is_numeric($pieces[1]) && is_numeric($restPiece[0]) && is_numeric($timePiece[0]) && is_numeric($timePiece[1]) && is_numeric($timePiece[2])) {
            $newDate = $pieces[0] . "-" . $pieces[1] . "-" . $restPiece[0] . " " . $timePiece[0] . ":" . $timePiece[1] . ":" . $timePiece[2];
        } else {
            $newDate = "";
        }

        $value = $newDate;
        $fp = fopen('timer.txt', 'w');
        fwrite($fp, $value);
        fclose($fp);
        header("location: admin.php#input");
    } elseif(isset($_POST["deleteall"])) {
        $sqlDel = "update majales23_votes set removed = 1;";
        $resultDel = mysqli_query($con, $sqlDel);
        header("location: admin.php#open-delete-all");
    }

?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes" />
        <title>Majáles 2023</title>
        <link rel="stylesheet" href="src/main.css">
    </head>
    <body>
<?php
}

if($_COOKIE["admin_auth"] != "[redacted]") {
    ob_end_clean();
    if(hash('sha256', $_POST["pass"] . "[redacted]") == "[redacted]") {
        setcookie("admin_auth", "[redacted]", time() + (10 * 365 * 24 * 3600), "/");
        header("location: admin.php");
    } elseif(isset($_POST["pass"])) {
        $wrong = true;
    }
    echo '<head> <meta charset="utf-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes" /> <title>Majáles 2023</title> <link rel="stylesheet" href="src/main.css"> </head>';
    echo '<h3>Admin rozhraní</h3><br>';
    if($wrong) { echo "<div class='wrong-pass'>nesprávné heslo</div>"; }
    echo '<form method="post"> <input type="password" placeholder="Heslo" name="pass"> <input type="submit" value="Přihlásit se"> </form>';
    echo "<p class='about-login'>Adminstrátorské rozhraní je dostupné pouze pro moderátory.</p>";
    
    $unauth = true;
}

?>

<style>
    body {
        font-size: 1.5rem;
    }

    input {
        margin-top: 1rem;
        font: inherit;
        font-size: 1.8rem;
        left: 50%;
        transform: translateX(-50%);
        width: 80%;
    }

    input[type=text] {
        width: 90%;
        margin-top: 2.5rem;
        margin-bottom: -0.7rem;
    }

    .classes-list {
        display: none;
        background: rgb(60,60,60);
        padding: 1rem;
    }
    .classes-list.display {
        display: block;
    }

    .opener {
        width: 100%;
        padding-top: 1rem;
        padding-bottom: 1rem;
        background: darkblue;
        margin-top: 1rem;
        color: white;
        text-align: center;
        left: 50%;
        transform: translateX(-50%);
        transition: 0.2s;
        cursor: pointer;
    }

    .opener:hover, .opener.display {
        background: blue;
    }

    #open-delete-all {
        background: rgb(120,0,0);
        color: rgb(255,200,200);
    }

    #open-delete-all:hover, #open-delete-all.display {
        background: rgb(255,20,20);
    }


    .total, h3 {
        text-align: center;
    }
    h3 {
        margin-top: 0.5rem;
        font-size: 1.7rem;
    }
    p {
        font-size: 1rem;
    }
    .expl {
        text-align: center;
        font-size: 1rem;
        padding-top: 0.5rem;
    }
    .expl a {
        color: white;
    }

    .disclaimer {
        font-size: 70%;
        color: rgb(180,255,180);
        margin-bottom: 0.2rem;
    }

    .disclaimer.alert {
        color: rgb(255,130,130);
    }

    .about-login {
        text-align: center;
        margin-top: 0.5rem;
    }

    .wrong-pass {
        text-align: center;
        display: block;
    }
</style>
<?php

if($unauth) {
    exit;
}

$i = 1;
echo "<h3>Admin rozhraní nebo něco</h3><br>";
echo "<div class='total'><b>Celkem hlasů: " . $array["total"] . "</b><br>Unikátních hlasů: ". implode($arrayUnique) ."<br><p>[". date('d. m. \'y H:i:s') ."]</p></div>";
unset($array["total"]);
$category1 = array_slice($array, 0, 6);
$category2 = array_slice($array, 6);

$discValue = ($endVoting == 0) ? "Hlasování je odemčeno, počty mohou být nekompletní" : "Hlasování je zastaveno";
$discClassValue = ($endVoting == 0) ? "alert" : "";

echo "<div class='opener' id='open-list-all'>Všechny třídy</div><div class='classes-list' id='list-all'><div class='disclaimer $discClassValue'>$discValue<br>Načteno ".date('d. m. \'y H:i:s')."</div>";
arsort($array);

//print_r($array);
foreach($array as $key=>$elem) {
    echo $i . ". ";
    echo ($key == "class_1" || $key == "class_2") ? "Prima" : "";
    echo ($key == "class_3" || $key == "class_4") ? "Sekunda" : "";
    echo ($key == "class_5" || $key == "class_6") ? "Tercie" : "";
    echo ($key == "class_7" || $key == "class_8") ? "Kvarta" : "";
    echo ($key == "class_9" || $key == "class_10") ? "Kvinta" : "";
    echo ($key == "class_11" || $key == "class_12") ? "Sexta" : "";
    echo (str_replace("class_","",$key) % 2 == 0) ? " B" : " A";
    echo ": " . $elem . "<br>";

    $i++;
}
echo "</div>";

$i = 1;
echo "<div class='opener' id='open-list-divide'>Rozděleno</div><div class='classes-list' id='list-divide'><div class='disclaimer $discClassValue'>$discValue<br>Načteno ".date('d. m. \'y H:i:s')."</div>";
arsort($category1);
arsort($category2);

foreach($category1 as $key=>$elem) {
    echo $i . ". ";
    echo ($key == "class_1" || $key == "class_2") ? "Prima" : "";
    echo ($key == "class_3" || $key == "class_4") ? "Sekunda" : "";
    echo ($key == "class_5" || $key == "class_6") ? "Tercie" : "";
    echo ($key == "class_7" || $key == "class_8") ? "Kvarta" : "";
    echo ($key == "class_9" || $key == "class_10") ? "Kvinta" : "";
    echo ($key == "class_11" || $key == "class_12") ? "Sexta" : "";
    echo (str_replace("class_","",$key) % 2 == 0) ? " B" : " A";
    echo ": " . $elem . "<br>";

    $i++;
}
$i = 1;
echo "<br>";
foreach($category2 as $key=>$elem) {
    echo $i . ". ";
    echo ($key == "class_1" || $key == "class_2") ? "Prima" : "";
    echo ($key == "class_3" || $key == "class_4") ? "Sekunda" : "";
    echo ($key == "class_5" || $key == "class_6") ? "Tercie" : "";
    echo ($key == "class_7" || $key == "class_8") ? "Kvarta" : "";
    echo ($key == "class_9" || $key == "class_10") ? "Kvinta" : "";
    echo ($key == "class_11" || $key == "class_12") ? "Sexta" : "";
    echo (str_replace("class_","",$key) % 2 == 0) ? " B" : " A";
    echo ": " . $elem . "<br>";

    $i++;
}
echo "</div><br>";

$btnValue = ($display == 0) ? "Zobrazit" : "Skrýt";
echo "<form method='POST'>";
echo "<input type='submit' id='input' name='counts' value='". $btnValue . " počty'>";
echo "</form>";

$btnValue = ($endVoting == 0) ? "Zastavit" : "Obnovit";
echo "<form method='POST'>";
echo "<input type='submit' id='input2' name='votes' value='". $btnValue . " hlasování'>";
echo "</form>";

echo "<form method='POST'>";
echo "<input type='text' id='date-input' name='date' value='". $date . "'><br>";
echo "<input type='submit' id='input2' name='timer' value='Nastavit odpočítávání'>";
echo "</form>";
echo "<div class='expl'><a href='#input' onclick=\"document.getElementById('date-input').value='2023-05-01 13:30:00'\">Vložit ukázkové datum</a><br>Datum musí být ve formátu YYYY-MM-DD HH:mm:ss.<br>Odpočítávání se aktivuje zadáním datumu, deaktivuje se zadáním prázdného pole.<br>Jsou-li zobrazené počty, odpočítávání není vidět.<br>V momentě, kdy vyprší čas, se nic nemění (volení je nutné zamknout manuálně).</div>";

echo "<br>";
echo "<div class='opener' id='open-delete-all'>Odstranění všech hlasů</div><div class='classes-list' id='delete-all'>";
echo "<form method='POST'>";
echo "<input type='submit' id='input3' name='deleteall' value='Odstranit všechny hlasy'>";
echo "</form>";
echo "<div class='expl'>Stiskem tlačítka výše se označí všechny aktuální hlasy za neplatné (pouze pro účely testování).</div>";
echo "</div><br>";
?>
    <script>
        document.querySelectorAll(".opener").forEach(el => el.addEventListener('click', event => {
            var open = el.id.replace("open-","");
            if(document.getElementById(open).classList.contains("display")) {
                document.getElementById(open).classList.remove("display");
                el.classList.remove("display");
            } else {
                document.getElementById(open).classList.add("display");
                el.classList.add("display");
            }
           
        }));
    </script>

    </body>
</html>