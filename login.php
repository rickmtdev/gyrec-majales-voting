<?php
$nonauth = true;
require __DIR__ . '/main.php';

$wrong = false;
if(isset($_POST["pass"])) {
    if(isLogin($_POST["pass"])) {
        setLogin();
        header("location: index.php");
        exit;
    } else {
        $wrong = true;
    }
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

            .wrong-pass {
                text-align: center;
            }
        </style>
        <h3>Přihlášení</h3><br>
        <?php
            if($wrong) {
                echo "<div class='wrong-pass'>nesprávné heslo</div>";
            }
        ?>
        <form method="post">
            <input type="password" placeholder="Heslo" name="pass">
            <input type="submit" value="Přihlásit se">
        </form>
    </body>
</html>