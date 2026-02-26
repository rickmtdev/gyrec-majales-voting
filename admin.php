<?php
require __DIR__ . '/main.php';

ob_start();
if(isLogin($_COOKIE[ADMIN_COOKIE_NAME], true, true)) {
    $array = getVotes($pdo);

    // Get unique votes
    $sql = "
    SELECT COUNT(*) AS total_unique
    FROM `" . SQL_TABLE . "`
    WHERE vote_id IS NULL 
    AND removed IS NULL
    ";
    try {
        $stmt = $pdo->query($sql);
        $arrayUnique = $stmt->fetch();
    } catch (PDOException $e) {
        die(json_encode(["text" => "query-fail"]));
    }

    $config = getConfig($pdo);

    $display = $config['counter'];
    $endVoting = $config['end_voting'];
    $date = $config['timer'];
    if(isset($_POST["counts"])) {
        setConfig($pdo, ['counter' => !$display]);
        
        header("location: admin.php#input");
        exit;
    } elseif(isset($_POST["votes"])) {        
        setConfig($pdo, ['end_voting' => !$endVoting]);

        header("location: admin.php#input");
        exit;
    } elseif(isset($_POST["date"])) {
        setconfig($pdo, ['timer' => $_POST["date"]]);

        header("location: admin.php#input");
        exit;
    } elseif(isset($_POST["deleteall"])) { 
        $sqlDel = "UPDATE `" . SQL_TABLE . "` SET removed = 1";

        try {
            $stmt = $pdo->exec($sqlDel); 
        } catch (PDOException $e) {
            die(json_encode(["text" => "query-fail"]));
        }
        header("location: admin.php#open-delete-all");
        exit;
    }

?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes" />
        <title><?= PAGE_TITLE ?></title>
        <link rel="stylesheet" href="src/main.css">
    </head>
    <body>
<?php
}

$unauth = false;

if(!isLogin($_COOKIE[ADMIN_COOKIE_NAME], true, true)) {
    ob_end_clean();
    $wrong = false;

    if(isset($_POST["pass"]) && isLogin($_POST["pass"], true)) {
        setLogin(true);
        header("location: admin.php");
        exit;
    } elseif(isset($_POST["pass"])) {
        $wrong = true;
    }
    echo '<head> <meta charset="utf-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes" /> <title>'. PAGE_TITLE .'</title> <link rel="stylesheet" href="src/main.css"> </head>';
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
echo "<h3>Admin rozhraní</h3><br>";
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