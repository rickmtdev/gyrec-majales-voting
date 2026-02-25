<html class="voting">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no">
        <link rel="stylesheet" href="src/main.css">
        <title>Majáles naruby</title>
    </head>
    <body>
        <div class="container">
            <div class="button-grid row-1">
                <div class="button cat-1" data-class="1"><p>prima a</p></div>
                <div class="button cat-1" data-class="3"><p>sekunda a</p></div>
                <div class="button cat-1" data-class="5"><p>tercie a</p></div>
            </div>
            <div class="button-grid row-2">
                <div class="button cat-1" data-class="2"><p>prima b</p></div>
                <div class="button cat-1" data-class="4"><p>sekunda b</p></div>
                <div class="button cat-1" data-class="6"><p>tercie b</p></div>
            </div>

            <div class="button-grid row-3">
                <div class="button cat-2" data-class="7"><p>kvarta a</p></div>
                <div class="button cat-2" data-class="9"><p>kvinta a</p></div>
                <div class="button cat-2" data-class="11"><p>sexta a</p></div>
            </div>
            <div class="button-grid row-4">
                <div class="button cat-2" data-class="8"><p>kvarta b</p></div>
                <div class="button cat-2" data-class="10"><p>kvinta b</p></div>
                <div class="button cat-2" data-class="12"><p>sexta b</p></div>
            </div>

            <div class="button-grid row-5">
                <div class="button-vote" data-class="vote"><p>odeslat</p></div>
            </div>
        </div>

        <div class="message" id="noclicks"></div>

        <div class="message success" id="succ">
            <div>✅Odesláno</div>
        </div>

        <div class="message failure" id="fail">
            <div>❌Došlo k chybě, zkus to znovu</div>
        </div>

        <div class="message nocon" id="nocon">
            <div>❌Problémy s připojením</div>
        </div>

        <div class="message locked" id="locked">
            <div>🔒Hlasování je uzamčeno</div>
        </div>

        <script src="src/send.js"></script>
    </body>
</html>