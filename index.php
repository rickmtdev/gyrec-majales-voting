<?php
// Password 1: [redacted]
// Password 2 (admin): [redacted]
if(isset($_POST["pass"])) {
    if(hash('sha256', $_POST["pass"] . "[redacted]") == "[redacted]") {
        setcookie("auth", "[redacted]", time() + (10 * 365 * 24 * 3600), "/");
        header("location: index.php");
    } else {
        $wrong = true;
    }
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
        <script>
            // Inline JS je sračka, ale kvůli takovýhle retardovaný věci se mi nechce tvožit nový soubor
            function playAudio(url) {
                const pckImg = document.createElement("img");
                pckImg.setAttribute("src","sounds/pck_cropped.png");
                document.getElementById("hrefs").appendChild(pckImg);
                pckImg.classList.add("pck");
                setTimeout(() => {
                    pckImg.style.animation = "pckspin 2s forwards";
                }, "200")
                var sound = new Audio(url).play();               

                setTimeout(() => {
                    pckImg.remove();
                }, "2200");
            } 
        </script>
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
            .expl a, .hrefs p a {
                color: white;
            }

            .hrefs a.link {
                color: rgb(200,200,200);
                transition: 0.2s;
                text-decoration: none;
                margin-bottom: 0.5rem;
                font-size: 1.5rem;
                display: block;
                text-align: center;
            }

            .hrefs p {
                font-size: 60%;
                text-align: center;
            }

            .hrefs a.link:hover {
                color: aqua;
            }

            .pck {
                position: fixed;
                left: 50%;
                top: 50%;
                transform: translate(-50%,-50%);
                z-index: -1;
                opacity: 0;
                transition: 2s;
            }

            @keyframes pckspin {
                0% {
                    opacity: 0;
                    transform: rotate(0deg);
                    width: 1rem;
                }

                50% {
                    opacity: 1;
                    transform: rotate(1100deg) scale(15);
                }

                100% {
                    opacity: 0;
                    transform: rotate(0deg);
                    width: 1rem;
                }
            }

            #gut {
                position:fixed;
                bottom: 4rem;
                text-align: center;
                width: 100%;
                display: none;
            }

            #gut a {
                color: white;
                background: black;
                border: 0.1rem solid grey;
            }
        </style>
        <h3>Rozcestník</h3><br>
        <div class="hrefs" id="hrefs">
            <a href="vote.php" class="link">Hlasování</a>
            <a href="admin.php" class="link">Admin. rozhraní a výsledky</a>
            <a href="chart.php" class="link">Živý graf</a>

            <br>
            <p>Stránka s grafem je přizpůsobena pro zobrazení na 16:9 <a onclick="playAudio('sounds/ryby_v_prdeli.mp3')" href="#">viewportu</a>.<br>Hlasování i graf vyžadují připojení k internetu.</p>
        </div>
    </body>
</html>